<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\Order;
use Elmet\SiteBundle\Entity\OrderItem;
use Elmet\AdminBundle\Entity\OrderTracking;

class SimpleOrderTrackingControllerTest extends WebTestCase
{
    var $trackingDetails;
    var $em;
    
    private function createOrderTracking($em,$email,$billingName,$postCode,$status) {
 
        $repository = $this->em->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneById('2');
                   
        $order = new Order();   
        $order->setBillingAddress("20 Sussex Gardens");
        $order->setBillingAddress2("Ancells Park");
        $order->setBillingName($billingName);
        $order->setBillingPostcode($postCode);
        $order->setBillingTown("Fleet");
        $order->setDeliveryAddress("8 Southwood Close");
        $order->setDeliveryAddress2("Great Lever");
        $order->setDeliveryName("Ranjiva Prasad");
        $order->setDeliveryPostcode($postCode);
        $order->setDeliveryTown("Bolton");
        $order->setEmail($email);
        $order->setFirstName("Ranjiva");
        $order->setLastName("Prasad");
        $order->setMobile("07769901335");
        $order->setNotes("Ring the Doorbell");
        $order->setOrderStatus($status);
        $order->setTelephone("01252 643872");
       
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
        $orderItem->setSize("52 X 90");
        $orderItem->setSubtotal(100.25);
        $orderItem->setProductCategoryId($curtainColour->getId());
        $orderItem->setOrder($order);
        
        $orderItems->add($orderItem);
        $order->updateOrderTotal();
        
        $orderTracking = new OrderTracking();
        $orderTracking->setOrder($order);
        $orderTracking->setTrackingStatus('Received');
        
        $em->persist($order);
        $em->persist($orderTracking);
        
        $em->flush();
        
        return $orderTracking;
    }
    
    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $this->trackingDetails = array();
        
        $this->trackingDetails[] = $this->createOrderTracking($this->em,"ranjiva.prasad@gmail.com", "R K Prasad", "GU51 3UP", "Paid");
        $this->trackingDetails[] = $this->createOrderTracking($this->em,"ranjiva.prasad@gmail.com", "S Prasad", "GU51 2TL", "Paid");
        $this->trackingDetails[] = $this->createOrderTracking($this->em,"orders@elmetcurtains.co.uk", "T Prasad", "GU51 3UP", "Paid");
        
    }
    
    protected function tearDown() {
          
        foreach($this->trackingDetails as $trackingDetail) {
            $this->em->remove($trackingDetail);
            $this->em->remove($trackingDetail->getOrder());
        }
         
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/simpleordertracking/view');  
        $viewCrawler = $client->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('td:contains("'.$this->trackingDetails[0]->getOrder()->getId().'")')->count() == 1);
        $this->assertTrue($viewCrawler->filter('td:contains("'.$this->trackingDetails[1]->getOrder()->getId().'")')->count() == 1);
        $this->assertTrue($viewCrawler->filter('td:contains("'.$this->trackingDetails[2]->getOrder()->getId().'")')->count() == 1);
    }
      
   public function testCancelDetails() {
            
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));        
        
        $client->request('GET', '/admin/simpleordertracking/cancel/'.$this->trackingDetails[0]->getId());
        $cancelCrawler = $client->followRedirect();

        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass')); 
        
        $client2->request('GET', 'admin/order/search');  
        $searchCrawler = $client2->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['status'] = 'Cancelled';
        $resultsCrawler = $client2->submit($form);
        
        $order = $this->trackingDetails[0]->getOrder();
        
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order->getId().'")')->count() > 0);
   }
   
   public function testDispatch() {
            
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));        
        
        $client->request('GET', '/admin/simpleordertracking/dispatch/'.$this->trackingDetails[0]->getId());
        $dispatchCrawler = $client->followRedirect();

        $sendCrawlerNode = $dispatchCrawler->selectButton('Send');
        $form = $sendCrawlerNode->form();
        
        $form['trackingnumber'] = 'JS205867';
        $form['dispatchdate'] = '09/02/15';
        
        $confirmationCrawler = $client->submit($form);
        
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass')); 
        
        $client2->request('GET', 'admin/order/search');  
        $searchCrawler = $client2->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['status'] = 'Dispatched';
        $resultsCrawler = $client2->submit($form);
        
        $order = $this->trackingDetails[0]->getOrder();
        
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order->getId().'")')->count() > 0);
        
        $client3 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass')); 
        
        $client3->request('GET','/admin/ordertracking/shipping/'.$this->trackingDetails[0]->getOrder()->getId());
        
        $shippingCrawler = $client3->followRedirect();
        
        $this->assertTrue($shippingCrawler->filter('td:contains("JS205867")')->count() > 0);
        $this->assertTrue($shippingCrawler->filter('td:contains("Monday 9th February 2015")')->count() > 0);
        
        
        
   }
   
    
}

