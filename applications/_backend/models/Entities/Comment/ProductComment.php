<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Comment;
 
 use models\Entities\Comment;
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity (repositoryClass="models\CommentRepository")
  */
 class ProductComment extends Comment {
 	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product", inversedBy="comments")
     * @JoinColumn(name="record_id", referencedColumnName="id")
     */
	private $product;
	
	/** 
     * @ManyToOne(targetEntity="models\Entities\Comment\ProductComment")
     * @JoinColumn(name="record_id", referencedColumnName="id")
     **/
    private $record; 
	
	public function setProduct( $value ) { $this->product = $value; }
	public function getProduct() { return $this->product; }
	public function getRecord() { return $this->record; }
 }
 
 /* End of file ProductComment.php */
 /* Location: ./system/applications/_frontend/models/Entities/Comment/ProductComment.php */