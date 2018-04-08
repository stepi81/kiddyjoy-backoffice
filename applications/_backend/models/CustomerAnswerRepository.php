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
 
 class CustomerAnswerRepository extends EntityRepository {
	
    private $relations = array(
        'date'		=> 'a.date',
        'user'		=> 'u.last_name',
        'product'	=> 'p.name',
        'status'	=> 'a.status'	 
    );  
    
	public function getAnswers( $criteria, $question_id ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('a, u, q, p')
			->from('models\Entities\CustomerAnswer', 'a')
			->leftJoin('a.user', 'u')
			->leftJoin('a.question', 'q')
			->leftJoin('q.product', 'p')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
		if( $question_id ) {
			$qb->where('q.id = :question_id')
				->setParameter('question_id', $question_id);	
		} else {
			$qb->where('a.status = :status')
				->setParameters(array( 'status' => 0 ));	
		}
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

			foreach( $data['records'] as $answer ) {
				
				if( $answer->getStatus() ) {
					$status = '<img src="'. asset_url("img/layout/_backend/flexigrid/check.png") .'" />';
				} else {
					$status = '<a class="table-icon delete" href="javascript:void(0);" onclick="changeItemStatusAlert(this, \'' . site_url('customer_questions/change_answer_status/' . $answer->getID()) . '\');">Status</a>';	
				}
				
				switch( get_class($answer->getUser()) ) {
                
                    case 'models\Entities\User\Customer\Personal':
                        if ($answer->getUser()->getNickname()){
                        	 $user_name = $answer->getUser()->getNickname();
						} else {
							$user_name = $answer->getUser()->getLastName().' '.$answer->getUser()->getFirstName();	
						}
                        break;
                        
                    case 'models\Entities\User\Customer\Business': 
                        $user_name = $answer->getUser()->getContactPerson();
                        break;
				} 
				
				$data['record_items'][] = array(
					$answer->getID(),
					$answer->getDate(),
					$user_name,
					$answer->getQuestion()->getProduct()->getName(),
					$answer->getQuestion()->getQuestion(),
	                $answer->getAnswer(),
	                $status,
	                '<a href="'.site_url('customer_questions/answer_details/'.$answer->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
				);
			}
		}
		
		return $data;
	}

	public function deleteAnswers( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('a')
			->from('models\Entities\CustomerAnswer', 'a')
			->where($qb->expr()->in('a.id', $id_list));
		
		$query = $qb->getQuery();
		return $answers = $query->getResult();
	}

 }
 
 /* End of file CustomerAnswerRepository.php */
 /* Location: ./system/applications/_backend/models/CustomerAnswerRepository.php */