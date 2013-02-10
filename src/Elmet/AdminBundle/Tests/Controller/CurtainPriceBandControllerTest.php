<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainPriceBand;

class CurtainPriceBandControllerTest extends WebTestCase
{
    var $curtainPriceBand;
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
            ->getRepository('ElmetSiteBundle:CurtainPriceBand');
        
        $this->curtainPriceBand = new CurtainPriceBand();
        $this->curtainPriceBand->setName("H");

        $this->em->persist($this->curtainPriceBand);
        $this->em->flush();
    }
    
    public function createSecondCurtainPriceBand() {
                 
        $curtainPriceBand = new CurtainPriceBand();
        $curtainPriceBand->setName("I");
        
        $this->em->persist($curtainPriceBand);
        $this->em->flush();
        
        return $curtainPriceBand;
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainPriceBand);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainpriceband/view');  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("H")')->count() == 1); 
        $this->assertTrue($viewCrawler->filter('td:contains("H")')->parents()->first()->attr('id') == 'view_'.$this->curtainPriceBand->getId());        
    }
    
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainpriceband/remove/'.$this->curtainPriceBand->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("H")')->count() == 0);
  
    }
    
    public function testEditOne() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/curtainpriceband/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_'.$this->curtainPriceBand->getId());
        $form = $editCrawlerNode->form();
        
        $form['single:name_'.$this->curtainPriceBand->getId()] = 'J';
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("J")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("J")')->parents()->first()->attr('id') == 'view_'.$this->curtainPriceBand->getId());
        
    }
    
    public function testEditAll() {
        
        $secondCurtainPriceBand = $this->createSecondCurtainPriceBand();
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainpriceband/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('submit_all');
        $form = $editCrawlerNode->form();
        
        $form['all:name_'.$this->curtainPriceBand->getId()] = 'J';
       
        $form['all:name_'.$secondCurtainPriceBand->getId()] = 'K';
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("J")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("J")')->parents()->first()->attr('id') == 'view_'.$this->curtainPriceBand->getId());
        
        $this->assertTrue($updateCrawler->filter('td:contains("K")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("K")')->parents()->first()->attr('id') == 'view_'.$secondCurtainPriceBand->getId());
        
        $this->em->remove($secondCurtainPriceBand);
        $this->em->flush();
        
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainpriceband/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_new');
        $form = $editCrawlerNode->form();
        
        $form['name_new'] = 'L';
        
        $updateCrawler = $client->submit($form);
        
        $curtainPriceBand = $this->repository->findOneBy(array('name' => 'L'));
       
        $this->assertTrue($updateCrawler->filter('td:contains("L")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("L")')->parents()->first()->attr('id') == 'view_'.$curtainPriceBand->getId());
   
        $this->em->remove($curtainPriceBand);
        $this->em->flush();
    }
}

