<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    
    var $client;
    var $crawler;
    var $form;
    
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/contact');
        $sendCrawlerNode = $this->crawler->selectButton('Send');
        $this->form = $sendCrawlerNode->form();
    }
    
    public function testNoValues()
    {
        $sendCrawler = $this->client->submit($this->form);
        
        $this->assertTrue($sendCrawler->filter('div.error-message:contains("Please enter your name")')->count() > 0);
        $this->assertTrue($sendCrawler->filter('div.error-message:contains("Please enter your telephone number")')->count() > 0);
        $this->assertTrue($sendCrawler->filter('div.error-message:contains("Please enter an email address")')->count() > 0);
        $this->assertTrue($sendCrawler->filter('div.error-message:contains("Please enter your enquiry")')->count() > 0);
    }
    
    public function testIncorrectNumber()
    {
        $this->form['form[tel]'] = 'Ranjiva';
                
        $sendCrawler = $this->client->submit($this->form);
    
        $this->assertTrue($sendCrawler->filter('div.error-message:contains("Please enter a valid telephone number")')->count() > 0);
    }
    
    public function testIncorrectEmail()
    {
        $this->form['form[email]'] = 'test@jsksjk';
                
        $sendCrawler = $this->client->submit($this->form);
    
        $this->assertTrue($sendCrawler->filter('div.error-message:contains("Please enter a valid email address")')->count() > 0);
    }
    
    public function testPartiallyCorrect()
    {
        $this->form['form[tel]'] = '07769901335';
        $this->form['form[email]'] = 'ranjiva@yahoo';
        $this->form['form[enquiry]'] = 'Do you sell curtains?';
        
        $sendCrawler = $this->client->submit($this->form);
        
        $this->assertTrue($sendCrawler->filter('div.error-message')->count() == 2);
    }
    
    public function testSend()
    {
        $this->form['form[tel]'] = '07769901335';
        $this->form['form[email]'] = 'ranjiva@yahoo.com';
        $this->form['form[name]'] = 'Ranjiva Prasad';
        $this->form['form[enquiry]'] = 'Do you sell curtains?';

        $sendCrawler = $this->client->submit($this->form);

        $this->assertTrue($sendCrawler->filter('div.error-message')->count() == 0);
    }
}



?>
