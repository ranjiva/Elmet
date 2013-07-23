<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\Order;
use Elmet\SiteBundle\Entity\OrderItem;

class DeliveryControllerTest extends WebTestCase
{
    var $order;
    var $em;
    
    private function createOrder() {
 
        $repository = $this->em->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneById('2');
                   
        $order = new Order();   
        $order->setBillingAddress("20 Sussex Gardens");
        $order->setBillingAddress2("Ancells Park");
        $order->setBillingName("Ranjiva Prasad");
        $order->setBillingPostcode("GU51 2TL");
        $order->setBillingTown("Fleet");
        $order->setDeliveryAddress("8 Southwood Close");
        $order->setDeliveryAddress2("Great Lever");
        $order->setDeliveryName("R Prasad");
        $order->setDeliveryPostcode("BL3 2DJ");
        $order->setDeliveryTown("Bolton");
        $order->setEmail("ranjiva@yahoo.com");
        $order->setFirstName("Ranjiva");
        $order->setLastName("Prasad");
        $order->setMobile("07769901335");
        $order->setNotes("Ring the Doorbell");
        $order->setOrderStatus("Paid");
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
        
        $this->order = $this->createOrder();
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->order);
        
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/delivery/view/'.$this->order->getId());  
        $viewCrawler = $client->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('input[name="name"]')->attr('value') == 'R Prasad');
        $this->assertTrue($viewCrawler->filter('input[name="address1"]')->attr('value') == '8 Southwood Close');
        $this->assertTrue($viewCrawler->filter('input[name="address2"]')->attr('value') == 'Great Lever');
        $this->assertTrue($viewCrawler->filter('input[name="town"]')->attr('value') == 'Bolton');
        $this->assertTrue($viewCrawler->filter('input[name="postcode"]')->attr('value') == 'BL3 2DJ');
        
    }
    
    public function testUpdate() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/delivery/view/'.$this->order->getId());  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('submit');
        $form = $editCrawlerNode->form();
        
        $form['name'] = 'Sabina Prasad';
        $form['address1'] = '20 Sussex Gardens';
        $form['address2'] = 'Ancells Farm';
        $form['town'] = 'Fleet';
        $form['postcode'] = 'GU51 2TL';
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('input[name="name"]')->attr('value') == 'Sabina Prasad');
        $this->assertTrue($updateCrawler->filter('input[name="address1"]')->attr('value') == '20 Sussex Gardens');
        $this->assertTrue($updateCrawler->filter('input[name="address2"]')->attr('value') == 'Ancells Farm');
        $this->assertTrue($updateCrawler->filter('input[name="town"]')->attr('value') == 'Fleet');
        $this->assertTrue($updateCrawler->filter('input[name="postcode"]')->attr('value') == 'GU51 2TL');
        
    }
}

