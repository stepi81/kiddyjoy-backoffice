<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class ResultRepository extends EntityRepository {
     
    public function getResultByQuestionnaire( $questionnaire_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('r')
           ->from('models\Entities\Questionnaire\Result', 'r')
           ->where ('r.questionnaire = :id') 
           ->setParameters (array('id' => $questionnaire_id));
        
        $query = $qb->getQuery();
        $question = $query->getResult();
        return $question;
    }
 }
 
 /* End of file ResultRepository.php */
 /* Location: ./system/applications/_backend/models/ResultRepository.php */