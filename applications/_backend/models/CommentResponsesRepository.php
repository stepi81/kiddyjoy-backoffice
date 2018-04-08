<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

namespace models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CommentResponsesRepository extends EntityRepository {

    public function getCommentResponses($criteria, $comment_id) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('c') 
           ->from('models\Entities\CommentResponses', 'c') 
           ->orderBy('c.' . $criteria->sortname, $criteria->sortorder)
           ->where ('c.comment_id = :comment_id') 
           ->setParameters (array('comment_id' => $comment_id))
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $commentResponses) {

                $data['record_items'][] = array(
                    $commentResponses->getID(),
                    $commentResponses->getDate(), 
                    $commentResponses->getMessage(),
                    '<a class="table-icon details" href="' . site_url('comments/response_details/' . $commentResponses->getID()) . '">Detalji</a>'
                );
            }
        }

        return $data;
    }

    public function deleteResponses($id_list){
	    
        $qb = $this->_em->createQueryBuilder();

        $qb->select('r') 
           ->from('models\Entities\CommentResponses', 'r') 
           ->where($qb->expr()->in('r.id', $id_list));

        $query = $qb->getQuery();
        $responses = $query->getResult();

        foreach ($responses as $response) {
              $this->_em->remove($response);
        }
        $this->_em->flush();
    }

}

/* End of file CommentResponsesRepository.php */
/* Location: ./system/applications/_backend/models/CommentResponsesRepository.php */
