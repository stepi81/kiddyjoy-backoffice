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
 class ArticleComment extends Comment {
 	
	/**
     * @ManyToOne(targetEntity="models\Entities\Article", inversedBy="comments")
     * @JoinColumn(name="record_id", referencedColumnName="id")
     */
	private $article;
	
	public function setArticle( $value ) { $this->article = $value; }
	public function getArticle() { return $this->article; }
 }
 
 /* End of file ArticleComment.php */
 /* Location: ./system/applications/_frontend/models/Entities/Comment/ArticleComment.php */