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

class ReviewRepository extends EntityRepository {

    private $relations = array(
        'id'         => 'r.id',
        'product_id' => 'p.name',
        'user_id'    => 'u.id',
        'date'       => 'r.date',
        'negative'   => 'r.negative',
        'positive'   => 'r.positive',
        'status'     => 'r.status',
        'rating'     => 'r.rating'
    );
    
    public function getReviewSpecifications( $criteria, $subcategory_id ) {
    	
    	$this->relations = array(
	        'id'		=> 's.id',
	        'name'		=> 's.name',
	        'position'	=> 's.position',
	    );
    	
    	$data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s') 
           ->from('models\Entities\Product\Review\ReviewSpecification', 's')
           ->where( $qb->expr()->eq('s.subcategory', $subcategory_id) )
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->setFirstResult($criteria->offset)
           ->setMaxResults($criteria->limit);
        
    	if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
        $data['records'] = new Paginator($qb->getQuery(), FALSE);
        
        if( $data['record_count'] = $data['records']->count() ) {
			
            foreach ($data['records'] as $specification) {
            	
                $data['record_items'][] = array(
                    $specification->getID(),
                    $specification->getPosition(),
                    $specification->getName(),
                    '<a href="'.site_url('reviews/edit_specification/'.$specification->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                );
            }
        }
        return $data;
    }
    
    public function getReviewSpecificationsByPosition( $subcategory_id ) {
    	
    	$qb = $this->_em->createQueryBuilder();
    	
    	$qb->select('s') 
           ->from('models\Entities\Product\Review\ReviewSpecification', 's')
           ->where( $qb->expr()->eq('s.subcategory', $subcategory_id) )
           ->orderBy('s.position', 'ASC');
           
        return $qb->getQuery()->getResult();
    }
    
    public function getReviewSpecificationByID( $id ) {
    	
    	$qb = $this->_em->createQueryBuilder();
    	
    	$qb->select('s, sub') 
           ->from('models\Entities\Product\Review\ReviewSpecification', 's')
           ->leftJoin('s.subcategory', 'sub')
           ->where( $qb->expr()->eq('s.id', $id) );
        
    	try {
			return $qb->getQuery()->getSingleResult();
		}
		catch( \Doctrine\ORM\NoResultException $e ) {
			return NULL;
		}
    }

    public function getReview( $criteria, $history = FALSE ) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('r, p, u') 
           ->from('models\Entities\Review', 'r')
           ->leftJoin('r.product_id', 'p') 
           ->leftJoin('r.user_id', 'u')
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->setFirstResult($criteria->offset)
           ->setMaxResults($criteria->limit);
        
        if( $history ) $qb->where( $qb->expr()->in('r.status', array(1, 2)) );
        else $qb->where( $qb->expr()->eq('r.status', 0) );
        
    	if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
		
        $data['records'] = new Paginator($qb->getQuery(), FALSE);
			
		if( $data['record_count'] = $data['records']->count() ) {
			
            foreach ($data['records'] as $review) {
            	
				switch( get_class($review->getUserId()) ) {
				
					case 'models\Entities\User\Customer\Personal':	
						$user = '<a href="'.site_url('users/personal_user/details/'.$review->getUserId()->getID()).'" target="_blank">'.$review->getUserId()->getFirstName().' '.$review->getUserId()->getLastName().'</a>';
						break;
						
					case 'models\Entities\User\Customer\Business':
						$user = '<a href="'.site_url('users/business_user/details/'.$review->getUserId()->getID()).'" target="_blank">'.$review->getUserId()->getCompanyName().'</a>';
						break;
				}

                if( $history ) {
	                $data['record_items'][] = array(
	                    $review->getID(),
	                    $review->getFormatedDate(),
	                    $review->getType(),
	                    $user,
	                    '<a href="'.$review->getProductId()->getURL().'" target="_blank">'.$review->getProductId()->getName().'</a>',
	                    $review->getPositive(),
	                    $review->getNegative(),
	                    $review->getOverall(),
	                    '<a class="table-icon details" href="' . site_url('reviews/details/' . $review->getID()) . '">Detalji</a>',
	                    '<img border="0" src="'.($review->getStatus() == 1 ? layout_url('flexigrid/check.png') : layout_url('flexigrid/delete.png')).'">',
	                );
                }
                else {   	
	                $data['record_items'][] = array(
	                    $review->getID(),
	                    $review->getFormatedDate(),
	                    $review->getType(),
	                    $user,
	                    '<a href="'.$review->getProductId()->getURL().'" target="_blank">'.$review->getProductId()->getName().'</a>',
	                    '<a class="table-icon details" href="' . site_url('reviews/details/' . $review->getID()) . '">Detalji</a>', 
	                );
                }
            }
        }
        return $data;
    }

    public function deleteReview( $id_list ) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('r') 
           ->from('models\Entities\Review', 'r') 
           ->where($qb->expr()->in('r.id', $id_list));

        $query = $qb->getQuery();
        $reviews = $query->getResult();

        foreach ($reviews as $review) {
                $this->_em->remove($review);
            }
         $this->_em->flush();
    }
    
    public function deleteReviewSpecification( $id_list ) {
    	
    	$qb = $this->_em->createQueryBuilder();

        $qb->select('r') 
           ->from('models\Entities\Product\Review\ReviewSpecification', 'r') 
           ->where($qb->expr()->in('r.id', $id_list));

        $query = $qb->getQuery();
        $reviews = $query->getResult();

        foreach ($reviews as $review) {
        	$this->_em->remove($review);
        }
        $this->_em->flush();	
    }
}

/* End of file ReviewRepository.php */
/* Location: ./system/applications/_backend/models/ReviewRepository.php */
