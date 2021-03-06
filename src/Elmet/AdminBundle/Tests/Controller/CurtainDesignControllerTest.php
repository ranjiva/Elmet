<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainDesign;
use Elmet\SiteBundle\Entity\CurtainColour;

class CurtainDesignControllerTest extends WebTestCase
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
            ->getRepository('ElmetSiteBundle:CurtainDesign');
        
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
        $this->curtainDesign->setPosition(2);
        $this->curtainDesign->setDisplay(0);
        $this->curtainDesign->setSpecialPurchase(0);
        
        $curtainColour = new CurtainColour();
        $curtainColour->setName("Cream");
        $curtainColour->setFullFilepath("loretta/lorretta_cream.jpg");
        $curtainColour->setThumbnailFilepath("loretta/lorretta_cream_detail.jpg");
        $curtainColour->setSwatchFilepath("loretta/lorretta_cream_t.jpg");
        $curtainColour->setBuynow(1);
        $curtainColour->setInStock(1);
        $curtainColour->setOnOffer(0);
        $curtainColour->setDisplay(1);
        $curtainColour->setPosition(1);
        $curtainColour->setCurtainDesign($this->curtainDesign);
        $this->curtainDesign->addCurtainColour($curtainColour);
        
        $secondCurtainColour = new CurtainColour();
        $secondCurtainColour->setName("Blue");
        $secondCurtainColour->setFullFilepath("loretta/lorretta_blue.jpg");
        $secondCurtainColour->setThumbnailFilepath("loretta/lorretta_blue_detail.jpg");
        $secondCurtainColour->setSwatchFilepath("loretta/lorretta_blue_t.jpg");
        $secondCurtainColour->setBuynow(1);
        $secondCurtainColour->setInStock(1);
        $secondCurtainColour->setOnOffer(0);
        $secondCurtainColour->setDisplay(0);
        $secondCurtainColour->setPosition(0);
        $secondCurtainColour->setCurtainDesign($this->curtainDesign);
        $this->curtainDesign->addCurtainColour($secondCurtainColour);
        
        $this->em->persist($this->curtainDesign);
        $this->em->flush();
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainDesign);
        $this->em->flush();
    }
    
    public function testIndex() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtaindesign/index');  
        $viewCrawler = $client->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('td:contains("loretta")')->count() == 1);
        $this->assertTrue($viewCrawler->filter('td:contains("Lorretta Ready Made Curtains")')->count() == 1);       
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtaindesign/view/'.$this->curtainDesign->getId());  
        $viewCrawler = $client->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('select[name="priceband"]')->children()->filter('option[value="2"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('input[name="shortname"]')->attr('value') == 'loretta');
        $this->assertTrue($viewCrawler->filter('textarea[name="name"]:contains("Lorretta Ready Made Curtains")')->count() == 1);
        $this->assertTrue($viewCrawler->filter('textarea[name="materials"]:contains("Polyester/Cotton")')->count() == 1);
        $this->assertTrue($viewCrawler->filter('select[name="lined"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[name="eyelets"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('input[name="fabricwidth"]')->attr('value') == '140');
        $this->assertTrue($viewCrawler->filter('input[name="position"]')->attr('value') == '2');
        $this->assertTrue($viewCrawler->filter('input[name="patternrepeatlength"]')->attr('value') == '8.00');
        $this->assertTrue($viewCrawler->filter('select[name="curtainfinish"]')->children()->filter('option[value="Fringed"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[name="cushionfinish"]')->children()->filter('option[value="Corded"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[name="new"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[name="display"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[name="special"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('select[name="tapesize"]')->children()->filter('option:contains("3\"")')->attr('selected') == 'true');
        $this->assertTrue($viewCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Blue') {
                                                        return false;
                                                    }
                                                })->count() == 1);
        $this->assertTrue($viewCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Cream') {
                                                        return false;
                                                    }
                                                })->count() == 1);
        
        $curtainColours = $this->curtainDesign->getCurtainColours();                                      
                                                
        $firstCurtainColourId = $curtainColours[0]->getId();
        $secondCurtainColourId = $curtainColours[1]->getId();
        
        $this->assertTrue($viewCrawler->filter('a[href="/admin/curtaincolour/remove/'.$firstCurtainColourId.'"]')->count() == 0);
        $this->assertTrue($viewCrawler->filter('a[href="/admin/curtaincolour/remove/'.$secondCurtainColourId.'"]')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'remove') {
                                                        return false;
                                                    }
                                                })->count() == 1);
                                                
    }
        
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtaindesign/remove/'.$this->curtainDesign->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("loretta")')->count() == 0);
  
    }
    
    public function testEdit() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/curtaindesign/view/'.$this->curtainDesign->getId());  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('Save');
        $form = $editCrawlerNode->form();
        
        $form['priceband']->select('1');
        $form['shortname'] = 'jeanetta';
        $form['name'] = 'Jeanetta Ready Made Curtains';
        $form['materials'] = '100% Cotton';
        $form['lined']->select('0');
        $form['eyelets']->select('0');
        $form['fabricwidth'] = 120;
        $form['patternrepeatlength'] = 10.00;
        $form['curtainfinish']->select('Straight');
        $form['cushionfinish']->select('Self-piped');
        $form['new']->select('0');
        $form['position'] = 3;
        $form['special']->select('1');
        $form['display']->select('1');
        
        $updateCrawler = $client->submit($form);
                
        $this->assertTrue($updateCrawler->filter('select[name="priceband"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('input[name="shortname"]')->attr('value') == 'jeanetta');
        $this->assertTrue($updateCrawler->filter('textarea[name="name"]:contains("Jeanetta Ready Made Curtains")')->count() == 1);
        $this->assertTrue($updateCrawler->filter('textarea[name="materials"]:contains("100% Cotton")')->count() == 1);
        $this->assertTrue($updateCrawler->filter('select[name="lined"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="eyelets"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('input[name="fabricwidth"]')->attr('value') == '120');
        $this->assertTrue($updateCrawler->filter('input[name="position"]')->attr('value') == '3');
        $this->assertTrue($updateCrawler->filter('input[name="patternrepeatlength"]')->attr('value') == '10.00');
        $this->assertTrue($updateCrawler->filter('select[name="curtainfinish"]')->children()->filter('option[value="Straight"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="cushionfinish"]')->children()->filter('option[value="Self-piped"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="new"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="display"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="special"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="tapesize"]')->children()->filter('option:contains("3\"")')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Blue') {
                                                        return false;
                                                    }
                                                })->count() == 1);
        $this->assertTrue($viewCrawler->filter('td')
                                      ->reduce(function ($node, $i) {
                                                    if (trim(preg_replace('/\s\s+/', '', $node->nodeValue)) != 'Cream') {
                                                        return false;
                                                    }
                                                })->count() == 1);
   
        
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtaindesign/new');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('Save');
        $form = $editCrawlerNode->form();
        
        $form['priceband']->select('1');
        $form['shortname'] = 'jeanetta';
        $form['name'] = 'Jeanetta Ready Made Curtains';
        $form['materials'] = '100% Cotton';
        $form['lined']->select('0');
        $form['eyelets']->select('0');
        $form['fabricwidth'] = 120;
        $form['patternrepeatlength'] = 10.00;
        $form['curtainfinish']->select('Straight');
        $form['cushionfinish']->select('Self-piped');
        $form['new']->select('0');
        $form['tapesize']->select("3\"");
        $form['position'] = 3;
        $form['special']->select('1');
        $form['display']->select('1');
        
        $updateCrawler = $client->submit($form);
                
        $this->assertTrue($updateCrawler->filter('select[name="priceband"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('input[name="shortname"]')->attr('value') == 'jeanetta');
        $this->assertTrue($updateCrawler->filter('textarea[name="name"]:contains("Jeanetta Ready Made Curtains")')->count() == 1);
        $this->assertTrue($updateCrawler->filter('textarea[name="materials"]:contains("100% Cotton")')->count() == 1);
        $this->assertTrue($updateCrawler->filter('select[name="lined"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="eyelets"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('input[name="fabricwidth"]')->attr('value') == '120');
        $this->assertTrue($updateCrawler->filter('input[name="position"]')->attr('value') == '3');
        $this->assertTrue($updateCrawler->filter('input[name="patternrepeatlength"]')->attr('value') == '10.00');
        $this->assertTrue($updateCrawler->filter('select[name="curtainfinish"]')->children()->filter('option[value="Straight"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="cushionfinish"]')->children()->filter('option[value="Self-piped"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="new"]')->children()->filter('option[value="0"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="display"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="special"]')->children()->filter('option[value="1"]')->attr('selected') == 'true');
        $this->assertTrue($updateCrawler->filter('select[name="tapesize"]')->children()->filter('option:contains("3\"")')->attr('selected') == 'true');
               
        $curtainPrice = $this->repository->findOneBy(array('url_name' => 'jeanetta'));
         
        $this->em->remove($curtainPrice);
        $this->em->flush();
    }
}

