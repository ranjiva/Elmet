<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LandingPageControllerTest extends WebTestCase
{
    
    var $client;
    var $crawler;
    var $form;
    
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/p/p175');
        $subscribeCrawlerNode = $this->crawler->selectButton('Subscribe');
        $this->form = $subscribeCrawlerNode->form();
    }
    
    public function testNoValues()
    {
        $subscribeCrawler = $this->client->submit($this->form);
        
        $this->assertTrue($subscribeCrawler->filter('div.error-message:contains("Please enter your name")')->count() > 0);
        $this->assertTrue($subscribeCrawler->filter('div.error-message:contains("Please enter an email address")')->count() > 0);
    }
    
    public function testIncorrectEmail()
    {
        $this->form['form[email]'] = 'test@jsksjk';
                
        $subscribeCrawler = $this->client->submit($this->form);
    
        $this->assertTrue($subscribeCrawler->filter('div.error-message:contains("Please enter a valid email address")')->count() > 0);
    }
    
    public function testPartiallyCorrect()
    {
        $this->form['form[email]'] = 'ranjiva@yahoo.com';
        
        $subscribeCrawler = $this->client->submit($this->form);
        
        $this->assertTrue($subscribeCrawler->filter('div.error-message')->count() == 1);
    }
    
    public function testSend()
    {
        $this->form['form[email]'] = 'ranjiva@yahoo.com';
        $this->form['form[name]'] = 'Ranjiva Prasad';

        $subscribeCrawler = $this->client->submit($this->form);

        $this->assertTrue($subscribeCrawler->filter('div.error-message')->count() == 0);
    }
}



?>
