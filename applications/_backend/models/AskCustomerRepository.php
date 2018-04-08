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
 
 class AskCustomerRepository extends EntityRepository {
	
    private $relations = array(
        'date'		=> 'q.date',
        'user'		=> 'u.last_name',
        'product'	=> 'p.name',
        'status'	=> 'q.status'	 
    );  
    
	public function getQuestions( $criteria ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('q, u, p')
			->from('models\Entities\AskCustomer', 'q')
			->leftJoin('q.user', 'u')
			->leftJoin('q.product', 'p')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

			foreach( $data['records'] as $question ) {
				
				
				if( $question->getStatus() ) {
					$status = '<img src="'. asset_url("img/layout/_backend/flexigrid/check.png") .'" />';
				} else {
					$status = '<a class="table-icon delete" href="javascript:void(0);" onclick="changeItemStatusAlert(this, \'' . site_url('customer_questions/change_status/' . $question->getID()) . '\');">Status</a>';	
				}
				
				switch( get_class($question->getUser()) ) {
                
                    case 'models\Entities\User\Customer\Personal':
                        if ($question->getUser()->getNickname()){
                        	 $user_name = $question->getUser()->getNickname();
						} else {
							$user_name = $question->getUser()->getLastName().' '.$question->getUser()->getFirstName();	
						}
                        break;
                        
                    case 'models\Entities\User\Customer\Business': 
                        $user_name = $question->getUser()->getContactPerson();
                        break;
				} 
				
				$data['record_items'][] = array(
					$question->getID(),
					$question->getDate(),
					$user_name,
					$question->getProduct()->getName(),
	                $question->getQuestion(),
	                $status,
	                '<a href="'.site_url('customer_questions/answers_listing/'.$question->getID()).'"><img border="0" src="'.layout_url('flexigrid/comments.png').'"></a>',
	                '<a href="'.site_url('customer_questions/question_details/'.$question->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
				);
			}
		}
		
		return $data;
	}

	public function deleteQuestions( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('q')
			->from('models\Entities\AskCustomer', 'q')
			->where($qb->expr()->in('q.id', $id_list));
		
		$query = $qb->getQuery();
		return $questions = $query->getResult();
	}

 }
 
 /* End of file AskCustomerRepository.php */
 /* Location: ./system/applications/_backend/models/AskCustomerRepository.php */