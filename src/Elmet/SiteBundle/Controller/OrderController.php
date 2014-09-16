<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;
use Elmet\SiteBundle\Entity\Order;
use Elmet\SiteBundle\Entity\OrderItem;
use Elmet\SiteBundle\Entity\CurtainColour;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\UnitOfWork;

class OrderController extends BaseController {
    
    /** * @codeCoverageIgnore */
    public function createAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneById('2');
                   
        $order = new Order();   
        $order->setAmountPaid(113.25);
        $order->setBillingAddress("20 Sussex Gardens");
        $order->setBillingAddress2("Ancells Park");
        $order->setBillingName("R K Prasad");
        $order->setBillingPostcode("GU51 2TL");
        $order->setBillingTown("Fleet");
        $order->setDeliveryAddress("8 Southwood Close");
        $order->setDeliveryAddress2("Great Lever");
        $order->setDeliveryName("Ranjiva Prasad");
        $order->setDeliveryPostcode("BL3 2DJ");
        $order->setDeliveryTown("Bolton");
        $order->setEmail("ranjiva@yahoo.com");
        $order->setFirstName("Ranjiva");
        $order->setLastName("Prasad");
        $order->setMobile("07769901335");
        $order->setNotes("Ring the Doorbell");
        $order->setOrderStatus("Pending");
        $order->setOrderTotal(106.75);
        $order->setDeliveryCharge(6.50);
        $order->setTelephone("01252 643872");
        
        $orderItem = new OrderItem();
   
        $orderItem->setColour($curtainColour->getName());       
        $orderItem->setDescription("Curtains");
        $orderItem->setDropAlteration("18");
        $orderItem->setItemFilepath($curtainColour->getThumbnailFilepath());
        $orderItem->setName("Ranjiva");
        $orderItem->setPrice(100.25);
        $orderItem->setCurtainColour($curtainColour);
        
        $orderItem->setProductType("Curtain");
        $orderItem->setQuantity(10);
        $orderItem->setSize("120 X 90");
        $orderItem->setSubtotal(125.67);
        $orderItem->setOrder($order);
        $orderItem->setProductCategoryId($curtainColour->getId());
        
        $orderItems = $order->getOrderItems();
        $orderItems->add($orderItem);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        
        $em->flush();
        
        $session = $this->getRequest()->getSession();    
        $session->set('order', $order);
             
