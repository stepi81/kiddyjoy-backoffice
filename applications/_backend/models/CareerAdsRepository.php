<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class CareerAdsRepository extends EntityRepository {
	  

     private $ad_relations = array(
     	'id'           		=> 'a.id',
        'career_job_id'     => 'a.career_job_id',
        'text'              => 'a.text',
        'active'            => 'a.active'
     );
         
	
	public function getAds( $criteria ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('a')
			->from('models\Entities\CareerAd', 'a')
			->orderBy($this->ad_relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->ad_relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {
			
			foreach( $data['records'] as $ad) {
				
				$status = $ad->getStatus() ? 'check' : 'delete';
				
				$data['record_items'][] = array(
					$ad->getID(),
					$this->_em->getRepository('models\Entities\CareerJob')->find($ad->getCareerJobId())->getName(),
					'<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('careers/change_status/' . $ad->getID()) . '\');">Status</a>', 
					'<a href="'.site_url('careers/ad_details/'.$ad->getID()).'"><img border="0" src="'.layout_url('/flexigrid/details.png').'"></a>',
				);
			}
		}
		
		return $data;
	}

	
	public function deleteAds( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('a')
			->from('models\Entities\CareerAd', 'a')
			->where($qb->expr()->in('a.id', $id_list));
		
		$query = $qb->getQuery();
		$ads = $query->getResult();
		
		foreach( $ads as $ad ) {
        	$this->_em->remove($ad);
		}
		
		$this->_em->flush();
	}


 }
 
 /* End of file CareerAdsRepository.php */
 /* Location: ./system/applications/_backend/models/CareerAdsRepository.php */