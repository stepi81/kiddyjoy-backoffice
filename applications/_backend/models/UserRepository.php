<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

namespace models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UserRepository extends EntityRepository {

    private $relations = array(
        'id'                    => 'u.id',
        'first_name'            => 'u.first_name',
        'last_name'             => 'u.last_name',
        'nick_name'             => 'u.nick_name',
        'email'                 => 'u.email',
        'registration_date'     => 'u.registration_date',
        'points'                => 'u.points',
        'phone'                 => 'u.phone',
        'contact_person'        => 'u.contact_person',
        'company_name'          => 'u.company_name',
        'master_id'             => 'u.master_id', 
        );
	
	public function getAdminByEmail( $email ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array(
			'partial a.{id, password}',
			'partial g.{id}',
			's','c'))
			->from('models\Entities\User\Admin', 'a')
			->leftJoin('a.group', 'g')
			->leftJoin('g.sections', 's')
			->leftJoin('s.children', 'c')
			->where('a.email = :email')
			->setParameter('email', $email);
		
		try {
			return $qb->getQuery()->getSingleResult();
		}
		catch( \Doctrine\ORM\NoResultException $e ) {
			return NULL;
		}
	}
	
	public function getAdminByID( $id ) {
		
	$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array(
			'partial a.{id, email, first_name, last_name, phone, registration_date}',
			'partial g.{id, name}',
			's','c'))
			->from('models\Entities\User\Admin', 'a')
			->leftJoin('a.group', 'g')
			->leftJoin('g.sections', 's')
			->leftJoin('s.children', 'c')
			->where('a.id = :id')
			->setParameter('id', $id);
		
		try {
			return $qb->getQuery()->getSingleResult();
		}
		catch( \Doctrine\ORM\NoResultException $e ) {
			return NULL;
		}
	}
	
    public function getAllSections($id_list = NULL) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s')->from('models\Entities\Section', 's')->where($qb->expr()->isNull('s.parent'));

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getAdminGroups($criteria) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('g')->from('models\Entities\User\Admin_Group', 'g')
            ->orderBy('g.' . $criteria->sortname, $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
            
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);

        if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $group) {

                $data['record_items'][] = array(
                    $group->getID(), 
                    $group->getName(), 
                    '<a href="' . site_url('users/admin_group/details/' . $group->getID()) . '"><img border="0" src="' . layout_url('flexigrid/details.png') . '"></a>'
                );
            }
        }

        return $data;
    }
    
    public function getAdminActivities( $criteria ) {
    	
    	$this->relations = array(
			'date'			=> 'a.date',
			'user_id'		=> 'u.first_name',
			'group_id'		=> 'g.name',
			'operation_id'	=> 'a.operation_id',
    		'process_id'	=> 'a.process_id'
         );
    	
    	$data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('a, u, g')
        	->from('models\Entities\User\Admin_Activity', 'a')
        	->leftJoin('a.admin', 'u')
        	->leftJoin('u.group', 'g')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
    	if( $criteria->search_keyword != '' ) {
    		if( $criteria->search_field == 'user_id' ) {
    			
				$elements = explode(" ", $criteria->search_keyword);
				if( count($elements) > 1 ) {
					$qb->andWhere($qb->expr()->like('u.first_name', ':first_name'))
	                	->setParameter( 'first_name', $elements[0] );  
					$qb->andWhere($qb->expr()->like('u.last_name', ':last_name'))
	                	->setParameter( 'last_name', $elements[1] );	
				} else {
					$qb->where($qb->expr()->like('u.first_name', ':first_name'))
	                	->setParameter( 'first_name', $elements[0] );  
					$qb->orWhere($qb->expr()->like('u.last_name', ':last_name'))
	                	->setParameter( 'last_name', $elements[0] );		
				} 
    		} else {
    			$qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );  	
    		} 
        }
		
        $data['records'] = new Paginator( $qb->getQuery() );
		
        if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $activity) {
				
                $data['record_items'][] = array(
                    $activity->getID(),
                    $activity->getDate(TRUE),
                    $activity->getAdminName(),
                    $activity->getAdminGroupName(),
                    $activity->getOperation(),
                    $activity->getProcess(),
                    '<a href="'.$activity->getRecordURL().'" target="_blank">'.$activity->getRecordName().'</a>'
                );
            }
        }

        return $data;
    }

    public function getUsersByType($criteria, $type) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        switch( $type ) {

            case USER_TYPE_ADMIN :
                $qb->select('u')->from('models\Entities\User\Admin', 'u')->leftJoin('u.group', 'g');
                break;

            case USER_TYPE_PERSONAL :
                $qb->select('u')->from('models\Entities\User\Customer\Personal', 'u');
                break;

            case USER_TYPE_BUSINESS :
                $qb->select('u')->from('models\Entities\User\Customer\Business', 'u');
                break;

            case USER_TYPE_NEWSLETTER :
                $qb->select('u')->from('models\Entities\User\NewsletterUser', 'u');
                break;
        }
        $qb->orderBy('u.' . $criteria->sortname, $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
        
        if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                   ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
            
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);

        if( $data['record_count'] = $data['records']->count() ) {
            
            switch( $type ) {

                case USER_TYPE_ADMIN :
                    foreach ($data['records'] as $user) {

                        $data['record_items'][] = array(
                            $user->getID(), 
                            $user->getGroup()->getName(), 
                            $user->getFirstName(), 
                            $user->getLastName(), 
                            $user->getEmail(), 
                            '<a href="' . site_url('users/admin_user/details/' . $user->getID()) . '"><img border="0" src="' . layout_url('flexigrid/details.png') . '"></a>'
                        );
                    }
                break;

                case USER_TYPE_PERSONAL :
                    foreach ($data['records'] as $user) {

                        $data['record_items'][] = array(
                            $user->getID(), 
                            $user->getFirstName(), 
                            $user->getLastName(),
                            $user->getNickname(), 
                            $user->getEmail(), 
                            $user->getPhone(), 
                            $user->getCityName(), 
                            $user->getFormatedRegistrationDate(), 
                            $user->getPoints(), 
                            '<a href="' . site_url('users/personal_user/details/' . $user->getID()) . '"><img border="0" src="' . layout_url('flexigrid/details.png') . '"></a>',
                            '<a href="' . site_url('users/personal_user/friends/' . $user->getID()) . '"><img border="0" src="' . layout_url('flexigrid/friends.png') . '"></a>'
                        );
                    }
                break;

                case USER_TYPE_BUSINESS :
                    foreach ($data['records'] as $user) {

                        $data['record_items'][] = array(
                            $user->getID(), 
                            $user->getMasterID(),
                            $user->getCompanyName(), 
                            $user->getContactPerson(), 
                            $user->getEmail(), 
                            $user->getPhone(), 
                            $user->getCityName(), 
                            '<a href="' . site_url('users/business_user/details/' . $user->getID()) . '"><img border="0" src="' . layout_url('flexigrid/details.png') . '"></a>'
                        );
                    }
                break;

                case USER_TYPE_NEWSLETTER :
                    foreach ($data['records'] as $user) {

                        $data['record_items'][] = array(
                            $user->getEmail(),
                            $user->getEmail()
                        );
                    }
                break;
            }
        }

        return $data;
    }

    public function getCitiesCheck($comment_id) {

        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p') 
           ->from('models\Entities\PostalCode', 'p') 
           ->where ('p.postal_code = :comment_id') 
           ->setParameters (array('comment_id' => $comment_id));
           
            $query = $qb->getQuery();
            $result = $query->getResult();
            if ($result){
            return $result;
            }
    }
    public function getCities() {
        
        // cache this query
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p.postal_code as postal_code, p.city as value')
            ->from('models\Entities\PostalCode', 'p')
            ->orderBy('p.city', 'ASC');
        
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }
    
    public function getShoppingHistory( $user_id, $criteria ) {
    	
    	$query = $this->_em->createQuery('SELECT u FROM models\Entities\User\Customer u WHERE u.id = :user_id');
    	$query->setParameter('user_id', $user_id);
    	$user = $query->getSingleResult();
    	
    	$data['record_items'] = array();
		
    	$qb = $this->_em->createQueryBuilder();
    	
    	$qb->select('o, t')
           ->from('models\Entities\OrderFinal', 'o')
           ->leftJoin('o.points_transaction', 't')
           ->where('o.user = :user_id')
           ->setParameter( 'user_id', $user->getID() );
        
        $web_orders = $qb->getQuery()->getResult();
        
        $qb = $this->_em->createQueryBuilder();
    	
    	$qb->select('o')
           ->from('models\Entities\OrderShopFinal', 'o')
           ->where('o.user = :email')
           ->setParameter( 'email', $user->getEmail() );
        
        $store_orders = $qb->getQuery()->getResult();
        
        $result = array_merge($web_orders, $store_orders);
        
        if( $data['record_count'] = count($result) ) {
        	usort($result, function($a, $b) {                                        
                if ($a->getDate() > $b->getDate()) return 1;
                if ($a->getDate() < $b->getDate()) return -1;                    
                return 0;                                        
            });
            
        	foreach (array_slice($result, $criteria->offset, $criteria->limit) as $order) {
				
        		switch( get_class($order) ) {
        			case 'models\Entities\OrderFinal':
        				$type = 'Web';
        				$temp = 1;
        				break;
        			case 'models\Entities\OrderShopFinal':
        				$type = 'Radnja';
        				$temp = 2;
        				break;
        		}
        		
                $data['record_items'][] = array(
                    $order->getID(),
                    $order->getFormatedDate(),
                    $type,
                    $order->getTotalPrice(),
                    $order->getDiscountValue(),
                    $order->getUsedPoints(),
                    $order->getStatusInfo(),
                    $order->getPoints(),
                    $order->getPointsActivationDate(),
                    '<a class="table-duoicon check" href="javascript:void(0);" onclick="points_management('.$order->getID().','.$temp.',1);"></a>
                    <a class="table-duoicon delete" href="javascript:void(0);" onclick="points_management('.$order->getID().','.$temp.',0);"></a>'
                );
            }
        }

        return $data;
    }

    public function getUserFriends($criteria, $id) {

        $data['record_items'] = array();
		$data['records'] = array();

        $qb = $this->_em->createQueryBuilder();
		
		$friends = $this->_em->getRepository('models\Entities\User\Customer\Personal')->find($id)->getFriends();
		$friend_ids = array();
		foreach ($friends as $friend) $friend_ids[] = $friend->getID();
		
        $qb->select('u')->from('models\Entities\User\Customer\Personal', 'u');

        $qb->orderBy('u.' . $criteria->sortname, $criteria->sortorder)
		    ->where($qb->expr()->in('u.id', $friend_ids))
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
            
        if (!empty($friend_ids)) $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);

        if( $data['record_count'] = count($data['records']) ) {

                    foreach ($data['records'] as $user) {

                        $data['record_items'][] = array(
                            $user->getID(), 
                            $user->getFirstName(), 
                            $user->getLastName(),
                            $user->getNickname(), 
                            $user->getEmail(), 
                            $user->getPhone(), 
                            $user->getCityName(), 
                            $user->getFormatedRegistrationDate(), 
                            '<a href="' . site_url('users/personal_user/details/' . $user->getID()) . '"><img border="0" src="' . layout_url('flexigrid/details.png') . '"></a>',
                            '<a href="' . site_url('users/personal_user/friends/' . $user->getID()) . '"><img border="0" src="' . layout_url('flexigrid/friends.png') . '"></a>'
                        );
                    }
              
        }

        return $data;
    }

	public function findUsersWithItem( $item_id ) {
		
		$qb = $this->_em->createQueryBuilder();
        
        $qb->select(array(
            'partial pu.{id, first_name, last_name, email}'
            ))
            ->from('models\Entities\User\Customer\Personal', 'pu')
            ->leftJoin('pu.orders_final', 'of')
			->leftJoin('pu.orders_shop_final', 'osf')
			->leftJoin('of.items', 'ofi')
			->leftJoin('osf.items', 'osfi')
			->leftJoin('ofi.product', 'ofp')
			->leftJoin('osfi.product', 'osfp')

			->where($qb->expr()->eq('ofp.id', $item_id ))
			->orWhere($qb->expr()->eq('osfp.id', $item_id ))
			->andWhere($qb->expr()->eq('pu.profile_asc_customer', 1 ));
				
		//echo $qb->getQuery()->getSQL();
        
        $query = $qb->getQuery();
        return $query->getResult();	
	}
	
	public function getNewsletterUsers( $offset = 0 ) {
		
		$qb = $this->_em->createQueryBuilder();
        
		$qb->select(array(
		    'partial u.{id, email}'
		    ))
		    ->from('models\Entities\User\Customer', 'u')
			->andWhere($qb->expr()->eq('u.status',1))
			->orderBy('u.id', 'ASC');
		$query = $qb->getQuery();
		return $query->getArrayResult();
	}

	public function getUsersForNewsletter( $type ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		switch ($type) {
            case '1':
                $qb->select(array(
				    'partial u.{id, email, request_token}'
				    ))
				    ->from('models\Entities\User\Customer\Personal', 'u')
					->andWhere($qb->expr()->eq('u.newsletter',1))
					->orderBy('u.id', 'ASC');
				$query = $qb->getQuery();
				return $query->getArrayResult();
                break;
            case '2':
                $qb->select(array(
				    'partial u.{id, email, request_token}'
				    ))
				    ->from('models\Entities\User\Customer\Business', 'u')
					->andWhere($qb->expr()->eq('u.newsletter',1))
					->orderBy('u.id', 'ASC');
				$query = $qb->getQuery();
				return $query->getArrayResult();
                break;
            case '3':
                $qb->select(array(
				    'partial u.{email, request_token}'
				    ))
				    ->from('models\Entities\User\NewsletterUser', 'u')
					->andWhere($qb->expr()->eq('u.status',1));
				$query = $qb->getQuery();
				return $query->getArrayResult();
                break;
        }	
	} 
}

/* End of file UserRepository.php */
/* Location: ./system/applications/_backend/models/UserRepository.php */