        return new Response('Created order id '.$order->getId().' Created curtain order item id '.$orderItem->getId());              
    }
    
    public function fetchAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
        $order = $repository->findOneById($id);
       
        $colourRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
        
        foreach($order->getOrderItems() as $orderItem)
        {
            $curtainColour = $colourRepository->findOneById($orderItem->getProductCategoryId());
            $orderItem->setCurtainColour($curtainColour);
        }
        
        $session = $this->getRequest()->getSession();    
        $session->set('order', $order);
        
        return new Response('Fetched order id '.$order->getId().' Number of Order Items '.count($order->getOrderItems()));      
    }
    
    public function addAction($urlName, $colour)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneByNameJoinedToDesignByUrlName($colour,$urlName);
        $curtainDesign = $curtainColour->getCurtainDesign();
        $curtainPriceBand = $curtainDesign->getCurtainPriceBand();
        $curtainFabrics = $curtainPriceBand->getCurtainFabrics();
        $cushionCovers = $curtainPriceBand->getCushionCovers();
        $curtainPelmets = $curtainPriceBand->getCurtainPelmets();
        $curtainTieBacks = $curtainPriceBand->getCurtainTieBacks();
        
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT cp FROM ElmetSiteBundle:CurtainPrice cp JOIN cp.curtain_price_band cpd WHERE cpd.id = :id ORDER BY cp.width ASC, cp.height asc');
        $query->setParameter('id',$curtainPriceBand->getId());
        $curtainPrices = $query->getResult(); 
        
        $order = $this->getOrder();
        $orderItems = $order->getOrderItems();
        $discount = 1 - $curtainColour->getDiscountPercentage() / 100;
        
        //add curtains first
        
        $homeWindowIndex = 0;
        $caravanWindowIndex = 0;
        $caravanDoorIndex = 0;
        
        foreach($curtainPrices as $curtainPrice) {
            
            $eyeletQuantity = 0;
            $quantity = 0;
            
            if ($curtainPrice->getType() == 'HomeWindow') {
                $quantity = $this->getRequest()->get('home_tape_curtain_'.$homeWindowIndex);
                $eyeletQuantity = $this->getRequest()->get('home_eyelet_curtain_'.$homeWindowIndex); 
            } else if ($curtainPrice->getType() == 'CaravanWindow') {
                $quantity = $this->getRequest()->get('caravan_window_curtain_'.$caravanWindowIndex);
            } else if ($curtainPrice->getType() == 'CaravanDoor') {
                $quantity = $this->getRequest()->get('caravan_door_curtain_'.$caravanDoorIndex);
            }
            
            if($quantity <> 0) {
                    
                    $orderItem = new OrderItem();
                    
                    $orderItem->setColour($curtainColour->getName());
                    
                    if($curtainPrice->getType() == 'CaravanDoor') {
                        $orderItem->setDescription("Ready-made Curtains");
                    } else {
                        $orderItem->setDescription("Ready-made Curtains (pair)");
                    }
                    $orderItem->setItemFilepath("/img/products/".$curtainColour->getThumbnailFilepath());
                    $orderItem->setName($curtainDesign->getName());
                    
                    if ($curtainColour->getOnOffer() == 0) {
                        $price = $curtainPrice->getPrice();
                    } else {
                        $price = number_format(round($curtainPrice->getPrice() * $discount,2),2);
                    }
                    $orderItem->setPrice($price);
                    $orderItem->setCurtainColour($curtainColour);
                    $orderItem->setProductType("Curtain");
                    $orderItem->setQuantity($quantity);
                    $orderItem->setSize($curtainPrice->getSize());
                    $orderItem->setSubtotal($quantity * $price);
                    $orderItem->setOrder($order);
                    $orderItem->setProductCategoryId($curtainColour->getId());
                    
                    $orderItems->add($orderItem);
            }
            
            if($eyeletQuantity <> 0) {

                $price = $curtainPrice->getPrice() + $curtainPrice->getCurtainEyeletPriceBand()->getPrice();

                if ($curtainColour->getOnOffer() == 1) {
                    $price = number_format(round($price * $discount,2),2);
                }
                
                $orderItem = new OrderItem();

                $orderItem->setColour($curtainColour->getName());       
                $orderItem->setDescription("Ready-made Curtains (pair)"); 
                $orderItem->setItemFilepath("/img/products/".$curtainColour->getThumbnailFilepath());
                $orderItem->setName($curtainDesign->getName()." with Eyelets");
                $orderItem->setPrice($price);
                $orderItem->setCurtainColour($curtainColour); 
                $orderItem->setProductType("Curtain");
                $orderItem->setQuantity($eyeletQuantity);
                $orderItem->setSize($curtainPrice->getSize());
                $orderItem->setSubtotal($eyeletQuantity * $price);
                $orderItem->setOrder($order);
                $orderItem->setProductCategoryId($curtainColour->getId());

                $orderItems->add($orderItem);
            }

            if ($curtainPrice->getType() == 'HomeWindow') {
                $homeWindowIndex = $homeWindowIndex + 1;
            } else if ($curtainPrice->getType() == 'CaravanWindow') {
                $caravanWindowIndex = $caravanWindowIndex + 1;
            } else if ($curtainPrice->getType() == 'CaravanDoor') {
                $caravanDoorIndex = $caravanDoorIndex + 1;
            }    
        }
        
        $i = 0;
        
        foreach($curtainPelmets as $curtainPelmet)
        {
            $quantity = $this->getRequest()->get('pelmet_'.$i);
            
            if($quantity <> 0) {
                    
                    $orderItem = new OrderItem();
                    
                    $orderItem->setColour($curtainColour->getName());       
                    $orderItem->setDescription("Pelmet"); 
                    $orderItem->setItemFilepath("/img/products/".$curtainColour->getThumbnailFilepath());
                    $orderItem->setName($curtainDesign->getName());
                    
                    if ($curtainColour->getOnOffer() == 0) {
                        $price = $curtainPelmet->getPrice();
                    } else {
                        $price = number_format(round($curtainPelmet->getPrice() * $discount,2),2);
                    }
                    $orderItem->setPrice($price);
                    $orderItem->setCurtainColour($curtainColour); 
                    $orderItem->setProductType("Pelmet");
                    $orderItem->setQuantity($quantity);
                    $orderItem->setSize($curtainPelmet->getSize());
                    $orderItem->setSubtotal($quantity * $price);
                    $orderItem->setOrder($order);
                    $orderItem->setProductCategoryId($curtainColour->getId());
                    
                    $orderItems->add($orderItem);
            }
            
            $i = $i + 1;
        }
        
        $i=0;
        
        foreach($cushionCovers as $cushionCover)
        {
            $quantity = $this->getRequest()->get('cushion_'.$i);
            
            if($quantity <> 0) {
                    
                    $orderItem = new OrderItem();
                    
                    $orderItem->setColour($curtainColour->getName());       
                    $orderItem->setDescription("Cushion Cover"); 
                    $orderItem->setItemFilepath("/img/products/".$curtainColour->getThumbnailFilepath());
                    $orderItem->setName($curtainDesign->getName());
                    
                    if ($curtainColour->getOnOffer() == 0) {
                        $price = $cushionCover->getPrice();
                    } else {
                        $price = number_format(round($cushionCover->getPrice() * $discount,2),2);
                    }
                    $orderItem->setPrice($price);
                    $orderItem->setCurtainColour($curtainColour); 
                    $orderItem->setProductType("Cushion Cover");
                    $orderItem->setQuantity($quantity);
                    $orderItem->setSize($cushionCover->getSize());
                    $orderItem->setSubtotal($quantity * $price);
                    $orderItem->setOrder($order);
                    $orderItem->setProductCategoryId($curtainColour->getId());
                    
                    $orderItems->add($orderItem);
            }
            
            $i = $i + 1;
        }
        
        $curtainFabric = $curtainFabrics->first();
        $quantity = $this->getRequest()->get('fabric');     
                
        if($quantity <> 0) {

            $orderItem = new OrderItem();

            $orderItem->setColour($curtainColour->getName());       
            $orderItem->setDescription("Fabric Only"); 
            $orderItem->setItemFilepath("/img/products/".$curtainColour->getThumbnailFilepath());
            $orderItem->setName($curtainDesign->getName());
            
            if ($curtainColour->getOnOffer() == 0) {
                $price = $curtainFabric->getPricePerMetre();
            } else {
                $price = number_format(round($curtainFabric->getPricePerMetre() * $discount,2),2);
            }
            $orderItem->setPrice($price);
            $orderItem->setCurtainColour($curtainColour); 
            $orderItem->setProductType("Fabric");
            $orderItem->setFabricQuantity($quantity);
            $orderItem->setSize($curtainDesign->getFabricWidth());
            $orderItem->setSubtotal($quantity * $price);
            $orderItem->setOrder($order);
            $orderItem->setProductCategoryId($curtainColour->getId());

            $orderItems->add($orderItem);
        }
            
        $homeTieBackIndex = 0;
        $caravanTieBackIndex = 0;
       
        foreach($curtainTieBacks as $curtainTieBack) {
            
            if ($curtainTieBack->getType() == 'HomeWindow') {
                $quantity = $this->getRequest()->get('home_tieback_'.$homeTieBackIndex);
            } else if ($curtainTieBack->getType() == 'CaravanWindow') {
                $quantity = $this->getRequest()->get('caravan_tieback_'.$caravanTieBackIndex);
            }
            
            if($quantity <> 0) {
                    
                $orderItem = new OrderItem();

                $orderItem->setColour($curtainColour->getName());       
                $orderItem->setDescription("Tieback (pair)"); 
                $orderItem->setItemFilepath("/img/products/".$curtainColour->getThumbnailFilepath());   
                $orderItem->setName($curtainDesign->getName());
                
                if ($curtainColour->getOnOffer() == 0) {
                    $price = $curtainTieBack->getPrice();
                } else {
                    $price = number_format(round($curtainTieBack->getPrice() * $discount,2),2);
                }
                $orderItem->setPrice($price);
                $orderItem->setCurtainColour($curtainColour); 
                $orderItem->setProductType("Tieback");
                $orderItem->setQuantity($quantity);
                $orderItem->setSize($curtainTieBack->getSize());
                $orderItem->setSubtotal($quantity * $price);
                $orderItem->setOrder($order);
                $orderItem->setProductCategoryId($curtainColour->getId());

                $orderItems->add($orderItem);
            }
            
            if ($curtainTieBack->getType() == 'HomeWindow') {
                $homeTieBackIndex = $homeTieBackIndex + 1;
            } else if ($curtainTieBack->getType() == 'CaravanWindow') {
                $caravanTieBackIndex = $caravanTieBackIndex + 1;
            } 
        
        }
        
        $order->updateOrderTotal();
        
        return $this->viewAction();
        
    }
    
    public function viewAction()
    {
        if ($this->getNumBasketItems() == 0)
        {
            return $this->render('ElmetSiteBundle:Order:view_empty.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
        }
        else
        {
            return $this->render('ElmetSiteBundle:Order:view.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems(),'order' => $this->getOrder()));
        }
    }
    
    public function emptyAction()
    {
        $order = new Order();
        $session = $this->getRequest()->getSession();
        $session->set('order', $order);
        return $this->render('ElmetSiteBundle:Order:view_empty.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function submitAction()
    {
        $confirmAndPayAction = $this->getRequest()->get('confirm');
        
        if ($confirmAndPayAction == null)
        {
            $order = $this->getOrder();
            $i = 0;
            
            foreach($order->getOrderItems() as $orderItem)
            {
                if($orderItem->getProductType() == 'Curtain')
                {
                    $orderItem->setDropAlteration($this->getRequest()->get('item_'.$i));
                }
                
                $i = $i + 1;
            }
            
            return $this->render('ElmetSiteBundle:Order:view.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems(),'order' => $this->getOrder())); 
        }
        else
        {
            $order = $this->getOrder();
            $order->setOrderStatus("Pending");
            
            $em = $this->getDoctrine()->getEntityManager();
            
            //unfortunately doctrine merge can not appear to cope with changes to the
            //structure of the object graph, which will occur if the customer has added
            //or removed order items since they last confirmed their order. In this case
            //best to delete the old order and create a new order
            
            if ($order->getId() <> null){
           
                $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
                $oldOrder = $repository->findOneById($order->getId());
                $em->remove($oldOrder);
                
            }
            
            $em->persist($order);
            
            
            $em->flush();
            
            return $this->render('ElmetSiteBundle:Order:customer_details.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems(),'order' => $this->getOrder()));
        }

    }
    
    public function removeAction($id)
    {
        $order = $this->getOrder();
        
        $num = substr($id,0,strpos($id,"_"));
        
        $order->removeOrderItem($num);
         
        if (count($order->getOrderItems()) == 0)
        {
            return $this->emptyAction();
        }
        else
        {
            $order->updateOrderTotal();
            
            return $this->render('ElmetSiteBundle:Order:view.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems(),'order' => $this->getOrder())); 
            
        }
    }
    
    public function cardAction()
    {
        return $this->render('ElmetSiteBundle:Order:pay_using_card.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function paypalAction()
    {
        return $this->render('ElmetSiteBundle:Order:pay_using_paypal.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function thankyouAction()
    {
        $order = new Order();
        $session = $this->getRequest()->getSession();
        $session->set('order', $order);
                
        return $this->render('ElmetSiteBundle:Order:thank_you.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
}

?>
