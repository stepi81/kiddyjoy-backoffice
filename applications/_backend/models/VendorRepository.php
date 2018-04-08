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
 
 class VendorRepository extends EntityRepository {
	
	private $relations = array(
        'code'         => 'v.code',
        'title'        => 'v.title',
        'position'     => 'v.position',   
        'vendor'       => 'v.vendor',
        );
	
	public function getVideos($criteria, $vendor_id) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
        
        $qb->select('v')
            ->from('models\Entities\Vendor\Video', 'v')
            ->where('v.vendor = :vendor')
            ->setParameters(array('vendor' => $vendor_id))
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
        
		if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {
			
			foreach( $data['records'] as $video ) {
				$data['record_items'][] = array(
					$video->getID(),
					$video->getPosition(),
					$video->getTitle(),
					$video->getCode(),
					$video->getVendor()->getID(),
	                '<a class="table-icon details" href="'.site_url('vendors/video_details/'.$video->getID()).'">Detalji</a>',
				);
			}
		}
		return $data;
	}
	
	public function getMaxVideoPosition(){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(v.position)')->from('models\Entities\Vendor\Video', 'v');

		$this->CI =& get_instance();
		if($this->CI->session->userdata('application_id'))
			$qb->where($qb->expr()->eq('v.vendor', $qb->expr()->literal( $this->CI->session->userdata('application_id') )));
		else
			$qb->where('v.vendor is NULL');
	
        $query = $qb->getQuery();
        $videos = $query->getResult();
        return $videos;
    }
	
	public function getAllVendorVideos(){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('v')->from('models\Entities\Vendor\Video', 'v');

		$this->CI =& get_instance();
		if($this->CI->session->userdata('application_id'))
			$qb->where($qb->expr()->eq('v.vendor', $qb->expr()->literal( $this->CI->session->userdata('application_id') )));
		else
			$qb->where('v.vendor is NULL');
	
        $query = $qb->getQuery();
        $videos = $query->getResult();
        return $videos;
    }
	
	public function deleteVideo($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('v')
           ->from('models\Entities\Vendor\Video', 'v')
           ->where($qb->expr()->in('v.id', $id_list));

        $query = $qb->getQuery();
        $videos = $query->getResult();

        foreach ($videos as $video) {
            $this->_em->remove($video);   
        }
        $this->_em->flush();
    }
	
	public function getMenuCategoryHighlights( $category_id ) {
		
		$qb = $this->_em->createQueryBuilder();
		$this->CI =& get_instance();
		
		$qb->select(array(
			'partial c.{id}',
            'partial p.{id}',
            ))
            ->from('models\Entities\Product\Category', 'c INDEX BY c.id');
		
        if( $this->CI->session->userdata('application_id') != 'MICROSOFT' ) {
        	$qb->leftJoin('c.highlights', 'p')
        		->leftJoin('p.master', 'pm')
				->where('c.id = '.$category_id)
        		->andWhere($qb->expr()->eq('pm.brand', $qb->expr()->literal( $this->CI->session->userdata('application_id') )));
        }
		elseif( $this->CI->session->userdata('application_id') == 'MICROSOFT' )
		{
			$qb->leftJoin('c.highlights', 'p')
        		->leftJoin('p.master', 'pm');
			$filterIDs = array(151, 152, 153, 154, 155, 156,
							   440, 441, 442, 443,
							   305, 306,
							   1163, 1737, 1165, 1164, 1741, 1166, 1739,
							   1090,
							   355,
							   3565, 2999,
							   295, 294, 297, 296);
			$or = $qb->expr()->orX();
			foreach( $filterIDs as $item ) {
                $or->add( ':item'.$item.' MEMBER OF p.filters' );
                $qb->setParameter('item'.$item, $item);
			}
			$or->add( 'pm.brand = '.$qb->expr()->literal( $this->CI->session->userdata('application_id') ) );
			$qb->andWhere($or);
		}
		//echo $qb->getQuery()->getSQL();
		//echo \Doctrine\Common\Util\Debug::dump($qb->getQuery()->getArrayResult());
		
		$result = $qb->getQuery()->getResult();
		if(!count($result))
			return array();
		else
			return $result[$category_id]->getVendorHighlights();
	}
	
 }
 
 /* End of file VendorRepository.php */
 /* Location: ./system/applications/_backend/models/VendorRepository.php */