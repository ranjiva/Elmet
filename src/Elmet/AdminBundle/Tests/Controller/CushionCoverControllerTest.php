<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CushionCover;

class CushionCoverControllerTest extends WebTestCase
{
    var $cushionCover;
    var $em;
    var $repository;
    var $cpbRepository;
    
    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $this->repository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CushionCover');
        
        $this->cpbRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainPriceBand');
        
        $curtainPriceBand = $this->cpbRepository->findOneById(1);
        
        $this->cushionCover = new CushionCover();
        $this->cushionCover->setCurtainPriceBand($curtainPriceBand);
        $this->cushionCover->setSize("18\" X 18\"");
        $this->cushionCover->setPrice(140.95);
        $this->cushionCover->setFinish("Shiny");

        $this->em->persist($this->cushionCover);
        $this->em->flush();
    }
    
    public function createSecondCushionCover() {
         
        $curtainPriceBand = $this->cpbRepository->findOneById(2);
        
        $cushionCover = new CushionCover();
        $cushionCover->setCurtainPriceBand($curtainPriceBand);
        $cushionCover->setSize("18\" X 18\"");
        $cushionCover->setPrice(130.95);
        $cushionCover->setFinish("Shiny");
        
        $this->em->persist($cushionCover);
        $this->em->flush();
        
        return $cushionCover;
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->cushionCover);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/cushioncover/view');  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 1); 
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->parents()->first()->attr('id') == 'view_'.$this->cushionCover->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(0)->text())) == '1');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(1)->text())) == '18" X 18"');        
    }
    
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/cushioncover/remove/'.$this->cushionCover->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 0);
  
    }
    
    public function testEditOne() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/cushioncover/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_'.$this->cushionCover->getId());
        $form = $editCrawlerNode->form();
        
        $form['single:priceband_'.$this->cushionCover->getId()] = 2;
        $form['single:size_'.$this->cushionCover->getId()] = "18\" X 18\"";
        $form['single:price_'.$this->cushionCover->getId()] = 141.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->cushionCover->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == "18\" X 18\"");
        
    }
    
    public function testEditAll() {
        
        $secondCushionCover = $this->createSecondCushionCover();
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/cushioncover/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('submit_all');
        $form = $editCrawlerNode->form();
        
        $form['all:priceband_'.$this->cushionCover->getId()] = 2;
        $form['all:size_'.$this->cushionCover->getId()] = "18\" X 18\"";
        $form['all:price_'.$this->cushionCover->getId()] = 141.95;
       
        $form['all:priceband_'.$secondCushionCover->getId()] = 4;
        $form['all:size_'.$secondCushionCover->getId()] = "18\" X 18\"";
        $form['all:price_'.$secondCushionCover->getId()] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->cushionCover->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == "18\" X 18\"");
        
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$secondCushionCover->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(0)->text())) == '4');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == "18\" X 18\"");
        
        $this->em->remove($secondCushionCover);
        $this->em->flush();
        
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/cushioncover/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_new');
        $form = $editCrawlerNode->form();
        
        $form['priceband_new'] = 4;
        $form['size_new'] = "18\" X 18\"";
        $form['price_new'] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $cushionCover = $this->repository->findOneBy(array('price' => 131.95));
       
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$cushionCover->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(0)->text())) == '4');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == "18\" X 18\"");
   
        $this->em->remove($cushionCover);
        $this->em->flush();
    }
}

