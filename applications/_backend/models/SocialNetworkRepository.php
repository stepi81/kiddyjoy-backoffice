<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 //use DoctrineExtensions\Paginate\Paginate;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class SocialNetworkRepository extends EntityRepository {
    
    private $relations = array(
        'id'           => 's.id',
        'name'         => 's.name',
        'status'	   => 'status'
    ); 
    
    public function getSocialNetworks($criteria) {
        
     $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();
        $qb->select('s')
           ->from('models\Entities\SocialNetwork', 's')
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
               ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $record) {
            	
				$status = $record->getStatus() ? 'check' : 'delete';
                
                $data['record_items'][] = array($record->getID(), 
                '<img border="0" src="' . $record->getImageURL() . '">',
                $record->getName(),  
                '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('settings/social_networks/change_status/'.$record->getID()).'\');">Status</a>',
                '<a class="table-icon details" href="' . site_url('settings/social_networks/details/' . $record->getID()) . '">Detalji</a>', );
            }
        }
        
        return $data;
    }
    
    public function deleteSocialNetworks($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s') 
           ->from('models\Entities\SocialNetwork', 's') 
           ->where($qb->expr()->in('s.id', $id_list));

        $query = $qb->getQuery();
        $records = $query->getResult();

        foreach ($records as $record) {
        	if( file_exists(SERVER_IMAGE_PATH . 'icons/social/' . $record->getImage()) ) {
        		unlink(SERVER_IMAGE_PATH . 'icons/social/' . $record->getImage());	
        	}
            $this->_em->remove($record);
        }

        $this->_em->flush();
    }

 }
 
 /* End of file SocialNetworkRepository.php */
 /* Location: ./system/applications/_backend/models/SocialNetworkRepository.php */