<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OffersControllerTest extends WebTestCase
{

    var $curtainColours;
    var $offerColours;
    var $em;
    
    protected function setUp() {
        
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
    } 
    
    protected function tearDown() {
        
        if ($this->curtainColours != null) {
            
            foreach($this->curtainColours as $curtainColour) {
                $curtainColour->setOnOffer(0);
                $curtainColour->setDiscountPercentage(0);
                $this->em->merge($curtainColour);
            }
                
            $this->em->flush();
        }
        
        if ($this->offerColours != null) {
            
            foreach($this->offerColours as $colour) {
                $colour->setOnOffer(1);
                $this->em->merge($colour);
            }
                
            $this->em->flush();
        }
        
    }
    
    public function testChange()
    {
        $query = $this->em->createQuery("SELECT DISTINCT cd FROM ElmetSiteBundle:CurtainDesign cd JOIN cd.curtain_colours cc WHERE cc.in_stock = 1 and cd.url_name != 'angelina' and cd.url_name != 'concerto' and cd.url_name != 'daisy_chain'");
        $curtainDesigns = $query->getResult(); 
        
        $firstSixDesigns = array_slice($curtainDesigns, 0, 6);   
        
        foreach($firstSixDesigns as $curtainDesign) {
            
            $query = $this->em->createQuery("SELECT cc FROM ElmetSiteBundle:CurtainColour cc JOIN cc.curtain_design cd WHERE cc.in_stock = 1 and cd.url_name = :urlName");
            $query->setParameter('urlName',$curtainDesign->getUrlName());
            
            $colours = $query->getResult();
            $firstColour = $colours[0];
            $firstColour->setOnOffer(1);
            $firstColour->setDiscountPercentage(25);
            $this->curtainColours[] = $firstColour;
            
            $this->em->merge($firstColour);
        }
        
        $this->em->flush();
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/offers/page/2');
        
        $this->assertTrue($crawler->filter('span.current:contains("2")')->count() > 0);
        $this->assertTrue($crawler->filter('p:contains("Up To")')->count() == 1);
    }
    
    public function testNoOffers() {
        
        $query = $this->em->createQuery("SELECT cc FROM ElmetSiteBundle:CurtainColour cc JOIN cc.curtain_design cd WHERE cc.on_offer = 1 and cd.url_name = 'angelina' or cd.url_name = 'concerto' or cd.url_name = 'daisy_chain'");
        $colours = $query->getResult(); 
        
        foreach($colours as $colour) {
                        
            $colour->setOnOffer(0);
            $this->offerColours[] = $colour;
            
            $this->em->merge($colour);
        }
        
        $this->em->flush();
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/offers');
        
        $this->assertTrue($crawler->filter('p:contains("Please check back soon")')->count() == 1);

    }
    
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/offers');
       
        $this->assertTrue($crawler->filter('html:contains("Angelina")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Concerto")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Daisy Chain")')->count() > 0);
    }
                
    public function testSelect()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/offers/select/concerto/Silk');
        
        $this->assertTrue($crawler->filter('h2:contains("Concerto Ready-made (Jacquard) Curtains")')->count() > 0);
        $this->assertTrue($crawler->filter('strong:contains("Silk")')->count() > 0);
    }
}



?>
