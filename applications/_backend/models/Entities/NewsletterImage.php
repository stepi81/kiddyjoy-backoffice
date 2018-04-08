<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */
 
 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_newsletter_images")
  */
 class NewsletterImage {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=60, nullable=false) */
    private $name;
    
    /**
     * @ManyToOne(targetEntity="Newsletters", inversedBy="images")
     * @JoinColumn(name="newsletter_id", referencedColumnName="id")
     */
    private $newsletter;
    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setNewsletter( Newsletters $value ) { $this->newsletter = $value; }
    public function getNewsletter() { return $this->newsletter; }
    public function getURL() { return assets_url('img/newsletter/'.$this->name); }

 }
 
 /* End of file NewsletterImage.php */
 /* Location: ./system/applications/_backend/models/entities/NewsletterImage.php */