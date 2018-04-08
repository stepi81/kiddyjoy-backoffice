<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_product_videos")
  */
 class Video {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $code;
	/** @Column(type="string", length=60, nullable=false) */
	private $title;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product", inversedBy="videos")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
	private $product;
	
	public function getID() { return $this->id; }
	public function setCode( $value ) { $this->code = $value; }
    public function getCode() { return $this->code; }
	public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
	public function setProduct( Product $value ) { $this->product = $value; }
    public function getProduct() { return $this->product; }
	
	public function getSource() {
		return '<iframe width="398" height="288" src="http://www.youtube.com/embed/'.$this->code.'?rel=0" frameborder="0" allowfullscreen></iframe>';
	}
 }
 
 /* End of file Video.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Video.php */