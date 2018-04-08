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
 
 class SubcategoryPriceRangeRepository extends EntityRepository {
    
    public function getPriceRanges($criteria, $subcategory_id) {
        
     $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('pr')
        	->from('models\Entities\Product\SubcategoryPriceRange', 'pr')
			->orderBy('pr.' . $criteria->sortname, $criteria->sortorder)
			->where('pr.subcategory = '.$subcategory_id) 
			->andWhere('pr.max_price != 0')
			->setFirstResult($criteria->offset)
			->setMaxResults($criteria->limit);


		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $key => $record) {
            	
				if( $key == $data['records']->count() ) {
					$price = $record->getMinPrice();
				} else {
					$price = $record->getMaxPrice();
				}
				
                $data['record_items'][] = array(
                    $price,
                    $price,
					);
            }
        }

        return $data;
    }

	public function getRangesBySubcategory( $subcategory_id ) {
		
		$qb = $this->_em->createQueryBuilder();

        $qb->select('pr')
        	->from('models\Entities\Product\SubcategoryPriceRange', 'pr')
			->orderBy('pr.max_price')
			->where('pr.subcategory = '.$subcategory_id);
			
		$query = $qb->getQuery();
		return $records = $query->getResult(); 	
	}
	
	public function deleteRangesBySubcategory( $subcategory_id ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('pr')
        	->from('models\Entities\Product\SubcategoryPriceRange', 'pr')
			->orderBy('pr.max_price')
			->where('pr.subcategory = '.$subcategory_id);
		
		$query = $qb->getQuery();
		$records = $query->getResult();
		
		foreach( $records as $record ) {

        	$this->_em->remove($record); 
    	}
		
		$this->_em->flush();
	}
	
	public function deletePriceRanges( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('pr')
			->from('models\Entities\Product\SubcategoryPriceRange', 'pr')
			->where($qb->expr()->in('pr.id', $id_list));
		
		$query = $qb->getQuery();
		$records = $query->getResult();
		
		foreach( $records as $record ) {

        	$this->_em->remove($record); 
    	}
		
		$this->_em->flush();
	}
 }
 
 /* End of file SubcategoryPriceRangeRepository.php */
 /* Location: ./system/applications/_backend/models/SubcategoryPriceRangeRepository.php */