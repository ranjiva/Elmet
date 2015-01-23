<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainDesign;
use Elmet\SiteBundle\Entity\CurtainColour;

class CurtainColourControllerTest extends WebTestCase
{
    var $curtainDesign;
    var $em;
    var $repository;
    var $secondCurtainDesign;
    var $thirdCurtainDesign;

    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $this->repository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainColour');

        $cpbRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainPriceBand');        
        
        $curtainPriceBand = $cpbRepository->findOneById(2);
        
        $this->curtainDesign = new CurtainDesign();
        $this->curtainDesign->setCurtainPriceBand($curtainPriceBand);
        $this->curtainDesign->setUrlName("loretta");
        $this->curtainDesign->setCushionFinish("Corded");
        $this->curtainDesign->setEyeletsAvailable(1);
        $this->curtainDesign->setFabricWidth(140);
        $this->curtainDesign->setFinish("Fringed");
        $this->curtainDesign->setLined(1);
        $this->curtainDesign->setMaterials("Polyester/Cotton");
        $this->curtainDesign->setName("Lorretta Ready Made Curtains");
        $this->curtainDesign->setNew(1);
        $this->curtainDesign->setPatternRepeatLength(8.00);
        $this->curtainDesign->setTapeSize("3\"");
        $this->curtainDesign->setPosition("0");
        $this->curtainDesign->setDisplay("1");
        $this->curtainDesign->setSpecialPurchase("0");
        
        $curtainColour = new CurtainColour();
        $curtainColour->setName("Cream");
        $curtainColour->setFullFilepath("loretta/lorretta_cream.jpg");
        $curtainColour->setThumbnailFilepath("loretta/lorretta_cream_detail.jpg");
        $curtainColour->setSwatchFilepath("loretta/lorretta_cream_t.jpg");
        $curtainColour->setBuynow(1);
        $curtainColour->setInStock(1);
        $curtainColour->setOnOffer(1);
        $curtainColour->setDisplay(1);
        $curtainColour->setPosition(1);
        $curtainColour->setDiscountPercentage(20);
        $curtainColour->setAvailableStock(20.0);
        $curtainColour->setCurtainDesign($this->curtainDesign);
        $this->curtainDesign->addCurtainColour($curtainColour);
        
        $secondCurtainColour = new CurtainColour();
        $secondCurtainColour->setName("Blue");
        $secondCurtainColour->setFullFilepath("loretta/lorretta_blue.jpg");
        $secondCurtainColour->setThumbnailFilepath("loretta/lorretta_bl, aque_detail.jpg");
        $secondCurtainColour->setSwatchFilepath("loretta/lorretta_blue_t.jpg");
        $secondCurtainColour->setBuynow(1);
        $secondCurtainColour->setInStock(1);
        $secondCurtainColour->setOnOffer(1);
        $secondCurtainColour->setDisplay(0);
        $secondCurtainColour->setPosition(0);
        $secondCurtainColour->setDiscountPercentage(20);
        $secondCurtainColour->setAvailableStock(30.0);
        $secondCurtainColour->setCurtainDesign($this->curtainDesign);
        $this->curtainDesign->addCurtainColour($secondCurtainColour);
        
        $this->secondCurtainDesign = new CurtainDesign();
        $this->secondCurtainDesign->setCurtainPriceBand($curtainPriceBand);
        $this->secondCurtainDesign->setUrlName("jeanetta");
        $this->secondCurtainDesign->setCushionFinish("Corded");
        $this->secondCurtainDesign->setEyeletsAvailable(1);
        $this->secondCurtainDesign->setFabricWidth(140);
        $this->secondCurtainDesign->setFinish("Fringed");
        $this->secondCurtainDesign->setLined(1);
        $this->secondCurtainDesign->setMaterials("Polyester/Cotton");
        $this->secondCurtainDesign->setName("Jeanetta Ready Made Curtains");
        $this->secondCurtainDesign->setNew(1);
        $this->secondCurtainDesign->setPatternRepeatLength(8.00);
        $this->secondCurtainDesign->setTapeSize("3\"");
        $this->secondCurtainDesign->setPosition("0");
        $this->secondCurtainDesign->setDisplay("1");
        $this->secondCurtainDesign->setSpecialPurchase("0");
        
