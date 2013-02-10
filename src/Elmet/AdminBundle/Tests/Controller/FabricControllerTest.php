<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainFabric;

class FabricControllerTest extends WebTestCase
{
    var $curtainFabric;
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
            ->getRepository('ElmetSiteBundle:CurtainFabric');
        
        $this->cpbRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainPriceBand');
        
        $curtainPriceBand = $this->cpbRepository->findOneById(1);
        
        $this->curtainFabric = new CurtainFabric();
        $this->curtainFabric->setCurtainPriceBand($curtainPriceBand);
        $this->curtainFabric->setPricePerMetre(140.95);
        
        $this->em->persist($this->curtainFabric);
        $this->em->flush();
    }
    
    public function createSecondFabric() {
         
        $curtainPriceBand = $this->cpbRepository->findOneById(1);
        
        $curtainFabric = new CurtainFabric();
        $curtainFabric->setCurtainPriceBand($curtainPriceBand);
        $curtainFabric->setPricePerMetre(130.95);

        $this->em->persist($curtainFabric);
        $this->em->flush();
        
        return $curtainFabric;
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainFabric);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/fabric/view');  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 1); 
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainFabric->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(0)->text())) == '1');
    }
    
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/fabric/remove/'.$this->curtainFabric->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 0);
  
    }
    
    public function testEditOne() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/fabric/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_'.$this->curtainFabric->getId());
        $form = $editCrawlerNode->form();
        
        $form['single:priceband_'.$this->curtainFabric->getId()] = 2;
        $form['single:price_'.$this->curtainFabric->getId()] = 141.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainFabric->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2'); 
    }
    
    public function testEditAll() {
        
        $secondCurtainFabric = $this->createSecondFabric();
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/fabric/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('submit_all');
        $form = $editCrawlerNode->form();
        
        $form['all:priceband_'.$this->curtainFabric->getId()] = 2;
        $form['all:price_'.$this->curtainFabric->getId()] = 141.95;
       
        $form['all:priceband_'.$secondCurtainFabric->getId()] = 4;
        $form['all:price_'.$secondCurtainFabric->getId()] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainFabric->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
     
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$secondCurtainFabric->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(0)->text())) == '4');
        
        $this->em->remove($secondCurtainFabric);
        $this->em->flush();
        
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/fabric/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_new');
        $form = $editCrawlerNode->form();
        
        $form['priceband_new'] = 5;
        $form['price_new'] = 32.95;
        
        $updateCrawler = $client->submit($form);
        
        $curtainFabric = $this->repository->findOneBy(array('id' => $this->curtainFabric->getId()+1));
        
        $this->assertTrue($updateCrawler->filter('td:contains("32.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("32.95")')->parents()->first()->attr('id') == 'view_'.$curtainFabric->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("32.95")')->siblings()->eq(0)->text())) == '5');
  
        $this->em->remove($curtainFabric);
        $this->em->flush();
    }
}

