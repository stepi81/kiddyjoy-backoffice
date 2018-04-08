<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Questionnaire;
 use models\Entities\Questionnaire;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ResultRepository")
  * @Table(name="ecom_questionnaire_results")
  */
 class Result {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=240, nullable=false) */
    private $data;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Questionnaire", inversedBy="results")
     * @JoinColumn(name="questionnaire_id", referencedColumnName="id")
     */
    private $questionnaire;
    
    public function getID() { return $this->id; }
    public function setData( $value ) { $this->data = $value; }
    public function getData() { return $this->data; }
    public function setQuestionnaire( Questionnaire $value ) { $this->questionnaire = $value; }
    public function getQuestionnaire() { return $this->questionnaire; }
 }
 
 /* End of file Result.php */
 /* Location: ./system/applications/_backend/models/Entities/Questionnaire/Result.php */