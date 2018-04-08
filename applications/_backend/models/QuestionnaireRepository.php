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
 
 class QuestionnaireRepository extends EntityRepository {
    
    public function getQuestionnaires( $criteria ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('q')
            ->from('models\Entities\Questionnaire', 'q')
            ->orderBy('q.'.$criteria->sortname, $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $questionnaire ) {
                
                $status = $questionnaire->getStatus() ? 'check' : 'delete';
                
                $data['record_items'][] = array(
                    $questionnaire->getID(),
                    $questionnaire->getFormatedDate(),
                    $questionnaire->getTitle(),
                    '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('inquiry/questionnaires/change_status/'.$questionnaire->getID()).'\');">Status</a>',
                    '<a class="table-icon results" href="'.site_url('inquiry/questionnaires/results/'.$questionnaire->getID()).'">Rezultati</a>',
                    '<a class="table-icon details" href="'.site_url('inquiry/questions/listing/'.$questionnaire->getID()).'">Detalji</a>',
                );
            }
        }
        
        return $data;
    }
    
    public function deleteQuestionnaires( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('q')
            ->from('models\Entities\Questionnaire', 'q')
            ->where($qb->expr()->in('q.id', $id_list));
        
        $query = $qb->getQuery();
        $questionnaires = $query->getResult();
        
        foreach( $questionnaires as $info ) {
            if ($info->getImage()==NULL) $this->_em->remove($info);
            if (unlink(SERVER_IMAGE_PATH.'questionnaires/'.$info->getImage()) ) $this->_em->remove($info);
        }
        
        $this->_em->flush();
    }
 }
 
 /* End of file QuestionnaireRepository.php */
 /* Location: ./system/applications/_backend/models/QuestionnaireRepository.php */
