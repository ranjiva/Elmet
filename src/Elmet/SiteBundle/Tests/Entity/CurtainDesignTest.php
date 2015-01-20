<?php


namespace Elmet\SiteBundle\Tests\Entity;
use Elmet\SiteBundle\Entity\CurtainDesign;
use Elmet\SiteBundle\Entity\CurtainColour;

class CurtainDesignTest extends \PHPUnit_Framework_TestCase
{
    public function testSortById()
    {
         $curtainDesign = new CurtainDesign();
         
         $curtainColour1 = new CurtainColour();
         $curtainColour1->setId(30);
         $curtainColour1->setName("two");
         $curtainDesign->addCurtainColour($curtainColour1);
         
         $curtainColour3 = new CurtainColour();
         $curtainColour3->setId(40);
         $curtainColour3->setName("three");
         $curtainDesign->addCurtainColour($curtainColour3);
         
         $curtainColour2 = new CurtainColour();
         $curtainColour2->setId(1);
         $curtainColour2->setName("one");
         $curtainDesign->addCurtainColour($curtainColour2);
         
         $sortedColours = $curtainDesign->getSortedCurtainColoursById();
         reset($sortedColours);
         
         $this->assertEquals("one",current($sortedColours)->getName());
         $this->assertEquals("two",next($sortedColours)->getName());
         $this->assertEquals("three",next($sortedColours)->getName());
    }
    
    public function testSortByIdInStock()
    {
         $curtainDesign = new CurtainDesign();
         
         $curtainColour1 = new CurtainColour();
         $curtainColour1->setId(30);
         $curtainColour1->setName("two");
         $curtainColour1->setinStock(1);
         $curtainDesign->addCurtainColour($curtainColour1);
         
         $curtainColour3 = new CurtainColour();
         $curtainColour3->setId(40);
         $curtainColour3->setName("three");
         $curtainColour3->setinStock(1);
         $curtainDesign->addCurtainColour($curtainColour3);
         
         $curtainColour2 = new CurtainColour();
         $curtainColour2->setId(1);
         $curtainColour2->setName("one");
         $curtainColour2->setinStock(0);
         $curtainDesign->addCurtainColour($curtainColour2);
         
         $sortedColours = $curtainDesign->getSortedCurtainColoursByIdInStock();
         reset($sortedColours);
         
         $this->assertEquals("two",current($sortedColours)->getName());
         $this->assertEquals("three",next($sortedColours)->getName());
         $this->assertEquals("one",next($sortedColours)->getName());
    }
    
    public function testCurtainColoursOnOffer()
    {
         $curtainDesign = new CurtainDesign();
         
         $curtainColour1 = new CurtainColour();
         $curtainColour1->setName("two");
         $curtainColour1->setinStock(1);
         $curtainColour1->setOnOffer(1);
         $curtainColour1->setDisplay(1);
         $curtainDesign->addCurtainColour($curtainColour1);
         
         $curtainColour3 = new CurtainColour();
         $curtainColour3->setName("three");
         $curtainColour3->setinStock(1);
         $curtainColour3->setOnOffer(0);
         
         $curtainDesign->addCurtainColour($curtainColour3);
         
         $curtainColour2 = new CurtainColour();
         $curtainColour2->setName("one");
         $curtainColour2->setinStock(0);
         $curtainColour2->setOnOffer(1);
         $curtainColour2->setDisplay(1);
         $curtainDesign->addCurtainColour($curtainColour2);
         
         $curtainColour4 = new CurtainColour();
         $curtainColour4->setName("four");
         $curtainColour4->setinStock(1);
         $curtainColour4->setOnOffer(1);
         $curtainColour4->setDisplay(0);
         $curtainDesign->addCurtainColour($curtainColour4);
         
         $curtainColour4 = new CurtainColour();
         $curtainColour4->setName("five");
         $curtainColour4->setinStock(1);
         $curtainColour4->setOnOffer(1);
         $curtainColour4->setDisplay(1);
         $curtainDesign->addCurtainColour($curtainColour4);
         
         $coloursOnOffer = $curtainDesign->getCurtainColoursOnOffer();
        
         $this->assertTrue(count($coloursOnOffer) == 2);
         $this->assertEquals("two",current($coloursOnOffer)->getName());
         $this->assertEquals("five",next($coloursOnOffer)->getName());
    }
    
