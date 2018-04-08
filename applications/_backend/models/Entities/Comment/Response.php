<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Comment;
 
 use models\Entities\Comment;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_comment_responses")
  */
 class Response {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="text", nullable=false) */
    private $message;
	/** @Column(type="datetime") */
    private $date;
 	
	/**
     * @ManyToOne(targetEntity="models\Entities\Comment", inversedBy="responses")
     * @JoinColumn(name="comment_id", referencedColumnName="id")
     */
    private $comment;
	
	public function getID() { return $this->id; }
	public function setMessage( $value ) { $this->message = $value; }
    public function getMessage() { return $this->message; } 
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date->format('d/m/Y'); }
	public function setComment( Comment $value ) { $this->comment = $value; }
	public function getComment() { return $this->comment; }
 }
 
 /* End of file Response.php */
 /* Location: ./system/applications/_frontend/models/Entities/Comment/Response.php */