        $this->thirdCurtainDesign = new CurtainDesign();
        $this->thirdCurtainDesign->setCurtainPriceBand($curtainPriceBand);
        $this->thirdCurtainDesign->setUrlName("beanetta");
        $this->thirdCurtainDesign->setCushionFinish("Corded");
        $this->thirdCurtainDesign->setEyeletsAvailable(1);
        $this->thirdCurtainDesign->setFabricWidth(140);
        $this->thirdCurtainDesign->setFinish("Fringed");
        $this->thirdCurtainDesign->setLined(1);
        $this->thirdCurtainDesign->setMaterials("Polyester/Cotton");
        $this->thirdCurtainDesign->setName("Beanetta Ready Made Curtains");
        $this->thirdCurtainDesign->setNew(1);
        $this->thirdCurtainDesign->setPatternRepeatLength(8.00);
        $this->thirdCurtainDesign->setTapeSize("3\"");
        $this->thirdCurtainDesign->setPosition("0");
        $this->thirdCurtainDesign->setDisplay("1");
        $this->thirdCurtainDesign->setSpecialPurchase("0");
        
        $thirdCurtainColour = new CurtainColour();
        $thirdCurtainColour->setName("Cream");
        $thirdCurtainColour->setFullFilepath("beanetta/beanetta_cream.jpg");
        $thirdCurtainColour->setThumbnailFilepath("beanetta/beanetta_cream_detail.jpg");
        $thirdCurtainColour->setSwatchFilepath("beanetta/beanetta_cream_t.jpg");
        $thirdCurtainColour->setBuynow(1);
        $thirdCurtainColour->setInStock(1);
        $thirdCurtainColour->setOnOffer(0);
        $thirdCurtainColour->setDisplay(1);
        $thirdCurtainColour->setPosition(1);
        $thirdCurtainColour->setDiscountPercentage(0);
        $thirdCurtainColour->setAvailableStock(20.0);
        $thirdCurtainColour->setCurtainDesign($this->thirdCurtainDesign);
        $this->thirdCurtainDesign->addCurtainColour($thirdCurtainColour);
        
