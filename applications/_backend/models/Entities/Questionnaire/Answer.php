<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 
 namespace models\Entities\Questionnaire;
 use models\Entities\Questionnaire\Question;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\AnswerRepository")
  * @Table(name="ecom_questionnaire_answers")
  */
 class Answer {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=240, nullable=false) */
    private $text;
    /** @Column(type="smallint", length=2, nullable=false) */
    private $position;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Questionnaire\Question", inversedBy="answers")
     * @JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $answer;
    
    public function getID() { return $this->id; }
    public function setText( $value ) { $this->text = $value; }
    public function getText() { return $this->text; }
    public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setAnswer( Question $value ) { $this->answer = $value; }
    public function getAnswer() { return $this->answer; }
 }
 
 /* End of file Answer.php */
 /* Location: ./system/applications/_backend/models/Entities/Questionnaire/Answer.php */