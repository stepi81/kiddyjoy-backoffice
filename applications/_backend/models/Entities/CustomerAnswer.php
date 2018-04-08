<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\User;
 use models\Entities\AskCustomer;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CustomerAnswerRepository")
  * @Table(name="ecom_customer_answers")
  */
 class CustomerAnswer {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="text", nullable=false) */
    private $answer;
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
     * @ManyToOne(targetEntity="models\Entities\AskCustomer")
     * @JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;
    
    public function setID( $value ) { $this->id = $value; }
    public function getID() { return $this->id; }
    public function setAnswer( $value ) { $this->answer = $value; }
    public function getAnswer() { return $this->answer; }
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date->format('d/m/Y H:i:s'); }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    public function setUser( User $value ) { $this->user = $value; }
    public function getUser() { return $this->user; }
	public function getUserName() { return $this->user->getNickname() ? $this->user->getNickname() : $this->user->getFirstName().' '.$this->user->getLastName(); }
    public function setQuestion( AskCustomer $value ) { $this->question = $value; }
    public function getQuestion() { return $this->question; }
	
	public function getURL() {
		return url_title($this->question->getProduct()->getName(), 'underscore', TRUE).'-'.$this->question->getProduct()->getID();
	}
 }
 
 /* End of file CustomerAnswer.php */
 /* Location: ./system/applications/_frontend/models/Entities/CustomerAnswer.php */