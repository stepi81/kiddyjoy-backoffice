<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ReviewRepository")
  * @Table(name="ecom_reviews")
  * @InheritanceType("SINGLE_TABLE")
  * @DiscriminatorColumn(name="type_id", type="integer")
  * @DiscriminatorMap({"1" = "models\Entities\ReviewWeb", "2" = "models\Entities\ReviewStore"})
  */
 class Review {
    
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="datetime") */
    private $date;
    /** @Column(type="text", nullable=false) */
    private $text_advantage;
    /** @Column(type="text", nullable=false) */
    private $text_against;
	/** @Column(type="integer", length=10, nullable=false) */
    private $positive;
    /** @Column(type="integer", length=10, nullable=false) */
    private $negative;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $overall;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
    /**
     * @ManyToOne(targetEntity="models\Entities\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product_id;

    /**
     * @ManyToOne(targetEntity="models\Entities\User\Customer")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user_id;
    
    /**
     * @ManyToMany(targetEntity="models\Entities\Product\Review\ReviewRating")
     * @JoinTable(name="ecom_reviews_ratings",
     *      joinColumns={@JoinColumn(name="review_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="rating_id", referencedColumnName="id")}
     *      )
     **/
    private $ratings;
    
 	public function __construct() {
        
        $this->ratings = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date; }
    public function getFormatedDate() { return $this->date->format('d/m/Y'); }
 	public function setTextAdvantage( $value ) { $this->text_advantage = $value; }
    public function getTextAdvantage() { return $this->text_advantage; }
 	public function setTextAgainst( $value ) { $this->text_against = $value; }
    public function getTextAgainst() { return $this->text_against; }
    public function setPositive( $value ) { $this->positive = $value; }
    public function getPositive() { return $this->positive; }
    public function setNegative( $value ) { $this->negative = $value; }
    public function getNegative() { return $this->negative; }
 	public function getTotalVotes() { return $this->positive + $this->negative; }
 	public function getOverall() { return $this->overall; }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
 	public function getRatings() { return $this->ratings; }
 	public function setProductId( $value ) { $this->product_id = $value; }
    public function getProductId() { return $this->product_id; }
    public function setUserId( $value ) { $this->user_id = $value; }
    public function getUserId() { return $this->user_id; }
    
 	public function getRating( $specification_id ) {
		
		foreach( $this->ratings as $rating ) {
			if( $rating->getSpecification()->getID() == $specification_id ) return $rating->getValuation();
		}
		return 0;
	}
 }
 
 /* End of file Review.php */
 /* Location: ./system/applications/_backend/models/Entities/Review.php */