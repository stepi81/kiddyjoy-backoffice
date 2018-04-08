<?php

/**
 * ...
 * @author Ivna Despic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 //use DoctrineExtensions\Paginate\Paginate;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class AnswerRepository extends EntityRepository {
    
        private $relations = array(
        'id'               => 'q.id',
        'position'         => 'q.position',
        ); 
    
    public function getAnswers( $criteria, $id ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('q')
            ->from('models\Entities\Questionnaire\Answer', 'q')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->where ('q.answer = :id') 
            ->setParameters (array('id' => $id))
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
			
        
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
		
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        //$query = $qb->getQuery();
        
        //if( $data['record_count'] = Paginate::getTotalQueryResults($query) ) {
        if( $data['record_count'] = $data['records']->count() ) {
        
            //$paginateQuery = Paginate::getPaginateQuery($query, $criteria->offset, $criteria->limit);
            //$data['records'] = $paginateQuery->getResult();
            
            foreach( $data['records'] as $answer ) {
                
                $data['record_items'][] = array(
                               $answer->getID(),
                               $answer->getPosition(),
                               $answer->getText(),
                               '<a class="table-icon details" href="'.site_url('inquiry/answers/details/'.$answer->getID()).'">Detalji</a>',
                );
            }
        }
        return $data;
    }
    
    public function deleteAnswer( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('a')
            ->from('models\Entities\Questionnaire\Answer', 'a')
            ->where($qb->expr()->in('a.id', $id_list));
        
        $query = $qb->getQuery();
        $answers = $query->getResult();
        
        foreach( $answers as $info ) {
            $this->_em->remove($info);
        }
        
        $this->_em->flush();
    }
    
        
    public function getAnswersByQuestion( $question_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('a')
           ->from('models\Entities\Questionnaire\Answer', 'a')
           ->where ('a.answer = :id') 
           ->setParameters (array('id' => $question_id));
        
        $query = $qb->getQuery();
        $answer = $query->getResult();
        return $answer;
    }
    
    public function getMaxAnswerPosition( $question_id ){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(a.position)')
           ->from('models\Entities\Questionnaire\Answer', 'a')
           ->where ('a.answer = :id') 
           ->setParameters (array('id' => $question_id));

        $query = $qb->getQuery();
        $answer = $query->getResult();
        return $answer;
    }

 }
 
 /* End of file AnswerRepository.php */
 /* Location: ./system/applications/_backend/models/AnswerRepository.php */
