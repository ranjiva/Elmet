<?php

namespace Elmet\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'adminpass'));

        $client->request('GET', 'admin/');  
        $crawler = $client->followRedirect();
        
        $this->assertTrue($crawler->filter('html:contains("Search")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Curtain Designs")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Curtain Price Bands")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Curtain Eyelet Price Bands")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Curtain Prices")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Pelmet Prices")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Cushion Cover Prices")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Tie Back Prices")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Fabric Prices")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Manage Testimonials")')->count() == 1);
        
    }
}

