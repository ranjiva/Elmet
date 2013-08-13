<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Elmet\SiteBundle\Entity\Testimonial;

class TestimonialControllerTest extends WebTestCase
{  
    var $em;
    var $testimonial;
    
    private function createTestimonial($em)
    {
          $testimonial = new Testimonial();
          
          $testimonial->setCustomerDetails("Ranjiva Prasad");
          $testimonial->setFeatured('1');
          $testimonial->setTestimonial("Great Job!");
          
          $this->em->persist($testimonial);
          $this->em->flush();
          
          return $testimonial;
    }
   
    protected function setUp() {
        
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $this->testimonial = $this->createTestimonial($this->em);
    }
    
    protected function tearDown() {
             
        $this->em->remove($this->testimonial);
        
        $this->em->flush();
    }
    
    public function testProcess()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/testimonial');
        
        $this->assertTrue($crawler->filter('div.testimonial')->count() > 0);
        $this->assertTrue($crawler->filter('p.customer-details')->eq(0)->text() == 'Ranjiva Prasad');
    }
}


?>
