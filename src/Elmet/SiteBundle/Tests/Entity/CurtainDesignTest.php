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
}



?>
