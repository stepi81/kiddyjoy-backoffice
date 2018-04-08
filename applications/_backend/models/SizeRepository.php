<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

namespace models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SizeRepository extends EntityRepository {

    private $relations = array(
        'id'           => 's.id',
        'name'         => 's.name',
        'status'       => 's.status',   
        'position'     => 's.position',
    );
	
	private $product_size_relations = array(
        'id'           => 'pc.id',
        'name'		   => 's.name',	
        'status'       => 'pc.status',   
        'position'     => 'pc.position',
    );  

    public function getSizes($criteria, $subcategory) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s') 
           	->from('models\Entities\Product\Size', 's') 
			->leftJoin('s.subcategory', 'sub')
        	->where('sub.id=' . $subcategory)
          	->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $size) {

                $status = $size->getStatus() ? 'check' : 'delete';

                $data['record_items'][] = array(
                    $size->getID(), 
                    $size->getPosition(), 
                    $size->getName(), 
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('settings/sizes/change_status/' . $size->getID()) . '\');">Status</a>', 
                    '<a class="table-icon details" href="' . site_url('settings/sizes/details/' . $size->getID()) . '">Detalji</a>', );
            }
        }

        return $data;
    }

    public function deleteSizes($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s') 
           ->from('models\Entities\Product\Size', 's') 
           ->where($qb->expr()->in('s.id', $id_list));

        $query = $qb->getQuery();
        $sizes = $query->getResult();

		foreach ($sizes as $size) {
        	$this->_em->remove($size);
		}

        $this->_em->flush();
    }
	
	public function getMaxPosition( $subcategory ){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(s.position)')->from('models\Entities\Product\Size', 's')
			->leftJoin('s.subcategory', 'sub')
        	->where('sub.id=' . $subcategory);

		$this->CI =& get_instance();
	
        $query = $qb->getQuery();
        $sizes = $query->getResult();
        return $sizes;
    }
    
    public function getAllSizesBySubcategory( $subcategory ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')->from('models\Entities\Product\Size', 's')
			->leftJoin('s.subcategory', 'sub')
        	->where('sub.id=' . $subcategory)
			->orderBy('s.position', 'ASC');
        
		$this->CI =& get_instance();
		
        $query = $qb->getQuery();
        $sizes = $query->getResult();
        return $sizes;
    }
	
	public function getProductSizes($criteria, $product_id) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('pc') 
           	->from('models\Entities\Product\ProductSize', 'pc') 
			->leftJoin('pc.product', 'p')
			->leftJoin('pc.size', 's')
			->where('p.id = :product_id')
			->setParameters(array('product_id' => $product_id))
           	->orderBy($this->product_size_relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->product_size_relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $product_size) {

                $status = $product_size->getStatus() ? 'check' : 'delete';

                $data['record_items'][] = array(
                    $product_size->getID(), 
                    $product_size->getPosition(), 
                    $product_size->getSize()->getName(), 
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('products/change_product_size_status/' . $product_size->getID()) . '\');">Status</a>', 
				);
            }
        }

        return $data;
    }
	
	public function getProductSizePosition( $product_id ){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(pc.position)')
        	->from('models\Entities\Product\ProductSize', 'pc')
			->leftJoin('pc.product', 'p')
        	->where('p.id=' . $product_id);

		$this->CI =& get_instance();
	
        $query = $qb->getQuery();
        $sizes = $query->getResult();
        return $sizes;
    }
    
    public function getSizesByProduct( $product_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('pc')->from('models\Entities\Product\ProductSize', 'pc')
        	->leftJoin('pc.product', 'p')
        	->where('p.id=' . $product_id);
        
		$this->CI =& get_instance();
		
        $query = $qb->getQuery();
        $sizes = $query->getResult();
        return $sizes;
    }
	
	public function deleteProductSizes($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('pc')
           ->from('models\Entities\Product\ProductSize', 'pc')
           ->where($qb->expr()->in('pc.id', $id_list));

        $query = $qb->getQuery();
        $sizes = $query->getResult();

        foreach ($sizes as $size) {

        	$this->_em->remove($size);   
        }

        $this->_em->flush();
    }
	
	public function checkSize( $product, $size ) {
		
		$qb = $this->_em->createQueryBuilder();
        
        $qb->select('pc')->from('models\Entities\Product\ProductSize', 'pc')
        	->leftJoin('pc.product', 'p')
			->leftJoin('pc.size', 's')
        	->where('p.id=' . $product)
			->andWhere('s.id=' . $size);
        
		$this->CI =& get_instance();
		
        $query = $qb->getQuery();
        $sizes = $query->getResult();
		if( $sizes ) {
        	return false;
		} else {
			return true;
		}
	}

}

/* End of file SizeRepository.php */
/* Location: ./system/applications/_backend/models/SizeRepository.php */
