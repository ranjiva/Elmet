<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainDesign;
use Elmet\SiteBundle\Entity\CurtainColour;

class CurtainStockControllerTest extends WebTestCase
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
        $curtainColour->setAvailableStock(99.99);
        $curtainColour->setCurtainDesign($this->curtainDesign);
        $this->curtainDesign->addCurtainColour($curtainColour);
        
        $secondCurtainColour = new CurtainColour();
        $secondCurtainColour->setName("Blue");
        $secondCurtainColour->setFullFilepath("loretta/lorretta_blue.jpg");
        $secondCurtainColour->setThumbnailFilepath("loretta/lorretta_blue_detail.jpg");
        $secondCurtainColour->setSwatchFilepath("loretta/lorretta_blue_t.jpg");
        $secondCurtainColour->setBuynow(1);
        $secondCurtainColour->setInStock(1);
        $secondCurtainColour->setAvailableStock(88.88);
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
        
        $client->request('GET', 'admin/curtainstock/view');  
        $viewCrawler = $client->followRedirect();
        
        $this->assertTrue($viewCrawler->filter('td:contains("99.99")')->count() == 1); 
        $this->assertTrue($viewCrawler->filter('td:contains("99.99")')->parents()->first()->attr('id') == 'view_'.$curtainColour->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("99.99")')->siblings()->eq(0)->text())) == 'loretta');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("99.99")')->siblings()->eq(1)->text())) == 'Cream');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("99.99")')->siblings()->eq(2)->text())) == 'Y');
    }
        
    public function testEditOne() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/curtainstock/view');  
        $viewCrawler = $client->followRedirect();
        
        $curtainColour = $this->curtainDesign->getCurtainColours()->get(0);
        
        $updateCrawlerNode = $viewCrawler->selectButton('save_'.$curtainColour->getId());
        $form = $updateCrawlerNode->form();
        
        $form['single:stock_'.$curtainColour->getId()] = 199.99;
        $form['single:instock_'.$curtainColour->getId()] = 0;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("199.99")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("199.99")')->parents()->first()->attr('id') == 'view_'.$curtainColour->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("199.99")')->siblings()->eq(0)->text())) == 'loretta');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("199.99")')->siblings()->eq(1)->text())) == 'Cream');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("199.99")')->siblings()->eq(2)->text())) == 'N');
        
    }
    
    public function testEditAll() {
        
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainstock/view');  
        $viewCrawler = $client->followRedirect();
        
        $updateCrawlerNode = $viewCrawler->selectButton('submit_all');
        $form = $updateCrawlerNode->form();
        
        $curtainColourOne = $this->curtainDesign->getCurtainColours()->get(0);
        $curtainColourTwo = $this->curtainDesign->getCurtainColours()->get(1);
        
        $form['all:stock_'.$curtainColourOne->getId()] = 199.99;
        $form['all:stock_'.$curtainColourTwo->getId()] = 188.99;
        $form['all:instock_'.$curtainColourOne->getId()] = 0;
        $form['all:instock_'.$curtainColourTwo->getId()] = 0;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("199.99")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("199.99")')->parents()->first()->attr('id') == 'view_'.$curtainColourOne->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("199.99")')->siblings()->eq(0)->text())) == 'loretta');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("199.99")')->siblings()->eq(1)->text())) == 'Cream');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("199.99")')->siblings()->eq(2)->text())) == 'N');
        
        $this->assertTrue($updateCrawler->filter('td:contains("188.99")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("188.99")')->parents()->first()->attr('id') == 'view_'.$curtainColourTwo->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("188.99")')->siblings()->eq(0)->text())) == 'loretta');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("188.99")')->siblings()->eq(1)->text())) == 'Blue');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("188.99")')->siblings()->eq(2)->text())) == 'N');
        
    }
}

