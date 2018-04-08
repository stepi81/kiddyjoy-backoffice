<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\Product;
 use models\Entities\User;
 use models\Entities\CustomerAnswer;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\AskCustomerRepository")
  * @Table(name="ecom_ask_customers")
  */
 class AskCustomer {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="text", nullable=false) */
    private $question;
    /** @Column(type="datetime") */
    private $date;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\User\Customer")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
     /**
     * @ManyToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
	
	/**
     * @OneToMany(targetEntity="models\Entities\CustomerAnswer", mappedBy="question", cascade={"remove"})
     */
    private $answers;
    
    public function setID( $value ) { $this->id = $value; }
    public function getID() { return $this->id; }
    public function setQuestion( $value ) { $this->question = $value; }
    public function getQuestion() { return $this->question; }
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date->format('d/m/Y H:i:s'); }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    public function setUser( User $value ) { $this->user = $value; }
    public function getUser() { return $this->user; }
	public function getUserName() { return $this->user->getNickname() ? $this->user->getNickname() : $this->user->getFirstName().' '.$this->user->getLastName(); }
    public function setProduct( Product $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
	
	public function getURL() {
		return url_title($this->product->getName(), 'underscore', TRUE).'-'.$this->product->getID();
	}
 }
 
 /* End of file AskCustomer.php */
 /* Location: ./system/applications/_frontend/models/Entities/AskCustomer.php */