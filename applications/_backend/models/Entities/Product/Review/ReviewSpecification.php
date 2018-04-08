<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product\Review;
 
 use models\Entities\Product\Subcategory;
 use models\Entities\Product\Review\ReviewRating;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_review_specifications")
  */
 class ReviewSpecification {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Subcategory")
     * @JoinColumn(name="subcategory_id", referencedColumnName="id")
     **/
    private $subcategory;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Product\Review\ReviewRating", mappedBy="specification", cascade={"persist"})
     * @OrderBy({"valuation" = "ASC"})
     **/
    private $ratings;
	
	public function __construct() {
		
        $this->ratings = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
 	public function setID( $value ) { $this->id = $value; }
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setRating( ReviewRating $value ) { $this->ratings->add($value); }
    public function getRatings() { return $this->ratings; }
    public function setSubcategory( Subcategory $value ) { $this->subcategory = $value; }
    public function getSubcategory() { return $this->subcategory; }
    
    public function initRatings() {
    	
    	for($i=1; $i<=10; $i++) {
        	$rating = new ReviewRating();
        	$rating->setValuation($i);
        	$rating->setSpecification($this);
        	$this->ratings[] = $rating;
        }
    }
    
 	public function getRatingIdList() {
 		foreach( $this->ratings as $rating ) $id_list[] = $rating->getID();
 		return implode(",", $id_list);
 	}
 	
 	public function getDefaultRatingID() {
 		return $this->ratings->first()->getID();
 	}
 }
 
 /* End of file ReviewSpecification.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Review/ReviewSpecification.php */