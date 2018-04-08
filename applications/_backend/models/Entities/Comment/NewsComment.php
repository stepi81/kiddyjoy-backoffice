<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Comment;
 
 use models\Entities\Comment;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity (repositoryClass="models\CommentRepository")
  */
 class NewsComment extends Comment {
 	
	/**
     * @ManyToOne(targetEntity="models\Entities\News\Info", inversedBy="comments")
     * @JoinColumn(name="record_id", referencedColumnName="id")
     */
	private $news;
	
	public function setNews( $value ) { $this->news = $value; }
	public function getNews() { return $this->news; }
 }
 
 /* End of file NewsComment.php */
 /* Location: ./system/applications/_frontend/models/Entities/Comment/NewsComment.php */