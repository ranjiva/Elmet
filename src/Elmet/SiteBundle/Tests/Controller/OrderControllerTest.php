<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    var $viewCrawler;
    var $client;
    
    protected function setUp()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/curtains/select/geneva/Natural');
        
        $buttonCrawlerNode = $crawler->selectButton('Add to Basket');
        $form = $buttonCrawlerNode->form();
        $form['home_tape_curtain_1'] = 1;
        $form['home_eyelet_curtain_3'] = 2;
        $form['pelmet_1'] = 1;
        $form['home_tieback_1'] = 2;
        $form['cushion_0'] = 2;
        $form['fabric'] = 2;
        $form['caravan_tieback_0'] = 1;
        $form['caravan_window_curtain_1'] = 2;
        $form['caravan_door_curtain_0'] = 2;
        
        $this->viewCrawler = $this->client->submit($form);
    }
    
    public function testAdd()
    {
        $this->assertTrue($this->viewCrawler->filter('strong:contains("9 Items")')->count() > 0);
        $this->assertTrue($this->viewCrawler->filter('div.total_price:contains("359.35")')->count() > 0);       
    }
    
    public function testAddDiscounted()
    {
        //test with 20% discount on Angelina Cream
        $crawler = $this->client->request('GET','/curtains/select/angelina/Cream');
        $buttonCrawlerNode = $crawler->selectButton('Add to Basket');
        
        $form = $buttonCrawlerNode->form();
        $form['home_tape_curtain_1'] = 1;
        $form['home_eyelet_curtain_3'] = 2;
        $form['pelmet_1'] = 1;
        $form['home_tieback_0'] = 2;
        $form['cushion_0'] = 2;
        $form['fabric'] = 2;
        $form['caravan_tieback_0'] = 1;
        $form['caravan_window_curtain_1'] = 2;
        $form['caravan_door_curtain_0'] = 2;
        
        $this->viewCrawler = $this->client->submit($form);
        
        $this->assertTrue($this->viewCrawler->filter('strong:contains("18 Items")')->count() > 0);
        $this->assertTrue($this->viewCrawler->filter('div.total_price:contains("582.03")')->count() > 0);
    }
    
    public function testAddMore()
    {
        $crawler = $this->client->request('GET','/curtains/select/malta_flowers/Black');
        $buttonCrawlerNode = $crawler->selectButton('Add to Basket');
        
        $form = $buttonCrawlerNode->form();
        $form['home_tape_curtain_1'] = 1;
        
        $this->viewCrawler = $this->client->submit($form);
        
        $this->assertTrue($this->viewCrawler->filter('strong:contains("10 Items")')->count() > 0);
        $this->assertTrue($this->viewCrawler->filter('div.total_price:contains("433.3")')->count() > 0);
        
    }
    
    public function testAddMoreThenRemove()
    {
        $crawler = $this->client->request('GET','/curtains/select/malta_flowers/Black');
        $buttonCrawlerNode = $crawler->selectButton('Add to Basket');
        
        $form = $buttonCrawlerNode->form();
        $form['home_tape_curtain_1'] = 1;
        
        $this->viewCrawler = $this->client->submit($form);
        
        $removeCrawler = $this->client->request('GET', '/order/remove/6_Natural:2:140');
        
        $this->assertTrue($removeCrawler->filter('strong:contains("9 Items")')->count() > 0);
        $this->assertTrue($removeCrawler->filter('div.total_price:contains("414.3")')->count() > 0);
        
        
    }
    
    public function testRemove()
    {
        $crawler = $this->client->request('GET', '/order/remove/6_Natural:2:140');
        
        $this->assertTrue($crawler->filter('strong:contains("8 Items")')->count() > 0);
        $this->assertTrue($crawler->filter('div.total_price:contains("340.35")')->count() > 0);
    }
    
    public function testEmpty()
    {
        $crawler = $this->client->request('GET', '/order/empty');
        $this->assertTrue($crawler->filter('h1:contains("0 items")')->count() > 0);
    }
    
    
    
    public function testViewOrder()
    {
        $crawler = $this->client->request('GET', '/order/view');
        $this->assertTrue($crawler->filter('strong:contains("9 Items")')->count() > 0);
        $this->assertTrue($crawler->filter('div.total_price:contains("359.35")')->count() > 0);
    }
    
    public function testViewNoOrder()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/order/view');
        $this->assertTrue($crawler->filter('h1:contains("0 items")')->count() > 0);
    }
    
    public function testUpdateDrop()
    {
        $buttonCrawlerNode = $this->viewCrawler->selectButton('confirm');
        
        $form = $buttonCrawlerNode->form();
        $form['confirm'] = null;
        $form['item_0'] = "23";
        $form['item_1'] = "45";
        $updateCrawler = $this->client->submit($form);
        
        $this->assertTrue($updateCrawler->filter('strong:contains("9 Items")')->count() > 0);
        $this->assertTrue($updateCrawler->filter('div.total_price:contains("359.35")')->count() > 0);
        
        $updateButtonCrawlerNode = $updateCrawler->selectButton('confirm');
        $updateForm = $updateButtonCrawlerNode->form();
        
        $values = $updateForm->getValues();
        
        $this->assertEquals("23",$values['item_0']);
        $this->assertEquals("45",$values['item_1']);
      
    }
    
    public function testSubmit()
    {
        $buttonCrawlerNode = $this->viewCrawler->selectButton('confirm');
        $form = $buttonCrawlerNode->form();
        $form['confirm'] = 'confirm';
        $submitCrawler = $this->client->submit($form);
      
        $this->assertTrue($submitCrawler->filter('div.summ_total_price:contains("359.35")')->count() > 0);
    }
    
    public function testSubmitThenAddMore()
    {
        $confirmCrawler = $this->viewCrawler->selectButton('confirm');
        $form = $confirmCrawler->form();
        $form['confirm'] = 'confirm';
        $submitCrawler = $this->client->submit($form);
        $custom1 = $submitCrawler->filter('input[name="custom"]')->attr('value');
        
        $selectCrawler = $this->client->request('GET','/curtains/select/malta_flowers/Black');
        $addCrawlerNode = $selectCrawler->selectButton('Add to Basket');
        
        $form = $addCrawlerNode->form();
        $form['home_tape_curtain_1'] = 1;
        
        $this->viewCrawler = $this->client->submit($form);
        
        $this->assertTrue($this->viewCrawler->filter('strong:contains("10 Items")')->count() > 0);
        $this->assertTrue($this->viewCrawler->filter('div.total_price:contains("433.3")')->count() > 0);
        
        
        $secondConfirmCrawler = $this->viewCrawler->selectButton('confirm');
        $form = $secondConfirmCrawler->form();
        $form['confirm'] = 'confirm';
        $secondSubmitCrawler = $this->client->submit($form);
        $custom2 = $secondSubmitCrawler->filter('input[name="custom"]')->attr('value');
        
        $this->assertTrue($secondSubmitCrawler->filter('div.summ_total_price:contains("433.3")')->count() > 0);
        $this->assertEquals(intval($custom1)+1,intval($custom2));
    }
    
    public function testSubmitThenRemove()
    {
        $confirmCrawler = $this->viewCrawler->selectButton('confirm');
        $form = $confirmCrawler->form();
        $form['confirm'] = 'confirm';
        $submitCrawler = $this->client->submit($form);
        $custom1 = $submitCrawler->filter('input[name="custom"]')->attr('value');
        
        $viewCrawler = $this->client->request('GET','/order/view');
        $removeCrawler = $this->client->request('GET', '/order/remove/6_Natural:2:140');
        
        $this->assertTrue($removeCrawler->filter('strong:contains("8 Items")')->count() > 0);
        $this->assertTrue($removeCrawler->filter('div.total_price:contains("340.35")')->count() > 0);
        
        $secondConfirmCrawler = $this->viewCrawler->selectButton('confirm');
        $form = $secondConfirmCrawler->form();
        $form['confirm'] = 'confirm';
        $secondSubmitCrawler = $this->client->submit($form);
        $custom2 = $secondSubmitCrawler->filter('input[name="custom"]')->attr('value');
        
        $this->assertTrue($secondSubmitCrawler->filter('div.summ_total_price:contains("340.35")')->count() > 0);
        $this->assertEquals(intval($custom1)+1,intval($custom2));
        
    }
    
    public function testFetch()
    {
        $buttonCrawlerNode = $this->viewCrawler->selectButton('confirm');
        $form = $buttonCrawlerNode->form();
        $form['confirm'] = 'confirm';
        $submitCrawler = $this->client->submit($form);
      
        $custom = $submitCrawler->filter('input[name="custom"]')->attr('value');
        
        $secondClient = static::createClient();
        
        $fetchCrawler = $secondClient->request('GET','/order/fetch/'.$custom);
        $basketViewCrawler = $secondClient->request('GET','/order/view');
        
        $this->assertTrue($basketViewCrawler->filter('strong:contains("9 Items")')->count() > 0);
        $this->assertTrue($basketViewCrawler->filter('div.total_price:contains("359.35")')->count() > 0);
    }
}



?>
