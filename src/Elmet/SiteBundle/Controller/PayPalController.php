<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use \Elmet\SiteBundle\Entity\InstantPaymentNotification;
use \Elmet\AdminBundle\Entity\OrderTracking;

class PayPalController extends BaseController
{
    
    public function sendAction()
    {
        $valid = $this->sendResponseToPayPal();
       
        if ($valid == true) {
            $status = "SUCCESS";
        } else {
            $status = "FAILURE";
        }
        
        return new Response($status);
        
    }
    
    public function processAction()
    {
        $ipn = $this->validateNotification();
        
        if ($ipn <> null)
        {
            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
            $order = $repository->findOneById($ipn->getCustom());
        
            $order->setFirstName($ipn->getFirstName());
            $order->setLastName($ipn->getLastName());
            $order->setDeliveryName($ipn->getAddressName());
            $order->setDeliveryAddress($ipn->getAddressStreet());
            $order->setDeliveryTown($ipn->getAddressCity());
            $order->setDeliveryPostcode($ipn->getAddressZip());
            $order->setBillingName($ipn->getAddressName());
            $order->setBillingAddress($ipn->getAddressStreet());
            $order->setBillingTown($ipn->getAddressCity());
            $order->setBillingPostcode($ipn->getAddressZip());
            $order->setEmail($ipn->getPayerEmail());
            
            if ($ipn->getPaymentStatus() == 'Completed') {
                $order->setOrderStatus("Paid");
            } elseif ($ipn->getPaymentStatus() == 'Refunded') {
                $order->setOrderStatus("Cancelled");
            } elseif ($ipn->getPaymentStatus() == 'Reversed') {
                $order->setOrderStatus("Disputed");
            } elseif ($ipn->getPaymentStatus() == 'Canceled_Reversal') {
                
                if ($this->hasOrderBeenDispatched($ipn->getCustom())) {
                      $order->setOrderStatus("Dispatched");
                } else {
                      $order->setOrderStatus("Paid");
                }
                
            } else {
                $order->setOrderStatus("Error");
            }
            
            $em = $this->getDoctrine()->getEntityManager();
            
            $order = $em->merge($order);
            
            if ($ipn->getPaymentStatus() <> 'Canceled_Reversal') {
                $em->persist($ipn);
            }
                        
            $em->flush();
                        
            $this->sendBackOfficeEmail($order);
            
            if ($ipn->getPaymentStatus() == 'Completed') {
            
                $orderTracking = new OrderTracking();
                $orderTracking->setOrder($order);
                $orderTracking->setTrackingStatus('Received');
                $orderTracking->setEstimatedDispatchDate($this->calculateDispatchDate());
                $em->persist($orderTracking);
                $em->flush();
                
                $this->sendCustomerEmail($orderTracking);

                $curtainColours = $this->updateStock($order);

                if (!empty($curtainColours['low'])) {
                    $this->sendLowStockNotification($curtainColours['low']);
                }

                if (!empty($curtainColours['out'])) {
                    $this->sendOutOfStockNotification($curtainColours['out']);
                }
            }
            
            $status = "Success";
            
        }
        else {
            $status = "Failure";;
        }
        
        return new Response($status);
        
    }
    
