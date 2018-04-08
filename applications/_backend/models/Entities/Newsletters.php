<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\NewsletterRepository")
  * @Table(name="ecom_newsletters")
  */
 class Newsletters {
    
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=120, nullable=false) */
    private $template;
    /** @Column(type="integer", length=10, nullable=false) */
    private $users_group;
    /** @Column(type="string", length=250, nullable=false) */
    private $title;
    /** @Column(type="string", nullable=false) */
    private $message;
    /** @Column(type="date") */
    private $send_date;
    /** @Column(type="integer", length=10, nullable=false) */
    private $offset;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @OneToMany(targetEntity="NewsletterImage", mappedBy="newsletter", cascade={"remove"})
     * @JoinColumn(name="id", referencedColumnName="newsletter_id")
     */
    private $images;
    
        public function __construct() {
        
        $this->message = '';
        $this->status = 0;
		$this->offset = 0;
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        }    
    
    public function getID() { return $this->id; }
    public function setTemplate( $value ) { $this->template = $value; }
    public function getTemplate() { return $this->template; }
    public function setUsersGroup( $value ) { $this->users_group = $value; }
    public function getUsersGroup() { return $this->users_group; }
    public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
    public function setMessage( $value ) { $this->message = $value; }
    public function getMessage() { return $this->message; }
    
    public function setSendDate( $value ) { $this->send_date = $value; }
    public function getSendDate() { return $this->send_date->format('d.m.Y'); }
    public function setImage( NewsletterImage $value ) { $this->images[] = $value; }
    public function getImages() { return $this->images; }

    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
 }
 
 /* End of file Newsletters.php */
 /* Location: ./system/applications/_backend/models/Entities/Newsletter.php */