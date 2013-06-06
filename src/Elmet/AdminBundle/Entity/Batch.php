<?php

namespace Elmet\AdminBundle\Entity;

use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="batch")
*/
class Batch
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\Column(type="integer", length=5) 
     */
     protected $next_item_id;
     
     /**
     * @ORM\Column(type="string", length=15) 
     */
     protected $batch_status;

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
     * Set next_item_id
     *
     * @param integer $nextItemId
     */
    public function setNextItemId($nextItemId)
    {
        $this->next_item_id = $nextItemId;
    }

    /**
     * Get next_item_id
     *
     * @return integer 
     */
    public function getNextItemId()
    {
        return $this->next_item_id;
    }

    /**
     * Set batch_status
     *
     * @param string $batchStatus
     */
    public function setBatchStatus($batchStatus)
    {
        $this->batch_status = $batchStatus;
    }

    /**
     * Get batch_status
     *
     * @return string 
     */
    public function getBatchStatus()
    {
        return $this->batch_status;
    }
}