    public function validateNotification()
    {
        //check that the response to PayPal is accepted by PayPal
        
        $valid = $this->sendResponseToPayPal();
        
        $receiver_email = $this->getRequest()->get('receiver_email');
        $item_name = $this->getRequest()->get('item_name');
        $custom = $this->getRequest()->get('custom');
        $txn_id = $this->getRequest()->get('txn_id');
        $payer_email = $this->getRequest()->get('payer_email');
        $first_name = $this->getRequest()->get('first_name');
        $last_name = $this->getRequest()->get('last_name');
        $address_city = $this->getRequest()->get('address_city');
        $address_country = $this->getRequest()->get('address_country');
        $address_name = $this->getRequest()->get('address_name');
        $address_street = $this->getRequest()->get('address_street');
        $address_zip = $this->getRequest()->get('address_zip');
        $payment_status = $this->getRequest()->get('payment_status');
        $mc_gross = $this->getRequest()->get('mc_gross');
        $mc_currency = $this->getRequest()->get('mc_currency');
        $mc_fee = $this->getRequest()->get('mc_fee');
        $contact_phone = $this->getRequest()->get('contact_phone');
        
        //check that the receiver email is correct
        
        if ($valid and ($receiver_email == $this->container->getParameter('paypal_business'))) {
            $valid = true;
        } else {
            $valid = false;
        }
        
        //check that the item name is correct
       
        if($valid and ($item_name == $this->container->getParameter('paypal_item_name'))) {
            $valid = true;
        } else {
            $valid = false;
        }
                
        //check that the notification is for a known order
        
        if ($valid and ($custom <> null) and is_numeric($custom))
        {
            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
            $order = $repository->findOneById($custom);
            
            if ($order <> null) {
                $valid = true;
            } else {
                $valid = false;
            }
        }
        else 
        {
            $valid = false;
        }
        
        //check that the amount of the order is correct
        
        if (($payment_status == 'Reversed') or ($payment_status == 'Canceled_Reversal') ) {
            $amount = abs($mc_gross + $mc_fee);
        } else {
            $amount = abs(floatval($mc_gross));
        }
        
        if ($valid and (abs($order->getAmountPaid() - $amount)) < 0.01 ) {
            $valid = true;
        } else {
            $valid = false;
        }
        
        //check that the currency is correct
        
        if ($valid and ($mc_currency == 'GBP')) {
            $valid = true;
        } else {
            $valid = false;
        }
        
        //check that the instant payment notification has not been sent already
        
        if ($valid and ($payment_status <> 'Canceled_Reversal')) {
            
            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:InstantPaymentNotification');
            $ipn = $repository->findBy(array('txn_id' => $txn_id));
        
            if ($ipn == null) {
                $valid = true;
            } else {
                $valid = false;
            }
        }        
        
        if ($valid) {
            
            $ipn = new InstantPaymentNotification();
                   
            $ipn->setReceiverEmail($receiver_email);
            $ipn->setItemName($item_name);
            $ipn->setCustom($custom);
            $ipn->setTxnId($txn_id);
            $ipn->setPayerEmail($payer_email);
            $ipn->setFirstName($first_name);
            $ipn->setLastName($last_name);
            $ipn->setAddressCity($address_city);
            $ipn->setAddressCountry($address_country);
            $ipn->setAddressName($address_name);
            $ipn->setAddressStreet($address_street);
            $ipn->setAddressZip($address_zip);
            $ipn->setPaymentStatus($payment_status);
            $ipn->setMcGross($amount);
            $ipn->setMcCurrency($mc_currency);
            $ipn->setId($txn_id);
            
            if ($contact_phone <> null) {
                $ipn->setContactPhone($contact_phone);
            }
                     
            return $ipn;
            
        } else {
            return null;
        }
    }
    
    public function sendResponseToPayPal()
    {
        //send response exactly as received
        
        $req = 'cmd=_notify-validate';
        
        if ($this->getRequest()->getMethod() == "GET") {
            $params = $this->getRequest()->query->all();
        } else {
            $params = $this->getRequest()->request->all();
        }
        
        $keys = array_keys($params);
        
        foreach($keys as $key) {
            $param = $params[$key];
            $req = $req."&".$key."=".urlencode($param);
        }
            
        $ch = curl_init($this->container->getParameter('paypal_response_url'));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: ".$this->container->getParameter('paypal_host')));
        
        $res = curl_exec($ch);
        curl_close($ch);
        
        if (strcmp ($res, "VERIFIED") == 0) {
            return true;
        } else {
            return false;
        }
        
    }
    
    public function sendBackOfficeEmail($order)
    {
        $message = \Swift_Message::newInstance()
                        ->setSubject('Elmet Curtains - Online Customer Order')
                        ->setFrom($this->container->getParameter('noreply_address'))
                        ->setTo($this->container->getParameter('orders_address'))
                        ->setBody($this->renderView('ElmetSiteBundle:PayPal:email.html.php', array('order' => $order)),'text/html');

        $this->get('mailer')->send($message);
    }
    
    public function sendCustomerEmail($trackingDetail)
    {
        $message = \Swift_Message::newInstance()
                        ->setSubject('Elmet Curtains - Online Customer Order')
                        ->setFrom($this->container->getParameter('orders_address'))
                        ->setTo($trackingDetail->getOrder()->getEmail())
                        ->setBody($this->renderView('ElmetSiteBundle:PayPal:customer_email.html.twig', array('trackingDetail' => $trackingDetail)),'text/html');

        $this->get('mailer')->send($message);
    }
    
