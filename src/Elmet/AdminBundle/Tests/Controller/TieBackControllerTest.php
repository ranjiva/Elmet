<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainTieBack;

class TieBackControllerTest extends WebTestCase
{
    var $curtainTieBack;
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
            ->getRepository('ElmetSiteBundle:CurtainTieBack');
        
        $this->cpbRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainPriceBand');
        
        $curtainPriceBand = $this->cpbRepository->findOneById(1);
        
        $this->curtainTieBack = new CurtainTieBack();
        $this->curtainTieBack->setCurtainPriceBand($curtainPriceBand);
        $this->curtainTieBack->setSize(19);
        $this->curtainTieBack->setPrice(140.95);
        $this->curtainTieBack->setType("HomeWindow");
        
        $this->em->persist($this->curtainTieBack);
        $this->em->flush();
    }
    
    public function createSecondTieBack() {
         
        $curtainPriceBand = $this->cpbRepository->findOneById(1);
        
        $curtainTieBack = new CurtainTieBack();
        $curtainTieBack->setCurtainPriceBand($curtainPriceBand);
        $curtainTieBack->setSize(36);
        $curtainTieBack->setPrice(130.95);
        $curtainTieBack->setType("CaravanWindow");
        
        $this->em->persist($curtainTieBack);
        $this->em->flush();
        
        return $curtainTieBack;
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainTieBack);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/tieback/view');  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 1); 
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainTieBack->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(0)->text())) == '1');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(1)->text())) == '19');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(2)->text())) == 'HomeWindow');
        
    }
    
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/tieback/remove/'.$this->curtainTieBack->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 0);
  
    }
    
    public function testEditOne() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/tieback/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_'.$this->curtainTieBack->getId());
        $form = $editCrawlerNode->form();
        
        $form['single:priceband_'.$this->curtainTieBack->getId()] = 2;
        $form['single:size_'.$this->curtainTieBack->getId()] = 26;
        $form['single:type_'.$this->curtainTieBack->getId()] = 'CaravanWindow';
        $form['single:price_'.$this->curtainTieBack->getId()] = 141.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainTieBack->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == '26');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(2)->text())) == 'CaravanWindow');
        
    }
    
    public function testEditAll() {
        
        $secondCurtainTieBack = $this->createSecondTieBack();
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/tieback/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('submit_all');
        $form = $editCrawlerNode->form();
        
        $form['all:priceband_'.$this->curtainTieBack->getId()] = 2;
        $form['all:size_'.$this->curtainTieBack->getId()] = 26;
        $form['all:type_'.$this->curtainTieBack->getId()] = 'CaravanWindow';
        $form['all:price_'.$this->curtainTieBack->getId()] = 141.95;
       
        $form['all:priceband_'.$secondCurtainTieBack->getId()] = 4;
        $form['all:size_'.$secondCurtainTieBack->getId()] = 19;
        $form['all:type_'.$secondCurtainTieBack->getId()] = 'HomeWindow';
        $form['all:price_'.$secondCurtainTieBack->getId()] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainTieBack->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == '26');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(2)->text())) == 'CaravanWindow');
        
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$secondCurtainTieBack->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(0)->text())) == '4');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == '19');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(2)->text())) == 'HomeWindow');
        
        $this->em->remove($secondCurtainTieBack);
        $this->em->flush();
        
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/tieback/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_new');
        $form = $editCrawlerNode->form();
        
        $form['priceband_new'] = 4;
        $form['size_new'] = 19;
        $form['type_new'] = 'HomeWindow';
        $form['price_new'] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $curtainTieBack = $this->repository->findOneBy(array('price' => 131.95));
       
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$curtainTieBack->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(0)->text())) == '4');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == '19');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(2)->text())) == 'HomeWindow');
  
        $this->em->remove($curtainTieBack);
        $this->em->flush();
    }
}