    public function testMaxDiscount()
    {
         $curtainDesign = new CurtainDesign();
         
         $curtainColour1 = new CurtainColour();
         $curtainColour1->setName("two");
         $curtainColour1->setinStock(1);
         $curtainColour1->setOnOffer(1);
         $curtainColour1->setDisplay(1);
         $curtainColour1->setDiscountPercentage(10);
         $curtainDesign->addCurtainColour($curtainColour1);
         
         $curtainColour3 = new CurtainColour();
         $curtainColour3->setName("three");
         $curtainColour3->setinStock(1);
         $curtainColour3->setOnOffer(0);
         $curtainColour3->setDisplay(1);
         $curtainColour3->setDiscountPercentage(60);
         $curtainDesign->addCurtainColour($curtainColour3);
         
         $curtainColour2 = new CurtainColour();
         $curtainColour2->setName("one");
         $curtainColour2->setinStock(0);
         $curtainColour2->setOnOffer(1);
         $curtainColour2->setDisplay(1);
         $curtainColour2->setDiscountPercentage(70);
         $curtainDesign->addCurtainColour($curtainColour2);
         
         $curtainColour4 = new CurtainColour();
         $curtainColour4->setName("four");
         $curtainColour4->setinStock(1);
         $curtainColour4->setOnOffer(1);
         $curtainColour4->setDisplay(0);
         $curtainColour4->setDiscountPercentage(20);
         $curtainDesign->addCurtainColour($curtainColour4);
         
         $this->assertEquals($curtainDesign->getMaxDiscount(),10);
    }
    
    public function testOnDisplaySortByIdInStock()
    {
         $curtainDesign = new CurtainDesign();
         
         $curtainColour1 = new CurtainColour();
         $curtainColour1->setId(30);
         $curtainColour1->setName("two");
         $curtainColour1->setinStock(1);
         $curtainColour1->setDisplay(1);
         $curtainDesign->addCurtainColour($curtainColour1);
         
         $curtainColour3 = new CurtainColour();
         $curtainColour3->setId(40);
         $curtainColour3->setName("three");
         $curtainColour3->setinStock(1);
         $curtainColour3->setDisplay(0);
         $curtainDesign->addCurtainColour($curtainColour3);
         
         $curtainColour2 = new CurtainColour();
         $curtainColour2->setId(1);
         $curtainColour2->setName("one");
         $curtainColour2->setinStock(0);
         $curtainColour2->setDisplay(1);
         $curtainDesign->addCurtainColour($curtainColour2);
         
         $sortedColours = $curtainDesign->getOnDisplayCurtainColoursSortedByIdInStock();
         reset($sortedColours);
         
         $this->assertEquals("two",current($sortedColours)->getName());
         $this->assertEquals("one",next($sortedColours)->getName());
    }
    
    public function testCurtainColoursOnDisplay()
    {
         $curtainDesign = new CurtainDesign();
         
         $curtainColour1 = new CurtainColour();
         $curtainColour1->setId(30);
         $curtainColour1->setName("two");
         $curtainColour1->setinStock(1);
         $curtainColour1->setDisplay(1);
         $curtainDesign->addCurtainColour($curtainColour1);
         
         $curtainColour3 = new CurtainColour();
         $curtainColour3->setId(40);
         $curtainColour3->setName("three");
         $curtainColour3->setinStock(1);
         $curtainColour3->setDisplay(0);
         $curtainDesign->addCurtainColour($curtainColour3);
         
         $curtainColour2 = new CurtainColour();
         $curtainColour2->setId(1);
         $curtainColour2->setName("one");
         $curtainColour2->setinStock(0);
         $curtainColour2->setDisplay(1);
         $curtainDesign->addCurtainColour($curtainColour2);
         
         $onDisplayColours = $curtainDesign->getCurtainColoursOnDisplay();
         reset($onDisplayColours);
         
         $this->assertEquals("two",current($onDisplayColours)->getName());
         $this->assertEquals("one",next($onDisplayColours)->getName());
    }
    
    public function testOnDisplaySortByInStockandPosition()
    {
         $curtainDesign = new CurtainDesign();
         
         $curtainColour1 = new CurtainColour();
         $curtainColour1->setId(1);
         $curtainColour1->setName("one");
         $curtainColour1->setinStock(1);
         $curtainColour1->setDisplay(1);
         $curtainColour1->setPosition(1);
         $curtainDesign->addCurtainColour($curtainColour1);
         
         $curtainColour2 = new CurtainColour();
         $curtainColour2->setId(2);
         $curtainColour2->setName("two");
         $curtainColour2->setinStock(0);
         $curtainColour2->setDisplay(1);
         $curtainColour2->setPosition(1);
         $curtainDesign->addCurtainColour($curtainColour2);
         
         $curtainColour3 = new CurtainColour();
         $curtainColour3->setId(3);
         $curtainColour3->setName("three");
         $curtainColour3->setinStock(1);
         $curtainColour3->setDisplay(0);
         $curtainColour1->setPosition(0);
         $curtainDesign->addCurtainColour($curtainColour3);
         
         $curtainColour4 = new CurtainColour();
         $curtainColour4->setId(4);
         $curtainColour4->setName("four");
         $curtainColour4->setinStock(0);
         $curtainColour4->setDisplay(1);
         $curtainColour4->setPosition(0);
         $curtainDesign->addCurtainColour($curtainColour4);
         
         $sortedColours = $curtainDesign->getOnDisplayCurtainColoursSortedByInStockAndPosition();
         reset($sortedColours);
         
         $this->assertEquals("one",current($sortedColours)->getName());
         $this->assertEquals("four",next($sortedColours)->getName());
         $this->assertEquals("two",next($sortedColours)->getName());
    }
}

?>
