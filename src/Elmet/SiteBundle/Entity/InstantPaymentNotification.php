<?php

namespace Elmet\SiteBundle\Entity;
use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="instant_payment_notifications")
*/
class InstantPaymentNotification
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     */
     protected $id;
     
     /**
     * @ORM\Column(type="string", length=19) 
     */
     protected $txn_id;
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $custom;
     
     /**
     * @ORM\Column(type="string", length=64) 
     */
     protected $first_name;
     
     /**
     * @ORM\Column(type="string", length=64) 
     */
     protected $last_name;
     
     /**
     * @ORM\Column(type="string", length=127) 
     */
     protected $payer_email;
     
     /**
     * @ORM\Column(type="string", length=40) 
     */
     protected $address_city;
     
     /**
     * @ORM\Column(type="string", length=64) 
     */
     protected $address_country;
     
     /**
     * @ORM\Column(type="string", length=128) 
     */
     protected $address_name;
     
     /**
     * @ORM\Column(type="string", length=40) 
     */
     protected $address_state;
     
     /**
     * @ORM\Column(type="string", length=200) 
     */
     protected $address_street;
     
     /**
     * @ORM\Column(type="string", length=20) 
     */
     protected $address_zip;
     
     /**
     * @ORM\Column(type="string", length=20) 
     */
     protected $payment_status;
     
     /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
     protected $mc_gross;

     /**
     * @ORM\Column(type="string", length=127) 
     */
     protected $item_name;
     
     /**
     * @ORM\Column(type="string", length=127) 
     */
     protected $receiver_email;
     
     /**
     * @ORM\Column(type="string", length=3) 
     */
     protected $mc_currency;
     
     /**
     * @ORM\Column(type="string", length=20) 
     */
     protected $contact_phone;
     
     
     /**
     * Set id
     *
     * @return string 
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Set txn_id
     *
     * @param string $txnId
     */
    public function setTxnId($txnId)
    {
        $this->txn_id = $txnId;
    }

    /**
     * Get txn_id
     *
     * @return string 
     */
    public function getTxnId()
    {
        return $this->txn_id;
    }

    /**
     * Set custom
     *
     * @param string $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * Get custom
     *
     * @return string 
     */
    public function getCustom()
    {
        return $this->custom;
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
     * Set payer_email
     *
     * @param string $payerEmail
     */
    public function setPayerEmail($payerEmail)
    {
        $this->payer_email = $payerEmail;
    }

    /**
     * Get payer_email
     *
     * @return string 
     */
    public function getPayerEmail()
    {
        return $this->payer_email;
    }

    /**
     * Set address_city
     *
     * @param string $addressCity
     */
    public function setAddressCity($addressCity)
    {
        $this->address_city = $addressCity;
    }

    /**
     * Get address_city
     *
     * @return string 
     */
    public function getAddressCity()
    {
        return $this->address_city;
    }

    /**
     * Set address_country
     *
     * @param string $addressCountry
     */
    public function setAddressCountry($addressCountry)
    {
        $this->address_country = $addressCountry;
    }

    /**
     * Get address_country
     *
     * @return string 
     */
    public function getAddressCountry()
    {
        return $this->address_country;
    }

    /**
     * Set address_name
     *
     * @param string $addressName
     */
    public function setAddressName($addressName)
    {
        $this->address_name = $addressName;
    }

    /**
     * Get address_name
     *
     * @return string 
     */
    public function getAddressName()
    {
        return $this->address_name;
    }

    /**
     * Set address_state
     *
     * @param string $addressState
     */
    public function setAddressState($addressState)
    {
        $this->address_state = $addressState;
    }

    /**
     * Get address_state
     *
     * @return string 
     */
    public function getAddressState()
    {
        return $this->address_state;
    }

    /**
     * Set address_street
     *
     * @param string $addressStreet
     */
    public function setAddressStreet($addressStreet)
    {
        $this->address_street = $addressStreet;
    }

    /**
     * Get address_street
     *
     * @return string 
     */
    public function getAddressStreet()
    {
        return $this->address_street;
    }

    /**
     * Set address_zip
     *
     * @param string $addressZip
     */
    public function setAddressZip($addressZip)
    {
        $this->address_zip = $addressZip;
    }

    /**
     * Get address_zip
     *
     * @return string 
     */
    public function getAddressZip()
    {
        return $this->address_zip;
    }

    /**
     * Set payment_status
     *
     * @param string $paymentStatus
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->payment_status = $paymentStatus;
    }

    /**
     * Get payment_status
     *
     * @return string 
     */
    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    /**
     * Set mc_gross
     *
     * @param decimal $mcGross
     */
    public function setMcGross($mcGross)
    {
        $this->mc_gross = $mcGross;
    }

    /**
     * Get mc_gross
     *
     * @return decimal 
     */
    public function getMcGross()
    {
        return $this->mc_gross;
    }

    /**
     * Set item_name
     *
     * @param string $itemName
     */
    public function setItemName($itemName)
    {
        $this->item_name = $itemName;
    }

    /**
     * Get item_name
     *
     * @return string 
     */
    public function getItemName()
    {
        return $this->item_name;
    }

    /**
     * Set receiver_email
     *
     * @param string $receiverEmail
     */
    public function setReceiverEmail($receiverEmail)
    {
        $this->receiver_email = $receiverEmail;
    }

    /**
     * Get receiver_email
     *
     * @return string 
     */
    public function getReceiverEmail()
    {
        return $this->receiver_email;
    }

    /**
     * Set mc_currency
     *
     * @param string $mcCurrency
     */
    public function setMcCurrency($mcCurrency)
    {
        $this->mc_currency = $mcCurrency;
    }

    /**
     * Get mc_currency
     *
     * @return string 
     */
    public function getMcCurrency()
    {
        return $this->mc_currency;
    }

    /**
     * Set contact_phone
     *
     * @param string $contactPhone
     */
    public function setContactPhone($contactPhone)
    {
        $this->contact_phone = $contactPhone;
    }

    /**
     * Get contact_phone
     *
     * @return string 
     */
    public function getContactPhone()
    {
        return $this->contact_phone;
    }
}