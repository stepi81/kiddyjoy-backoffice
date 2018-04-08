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
 
 class BrandRepository extends EntityRepository {
	
    private $relations = array(
        'id' => 'b.id',
        'name' => 'b.name', 
        'position' => 'b.position'
    );  
    
	public function getBrands( $criteria ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('b')
			->from('models\Entities\Product\Brand', 'b')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

			foreach( $data['records'] as $brand ) {
				
				$status = $brand->getStatus() ? 'check' : 'delete';
				$featured = $brand->getFeatured() ? 'check' : 'delete';
				
				$data['record_items'][] = array(
					$brand->getID(),
					$brand->getID(),
					$brand->getPosition(),
					'<img border="0" src="'.$brand->getImageURL().'" alt="'.$brand->getName().'">',
					$brand->getName(),
					'<a class="table-icon ' . $featured . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('brands/change_featured/' . $brand->getID()) . '\');">Status</a>',
					'<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('brands/change_status/' . $brand->getID()) . '\');">Status</a>', 
	                '<a href="'.site_url('brands/details/'.$brand->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
				);
			}
		}
		
		return $data;
	}

	public function getBrandByID( $id ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('b')
			->from('models\Entities\Product\Brand', 'b')
			->where('b.id = :id')
			->setParameter('id', $id);
		
		return $qb->getQuery()->getSingleResult();
	}
	
	public function deleteBrands( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('b')
			->from('models\Entities\Product\Brand', 'b')
			->where($qb->expr()->in('b.id', $id_list));
		
		$query = $qb->getQuery();
		$brands = $query->getResult();
		
		foreach( $brands as $brand ) {
			if( unlink(SERVER_PATH . 'assets/img/brands/' . $brand->getImage()) ) $this->_em->remove($brand);
		}
		
		$this->_em->flush();
	}

	public function getVendors( $criteria ) { //TODO Make a new repo.
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('b')
			->from('models\Entities\Vendor', 'b')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

			foreach( $data['records'] as $vendor ) {
				
				$data['record_items'][] = array(
					$vendor->getID(),
					$vendor->getID(),
				);
			}
		}
		
		return $data;
	}
	
	public function getAllBrands()
	{
	
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('b')
			->from('models\Entities\Product\Brand', 'b');
		
		return $qb->getQuery()->getResult();	
	
	}
	
	public function getSubcategoryBrands( $subcategory ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select(' b.id, b.name')
            ->from('models\Entities\Product', 'p')
			->leftJoin('p.master', 'pm')
			->leftJoin('pm.brand', 'b')
            ->where('p.status = :status')
            ->andWhere('pm.archive = :archive')
            ->setParameters(array('status' => 1, 'archive' => 0))
            ->orderBy('b.name', 'ASC');

			$subcat_array = array();
			$subcat_array[] = $qb->expr()->literal($subcategory->getID());
			foreach ($subcategory->getChildren() as $child) 
				$subcat_array[] = $qb->expr()->literal($child->getID());	
			$qb->andWhere($qb->expr()->in('p.subcategory', $subcat_array ));
			
			$qb->groupBy('b.id');
        
        return $qb->getQuery()->getResult();
    }
	
	public function getMaxRecord(){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(b.position)')->from('models\Entities\Product\Brand', 'b');

		$this->CI =& get_instance();
	
        $query = $qb->getQuery();
        $records = $query->getResult();
        return $records;
    }

 }
 
 /* End of file BrandRepository.php */
 /* Location: ./system/applications/_backend/models/BrandRepository.php */