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

class ColorRepository extends EntityRepository {

    private $relations = array(
        'id'           => 'c.id',
        'name'         => 'c.name',
        'status'       => 'c.status',   
        'position'     => 'c.position',
    ); 
	   
	private $product_color_relations = array(
        'id'           => 'pc.id',
        'name'		   => 'c.name',	
        'status'       => 'pc.status',   
        'position'     => 'pc.position',
    ); 

    public function getColors($criteria) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('c') 
           ->from('models\Entities\Product\Color', 'c') 
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $color) {

                $status = $color->getStatus() ? 'check' : 'delete';

                $data['record_items'][] = array(
                    $color->getID(), 
                    $color->getPosition(), 
                    $color->getName(), 
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('settings/colors/change_status/' . $color->getID()) . '\');">Status</a>', 
                    '<a class="table-icon details" href="' . site_url('settings/colors/details/' . $color->getID()) . '">Detalji</a>', );
            }
        }

        return $data;
    }

    public function deleteColors($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('c') 
           ->from('models\Entities\Product\Color', 'c') 
           ->where($qb->expr()->in('c.id', $id_list));

        $query = $qb->getQuery();
        $colors = $query->getResult();

		foreach ($colors as $color) {
        	$this->_em->remove($color);
		}

        $this->_em->flush();
    }
	
	public function getMaxPosition(){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(c.position)')->from('models\Entities\Product\Color', 'c');

		$this->CI =& get_instance();
	
        $query = $qb->getQuery();
        $colors = $query->getResult();
        return $colors;
    }
    
    public function getAllColors(){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('c')
        	->from('models\Entities\Product\Color', 'c')
			->orderBy('c.position', 'ASC');
        
		$this->CI =& get_instance();
		
        $query = $qb->getQuery();
        $colors = $query->getResult();
        return $colors;
    }

	public function getProductColors($criteria, $product_id) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('pc') 
           	->from('models\Entities\Product\ProductColor', 'pc') 
			->leftJoin('pc.product', 'p')
			->leftJoin('pc.color', 'c')
			->where('p.id = :product_id')
			->setParameters(array('product_id' => $product_id))
           	->orderBy($this->product_color_relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->product_color_relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $product_color) {

                $status = $product_color->getStatus() ? 'check' : 'delete';

                $data['record_items'][] = array(
                    $product_color->getID(), 
                    $product_color->getPosition(), 
                    $product_color->getColor()->getName(), 
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('products/change_product_color_status/' . $product_color->getID()) . '\');">Status</a>', 
				);
            }
        }

        return $data;
    }
	
	public function getProductColorPosition( $product_id ){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(pc.position)')
        	->from('models\Entities\Product\ProductColor', 'pc')
			->leftJoin('pc.product', 'p')
        	->where('p.id=' . $product_id);

		$this->CI =& get_instance();
	
        $query = $qb->getQuery();
        $colors = $query->getResult();
        return $colors;
    }
    
    public function getColorsByProduct( $product_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('pc')->from('models\Entities\Product\ProductColor', 'pc')
        	->leftJoin('pc.product', 'p')
        	->where('p.id=' . $product_id);
        
		$this->CI =& get_instance();
		
        $query = $qb->getQuery();
        $colors = $query->getResult();
        return $colors;
    }
	
	public function deleteProductColors($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('pc')
           ->from('models\Entities\Product\ProductColor', 'pc')
           ->where($qb->expr()->in('pc.id', $id_list));

        $query = $qb->getQuery();
        $colors = $query->getResult();

        foreach ($colors as $color) {

        	$this->_em->remove($color);   
        }

        $this->_em->flush();
    }
	
	public function checkColor( $product, $color ) {
		
		$qb = $this->_em->createQueryBuilder();
        
        $qb->select('pc')->from('models\Entities\Product\ProductColor', 'pc')
        	->leftJoin('pc.product', 'p')
			->leftJoin('pc.color', 'c')
        	->where('p.id=' . $product)
			->andWhere('c.id=' . $color);
        
		$this->CI =& get_instance();
		
        $query = $qb->getQuery();
        $color = $query->getResult();
		if( $color ) {
        	return false;
		} else {
			return true;
		}
	}

}

/* End of file ColorRepository.php */
/* Location: ./system/applications/_backend/models/ColorRepository.php */
