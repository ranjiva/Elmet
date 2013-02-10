<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainPelmet;

class PelmetControllerTest extends WebTestCase
{
    var $curtainPelmet;
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
            ->getRepository('ElmetSiteBundle:CurtainPelmet');
        
        $this->cpbRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainPriceBand');
        
        $curtainPriceBand = $this->cpbRepository->findOneById(1);
        
        $this->curtainPelmet = new CurtainPelmet();
        $this->curtainPelmet->setCurtainPriceBand($curtainPriceBand);
        $this->curtainPelmet->setSize(322);
        $this->curtainPelmet->setPrice(140.95);

        $this->em->persist($this->curtainPelmet);
        $this->em->flush();
    }
    
    public function createSecondPelmet() {
         
        $curtainPriceBand = $this->cpbRepository->findOneById(1);
        
        $curtainPelmet = new CurtainPelmet();
        $curtainPelmet->setCurtainPriceBand($curtainPriceBand);
        $curtainPelmet->setSize(230);
        $curtainPelmet->setPrice(130.95);
        
        $this->em->persist($curtainPelmet);
        $this->em->flush();
        
        return $curtainPelmet;
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainPelmet);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/pelmet/view');  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 1); 
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainPelmet->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(0)->text())) == '1');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(1)->text())) == '322');        
    }
    
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/pelmet/remove/'.$this->curtainPelmet->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 0);
  
    }
    
    public function testEditOne() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/pelmet/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_'.$this->curtainPelmet->getId());
        $form = $editCrawlerNode->form();
        
        $form['single:priceband_'.$this->curtainPelmet->getId()] = 2;
        $form['single:size_'.$this->curtainPelmet->getId()] = 230;
        $form['single:price_'.$this->curtainPelmet->getId()] = 141.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainPelmet->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == '230');
        
    }
    
    public function testEditAll() {
        
        $secondCurtainPelmet = $this->createSecondPelmet();
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/pelmet/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('submit_all');
        $form = $editCrawlerNode->form();
        
        $form['all:priceband_'.$this->curtainPelmet->getId()] = 2;
        $form['all:size_'.$this->curtainPelmet->getId()] = 230;
        $form['all:price_'.$this->curtainPelmet->getId()] = 141.95;
       
        $form['all:priceband_'.$secondCurtainPelmet->getId()] = 4;
        $form['all:size_'.$secondCurtainPelmet->getId()] = 138;
        $form['all:price_'.$secondCurtainPelmet->getId()] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainPelmet->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == '230');
        
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$secondCurtainPelmet->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(0)->text())) == '4');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == '138');
        
        $this->em->remove($secondCurtainPelmet);
        $this->em->flush();
        
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/pelmet/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_new');
        $form = $editCrawlerNode->form();
        
        $form['priceband_new'] = 4;
        $form['size_new'] = 138;
        $form['price_new'] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $curtainPelmet = $this->repository->findOneBy(array('price' => 131.95));
       
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$curtainPelmet->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(0)->text())) == '4');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == '138');
   
        $this->em->remove($curtainPelmet);
        $this->em->flush();
    }
}

