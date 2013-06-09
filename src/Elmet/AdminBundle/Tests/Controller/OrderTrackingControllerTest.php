<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\Order;
use Elmet\SiteBundle\Entity\OrderItem;
use Elmet\AdminBundle\Entity\OrderTracking;
use Elmet\AdminBundle\Entity\Batch;

class OrderTrackingControllerTest extends WebTestCase
{
    var $trackingDetails;
    var $em;
    var $oldCurrentBatch;
    var $oldNextBatch;
    var $newCurrentBatch;
    var $newNextBatch;
    
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
        
        $repository = $this->em->getRepository('ElmetAdminBundle:Batch');
        
        $this->oldCurrentBatch = $repository->findOneBy(array('batch_status' => 'Current'));
        $this->oldNextBatch = $repository->findOneBy(array('batch_status' => 'Next'));
        
        $this->oldCurrentBatch->setBatchStatus('Closed');
        $this->oldNextBatch->setBatchStatus('Closed');
        
        $this->em->merge($this->oldCurrentBatch);
        $this->em->merge($this->oldNextBatch);
        
        $this->newCurrentBatch = new Batch();
        $this->newCurrentBatch->setNextItemId(1);
        $this->newCurrentBatch->setBatchStatus('Current');
        
        $this->newNextBatch = new Batch();
        $this->newNextBatch->setNextItemId(1);
        $this->newNextBatch->setBatchStatus('Next');
        
        $this->em->persist($this->newCurrentBatch);
        $this->em->persist($this->newNextBatch);
        
