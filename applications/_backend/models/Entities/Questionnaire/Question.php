<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities\Questionnaire;
 use models\Entities\Questionnaire\Answer;
 use models\Entities\Questionnaire;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\QuestionRepository")
  * @Table(name="ecom_questionnaire_questions")
  */
 class Question {
     
    /**
     * @Id 
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="smallint", length=2, nullable=false) */
    private $type;
    /** @Column(type="string", length=240, nullable=false) */
    private $text;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $position;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Questionnaire", inversedBy="questions")
     * @JoinColumn(name="questionnaire_id", referencedColumnName="id")
     */
    private $questionnaire;
    
    /**
     * @OneToMany(targetEntity="models\Entities\Questionnaire\Answer", mappedBy="answer", cascade={"remove"})
     */
    private $answers;
    
    public function getID() { return $this->id; }
    public function setType( $value ) { $this->type = $value; }
    public function getType() { return $this->type; }
    public function setText( $value ) { $this->text = $value; }
    public function getText() { return $this->text; }
    public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setQuestionnaire( Questionnaire $value ) { $this->questionnaire = $value; }
    public function getQuestionnaire() { return $this->questionnaire; }
    public function setAnswer( Answer $value ) { $this->answers[] = $value; }
    public function getAnswers() { return $this->answers; }
 }
 
 /* End of file Question.php */
 /* Location: ./system/applications/_backend/models/Entities/Questionnaire/Question.php */