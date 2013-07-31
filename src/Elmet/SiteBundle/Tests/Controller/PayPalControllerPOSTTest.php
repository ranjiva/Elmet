<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\Order;
use Elmet\SiteBundle\Entity\OrderItem;
use Elmet\SiteBundle\Entity\InstantPaymentNotification;
use Elmet\AdminBundle\Entity\OrderTracking;

//this class only needed to test POST requests to PayPalController

class PayPalControllerPOSTTest extends WebTestCase
{
    var $order;
    var $trackingDetail;
    var $ipns;
    var $em;
    
    private function createOrder($status,$stockLevel) {
 
        $repository = $this->em->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneById('1');
        
        $curtainColour->setAvailableStock($stockLevel);
        $curtainColour->setInStock(1);
                   
        $order = new Order();
        $order->setOrderStatus($status);
       
        $orderItems = $order->getOrderItems();
        
        $orderItem = new OrderItem();
   
        $orderItem->setColour($curtainColour->getName());       
        $orderItem->setDescription("Geneva Ready-made (Jacquard) Curtains");
        $orderItem->setItemFilepath($curtainColour->getThumbnailFilepath());
        $orderItem->setName("Ready-made Curtains (pair)");
        $orderItem->setPrice(100.25);
        $orderItem->setCurtainColour($curtainColour);
        
        $orderItem->setProductType("Curtain");
        $orderItem->setQuantity(1);
        $orderItem->setSize("52\" x 90\"");
        $orderItem->setSubtotal(100.25);
        $orderItem->setProductCategoryId($curtainColour->getId());
        $orderItem->setOrder($order);
        
        $orderItems->add($orderItem);
        $order->updateOrderTotal();
        
        $this->em->merge($curtainColour);
        $this->em->persist($order);
        
        $this->em->flush();
        
        return $order;
    }
    
    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
    }
    
    protected function tearDown() {
        
      $this->em->remove($this->order);
      
      if ($this->trackingDetail != null)
          $this->em->remove($this->trackingDetail);
      
      foreach($this->ipns as $ipn)
          $this->em->remove($ipn);
      
      $this->em->flush();
    }
    
    public function testProcessCompleted()
    {      
        $this->order = $this->createOrder("Pending",20.0);
        $this->ipns = array();
        
        $req = $this->getReq($this->order,"Completed","TXN".$this->order->getId());
        
        $file = "web/paypal_test.txt";
        $fh = fopen($file, 'w');
        fwrite($fh, $req);
        fclose($fh);
        
        $client = static::createClient();
       
        $url = "/generate_ipn/send?".$req;
        
        $processCrawler = $client->request('GET',$url);
        
        $this->assertTrue($processCrawler->filter('H1:contains("Success")')->count() > 0);
               
        $this->em->refresh($this->order);
        
        $query = $this->em->createQuery('SELECT ot FROM ElmetAdminBundle:OrderTracking ot JOIN ot.order o WHERE o.id = :id');

        $query->setParameter('id',intval($this->order->getId()));
                 
         try {
            $this->trackingDetail = $query->getSingleResult();
         }
         catch (\Doctrine\ORM\NoResultException $e) {
            $this->trackingDetail = null;
         }
     
        $ipnQuery = $this->em->createQuery('SELECT ipn FROM ElmetSiteBundle:InstantPaymentNotification ipn WHERE ipn.custom = :id');
        $ipnQuery->setParameter('id',$this->order->getId());
        
        try {
            $ipn = $ipnQuery->getSingleResult();
            $this->ipns[] = $ipn;
        }
        catch (\Doctrine\ORM\NoResultException $e) {
           $ipn = null;
        }
        
        $this->assertTrue($ipn != null);
        $this->assertTrue($ipn->getId() == "TXN".$this->order->getId());
        
        $this->assertTrue($this->trackingDetail != null);
        $this->assertTrue($this->trackingDetail->getTrackingStatus() == "Received");
        $this->assertTrue($this->trackingDetail->getEstimatedDispatchDate() != null);
        $this->assertTrue($this->trackingDetail->getOrder()->getId() == $this->order->getId());
        
        $this->assertTrue($this->order->getFirstName() == "Ranjiva");
        $this->assertTrue($this->order->getLastName() == "Prasad");
        $this->assertTrue($this->order->getDeliveryName() == "Ranjiva Prasad");
        $this->assertTrue($this->order->getBillingName() == "Ranjiva Prasad");
        $this->assertTrue($this->order->getBillingAddress() == "20 Sussex Gardens");
        $this->assertTrue($this->order->getDeliveryAddress() == "20 Sussex Gardens");
        $this->assertTrue($this->order->getBillingTown() == "Fleet");
        $this->assertTrue($this->order->getDeliveryTown() == "Fleet");
        $this->assertTrue($this->order->getBillingPostcode() == "GU51 2TL");
        $this->assertTrue($this->order->getDeliveryPostcode() == "GU51 2TL");
        $this->assertTrue($this->order->getEmail() == "ranjiva@yahoo.com");
        $this->assertTrue($this->order->getOrderStatus() == "Paid");
        
        $orderItems = $this->order->getOrderItems();
        
        $curtainColour = $orderItems[0]->getCurtainColour();
        $this->em->refresh($curtainColour);
        
        $difference = $curtainColour->getAvailableStock() - 14.94;
        
        $this->assertTrue($difference == 0);
        $this->assertTrue($curtainColour->getInStock() == 1);
    }
       
    public function getReq($order,$status,$txnId)
    {
        $receiver_email = static::$kernel->getContainer()->getParameter('paypal_business');
        $item_name = static::$kernel->getContainer()->getParameter('paypal_item_name');
        $custom = $order->getId();
        $txn_id = $txnId;
        $payer_email = "ranjiva@yahoo.com";
        $first_name = "Ranjiva";
        $last_name = "Prasad";
        $address_city = "Fleet";
        $address_country = "UK";
        $address_name = "Ranjiva Prasad";
        $address_street = "20 Sussex Gardens";
        $address_zip = "GU51 2TL";
        $payment_status = $status;
        
        if ($status == 'Reversed')  {
            $mc_fee = -1.5;
            $mc_gross = 1.5 - $order->getAmountPaid();
        } elseif ($status == 'Canceled_Reversal') {
            $mc_fee = 1.5;
            $mc_gross = $order->getAmountPaid() - 1.5;
        }
        else {
            $mc_gross = $order->getAmountPaid();
        }
        
        $mc_currency = "GBP";
        
        $req = "receiver_email=".urlencode($receiver_email);
        $req = $req."&item_name=".urlencode($item_name);
        $req = $req."&custom=".urlencode($custom);
        $req = $req."&txn_id=".urlencode($txn_id);
        $req = $req."&payer_email=".urlencode($payer_email);
        $req = $req."&first_name=".urlencode($first_name);
        $req = $req."&last_name=".urlencode($last_name);
        $req = $req."&address_city=".urlencode($address_city);
        $req = $req."&address_country=".urlencode($address_country);
        $req = $req."&address_name=".urlencode($address_name);
        $req = $req."&address_street=".urlencode($address_street);
        $req = $req."&address_zip=".urlencode($address_zip);
        $req = $req."&payment_status=".urlencode($payment_status);
        $req = $req."&mc_gross=".urlencode($mc_gross);
        $req = $req."&mc_currency=".urlencode($mc_currency);
        
        if (($status == 'Reversed') or ($status == 'Canceled_Reversal') ) {
            $req = $req."&mc_fee=".urlencode($mc_fee);
        }
        
        return $req;
    }
}



?>