        $this->em->flush();
    }
    
    protected function tearDown() {
          
        foreach($this->trackingDetails as $trackingDetail) {
            $this->em->remove($trackingDetail);
            $this->em->remove($trackingDetail->getOrder());
        }
        
        $this->em->remove($this->newCurrentBatch);
        $this->em->remove($this->newNextBatch);
        
        $this->oldCurrentBatch->setBatchStatus('Current');
        $this->oldNextBatch->setBatchStatus('Next');
        
        $this->em->merge($this->oldCurrentBatch);
        $this->em->merge($this->oldNextBatch);
        
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/ordertracking/view');  
        $viewCrawler = $client->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('td:contains("'.$this->trackingDetails[0]->getOrder()->getId().'")')->count() == 1);
        $this->assertTrue($viewCrawler->filter('td:contains("'.$this->trackingDetails[1]->getOrder()->getId().'")')->count() == 1);
        $this->assertTrue($viewCrawler->filter('td:contains("'.$this->trackingDetails[2]->getOrder()->getId().'")')->count() == 1);
    }
    
    public function testProcess() {
        
        $file = "temp.jpg";
        $fh = fopen($file, 'w');
        fclose($fh);
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/ordertracking/view');  
        $viewCrawler = $client->followRedirect();
        
        $processCrawlerNode = $viewCrawler->selectButton('process');
        $form = $processCrawlerNode->form();
        
        $form['order_'.$this->trackingDetails[0]->getId()] = 'selected';
        $form['order_'.$this->trackingDetails[2]->getId()] = 'selected';
        $form['batch'] = 'Current';
        $form['tracking_file']->upload('temp.jpg');
        
        $updateCrawler = $client->submit($form);
                
        $firstItemId = trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("'.$this->trackingDetails[0]->getOrder()->getId().'")')->siblings()->eq(0)->text()));
        $secondItemId = trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("'.$this->trackingDetails[2]->getOrder()->getId().'")')->siblings()->eq(0)->text()));
        
        $this->assertTrue(($firstItemId == '1') || ($secondItemId == '1'));
        $this->assertTrue(($firstItemId == '2') || ($secondItemId == '2'));
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("'.$this->trackingDetails[0]->getOrder()->getId().'")')->siblings()->eq(1)->text())) == 'Current');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("'.$this->trackingDetails[0]->getOrder()->getId().'")')->siblings()->eq(3)->text())) == 'In Progress');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("'.$this->trackingDetails[1]->getOrder()->getId().'")')->siblings()->eq(3)->text())) == 'Received');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("'.$this->trackingDetails[2]->getOrder()->getId().'")')->siblings()->eq(1)->text())) == 'Current');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("'.$this->trackingDetails[2]->getOrder()->getId().'")')->siblings()->eq(3)->text())) == 'In Progress');
    }
    
    public function testViewDetails() {
       
       $trackingDetail = $this->trackingDetails[2];
       
       $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', '/admin/ordertracking/details/'.$trackingDetail->getOrder()->getId());  
        $detailsCrawler = $client->followRedirect();     
        
        $this->assertTrue($detailsCrawler->filterXPath('//div[contains(string(),": '.$trackingDetail->getOrder()->getDeliveryPostCode().'")]')->count() > 0);
   }
    
   public function testViewWorkSheetDetails() {
       
        $firstTrackingDetail = $this->trackingDetails[0];
        $secondTrackingDetail = $this->trackingDetails[2];
        
        $firstTrackingDetail->setTrackingStatus("In Progress");
        $secondTrackingDetail->setTrackingStatus("In Progress");
        $firstTrackingDetail->setBatch($this->newCurrentBatch);
        $secondTrackingDetail->setBatch($this->newCurrentBatch);
        
        $this->em->merge($firstTrackingDetail);
        $this->em->merge($secondTrackingDetail);
        $this->em->flush();
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));        
        
        $client->request('GET', '/admin/ordertracking/worksheet/Current');
        $workSheetCrawler = $client->followRedirect();
        
        $this->assertTrue($workSheetCrawler->filter('td:contains("'.$this->trackingDetails[0]->getOrder()->getBillingName().'")')->text() == 'R K Prasad');
        $this->assertTrue($workSheetCrawler->filter('td:contains("'.$this->trackingDetails[1]->getOrder()->getBillingName().'")')->count() == 0);
        $this->assertTrue($workSheetCrawler->filter('td:contains("'.$this->trackingDetails[2]->getOrder()->getBillingName().'")')->text() == 'T Prasad');
        
   }
    
   public function testCancelDetails() {
            
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));        
        
        $client->request('GET', '/admin/ordertracking/cancel/'.$this->trackingDetails[0]->getId());
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
        
        $firstTrackingDetail = $this->trackingDetails[0];
        $secondTrackingDetail = $this->trackingDetails[2];
        
        $firstTrackingDetail->setTrackingStatus("In Progress");
        $secondTrackingDetail->setTrackingStatus("Processed");
        $firstTrackingDetail->setBatch($this->newCurrentBatch);
        $secondTrackingDetail->setBatch($this->newCurrentBatch);
        
        $this->em->merge($firstTrackingDetail);
        $this->em->merge($secondTrackingDetail);
        $this->em->flush();
       
        $file = "temp.jpg";
        $fh = fopen($file, 'w');
        fclose($fh);
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/ordertracking/view');  
        $viewCrawler = $client->followRedirect();
        
        $dispatchCrawlerNode = $viewCrawler->selectButton('dispatch');
        $form = $dispatchCrawlerNode->form();
        
        $form['order_'.$this->trackingDetails[0]->getId()] = 'selected';
        $form['order_'.$this->trackingDetails[2]->getId()] = 'selected';
        $form['dispatch_date'] = '05/06/13';
        $form['tracking_file']->upload('temp.jpg');
        
        $updateCrawler = $client->submit($form);
        
        $body = $client->getResponse()->getContent();
                
        $lines = explode("\n",$body);

        $firstLine = $lines[0];
        $firstLineElements = explode(",",$firstLine);
        
        $secondLine = $lines[1];
        $secondLineElements = explode(",",$secondLine);
        
        $this->assertTrue(($firstLineElements[0] == $this->trackingDetails[0]->getOrder()->getDeliveryName()) || ($secondLineElements[0] == $this->trackingDetails[0]->getOrder()->getDeliveryName()));
        $this->assertTrue(($firstLineElements[1] == $this->trackingDetails[0]->getOrder()->getDeliveryAddress()) || ($secondLineElements[1] == $this->trackingDetails[0]->getOrder()->getDeliveryAddress()));
        $this->assertTrue(($firstLineElements[2] == $this->trackingDetails[0]->getOrder()->getDeliveryAddress2()) || ($secondLineElements[2] == $this->trackingDetails[0]->getOrder()->getDeliveryAddress2()));
        $this->assertTrue(($firstLineElements[3] == $this->trackingDetails[0]->getOrder()->getDeliveryTown()) || ($secondLineElements[3] == $this->trackingDetails[0]->getOrder()->getDeliveryTown()));
        $this->assertTrue(($firstLineElements[4] == $this->trackingDetails[0]->getOrder()->getDeliveryPostcode()) || ($secondLineElements[4] == $this->trackingDetails[0]->getOrder()->getDeliveryPostcode()));
        $this->assertTrue(($firstLineElements[5] == $this->trackingDetails[0]->getOrder()->getEmail()) || ($secondLineElements[5] == $this->trackingDetails[0]->getOrder()->getEmail()));
        $this->assertTrue(($firstLineElements[6] == '05/06/13') || ($secondLineElements[6] == '05/06/13'));
        $this->assertTrue(($firstLineElements[7] == $this->trackingDetails[0]->getOrder()->getId()) || ($secondLineElements[7] == $this->trackingDetails[0]->getOrder()->getId()));
   
        $this->assertTrue(($firstLineElements[0] == $this->trackingDetails[2]->getOrder()->getDeliveryName()) || ($secondLineElements[0] == $this->trackingDetails[2]->getOrder()->getDeliveryName()));
        $this->assertTrue(($firstLineElements[1] == $this->trackingDetails[2]->getOrder()->getDeliveryAddress()) || ($secondLineElements[1] == $this->trackingDetails[2]->getOrder()->getDeliveryAddress()));
        $this->assertTrue(($firstLineElements[2] == $this->trackingDetails[2]->getOrder()->getDeliveryAddress2()) || ($secondLineElements[2] == $this->trackingDetails[2]->getOrder()->getDeliveryAddress2()));
        $this->assertTrue(($firstLineElements[3] == $this->trackingDetails[2]->getOrder()->getDeliveryTown()) || ($secondLineElements[3] == $this->trackingDetails[2]->getOrder()->getDeliveryTown()));
        $this->assertTrue(($firstLineElements[4] == $this->trackingDetails[2]->getOrder()->getDeliveryPostcode()) || ($secondLineElements[4] == $this->trackingDetails[2]->getOrder()->getDeliveryPostcode()));
        $this->assertTrue(($firstLineElements[5] == $this->trackingDetails[2]->getOrder()->getEmail()) || ($secondLineElements[5] == $this->trackingDetails[2]->getOrder()->getEmail()));
        $this->assertTrue(($firstLineElements[6] == '05/06/13') || ($secondLineElements[6] == '05/06/13'));
        $this->assertTrue(($firstLineElements[7] == $this->trackingDetails[2]->getOrder()->getId()) || ($secondLineElements[7] == $this->trackingDetails[2]->getOrder()->getId()));
        
    }
    
    public function testTwoLoadTrackingFile() {
            
        $firstTrackingDetail = $this->trackingDetails[0];
        $secondTrackingDetail = $this->trackingDetails[2];
        $thirdTrackingDetail = $this->trackingDetails[1];
        
        $firstTrackingDetail->setTrackingStatus("Processed");
        $secondTrackingDetail->setTrackingStatus("Processed");
        $thirdTrackingDetail->setTrackingStatus("In Progress");
        
        $firstTrackingDetail->setBatch($this->newCurrentBatch);
        $secondTrackingDetail->setBatch($this->newCurrentBatch);
        $thirdTrackingDetail->setBatch($this->newNextBatch);
        
        $this->em->merge($firstTrackingDetail);
        $this->em->merge($secondTrackingDetail);
        $this->em->merge($thirdTrackingDetail);
        $this->em->flush();
       
        $firstLine = $firstTrackingDetail->getOrder()->getId().',05/06/2013,JA1';
        $secondLine = $secondTrackingDetail->getOrder()->getId().',05/06/2013,JA2';
        
        $file = "tracking.txt";
        $fh = fopen($file, 'w');
        fwrite($fh, $firstLine."\n");
        fwrite($fh, $secondLine);
        fclose($fh);
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/ordertracking/view');  
        $viewCrawler = $client->followRedirect();
        
        $loadCrawlerNode = $viewCrawler->selectButton('load');
        $form = $loadCrawlerNode->form();
        
        $form['tracking_file']->upload('tracking.txt');
        $updateCrawler = $client->submit($form);
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass')); 
        
        $client2->request('GET', 'admin/order/search');  
        $searchCrawler = $client2->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['status'] = 'Dispatched';
        $resultsCrawler = $client2->submit($form);
        
        $firstOrder = $this->trackingDetails[0]->getOrder();
        $secondOrder = $this->trackingDetails[2]->getOrder();
        
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$firstOrder->getId().'")')->count() > 0);
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$secondOrder->getId().'")')->count() > 0);
        
        $client3 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client3->request('GET', 'admin/ordertracking/view');  
        $viewCrawler2 = $client3->followRedirect();
        
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler2->filter('td:contains("'.$thirdTrackingDetail->getOrder()->getId().'")')->siblings()->eq(1)->text())) == 'Current');
        
        $repository = $this->em->getRepository('ElmetAdminBundle:Batch');
        
        $nextBatch = $repository->findOneBy(array('batch_status' => 'Next'));
        
        $this->em->remove($nextBatch);
        $this->em->flush();
    }
    
    public function testOneLoadTrackingFile() {
            
        $firstTrackingDetail = $this->trackingDetails[0];
        $secondTrackingDetail = $this->trackingDetails[2];
        $thirdTrackingDetail = $this->trackingDetails[1];
        
        $firstTrackingDetail->setTrackingStatus("Processed");
        $firstTrackingDetail->setItemId(1);
        $secondTrackingDetail->setTrackingStatus("Processed");
        $secondTrackingDetail->setItemId(2);
        $thirdTrackingDetail->setTrackingStatus("In Progress");
        $thirdTrackingDetail->setItemId(1);
        
        $firstTrackingDetail->setBatch($this->newCurrentBatch);
        $secondTrackingDetail->setBatch($this->newCurrentBatch);
        $thirdTrackingDetail->setBatch($this->newNextBatch);
        
        $this->newCurrentBatch->setNextItemId(3);
        $this->newNextBatch->setNextItemId(2);
        
        $this->em->merge($firstTrackingDetail);
        $this->em->merge($secondTrackingDetail);
        $this->em->merge($thirdTrackingDetail);
        $this->em->merge($this->newCurrentBatch);
        $this->em->merge($this->newNextBatch);
        $this->em->flush();
       
        $firstLine = $firstTrackingDetail->getOrder()->getId().',05/06/2013,JA1';
        
        $file = "tracking.txt";
        $fh = fopen($file, 'w');
        fwrite($fh, $firstLine);
        fclose($fh);
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/ordertracking/view');  
        $viewCrawler = $client->followRedirect();
        
        $loadCrawlerNode = $viewCrawler->selectButton('load');
        $form = $loadCrawlerNode->form();
        
        $form['tracking_file']->upload('tracking.txt');
        $updateCrawler = $client->submit($form);
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass')); 
        
        $client2->request('GET', 'admin/order/search');  
        $searchCrawler = $client2->followRedirect();
        
        $searchCrawlerNode = $searchCrawler->selectButton('Search');
        $form = $searchCrawlerNode->form();
        
        $form['status'] = 'Dispatched';
        $resultsCrawler = $client2->submit($form);
        
        $firstOrder = $this->trackingDetails[0]->getOrder();
        $secondOrder = $this->trackingDetails[2]->getOrder();
        
        $this->assertTrue($resultsCrawler->filter('td:contains("'.$firstOrder->getId().'")')->count() > 0);
        
        $client3 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client3->request('GET', 'admin/ordertracking/view');  
        $viewCrawler2 = $client3->followRedirect();
        
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler2->filter('td:contains("'.$secondTrackingDetail->getOrder()->getId().'")')->siblings()->eq(1)->text())) == 'Current');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("'.$secondTrackingDetail->getOrder()->getId().'")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler2->filter('td:contains("'.$thirdTrackingDetail->getOrder()->getId().'")')->siblings()->eq(1)->text())) == 'Current');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler2->filter('td:contains("'.$thirdTrackingDetail->getOrder()->getId().'")')->siblings()->eq(0)->text())) == '1');
        
        $repository = $this->em->getRepository('ElmetAdminBundle:Batch');
        
        $nextBatch = $repository->findOneBy(array('batch_status' => 'Next'));
        
        $this->em->remove($nextBatch);
        $this->em->flush();
    }
    
    public function testShippingDetails() {
            
        $firstTrackingDetail = $this->trackingDetails[0];
        $secondTrackingDetail = $this->trackingDetails[2];
        $thirdTrackingDetail = $this->trackingDetails[1];
        
        $firstTrackingDetail->setTrackingStatus("Processed");
        $firstTrackingDetail->setItemId(1);
        $secondTrackingDetail->setTrackingStatus("Processed");
        $secondTrackingDetail->setItemId(2);
        $thirdTrackingDetail->setTrackingStatus("In Progress");
        $thirdTrackingDetail->setItemId(1);
        
        $firstTrackingDetail->setBatch($this->newCurrentBatch);
        $secondTrackingDetail->setBatch($this->newCurrentBatch);
        $thirdTrackingDetail->setBatch($this->newNextBatch);
        
        $this->newCurrentBatch->setNextItemId(3);
        $this->newNextBatch->setNextItemId(2);
        
        $this->em->merge($firstTrackingDetail);
        $this->em->merge($secondTrackingDetail);
        $this->em->merge($thirdTrackingDetail);
        $this->em->merge($this->newCurrentBatch);
        $this->em->merge($this->newNextBatch);
        $this->em->flush();
       
        $firstLine = $firstTrackingDetail->getOrder()->getId().',05/06/2013,JA1';
        
        $file = "tracking.txt";
        $fh = fopen($file, 'w');
        fwrite($fh, $firstLine);
        fclose($fh);
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/ordertracking/view');  
        $viewCrawler = $client->followRedirect();
        
        $loadCrawlerNode = $viewCrawler->selectButton('load');
        $form = $loadCrawlerNode->form();
        
        $form['tracking_file']->upload('tracking.txt');
        $updateCrawler = $client->submit($form);
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass')); 
        
        $client2->request('GET', 'admin/ordertracking/shipping/'.$firstTrackingDetail->getOrder()->getId());  
        $shippingCrawler = $client2->followRedirect();
        
        $this->assertTrue($shippingCrawler->filter('td:contains("JA1")')->count() > 0);
        
        $repository = $this->em->getRepository('ElmetAdminBundle:Batch');
        
        $nextBatch = $repository->findOneBy(array('batch_status' => 'Next'));
        
        $this->em->remove($nextBatch);
        $this->em->flush();
    }
    
}

