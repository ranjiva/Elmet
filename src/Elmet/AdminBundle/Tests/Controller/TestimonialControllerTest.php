<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\Testimonial;

class TestimonialControllerTest extends WebTestCase
{
    var $testimonial;
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
            ->getRepository('ElmetSiteBundle:Testimonial');
        
        $this->testimonial = new Testimonial();
        
        $this->testimonial->setCustomerDetails("Ranjiva Prasad");
        $this->testimonial->setFeatured("N");
        $this->testimonial->setTestimonial("Great Job Guys");
        
        $this->em->persist($this->testimonial);
        $this->em->flush();
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->testimonial);
        $this->em->flush();
    }
    
    public function testView() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/testimonial/view');  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("Ranjiva Prasad")')->count() == 1);
        $this->assertTrue($viewCrawler->filter('textarea:contains("Great Job Guys")')->count() == 1);
       
    }
    
    public function testRemove() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/testimonial/remove/'.$this->testimonial->getId());  
        $viewCrawler = $client->followRedirect();
                
        $this->assertTrue($viewCrawler->filter('td:contains("Ranjiva Prasad")')->count() == 0);
        $this->assertTrue($viewCrawler->filter('textarea:contains("Great Job Guys")')->count() == 0);
  
    }
    
    public function testEdit() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/testimonial/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_'.$this->testimonial->getId());
        $form = $editCrawlerNode->form();
        
        $form['customer_'.$this->testimonial->getId()] = 'Sabina Prasad';
        $form['testimonial_'.$this->testimonial->getId()] = 'Just Brilliant Folks';
        $form['featured_'.$this->testimonial->getId()] = 1;
        
        $updateCrawler = $client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('td:contains("Sabina Prasad")')->count() == 1);
        $this->assertTrue($updateCrawler->filter('textarea:contains("Just Brilliant Folks")')->count() == 1);   
        $this->assertTrue($updateCrawler->filter('td:contains("Sabina Prasad")')->parents()->first()->attr('id') == 'view_'.$this->testimonial->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("Sabina Prasad")')->siblings()->eq(1)->text())) == 'Y');
    }
    
    public function testNew() {
        
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/testimonial/view');  
        $viewCrawler = $client->followRedirect();
        
        $editCrawlerNode = $viewCrawler->selectButton('save_new');
        $form = $editCrawlerNode->form();
        
        $form['customer_new'] = 'Sabina Prasad2';
        $form['testimonial_new'] = 'Just Brilliant Folks';
        $form['featured_new'] = 1;
        
        $updateCrawler = $client->submit($form);
        
        $testimonial = $this->repository->findOneBy(array('customer_details' => "Sabina Prasad2"));
       
        $this->assertTrue($updateCrawler->filter('td:contains("Sabina Prasad2")')->count() == 1);
        $this->assertTrue($updateCrawler->filter('textarea:contains("Just Brilliant Folks")')->count() == 1);
        $this->assertTrue($updateCrawler->filter('td:contains("Sabina Prasad2")')->parents()->first()->attr('id') == 'view_'.$testimonial->getId());
        $this->assertTrue(trim(preg_replace('/\s\s+/', '', $updateCrawler->filter('td:contains("Sabina Prasad2")')->siblings()->eq(1)->text())) == 'Y');
  
        $this->em->remove($testimonial);
        $this->em->flush();
    }
}

