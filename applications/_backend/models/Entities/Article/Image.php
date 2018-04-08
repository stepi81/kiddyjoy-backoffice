<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Article;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_article_images")
  */
 class Image {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Article", inversedBy="images")
     * @JoinColumn(name="article_id", referencedColumnName="id")
     */
	private $article;
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setArticle( $value ) { $this->article = $value; }
    public function getArticle() { return $this->article; }
	public function getURL() { return assets_url('img/articles/content/'.$this->name); }
 }
 
 /* End of file Image.php */
 /* Location: ./system/applications/_backend/models/Entities/Article/Image.php */