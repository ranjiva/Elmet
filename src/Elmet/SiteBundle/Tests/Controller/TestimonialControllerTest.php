<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestimonialControllerTest extends WebTestCase
{
    
    
    
    public function testProcess()
    {
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/testimonial');
        
        $this->assertTrue($crawler->filter('div.testimonial')->count() > 0);
    }
}



?>
