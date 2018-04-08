<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class QuestionRepository extends EntityRepository {
    
        private $relations = array(
        'id'               => 'q.id',
        'position'         => 'q.position',
        ); 
    
    public function getQuestions( $criteria, $id ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('q')
            ->from('models\Entities\Questionnaire\Question', 'q')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->where ('q.questionnaire = :id') 
            ->setParameters (array('id' => $id))
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
        
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        	
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
		
		if( $data['record_count'] = $data['records']->count() ) {
		    
            foreach( $data['records'] as $question ) {
                
                $data['record_items'][] = array(
                   $question->getID(),
                   $question->getPosition(),
                   $question->getText(),
                   '<a class="table-icon details" href="'.site_url('inquiry/answers/listing/'.$question->getID()).'">Detalji</a>',
                );
            }
            
        }
        
        return $data;
    }
    
    public function deleteQuestions( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('q')
            ->from('models\Entities\Questionnaire\Question', 'q')
            ->where($qb->expr()->in('q.id', $id_list));
        
        $query = $qb->getQuery();
        $questionnaires = $query->getResult();
        
        foreach( $questionnaires as $info ) {
            $this->_em->remove($info);
        }
        
        $this->_em->flush();
    }
    
        
    public function getQuestionByQuestionnaire( $questionnaire_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('q')
           ->from('models\Entities\Questionnaire\Question', 'q')
           ->where ('q.questionnaire = :id') 
           ->setParameters (array('id' => $questionnaire_id));
        
        $query = $qb->getQuery();
        $question = $query->getResult();
        return $question;
    }
    
    public function getMaxQuestionPosition( $questionnaire_id ){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(q.position)')
           ->from('models\Entities\Questionnaire\Question', 'q')
           ->where ('q.questionnaire = :id') 
           ->setParameters (array('id' => $questionnaire_id));

        $query = $qb->getQuery();
        $ads = $query->getResult();
        return $ads;
    }

 }
 
 /* End of file QuestionRepository.php */
 /* Location: ./system/applications/_backend/models/QuestionRepository.php */
