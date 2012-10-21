<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurtainsControllerTest extends WebTestCase
{
    public function testChange()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/curtains/page/4');

        
        $this->assertTrue($crawler->filter('span.current:contains("4")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Geneva")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Austral")')->count() > 0);
    }
    
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/curtains');
        
        $this->assertTrue($crawler->filter('span.current:contains("1")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Liberty")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Zenna")')->count() > 0);
    }
    
    public function testCloseUp()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/curtains/closeup/geneva/Natural');
        
        $this->assertTrue($crawler->filter('p:contains("Geneva Ready-made (Jacquard) Curtains - Natural")')->count() > 0);
    }
            
    public function testSelect()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/curtains/select/geneva/Natural');
        
        $this->assertTrue($crawler->filter('h2:contains("Geneva Ready-made (Jacquard) Curtains")')->count() > 0);
        $this->assertTrue($crawler->filter('strong:contains("Natural")')->count() > 0);
    }
}



?>
