<?php

namespace Elmet\SiteBundle\Tests\Controller;

use Elmet\SiteBundle\Entity\CurtainDesign;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurtainsControllerTest extends WebTestCase
{
    var $curtainDesign;
    var $em;
    var $repository;
    var $secondCurtainDesign;

    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $this->repository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainColour');

        $cpbRepository = static::$kernel->getContainer()
            ->get('doctrine')
            ->getRepository('ElmetSiteBundle:CurtainPriceBand');        
        
        $curtainPriceBand = $cpbRepository->findOneById(2);
        
        $this->curtainDesign = new CurtainDesign();
        $this->curtainDesign->setCurtainPriceBand($curtainPriceBand);
        $this->curtainDesign->setUrlName("loretta");
        $this->curtainDesign->setCushionFinish("Corded");
        $this->curtainDesign->setEyeletsAvailable(1);
        $this->curtainDesign->setFabricWidth(140);
        $this->curtainDesign->setFinish("Fringed");
        $this->curtainDesign->setLined(1);
        $this->curtainDesign->setMaterials("Polyester/Cotton");
        $this->curtainDesign->setName("Lorretta Ready Made Curtains");
        $this->curtainDesign->setNew(1);
        $this->curtainDesign->setPatternRepeatLength(8.00);
        $this->curtainDesign->setTapeSize("3\"");
        $this->curtainDesign->setPosition(0);
        $this->curtainDesign->setDisplay(1);
        $this->curtainDesign->setSpecialPurchase(0);
            
        $this->em->persist($this->curtainDesign);
        $this->em->flush();
    }
    
    protected function tearDown() {
          
        $this->em->remove($this->curtainDesign);
        $this->em->flush();
    }
    
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
    
    public function testPosition()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $query = $em->createQuery("SELECT cd from ElmetSiteBundle:CurtainDesign cd WHERE cd.url_name='daisy_chain' or cd.url_name='geneva'");
        
        $curtainDesigns = $query->getResult();
        
        foreach($curtainDesigns as $curtainDesign) {
        
            $curtainDesign->setPosition(-1);
            $em->merge($curtainDesign);
        }
        
        $em->flush();
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/curtains');
        $this->assertTrue($crawler->filter('html:contains("Daisy Chain")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Geneva")')->count() > 0);
        
        foreach($curtainDesigns as $curtainDesign) {
        
            $curtainDesign->setPosition(0);
            $em->merge($curtainDesign);
        }
          
        $em->flush();
        
    }
    
    public function testDisplay() {
        
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getEntityManager();
        
        $query = $em->createQuery("SELECT cd from ElmetSiteBundle:CurtainDesign cd WHERE cd.url_name='daisy_chain' or cd.url_name='geneva'");
        
        $curtainDesigns = $query->getResult();
        
        foreach($curtainDesigns as $curtainDesign) {
        
            $curtainDesign->setPosition(-1);
            $em->merge($curtainDesign);
        }
        
        $em->flush();
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/curtains');
        $this->assertTrue($crawler->filter('html:contains("Daisy Chain")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Geneva")')->count() > 0);
        
        foreach($curtainDesigns as $curtainDesign) {
        
            $curtainDesign->setDisplay(0);
            $em->merge($curtainDesign);
        }
          
        $em->flush();
        
        $crawler = $client->request('GET', '/curtains');
        $this->assertTrue($crawler->filter('html:contains("Daisy Chain")')->count() == 0);
        $this->assertTrue($crawler->filter('html:contains("Geneva")')->count() == 0);
        
        foreach($curtainDesigns as $curtainDesign) {
        
            $curtainDesign->setPosition(0);
            $curtainDesign->setDisplay(1);
            $em->merge($curtainDesign);
        }
          
        $em->flush();
        
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
        
        $crawler = $client->request('GET', '/curtains/select/daisy_chain/Cream');
        
        $this->assertTrue($crawler->filter('h2:contains("Daisy Chain Ready-made (Jacquard) Curtains")')->count() > 0);
        $this->assertTrue($crawler->filter('p:contains("On Sale")')->count() > 0);
        $this->assertTrue($crawler->filter('strong:contains("Cream")')->count() > 0);
        $this->assertTrue($crawler->filter('strong:contains("Blue")')->count() > 0);
        $this->assertTrue($crawler->filter('strong:contains("Coffee")')->count() > 0);
        $this->assertTrue($crawler->filter('strong:contains("Rose")')->count() > 0);
        
        $crawler = $client->request('GET', '/curtains/select/daisy_chain/Rose');
        $this->assertTrue($crawler->filter('p:contains("Currently")')->count() > 0);
        
    }
}

?>
