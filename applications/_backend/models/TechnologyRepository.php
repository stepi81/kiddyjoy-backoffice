<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class TechnologyRepository extends EntityRepository {
	  
      
    private $relations = array(
        'date'         => 'n.date',
        'title'        => 'n.title',
        'status'       => 'n.status',   
        );
         
	public function getTechnologies( $criteria ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('n')
			->from('models\Entities\Technology', 'n')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
			
			foreach( $data['records'] as $technology ) {
				$data['record_items'][] = array(
					$technology->getID(),
					'<img border="0" src="'.$technology->getImageURL().'">',
					$technology->getTitle(),
	                '<a class="table-icon details" href="'.site_url('settings/technologies/details/'.$technology->getID()).'">Detalji</a>',
				);
			}
		}
		
		return $data;
	}
	
	public function deleteTechnologies( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('t')
			->from('models\Entities\Technology', 't')
			->where($qb->expr()->in('t.id', $id_list));
		
		$query = $qb->getQuery();
		$technologies = $query->getResult();
		
		foreach( $technologies as $technology ) {
            if( $technology->getImage() ) {
                if( unlink(SERVER_IMAGE_PATH.'technologies/'.$technology->getImage()) ) {
                	$this->_em->remove($technology);
				}
            } else {
                $this->_em->remove($technology); 
            }
		}
		$this->_em->flush();
	}
 }
 
 /* End of file TechnologyRepository.php */
 /* Location: ./system/applications/_backend/models/TechnologyRepository.php */