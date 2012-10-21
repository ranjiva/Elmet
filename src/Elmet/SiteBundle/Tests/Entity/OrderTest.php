<?php


namespace Elmet\SiteBundle\Tests\Entity;
use Elmet\SiteBundle\Entity\Order;
use Elmet\SiteBundle\Entity\OrderItem;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    public function testUpdateTotalLessThan150()
    {
         $order = new Order();
         $orderItems = $order->getOrderItems();
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(19.95);
         $orderItems->add($orderItem);
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(100);
         $orderItems->add($orderItem);
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(30);
         $orderItems->add($orderItem);
    
         $order->updateOrderTotal();
         
         $this->assertEquals(149.95,$order->getOrderTotal());
         $this->assertEquals(6.5,$order->getDeliveryCharge());
         $this->assertEquals(156.45, $order->getAmountPaid());
    }
    
    public function testUpdateTotalMoreThanEqual150()
    {
         $order = new Order();
         $orderItems = $order->getOrderItems();
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(10);
         $orderItems->add($orderItem);
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(60);
         $orderItems->add($orderItem);
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(80);
         $orderItems->add($orderItem);
    
         $order->updateOrderTotal();
         
         $this->assertEquals(150,$order->getOrderTotal());
         $this->assertEquals(0,$order->getDeliveryCharge());
         $this->assertEquals(150, $order->getAmountPaid());
    }
    
    public function testRemoveItem()
    {
         $order = new Order();
         $orderItems = $order->getOrderItems();
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(10);
         $orderItems->add($orderItem);
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(60);
         $orderItems->add($orderItem);
         
         $orderItem = new OrderItem();
         $orderItem->setSubtotal(80);
         $orderItems->add($orderItem);
         
         $order->removeOrderItem(2);
         $order->updateOrderTotal();
         
         $this->assertEquals(2,count($order->getOrderItems()));
         $this->assertEquals(76.50,$order->getAmountPaid());
    }
    
}



?>
