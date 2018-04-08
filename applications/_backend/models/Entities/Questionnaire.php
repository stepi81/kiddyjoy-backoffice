<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities;
 use models\Entities\Questionnaire\Question;
 use models\Entities\Questionnaire\Result;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\QuestionnaireRepository")
  * @Table(name="ecom_questionnaires")
  */
 class Questionnaire {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="datetime") */
    private $date;
    /** @Column(type="string", length=120, nullable=false) */
    private $title;
    /** @Column(type="string", length=60, nullable=true) */
    private $image;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $status;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Questionnaire\Question", mappedBy="questionnaire", cascade={"remove"})
     */
    private $questions;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Questionnaire\Result", mappedBy="questionnaire", cascade={"remove"})
     */
    private $results;
    
    public function __construct() {
        
        $this->page = '';
        $this->status = 0;
        
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
    public function setDate() { $this->date = new \DateTime("now"); }
    public function getDate() { return $this->date; }
    public function getFormatedDate() { return $this->date->format('d/m/Y'); }
    public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
    public function setImage( $value ) { $this->image = $value; }
    public function getImage() { return $this->image; }
    public function getImageURL() { return $this->image ? assets_url('img/questionnaires/'.$this->image) : assets_url('img/questionnaires/kiddyjoy_anketa.jpg'); }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
    public function setQuestion( Question $value ) { $this->questions[] = $value; }
    public function getQuestions() { return $this->questions; }
    public function setResult( Result $value ) { $this->results[] = $value; }
    public function getResults() { return $this->results; }
 }
 
 /* End of file Questionnaire.php */
 /* Location: ./system/applications/_backend/models/entities/Questionnaire/Questionnaire.php */