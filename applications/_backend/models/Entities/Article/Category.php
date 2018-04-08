<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Article;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ArticleCategoryRepository")
  * @Table(name="ecom_article_categories")
  */
 class Category {
 	
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
	private $id;
	
	/** @Column(type="string", length=100, nullable=false) */
	private $name;
	/** @Column(type="string", length=120, nullable=false) */
    private $icon;
	/** @Column(type="string", length=240, nullable=false) */
    private $seo_title;
	/** @Column(type="string", length=240, nullable=false) */
    private $seo_keywords;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	/** @Column(type="integer", length=2, nullable=false) */
	private $status;
    
    /**
     * @OneToMany(targetEntity="Category", mappedBy="parent", cascade={"remove"})
     **/
    private $children;
    
    /**
     * @ManyToOne(targetEntity="Category", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Article", mappedBy="category", cascade={"remove"})
     **/
    private $articles;

	public function __construct() {

        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
		$this->articles = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setSeoTitle( $value ) { $this->seo_title = $value; }
	public function getSeoTitle() { return $this->seo_title; }
	public function setSeoKeywords( $value ) { $this->seo_keywords = $value; }
	public function getSeoKeywords() { return $this->seo_keywords; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setStatus( $value ) { $this->status = $value; }
	public function getStatus() { return $this->status; }
    public function setParent( $value ) { $this->parent = $value; }
    public function getParent() { return $this->parent; }
    public function getChildren() { return $this->children; }
	public function getArticles() { return $this->articles; }
	public function setImage( $value ) { $this->icon = $value; }
	public function getImage() { return $this->icon; }
	public function getImageURL() { return $this->icon ? assets_url('img/articles/categories/'.$this->icon) : assets_url('img/articles/categories/default.png'); }
 }
 
 /* End of file Category.php */
 /* Location: ./system/applications/_frontend/models/Entities/Article/Category.php */