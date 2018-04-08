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
 
 class CareerJobsRepository extends EntityRepository {
	  

	private $job_relations = array(
		'id'           		=> 'j.id',
        'name'              => 'j.name'
     );


	public function getJobs( $criteria ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('j')
			->from('models\Entities\CareerJob', 'j')
			->orderBy($this->job_relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->job_relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {
			
			foreach( $data['records'] as $job) {
				
				$data['record_items'][] = array(
					$job->getID(),
					$job->getName(),
					'<a href="'.site_url('careers/job_details/'.$job->getID()).'"><img border="0" src="'.layout_url('/flexigrid/details.png').'"></a>',
				);
			}
		}
		
		return $data;
	}
	
	
	public function deleteJobs( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('j')
			->from('models\Entities\CareerJob', 'j')
			->where($qb->expr()->in('j.id', $id_list));
		
		$query = $qb->getQuery();
		$jobs = $query->getResult();
		
		foreach( $jobs as $job ) {


			$qb2 = $this->_em->createQueryBuilder();
		
			$qb2->select('c')
				->from('models\Entities\CareerRecord', 'c')
				->where( $qb2->expr()->in('c.career_job_id', $job->getID()) );
			
			$query2 = $qb2->getQuery();
			$careers = $query2->getResult();
			
			foreach( $careers as $career ) {
				unlink(SERVER_PATH.'assets/cv/'.$career->getCv());
	        	$this->_em->remove($career);
			}
			
			

			$qb1 = $this->_em->createQueryBuilder();
		
			$qb1->select('a')
				->from('models\Entities\CareerAd', 'a')
				->where( $qb1->expr()->in('a.career_job_id', $job->getID()) );
			
			$query1 = $qb1->getQuery();
			$ads = $query1->getResult();
			
			foreach( $ads as $ad ) {
	        	$this->_em->remove($ad);
			}			

            $this->_em->remove($job);
		}
		$this->_em->flush();
	}
	
 }
 
 /* End of file CareerJobsRepository.php */
 /* Location: ./system/applications/_backend/models/CareerJobsRepository.php */