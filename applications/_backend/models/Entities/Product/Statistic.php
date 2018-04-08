<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_product_statistics")
  */
 class Statistic {
 	
	/**
	 * @Id
	 * @Column(type="string", length=16, nullable=false)
	 */
	private $product_id;
	
	/** @Column(type="integer", length=10, nullable=false) */
    private $visits;
	/** @Column(type="integer", length=10, nullable=false) */
	private $votes;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
	private $rating;
	/** @Column(type="integer", length=10, nullable=false) */
    private $sold;
	
	public function __construct() {
		
		$this->visits = 1;
		$this->votes = 0;
		$this->rating = 0;
		$this->sold = 0;
	}
	
	public function setID( $value ) { $this->product_id = $value; }
    public function getID() { return $this->product_id; }
	public function setVisit() { $this->visits += 1; }
    public function getVisits() { return $this->visits; }
	public function setVote() { $this->votes += 1; }
    public function getVotes() { return $this->votes; }
	public function setRating( $value ) { $this->rating = $value; }
    public function getRating() { return $this->rating; }
	public function setSold() { $this->sold += 1; }
    public function getSold() { return $this->sold; }
 }
 
 /* End of file Statistic.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Statistic.php */