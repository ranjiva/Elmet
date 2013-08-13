<?php

namespace Elmet\SiteBundle\Entity;

use Doctrine\ORM\mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="order_items")
*/
class OrderItem {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
      * @ORM\ManyToOne(targetEntity="Order", inversedBy="order_items")
      * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
      */
     protected $order;
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $name;
    
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $description;
     
     /**
     * @ORM\Column(type="string", length=100) 
     */
     protected $size;
     
     /**
     * @ORM\Column(type="string", length=25) 
     */
     protected $drop_alteration;
     
     /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
     protected $price;
     
     /**
     * @ORM\Column(type="integer", length=11)
     */
     protected $quantity;
     
     /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
     protected $subtotal;
     
     /**
     * @ORM\Column(type="string", length=20) 
     */
     protected $product_type;
     
     /**
     * @ORM\Column(type="integer", length=11)
     */
     protected $product_category_id;
     
     protected $curtain_colour;
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $colour;
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $item_filepath;
     
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set size
     *
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set drop_alteration
     *
     * @param string $dropAlteration
     */
    public function setDropAlteration($dropAlteration)
    {
        $this->drop_alteration = $dropAlteration;
    }

    /**
     * Get drop_alteration
     *
     * @return string 
     */
    public function getDropAlteration()
    {
        return $this->drop_alteration;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return decimal 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set subtotal
     *
     * @param decimal $subtotal
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = round($subtotal,2);
    }

    /**
     * Get subtotal
     *
     * @return decimal 
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set product_type
     *
     * @param string $productType
     */
    public function setProductType($productType)
    {
        $this->product_type = $productType;
    }

    /**
     * Get product_type
     *
     * @return string 
     */
    public function getProductType()
    {
        return $this->product_type;
    }

    /**
     * Set colour
     *
     * @param string $colour
     */
    public function setColour($colour)
    {
        $this->colour = $colour;
    }

    /**
     * Get colour
     *
     * @return string 
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * Set item_filepath
     *
     * @param string $itemFilepath
     */
    public function setItemFilepath($itemFilepath)
    {
        $this->item_filepath = $itemFilepath;
    }

    /**
     * Get item_filepath
     *
     * @return string 
     */
    public function getItemFilepath()
    {
        return $this->item_filepath;
    }

    /**
     * Set order
     *
     * @param Elmet\SiteBundle\Entity\Order $order
     */
    public function setOrder(\Elmet\SiteBundle\Entity\Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get order
     *
     * @return Elmet\SiteBundle\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set curtain_colour
     *
     * @param Elmet\SiteBundle\Entity\CurtainColour $curtainColour
     */
    public function setCurtainColour(\Elmet\SiteBundle\Entity\CurtainColour $curtainColour)
    {
        $this->curtain_colour = $curtainColour;
    }

    /**
     * Get curtain_colour
     *
     * @return Elmet\SiteBundle\Entity\CurtainColour 
     */
    public function getCurtainColour()
    {
        return $this->curtain_colour;
    }
    
    /**
     * Set product_category_id
     *
     * @param integer $product_category_id
     */
    public function setProductCategoryId($product_category_id)
    {
        $this->product_category_id = $product_category_id;
    }

    /**
     * Get product_category_id
     *
     * @return integer 
     */
    public function getProductCategoryId()
    {
        return $this->product_category_id;
    }
    
    /**
     * Quantity is stored as an integer but fabric quantity is a decimal
     * So convert fabric quantity into an integer by multiplying by 100
     */
    public function setFabricQuantity($quantity) {
        
        $this->quantity = intVal(100*$quantity);
    }
    
    /**
     * Quantity is stored as an integer but fabric quantity is a decimal
     * So convert fabric quantity into a decimal by dividing by 100
     */
    public function getFabricQuantity() {
        
       return doubleVal($this->quantity / 100);
    }
    
}