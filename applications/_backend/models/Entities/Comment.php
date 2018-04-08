<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities;
 
 use models\Entities\User\Customer\Personal;
 use models\Entities\Comment\Response;
 use models\Entities\Comment\ProductComment;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\CommentRepository")
  * @Table(name="ecom_comments")
  * @InheritanceType("SINGLE_TABLE")
  * @DiscriminatorColumn(name="type_id", type="integer")
  * @DiscriminatorMap({
  * "1" = "models\Entities\Comment\ProductComment",
  * "2" = "models\Entities\Comment\NewsComment",
  * "3" = "models\Entities\Comment\ArticleComment"})
  */
 class Comment {
    
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=120, nullable=true) */
    private $user_name;
    /** @Column(type="text", nullable=false) */
    private $message;
    /** @Column(type="datetime") */
    private $date;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @OneToOne(targetEntity="models\Entities\User\Customer")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Comment\Response", mappedBy="comment")
     * @OrderBy({"date" = "DESC"})
     */
    private $responses;
    
    public function __construct() {
        
        $this->status = 0;
        $this->user = NULL;
        $this->user_name = NULL;
        $this->responses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
    public function setUserName( $value ) { $this->user_name = $value; }
    public function getUserName() { return $this->user ? $this->user->getNickname() : $this->user_name; }
    public function getUserAvatar() { return $this->user ? $this->user->getAvatarURL() : assets_url('img/users/avatar.png'); }
    public function setMessage( $value ) { $this->message = $value; }
    public function getMessage() { return $this->message; } 
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date->format('d/m/Y H:i:s'); }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    public function setUser( Personal $value ) { $this->user = $value; }
    public function getUser() { return $this->user; }
    public function setResponse( Response $value ) { $this->responses[] = $value; }
    public function getResponses() { return $this->responses; }
 }
 
 /* End of file Comment.php */
 /* Location: ./system/applications/_backend/models/Entities/Comment.php */