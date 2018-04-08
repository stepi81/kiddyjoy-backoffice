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
 
 class CareersRepository extends EntityRepository {
	  
      
    private $relations = array(
        'id'           		=> 'c.id',    
        'registration_date' => 'c.registration_date',
        'career_job_id'     => 'c.career_job_id',
        'name'              => 'c.name',
        'email'             => 'c.email',
        'phone'             => 'c.phone'  
        );
		
         
	public function getCareers( $criteria ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('c')
			->from('models\Entities\CareerRecord', 'c')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
			
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
			
			foreach( $data['records'] as $career) {
				
                if( $career->getCv() != "" ) {
                    $career_cv = '<a href="'.APP_URL.'assets/cv/'.$career->getCv().'" target="_blanc"><img border="0" src="'.layout_url('flexigrid/backoffice_document.png').'"></a>'; 
                } else {
                    $career_cv = "";
                }
                
                if( $career->getCareerJobID() ) {
                    $career_job = $this->_em->getRepository('models\Entities\CareerJob')->find($career->getCareerJobID())->getName();    
                } else {
                    $career_job = '';    
                }
				
				$data['record_items'][] = array(
					$career->getID(),
					$career->getFormatedDate(),
					$career_job, 
					$career->getName(),
					$career->getEmail(),
					$career->getPhone(),
	                $career_cv,
	                '<a class="table-icon details" href="'.site_url('careers/details/'.$career->getID()).'">Detalji</a>',
				);
			}
		}
		
		return $data;
	}

	
	public function deleteCareers( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('c')
			->from('models\Entities\CareerRecord', 'c')
			->where($qb->expr()->in('c.id', $id_list));
		
		$query = $qb->getQuery();
		$careers = $query->getResult();
		
		foreach( $careers as $career ) {
			unlink(SERVER_PATH.'assets/cv/'.$career->getCv());
        	$this->_em->remove($career);
		}
		
		$this->_em->flush();
	}
	

 }
 
 /* End of file CareersRepository.php */
 /* Location: ./system/applications/_backend/models/CareersRepository.php */