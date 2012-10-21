<?php

namespace Elmet\SiteBundle\Entity;

use Doctrine\ORM\mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="orders")
*/
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $first_name = "";
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $last_name = "";
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $email = "";
     
     /**
     * @ORM\Column(type="string", length=50) 
     */
     protected $telephone = "";
     
     /**
     * @ORM\Column(type="string", length=50) 
     */
     protected $mobile = "";
     
     /**
     * @ORM\Column(type="string", length=125) 
     */
     protected $billing_name = "";
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $billing_address = "";
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $billing_address_2 = "";
     
     /**
     * @ORM\Column(type="string", length=150) 
     */
     protected $billing_town = "";
     
     /**
     * @ORM\Column(type="string", length=10) 
     */
     protected $billing_postcode = "";
     
     /**
     * @ORM\Column(type="string", length=125) 
     */
     protected $delivery_name = "";
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $delivery_address = "";
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $delivery_address_2 = "";
     
     /**
     * @ORM\Column(type="string", length=150) 
     */
     protected $delivery_town = "";
     
     /**
     * @ORM\Column(type="string", length=10) 
     */
     protected $delivery_postcode = "";
     
     /**
     * @ORM\Column(type="datetime") 
     */
     protected $created;
     
     /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
     protected $order_total;
     
     /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
     protected $delivery_charge;
     
     /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
     protected $amount_paid;
     
     /**
     * @ORM\Column(type="string", length=10)
     */
     protected $order_status;
     
     /**
     * @ORM\Column(type="string", length=255)
     */
     protected $notes = "";
     
     /**
      * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist", "merge", "detach", "remove"})
      */
     protected $order_items;

     public function __construct()
     {
          $this->order_items = new ArrayCollection();
          $this->delivery_charge = 0.0;
     }

     public function removeOrderItem($index)
     {
         $this->order_items->remove($index);
         
         $new_order_items = new ArrayCollection();
         
         foreach($this->order_items as $orderItem)
         {
             $new_order_items->add($orderItem);
         }
         
         $this->order_items = $new_order_items;
     }
     
     public function updateOrderTotal()
     {
         $this->order_total = 0.00;
         $this->delivery_charge = 0.00;
         
         foreach($this->order_items as $orderItem)
         {
             $this->order_total = $this->order_total + $orderItem->getSubTotal();
         }
         
         if ($this->order_total < 150.0)
         {
             $this->delivery_charge = 6.50;
         }
         
         $this->amount_paid = $this->order_total + $this->delivery_charge;
     }
     
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set billing_name
     *
     * @param string $billingName
     */
    public function setBillingName($billingName)
    {
        $this->billing_name = $billingName;
    }

    /**
     * Get billing_name
     *
     * @return string 
     */
    public function getBillingName()
    {
        return $this->billing_name;
    }

    /**
     * Set billing_address
     *
     * @param string $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billing_address = $billingAddress;
    }

    /**
     * Get billing_address
     *
     * @return string 
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }

    /**
     * Set billing_address2
     *
     * @param string $billingAddress2
     */
    public function setBillingAddress2($billingAddress2)
    {
        $this->billing_address_2 = $billingAddress2;
    }

    /**
     * Get billing_address2
     *
     * @return string 
     */
    public function getBillingAddress2()
    {
        return $this->billing_address_2;
    }

    /**
     * Set billing_town
     *
     * @param string $billingTown
     */
    public function setBillingTown($billingTown)
    {
        $this->billing_town = $billingTown;
    }

    /**
     * Get billing_town
     *
     * @return string 
     */
    public function getBillingTown()
    {
        return $this->billing_town;
    }

    /**
     * Set billing_postcode
     *
     * @param string $billingPostcode
     */
    public function setBillingPostcode($billingPostcode)
    {
        $this->billing_postcode = $billingPostcode;
    }

    /**
     * Get billing_postcode
     *
     * @return string 
     */
    public function getBillingPostcode()
    {
        return $this->billing_postcode;
    }

    /**
     * Set delivery_name
     *
     * @param string $deliveryName
     */
    public function setDeliveryName($deliveryName)
    {
        $this->delivery_name = $deliveryName;
    }

    /**
     * Get delivery_name
     *
     * @return string 
     */
    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    /**
     * Set delivery_address
     *
     * @param string $deliveryAddress
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->delivery_address = $deliveryAddress;
    }

    /**
     * Get delivery_address
     *
     * @return string 
     */
    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    /**
     * Set delivery_address2
     *
     * @param string $deliveryAddress2
     */
    public function setDeliveryAddress2($deliveryAddress2)
    {
        $this->delivery_address_2 = $deliveryAddress2;
    }

    /**
     * Get delivery_address2
     *
     * @return string 
     */
    public function getDeliveryAddress2()
    {
        return $this->delivery_address_2;
    }

    /**
     * Set delivery_town
     *
     * @param string $deliveryTown
     */
    public function setDeliveryTown($deliveryTown)
    {
        $this->delivery_town = $deliveryTown;
    }

    /**
     * Get delivery_town
     *
     * @return string 
     */
    public function getDeliveryTown()
    {
        return $this->delivery_town;
    }

    /**
     * Set delivery_postcode
     *
     * @param string $deliveryPostcode
     */
    public function setDeliveryPostcode($deliveryPostcode)
    {
        $this->delivery_postcode = $deliveryPostcode;
    }

    /**
     * Get delivery_postcode
     *
     * @return string 
     */
    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    /**
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set order_total
     *
     * @param decimal $orderTotal
     */
    public function setOrderTotal($orderTotal)
    {
        $this->order_total = $orderTotal;
    }

    /**
     * Get order_total
     *
     * @return decimal 
     */
    public function getOrderTotal()
    {
        return $this->order_total;
    }

    /**
     * Set delivery_total
     *
     * @param decimal $deliveryTotal
     */
    public function setDeliveryCharge($deliveryCharge)
    {
        $this->delivery_charge = $deliveryCharge;
    }

    /**
     * Get delivery_total
     *
     * @return decimal 
     */
    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    /**
     * Set amount_paid
     *
     * @param decimal $amountPaid
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amount_paid = $amountPaid;
    }

    /**
     * Get amount_paid
     *
     * @return decimal 
     */
    public function getAmountPaid()
    {
        return $this->amount_paid;
    }

    /**
     * Set new
     *
     * @param string $new
     */
    public function setOrderStatus($orderStatus)
    {
        $this->order_status = $orderStatus;
    }

    /**
     * Get new
     *
     * @return string 
     */
    public function getOrderStatus()
    {
        return $this->order_status;
    }

    /**
     * Set notes
     *
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add order_items
     *
     * @param Elmet\SiteBundle\Entity\orderItem $orderItems
     */
    public function addorderItem(\Elmet\SiteBundle\Entity\orderItem $orderItems)
    {
        $this->order_items[] = $orderItems;
    }

    /**
     * Get order_items
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrderItems()
    {
        return $this->order_items;
    }
}