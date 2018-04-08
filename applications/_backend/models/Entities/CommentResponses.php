<?php                                                        

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CommentResponsesRepository")
  * @Table(name="ecom_comment_responses")
  */
 class CommentResponses {
   
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="datetime") */
    private $date;
    /** @Column(type="string", length=120, nullable=true) */
    private $comment_id;
    /** @Column(type="text", nullable=false) */
    private $message;

    public function getID() { return $this->id; }
    public function setCommentId( $value ) { $this->comment_id = $value; }
    public function getCommentId() { return $this->comment_id; }
    public function setMessage( $value ) { $this->message = $value; }
    public function getMessage() { return $this->message; } 
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date->format('d/m/Y'); }
    
 }
 
 /* End of file Comment.php */
 /* Location: ./system/applications/_backend/models/Entities/CommentResponses.php */