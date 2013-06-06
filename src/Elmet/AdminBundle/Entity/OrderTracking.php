<?php

namespace Elmet\AdminBundle\Entity;

use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="order_tracking")
*/
class OrderTracking
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
      * @ORM\OneToOne(targetEntity="Elmet\SiteBundle\Entity\Order", cascade={"merge"})
      * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
      */
     protected $order;
     
     /**
     * @ORM\Column(type="date") 
     */
     protected $shipment_date;
     
     /**
     * @ORM\Column(type="string", length=15) 
     */
     protected $tracking_status;
     
     /**
     * @ORM\Column(type="integer", length=11) 
     */
     protected $invoice_number;
     
     /**
     * @ORM\Column(type="string", length=20) 
     */
     protected $tracking_number;
     
     /**
      * @ORM\OneToOne(targetEntity="Elmet\AdminBundle\Entity\Batch")
      * @ORM\JoinColumn(name="batch_id", referencedColumnName="id")
      */
     protected $batch;
     
     /**
     * @ORM\Column(type="integer", length=11) 
     */
     protected $item_id;
     
     /**
     * @ORM\Column(type="date") 
     */
     protected $estimated_dispatch_date;

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
     * Set tracking_status
     *
     * @param string $trackingStatus
     */
    public function setTrackingStatus($trackingStatus)
    {
        $this->tracking_status = $trackingStatus;
    }

    /**
     * Get tracking_status
     *
     * @return string 
     */
    public function getTrackingStatus()
    {
        return $this->tracking_status;
    }

    /**
     * Set invoice_number
     *
     * @param integer $invoiceNumber
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoice_number = $invoiceNumber;
    }

    /**
     * Get invoice_number
     *
     * @return integer 
     */
    public function getInvoiceNumber()
    {
        return $this->invoice_number;
    }

    /**
     * Set tracking_number
     *
     * @param string $trackingNumber
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->tracking_number = $trackingNumber;
    }

    /**
     * Get tracking_number
     *
     * @return string 
     */
    public function getTrackingNumber()
    {
        return $this->tracking_number;
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
     * Set shipment_date
     *
     * @param date $shipmentDate
     */
    public function setShipmentDate($shipmentDate)
    {
        $this->shipment_date = $shipmentDate;
    }

    /**
     * Get shipment_date
     *
     * @return date 
     */
    public function getShipmentDate()
    {
        return $this->shipment_date;
    }

    /**
     * Set item_id
     *
     * @param integer $itemId
     */
    public function setItemId($itemId)
    {
        $this->item_id = $itemId;
    }

    /**
     * Get item_id
     *
     * @return integer 
     */
    public function getItemId()
    {
        return $this->item_id;
    }


    /**
     * Set batch
     *
     * @param Elmet\AdminBundle\Entity\Batch $batch
     */
    public function setBatch(\Elmet\AdminBundle\Entity\Batch $batch)
    {
        $this->batch = $batch;
    }

    /**
     * Get batch
     *
     * @return Elmet\AdminBundle\Entity\Batch 
     */
    public function getBatch()
    {
        return $this->batch;
    }
    

    /**
     * Set estimated_dispatch_date
     *
     * @param date $estimatedDispatchDate
     */
    public function setEstimatedDispatchDate($estimatedDispatchDate)
    {
        $this->estimated_dispatch_date = $estimatedDispatchDate;
    }

    /**
     * Get estimated_dispatch_date
     *
     * @return date 
     */
    public function getEstimatedDispatchDate()
    {
        return $this->estimated_dispatch_date;
    }
}