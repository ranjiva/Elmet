<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainEyeletPriceBand;

class CEPBControllerTest extends WebTestCase
{
    var $curtainEyeletPriceBand;
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
            ->getRepository('ElmetSiteBundle:CurtainEyeletPriceBand');
                
        $this->curtainEyeletPriceBand = new CurtainEyeletPriceBand();
        $this->curtainEyeletPriceBand->setCurtainSize("152\"");
        $this->curtainEyeletPriceBand->setPrice(140.95);

        $this->em->persist($this->curtainEyeletPriceBand);
        $this->em->flush();
    }
    
    public function createSecondCEPB() {
                 
        $curtainEyeletPriceBand = new CurtainEyeletPriceBand();
        $curtainEyeletPriceBand->setCurtainSize("152\"");
        $curtainEyeletPriceBand->setPrice(130.95);
        
        $this->em->persist($curtainEyeletPriceBand);
        $this->em->flush();
        
        return $curtainEyeletPriceBand;
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainEyeletPriceBand);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtaineyeletpriceband/view');  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 1); 
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainEyeletPriceBand->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(1)->text())) == '152"');   
    }
    
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtaineyeletpriceband/remove/'.$this->curtainEyeletPriceBand->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 0);
  
    }
    
    public function testEditOne() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/curtaineyeletpriceband/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_'.$this->curtainEyeletPriceBand->getId());
        $form = $editCrawlerNode->form();
        
        $form['single:size_'.$this->curtainEyeletPriceBand->getId()] = "132\"";
        $form['single:price_'.$this->curtainEyeletPriceBand->getId()] = 141.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainEyeletPriceBand->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == "132\"");
           
    }
    
    public function testEditAll() {
        
        $secondCEPB = $this->createSecondCEPB();
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtaineyeletpriceband/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('submit_all');
        $form = $editCrawlerNode->form();
        
        $form['all:size_'.$this->curtainEyeletPriceBand->getId()] = "132\"";
        $form['all:price_'.$this->curtainEyeletPriceBand->getId()] = 141.95;
       
        $form['all:size_'.$secondCEPB->getId()] = "104\"";
        $form['all:price_'.$secondCEPB->getId()] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainEyeletPriceBand->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == "132\"");
        
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$secondCEPB->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == "104\"");
        
        $this->em->remove($secondCEPB);
        $this->em->flush();
        
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtaineyeletpriceband/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_new');
        $form = $editCrawlerNode->form();
        
        $form['size_new'] = "132\"";
        $form['price_new'] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $curtainEyeletPriceBand = $this->repository->findOneBy(array('price' => 131.95));
       
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$curtainEyeletPriceBand->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == "132\"");
   
        $this->em->remove($curtainEyeletPriceBand);
        $this->em->flush();
    }
}

