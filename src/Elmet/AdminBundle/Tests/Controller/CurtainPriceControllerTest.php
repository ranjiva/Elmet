<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\CurtainPrice;

class CurtainPriceControllerTest extends WebTestCase
{
    var $curtainPrice;
    var $em;
    var $repository;
    var $cpbRepository;
    var $cepbRepository;
    
    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $this->repository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainPrice');
        
        $this->cpbRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainPriceBand');
        
        $this->cepbRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainEyeletPriceBand');
        
        
        $curtainPriceBand = $this->cpbRepository->findOneById(2);
        $curtainEyeletPriceBand = $this->cepbRepository->findOneById(2);
        
        $this->curtainPrice = new CurtainPrice();
        $this->curtainPrice->setCurtainPriceBand($curtainPriceBand);
        $this->curtainPrice->setCurtainEyeletPriceBand($curtainEyeletPriceBand);
        $this->curtainPrice->setSize("52\" x 54\"");
        $this->curtainPrice->setType("HomeWindow");
        $this->curtainPrice->setPrice(140.95);
        $this->curtainPrice->setWidth(52.0);
        $this->curtainPrice->setHeight(54.0);
        
        $this->em->persist($this->curtainPrice);
        $this->em->flush();
    }
    
    public function createSecondPrice() {
         
        $curtainPriceBand = $this->cpbRepository->findOneById(2);
        
        $curtainPrice = new CurtainPrice();
        $curtainPrice->setCurtainPriceBand($curtainPriceBand);
        $curtainPrice->setSize("3ft x 3ft");
        $curtainPrice->setType("CaravanWindow");
        $curtainPrice->setPrice(130.95);
        $curtainPrice->setWidth(3.0);
        $curtainPrice->setHeight(3.0);
        
        $this->em->persist($curtainPrice);
        $this->em->flush();
        
        return $curtainPrice;
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainPrice);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainprice/view/2');  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 1); 
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainPrice->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(1)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(2)->text())) == "52\" x 54\"");
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $viewCrawler->filter('td:contains("140.95")')->siblings()->eq(3)->text())) == 'HomeWindow');
        
    }
    
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainprice/remove/'.$this->curtainPrice->getId().'/'.$this->curtainPrice->getCurtainPriceBand()->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("140.95")')->count() == 0);
  
    }
    
    public function testEditOne() {
                
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));


        $client->request('GET', 'admin/curtainprice/view/2');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_'.$this->curtainPrice->getId());
        $form = $editCrawlerNode->form();
        
        $form['single:price_'.$this->curtainPrice->getId()] = 141.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainPrice->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(2)->text())) == "52\" x 54\"");
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(3)->text())) == 'HomeWindow');
        
    }
    
    public function testEditAll() {
        
        $secondCurtainPrice = $this->createSecondPrice();
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainprice/view/2');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('submit_all');
        $form = $editCrawlerNode->form();
        
        $form['all:price_'.$this->curtainPrice->getId()] = 141.95;
        $form['all:price_'.$secondCurtainPrice->getId()] = 131.95;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("141.95")')->parents()->first()->attr('id') == 'view_'.$this->curtainPrice->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(1)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(2)->text())) == "52\" x 54\"");
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("141.95")')->siblings()->eq(3)->text())) == 'HomeWindow');
        
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("131.95")')->parents()->first()->attr('id') == 'view_'.$secondCurtainPrice->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(1)->text())) == '');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(2)->text())) == "3ft x 3ft");
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("131.95")')->siblings()->eq(3)->text())) == 'CaravanWindow');
        
        $this->em->remove($secondCurtainPrice);
        $this->em->flush();
        
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/curtainprice/view/2');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_new');
        $form = $editCrawlerNode->form();
        
        $form['curtaineyeletpriceband_new'] = 1;
        $form['size_new'] = "52\" x 54\"";
        $form['type_new'] = 'CaravanWindow';
        $form['price_new'] = 1131.95;
        
        $updateCrawler = $client->submit($form);
        
        $curtainPrice = $this->repository->findOneBy(array('price' => 1131.95));
        
        $this->assertTrue($updateCrawler->filter('td:contains("1131.95")')->count() == 1); 
        $this->assertTrue($updateCrawler->filter('td:contains("1131.95")')->parents()->first()->attr('id') == 'view_'.$curtainPrice->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("1131.95")')->siblings()->eq(0)->text())) == '2');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("1131.95")')->siblings()->eq(1)->text())) == '');
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("1131.95")')->siblings()->eq(2)->text())) == "52\" x 54\"");
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("1131.95")')->siblings()->eq(3)->text())) == 'CaravanWindow');
        
        $this->em->remove($curtainPrice);
        $this->em->flush();
    }
}

