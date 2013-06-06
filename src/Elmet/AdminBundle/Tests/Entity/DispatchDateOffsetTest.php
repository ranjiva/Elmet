<?php


namespace Elmet\AdminBundle\Tests\Entity;
use Elmet\AdminBundle\Entity\DispatchDateOffset;

class DispatchDateOffsetTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDispatchDate()
    {
         $offset = new DispatchDateOffset();
       
         $day_of_week = date("d");
         $offset->setDayOfWeek($day_of_week);
         $offset->setOffset(4);
         
         $dispatchDate = strtotime("+4 day");
         
         $this->assertEquals(date('d/m/y',$dispatchDate),date_format($offset->getDispatchDate(),'d/m/y'));
    }    
}



?>