    public function updateStock($order) {
        
        $colourRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
        $meterageRepository =  $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainMeterage');
        $em = $this->getDoctrine()->getEntityManager();
        
        $lowStockCurtainColours = array();
        $outOfStockCurtainColours = array();
        
        foreach($order->getOrderItems() as $orderItem) {
            
            $productType = $orderItem->getProductType();
            
            if (($productType == "Curtain") || ($productType == "Fabric")) {
                
                $curtainColour = $colourRepository->findOneById($orderItem->getProductCategoryId());
                 
                if ($productType == "Curtain") {
                    
                    $curtainMeterage = $meterageRepository->findOneBySize($orderItem->getSize());
                    
                    $meterageUsed  = $curtainMeterage->getMeterage() * $orderItem->getQuantity();
                } else {
                    $meterageUsed = $orderItem->getQuantity();
                }
                
                $stockAvailable = $curtainColour->getAvailableStock() - $meterageUsed;
                
                if ($stockAvailable < 0.0) {
                    $stockAvailable = 0.00;
                }
                
                $curtainColour->setAvailableStock($stockAvailable);
                
                if ($stockAvailable < $this->container->getParameter('no_stock_floor')) {  
                    
                    $outOfStockCurtainColours[$curtainColour->getId()] = $curtainColour;
                    $curtainColour->setInStock(0);
                
                } else if ($stockAvailable < $this->container->getParameter('low_stock_floor')) {
                    
                    $lowStockCurtainColours[$curtainColour->getId()] = $curtainColour;
                }
                
                $em->merge($curtainColour);
            }
        }
        
        $em->flush();
        
        return array('low' => $lowStockCurtainColours, 'out' => $outOfStockCurtainColours);
          
    }

    public function sendLowStockNotification($lowStockCurtainColours) {
        
        $message = \Swift_Message::newInstance()
                        ->setSubject('Elmet Curtains - Low Stock Notification')
                        ->setFrom($this->container->getParameter('noreply_address'))
                        ->setTo($this->container->getParameter('orders_address'))
                        ->setBody($this->renderView('ElmetAdminBundle:CurtainStock:email.html.twig', array('title' => 'Low Stock', 'message' => 'The following curtain designs and colours have a low level of stock:', 'curtainColours' => $lowStockCurtainColours)),'text/html');

        $this->get('mailer')->send($message);
        
    }

    public function sendOutOfStockNotification($outOfStockCurtainColours) {
        
        $message = \Swift_Message::newInstance()
                        ->setSubject('Elmet Curtains - Out Of Stock Notification')
                        ->setFrom($this->container->getParameter('noreply_address'))
                        ->setTo($this->container->getParameter('orders_address'))
                        ->setBody($this->renderView('ElmetAdminBundle:CurtainStock:email.html.twig', array('title' => 'Out Of Stock', 'message' => 'The following curtain designs and colours have been put out of stock:', 'curtainColours' => $outOfStockCurtainColours)),'text/html');

        $this->get('mailer')->send($message);
        
    }
    
    public function calculateDispatchDate() {
        
        $repository = $this->getDoctrine()->getRepository('ElmetAdminBundle:DispatchDateOffset');
        
        $day_of_week = date("w");
        
        $dispatchDateOffset = $repository->findOneBy(array('day_of_week' => $day_of_week));
        
        return $dispatchDateOffset->getDispatchDate();
    }
    
    public function hasOrderBeenDispatched($order_id) {
        
        $dispatched = false;
        
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT ot FROM ElmetAdminBundle:OrderTracking ot JOIN ot.order o WHERE o.id = :id');

        $query->setParameter('id',intval($order_id));
                 
         try {
            $trackingDetail = $query->getSingleResult();
         }
         catch (\Doctrine\ORM\NoResultException $e) {
            $trackingDetail = null;
         }

         if ($trackingDetail != null) {
             
             if ($trackingDetail->getTrackingStatus() == 'Dispatched') {
                 $dispatched = true;
             }
         } 
         
         return $dispatched;
    }
}
?>