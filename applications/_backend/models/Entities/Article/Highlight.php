<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Article;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ArticleHighlightRepository")
  * @Table(name="ecom_article_highlights")
  */
 class Highlight {
 	
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
	private $id;
	
	/** @Column(type="smallint", length=2, nullable=false) */
	private $type;
	/** @Column(type="smallint", length=4, nullable=false) */
	private $position;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Article")
     * @JoinColumn(name="article_id", referencedColumnName="id")
     **/
    private $article;

	public function __construct() {

        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
    public function getID() { return $this->id; }
	public function setType( $value ) { $this->type = $value; }
    public function getType() { return $this->type; }
	public function setArticle( $value ) { $this->article = $value; }
	public function getArticle() { return $this->article; }
	public function setPosition( $value ) { $this->position = $value; }
	public function getPosition() { return $this->position; }
 }
 
 /* End of file Highlight.php */
 /* Location: ./system/applications/_frontend/models/Entities/Article/Highlight.php */