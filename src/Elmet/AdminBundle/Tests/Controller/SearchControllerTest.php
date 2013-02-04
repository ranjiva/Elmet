<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\Order;
use Elmet\SiteBundle\Entity\OrderItem;

class SearchControllerTest extends WebTestCase
{
    var $orders;
    var $em;
    
    private function createOrder($em,$email,$billingName,$postCode,$status) {
 
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
        $order->setDeliveryPostcode("BL3 2DJ");
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
        
        $em->persist($order);
        
        $em->flush();
        
        return $order;
    }
    
    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $this->orders = array();
        
        $this->orders[] = $this->createOrder($this->em,"ranjiva.prasad@gmail.com", "R K Prasad", "GU51 3UP", "Paid");
        $this->orders[] = $this->createOrder($this->em,"ranjiva.prasad@gmail.com", "S Prasad", "GU51 2TL", "Paid");
        $this->orders[] = $this->createOrder($this->em,"orders@elmetcurtains.co.uk", "S Prasad", "GU51 3UP", "Pending");
    }
    
    protected function tearDown() {
          
        foreach($this->orders as $order) {
            $this->em->remove($order);
        }
        
        $this->em->flush();
    }
    
    public function testSearchByPayPal() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $order = $this->orders[2];
        
        $form['custom'] = $order->getId();
        $resultsCrawler = $client->submit($form);
        
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order->getId().'")')->count() == 1);
        $this->assertTrue($resultsCrawler->filterXPath('//div/table/tbody/tr')->count() == 1);
       
    }
    
    public function testSearchByEmail() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['email'] = 'ranjiva.prasad@gmail.com';
        
        $resultsCrawler = $client->submit($form);
        
        $this->assertTrue($resultsCrawler->filter('td:contains("ranjiva.prasad@gmail.com")')->count() == 2);
        $this->assertTrue($resultsCrawler->filterXPath('//div/table/tbody/tr')->count() == 2);
        
    }
    
    public function testSearchByPostCode() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['postcode'] = 'GU51 3UP';
     
        $resultsCrawler = $client->submit($form);
        
        $this->assertTrue($resultsCrawler->filter('td:contains("GU51 3UP")')->count() == 2);
        $this->assertTrue($resultsCrawler->filterXPath('//div/table/tbody/tr')->count() == 2);
        $this->assertTrue($resultsCrawler->filter('td:contains("ranjiva.prasad@gmail.com")')->count() == 1);
        $this->assertTrue($resultsCrawler->filter('td:contains("orders@elmetcurtains.co.uk")')->count() == 1);
        
    }
    
    public function testSearchByBillingName() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['name'] = 'S Prasad';
        
        $resultsCrawler = $client->submit($form);
        
        $this->assertTrue($resultsCrawler->filter('td:contains("S Prasad")')->count() == 2);
        $this->assertTrue($resultsCrawler->filterXPath('//div/table/tbody/tr')->count() == 2);
        $this->assertTrue($resultsCrawler->filter('td:contains("ranjiva.prasad@gmail.com")')->count() == 1);
        $this->assertTrue($resultsCrawler->filter('td:contains("orders@elmetcurtains.co.uk")')->count() == 1);
        
    }
    
    public function testSearchByStatus() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['status'] = 'Paid';
        $resultsCrawler = $client->submit($form);
        
        $order1 = $this->orders[0];
        $order2 = $this->orders[1];
        $order3 = $this->orders[2];
        
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order1->getId().'")')->count() > 0);
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order2->getId().'")')->count() > 0);
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order3->getId().'")')->count() == 0);
   }
    
    public function testSearchByNoneExceptPayPal() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $order = $this->orders[2];
        
        $form['custom'] = $order->getId();
        $form['email'] = 'ranjiva.prasad@gmail.com';
        $form['postcode'] = 'GU51 3UP';
        $form['name'] = 'S Prasad';
        $form['status'] = 'Paid';
        $resultsCrawler = $client->submit($form);
        
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order->getId().'")')->count() == 1);
        $this->assertTrue($resultsCrawler->filterXPath('//div/table/tbody/tr')->count() == 1);
       
    }
    
    public function testSearchByNoneExceptEmail() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['custom'] = '-1';
        $form['email'] = 'ranjiva.prasad@gmail.com';
        $form['postcode'] = 'GU51 3UP';
        $form['name'] = 'S Prasad';
        $form['status'] = 'Paid';
        
        $resultsCrawler = $client->submit($form);
        
        $this->assertTrue($resultsCrawler->filter('td:contains("ranjiva.prasad@gmail.com")')->count() == 2);
        $this->assertTrue($resultsCrawler->filterXPath('//div/table/tbody/tr')->count() == 2);
        
    }
    
    public function testSearchByNoneExceptPostCode() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['custom'] = '-1';
        $form['email'] = '-1';
        $form['postcode'] = 'GU51 3UP';
        $form['name'] = 'S Prasad';
        $form['status'] = 'Paid';
     
        $resultsCrawler = $client->submit($form);
        
        $this->assertTrue($resultsCrawler->filter('td:contains("GU51 3UP")')->count() == 2);
        $this->assertTrue($resultsCrawler->filterXPath('//div/table/tbody/tr')->count() == 2);
        $this->assertTrue($resultsCrawler->filter('td:contains("ranjiva.prasad@gmail.com")')->count() == 1);
        $this->assertTrue($resultsCrawler->filter('td:contains("orders@elmetcurtains.co.uk")')->count() == 1);
        
    }
    
    public function testSearchByNoneExceptBillingName() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['custom'] = '-1';
        $form['email'] = '-1';
        $form['postcode'] = '-1';
        $form['name'] = 'S Prasad';
        $form['status'] = 'Paid';
        
        $resultsCrawler = $client->submit($form);
        
        $this->assertTrue($resultsCrawler->filter('td:contains("S Prasad")')->count() == 2);
        $this->assertTrue($resultsCrawler->filterXPath('//div/table/tbody/tr')->count() == 2);
        $this->assertTrue($resultsCrawler->filter('td:contains("ranjiva.prasad@gmail.com")')->count() == 1);
        $this->assertTrue($resultsCrawler->filter('td:contains("orders@elmetcurtains.co.uk")')->count() == 1);
        
    }
    
    public function testSearchByNoneExceptStatus() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/order/search');  
        $searchCrawler = $client->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['custom'] = '-1';
        $form['email'] = '-1';
        $form['name'] = '-1';
        $form['postcode'] = '-1';
        $form['status'] = 'Paid';
        $resultsCrawler = $client->submit($form);
        
        $order1 = $this->orders[0];
        $order2 = $this->orders[1];
        $order3 = $this->orders[2];
        
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order1->getId().'")')->count() > 0);
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order2->getId().'")')->count() > 0);
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$order3->getId().'")')->count() == 0);
   }
  
}

