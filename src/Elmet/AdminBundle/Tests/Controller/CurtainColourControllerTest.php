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
        
        $curtainColour = new CurtainColour();
        $curtainColour->setName("Cream");
        $curtainColour->setFullFilepath("loretta/lorretta_cream.jpg");
        $curtainColour->setThumbnailFilepath("loretta/lorretta_cream_detail.jpg");
        $curtainColour->setSwatchFilepath("loretta/lorretta_cream_t.jpg");
        $curtainColour->setBuynow(1);
        $curtainColour->setInStock(1);
        $curtainColour->setOnOffer(1);
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
        $secondCurtainColour->setDiscountPercentage(20);
        $secondCurtainColour->setAvailableStock(30.0);
        $secondCurtainColour->setCurtainDesign($this->curtainDesign);
        $this->curtainDesign->addCurtainColour($secondCurtainColour);
        
        $this->em->persist($this->curtainDesign);
        $this->em->flush();
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainDesign);
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
        $this->assertTrue($viewCrawler->filter('input[name="discount"]')->attr('value') == $curtainColour->getDiscountPercentage());
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
    
    public function testEditAll() {
                
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

        $curtainColour = $this->curtainDesign->getCurtainColours()->get(0);
        
        $client->request('GET', 'admin/curtaincolour/view/'.$curtainColour->getId());  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('Save');
        $form = $editCrawlerNode->form();
        
        $form['name'] = 'Grey2'; 
        $form['instock']->select('0');
        $form['buynow']->select('0');
        $form['stock'] = 35.5;
        $form['onoffer'] = 0;
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
        $this->assertTrue($updatedViewCrawler->filter('input[name="discount"]')->attr('value') == 0);
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
        $form['discount'] = 15;
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
        $this->assertTrue($updatedViewCrawler->filter('input[name="discount"]')->attr('value') == 15);
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
        $this->assertTrue($updatedViewCrawler->filter('input[name="discount"]')->attr('value') == 0);
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
}

