<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product\Review;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 use models\Entities\Product\Review\ReviewSpecification;
 
 /**
  * @Entity
  * @Table(name="ecom_review_specification_ratings")
  */
 class ReviewRating {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="integer", length=2, nullable=false) */
	private $valuation;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Review\ReviewSpecification", inversedBy="ratings")
     * @JoinColumn(name="specification_id", referencedColumnName="id")
     **/
	private $specification;
	
	public function __construct() {
		
        // TODO
    }
	
 	public function setID( $value ) { $this->id = $value; }
    public function getID() { return $this->id; }
	public function setValuation( $value ) { $this->valuation = $value; }
    public function getValuation() { return $this->valuation; }
    public function setSpecification( ReviewSpecification $value ) { $this->specification = $value; }
    public function getSpecification() { return $this->specification; }
    public function getSubcategory() { return $this->specification->getSubcategory(); }
 }
 
 /* End of file ReviewRating.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Review/ReviewRating.php */