        $this->em->persist($this->curtainDesign);
        $this->em->persist($this->secondCurtainDesign);
        $this->em->persist($this->thirdCurtainDesign);
        $this->em->flush();
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainDesign);
        $this->em->remove($this->secondCurtainDesign);
        $this->em->remove($this->thirdCurtainDesign);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $curtainColour = $this->curtainDesign->getCurtainColours()->get(0);
        
        $client->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $viewCrawler = $client->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('input[name="name"]')->attr('value') == $curtainColour->getName());
        $this->assertTrue($viewCrawler->filter('input[name="stock"]')->attr('value') == $curtainColour->getAvailableStock());
        $this->assertTrue($viewCrawler->filter('select[name="buynow"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[name="instock"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[name="onoffer"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[id="onDisplayFixed"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('input[name="discount"]')->attr('value') == $curtainColour->getDiscountPercentage());
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));
        
        $curtainColour = $this->curtainDesign->getCurtainColours()->get(1);
        
        $client2->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $viewCrawler = $client2->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('select[id="onDisplay"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
       
    }
         
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $curtainColour = $this->curtainDesign->getCurtainColours()->get(0);
        
        $client->request('GET', 'admin/curtaincolour/remove/'.$curtainColour->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Cream') {
                                                        return false;
                                                    }
                                                })->count() == 0);
        $this->assertTrue($viewCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Blue') {
                                                        return false;
                                                    }
                                                })->count() == 1);
  
    }
    
    public function testEdit() {
                
        $file_full = "loretta_grey2.jpg";
        $fh_full = fopen($file_full, 'w');
        fclose($fh_full);
        
        $file_thumb = "loretta_grey_t2.jpg";
        $fh_thumb = fopen($file_thumb, 'w');
        fclose($fh_thumb);
        
        $file_detail = "loretta_grey_detail2.jpg";
        $fh_detail = fopen($file_detail, 'w');
        fclose($fh_detail);
 
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $curtainColour = $this->curtainDesign->getCurtainColours()->get(1);
        
        $client->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('Save');
        $form = $editCrawlerNode->form();
        
        $form['name'] = 'Grey2'; 
        $form['instock']->select('0');
        $form['buynow']->select('0');
        $form['stock'] = 35.5;
        $form['onoffer'] = 0;
        $form['onDisplay'] = 1;
        $form['position'] = 10;
        $form['display']->upload('loretta_grey2.jpg');
        $form['thumbnail']->upload('loretta_grey_t2.jpg');
        $form['swatch']->upload('loretta_grey_detail2.jpg');
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Grey2') {
                                                        return false;
                                                    }
                                                })->count() == 1);
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));                                        
                                                
        $client2->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $updatedViewCrawler = $client2->followRedirect();
        
        $this->em->refresh($curtainColour);
        
        $fileRoot = static::$kernel->getContainer()->getParameter('fileroot');
             
        $this->assertTrue($updatedViewCrawler->filter('input[name="name"]')->attr('value') == 'Grey2');
        $this->assertTrue($updatedViewCrawler->filter('input[name="stock"]')->attr('value') == 35.5);
        $this->assertTrue($updatedViewCrawler->filter('select[name="buynow"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="instock"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onoffer"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onDisplay"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('input[name="discount"]')->attr('value') == 0);
        $this->assertTrue($updatedViewCrawler->filter('input[name="position"]')->attr('value') == 10);
        $this->assertTrue($curtainColour->getFullFilepath() == 'loretta/loretta_grey2.jpg');
        $this->assertTrue($curtainColour->getSwatchFilepath() == 'loretta/loretta_grey_detail2.jpg');
        $this->assertTrue($curtainColour->getThumbnailFilepath() == 'loretta/loretta_grey_t2.jpg');
        $this->assertTrue(file_exists($fileRoot.'loretta/loretta_grey2.jpg'));
        $this->assertTrue(file_exists($fileRoot.'loretta/loretta_grey_detail2.jpg'));
        $this->assertTrue(file_exists($fileRoot.'loretta/loretta_grey_t2.jpg'));
        
        unlink($file_full);
        unlink($file_thumb);
        unlink($file_detail);
        unlink($fileRoot.'loretta/loretta_grey2.jpg');
        unlink($fileRoot.'loretta/loretta_grey_detail2.jpg');
        unlink($fileRoot.'loretta/loretta_grey_t2.jpg');
   
    } 
    
    public function testEditOneColour() {
                
        $file_full = "beanetta_grey2.jpg";
        $fh_full = fopen($file_full, 'w');
        fclose($fh_full);
        
        $file_thumb = "beanetta_grey_t2.jpg";
        $fh_thumb = fopen($file_thumb, 'w');
        fclose($fh_thumb);
        
        $file_detail = "beanetta_grey_detail2.jpg";
        $fh_detail = fopen($file_detail, 'w');
        fclose($fh_detail);
 
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $curtainColour = $this->thirdCurtainDesign->getCurtainColours()->get(0);
        
        $client->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('Save');
        $form = $editCrawlerNode->form();
        
        $form['name'] = 'Grey2'; 
        $form['instock']->select('0');
        $form['buynow']->select('0');
        $form['stock'] = 35.5;
        $form['onoffer'] = 1;
        $form['discount'] = 10;
        $form['position'] = 10;
        $form['display']->upload('beanetta_grey2.jpg');
        $form['thumbnail']->upload('beanetta_grey_t2.jpg');
        $form['swatch']->upload('beanetta_grey_detail2.jpg');
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Grey2') {
                                                        return false;
                                                    }
                                                })->count() == 1);
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));                                        
                                                
        $client2->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $updatedViewCrawler = $client2->followRedirect();
        
        $this->em->refresh($curtainColour);
        
        $fileRoot = static::$kernel->getContainer()->getParameter('fileroot');
             
        $this->assertTrue($updatedViewCrawler->filter('input[name="name"]')->attr('value') == 'Grey2');
        $this->assertTrue($updatedViewCrawler->filter('input[name="stock"]')->attr('value') == 35.5);
        $this->assertTrue($updatedViewCrawler->filter('select[name="buynow"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="instock"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onoffer"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onDisplay"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('input[name="discount"]')->attr('value') == 10);
        $this->assertTrue($updatedViewCrawler->filter('input[name="position"]')->attr('value') == 10);
        $this->assertTrue($curtainColour->getFullFilepath() == 'beanetta/beanetta_grey2.jpg');
        $this->assertTrue($curtainColour->getSwatchFilepath() == 'beanetta/beanetta_grey_detail2.jpg');
        $this->assertTrue($curtainColour->getThumbnailFilepath() == 'beanetta/beanetta_grey_t2.jpg');
        $this->assertTrue(file_exists($fileRoot.'beanetta/beanetta_grey2.jpg'));
        $this->assertTrue(file_exists($fileRoot.'beanetta/beanetta_grey_detail2.jpg'));
        $this->assertTrue(file_exists($fileRoot.'beanetta/beanetta_grey_t2.jpg'));
        
        unlink($file_full);
        unlink($file_thumb);
        unlink($file_detail);
        unlink($fileRoot.'beanetta/beanetta_grey2.jpg');
        unlink($fileRoot.'beanetta/beanetta_grey_detail2.jpg');
        unlink($fileRoot.'beanetta/beanetta_grey_t2.jpg');
   
    }
    
    public function testNewWithOffer() {
        
        $file_full = "loretta_grey2.jpg";
        $fh_full = fopen($file_full, 'w');
        fclose($fh_full);
        
        $file_thumb = "loretta_grey_t2.jpg";
        $fh_thumb = fopen($file_thumb, 'w');
        fclose($fh_thumb);
        
        $file_detail = "loretta_grey_detail2.jpg";
        $fh_detail = fopen($file_detail, 'w');
        fclose($fh_detail);
 
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));
        
        $client->request('GET', 'admin/curtaincolour/new/'.$this->curtainDesign->getId());  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('Save');
        $form = $editCrawlerNode->form();
        
        $form['name'] = 'Grey2'; 
        $form['instock']->select('0');
        $form['buynow']->select('0');
        $form['stock'] = 45.6;
        $form['onoffer'] = 1;
        $form['onDisplay'] = 1;
        $form['discount'] = 15;
        $form['position'] = 12;
        $form['display']->upload('loretta_grey2.jpg');
        $form['thumbnail']->upload('loretta_grey_t2.jpg');
        $form['swatch']->upload('loretta_grey_detail2.jpg');
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Grey2') {
                                                        return false;
                                                    }
                                                })->count() == 1);
        
        $curtainColour = $this->repository->findOneBy(array('name' => 'Grey2'));                                
        $this->curtainDesign->addCurtainColour($curtainColour);
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));                                        
         
        $client2->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $updatedViewCrawler = $client2->followRedirect();
        $fileRoot = static::$kernel->getContainer()->getParameter('fileroot');
        
        $this->assertTrue($updatedViewCrawler->filter('input[name="name"]')->attr('value') == 'Grey2');
        $this->assertTrue($updatedViewCrawler->filter('input[name="stock"]')->attr('value') == 45.6);
        $this->assertTrue($updatedViewCrawler->filter('select[name="buynow"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="instock"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onoffer"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onDisplay"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('input[name="discount"]')->attr('value') == 15);
        $this->assertTrue($updatedViewCrawler->filter('input[name="position"]')->attr('value') == 12);
        $this->assertTrue($curtainColour->getFullFilepath() == 'loretta/loretta_grey2.jpg');
        $this->assertTrue($curtainColour->getSwatchFilepath() == 'loretta/loretta_grey_detail2.jpg');
        $this->assertTrue($curtainColour->getThumbnailFilepath() == 'loretta/loretta_grey_t2.jpg');
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getFullFilepath()));
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getSwatchFilepath()));
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getThumbnailFilepath()));
        
        unlink($file_full);
        unlink($file_thumb);
        unlink($file_detail);
        unlink($fileRoot.$curtainColour->getFullFilepath());
        unlink($fileRoot.$curtainColour->getSwatchFilepath());
        unlink($fileRoot.$curtainColour->getThumbnailFilepath());
        
    }
    
    public function testNewWithoutOffer() {
        
        $file_full = "loretta_grey2.jpg";
        $fh_full = fopen($file_full, 'w');
        fclose($fh_full);
        
        $file_thumb = "loretta_grey_t2.jpg";
        $fh_thumb = fopen($file_thumb, 'w');
        fclose($fh_thumb);
        
        $file_detail = "loretta_grey_detail2.jpg";
        $fh_detail = fopen($file_detail, 'w');
        fclose($fh_detail);
 
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));
        
        $client->request('GET', 'admin/curtaincolour/new/'.$this->curtainDesign->getId());  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('Save');
        $form = $editCrawlerNode->form();
        
        $form['name'] = 'Grey2'; 
        $form['instock']->select('0');
        $form['buynow']->select('0');
        $form['stock'] = 45.6;
        $form['onoffer'] = 0;
        $form['onDisplay'] = 0;
        $form['position'] = 12;
        $form['display']->upload('loretta_grey2.jpg');
        $form['thumbnail']->upload('loretta_grey_t2.jpg');
        $form['swatch']->upload('loretta_grey_detail2.jpg');
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Grey2') {
                                                        return false;
                                                    }
                                                })->count() == 1);
        
        $curtainColour = $this->repository->findOneBy(array('name' => 'Grey2'));                                
        $this->curtainDesign->addCurtainColour($curtainColour);
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));                                        
         
        $client2->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $updatedViewCrawler = $client2->followRedirect();
        $fileRoot = static::$kernel->getContainer()->getParameter('fileroot');
        
        $this->assertTrue($updatedViewCrawler->filter('input[name="name"]')->attr('value') == 'Grey2');
        $this->assertTrue($updatedViewCrawler->filter('input[name="stock"]')->attr('value') == 45.6);
        $this->assertTrue($updatedViewCrawler->filter('select[name="buynow"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="instock"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onoffer"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onDisplay"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('input[name="discount"]')->attr('value') == 0);
        $this->assertTrue($updatedViewCrawler->filter('input[name="position"]')->attr('value') == 12);
        $this->assertTrue($curtainColour->getFullFilepath() == 'loretta/loretta_grey2.jpg');
        $this->assertTrue($curtainColour->getSwatchFilepath() == 'loretta/loretta_grey_detail2.jpg');
        $this->assertTrue($curtainColour->getThumbnailFilepath() == 'loretta/loretta_grey_t2.jpg');
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getFullFilepath()));
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getSwatchFilepath()));
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getThumbnailFilepath()));
        
        unlink($file_full);
        unlink($file_thumb);
        unlink($file_detail);
        unlink($fileRoot.$curtainColour->getFullFilepath());
        unlink($fileRoot.$curtainColour->getSwatchFilepath());
        unlink($fileRoot.$curtainColour->getThumbnailFilepath());
        
    }
    
    public function testFirstNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));
        
        $client->request('GET', 'admin/curtaincolour/new/'.$this->secondCurtainDesign->getId());  
        $viewCrawler = $client->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('select[id="onDisplayFixed"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        
        $file_full = "jeanetta_grey2.jpg";
        $fh_full = fopen($file_full, 'w');
        fclose($fh_full);
        
        $file_thumb = "jeanetta_grey_t2.jpg";
        $fh_thumb = fopen($file_thumb, 'w');
        fclose($fh_thumb);
        
        $file_detail = "jeanetta_grey_detail2.jpg";
        $fh_detail = fopen($file_detail, 'w');
        fclose($fh_detail);
        
        $editCrawlerNode = $viewCrawler->selectButton('Save');
        $form = $editCrawlerNode->form();
        
        $form['name'] = 'Grey2'; 
        $form['instock']->select('0');
        $form['buynow']->select('0');
        $form['stock'] = 45.6;
        $form['onoffer'] = 0;
        $form['position'] = 12;
        $form['display']->upload('jeanetta_grey2.jpg');
        $form['thumbnail']->upload('jeanetta_grey_t2.jpg');
        $form['swatch']->upload('jeanetta_grey_detail2.jpg');
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Grey2') {
                                                        return false;
                                                    }
                                                })->count() == 1);
        
        $curtainColour = $this->repository->findOneBy(array('name' => 'Grey2'));                                
        $this->secondCurtainDesign->addCurtainColour($curtainColour);
        
        $client2 = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));                                        
         
        $client2->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $updatedViewCrawler = $client2->followRedirect();
        $fileRoot = static::$kernel->getContainer()->getParameter('fileroot');
        
        $this->assertTrue($updatedViewCrawler->filter('input[name="name"]')->attr('value') == 'Grey2');
        $this->assertTrue($updatedViewCrawler->filter('input[name="stock"]')->attr('value') == 45.6);
        $this->assertTrue($updatedViewCrawler->filter('select[name="buynow"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="instock"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onoffer"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('select[name="onDisplay"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updatedViewCrawler->filter('input[name="discount"]')->attr('value') == 0);
        $this->assertTrue($updatedViewCrawler->filter('input[name="position"]')->attr('value') == 12);
        $this->assertTrue($curtainColour->getFullFilepath() == 'jeanetta/jeanetta_grey2.jpg');
        $this->assertTrue($curtainColour->getSwatchFilepath() == 'jeanetta/jeanetta_grey_detail2.jpg');
        $this->assertTrue($curtainColour->getThumbnailFilepath() == 'jeanetta/jeanetta_grey_t2.jpg');
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getFullFilepath()));
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getSwatchFilepath()));
        $this->assertTrue(file_exists($fileRoot.$curtainColour->getThumbnailFilepath()));
        
        unlink($file_full);
        unlink($file_thumb);
        unlink($file_detail);
        unlink($fileRoot.$curtainColour->getFullFilepath());
        unlink($fileRoot.$curtainColour->getSwatchFilepath());
        unlink($fileRoot.$curtainColour->getThumbnailFilepath());
        
        
    }
}

