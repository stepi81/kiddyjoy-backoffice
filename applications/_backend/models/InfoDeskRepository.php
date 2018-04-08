<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 //use DoctrineExtensions\Paginate\Paginate;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class InfoDeskRepository extends EntityRepository {
    
    public function getPages($criteria, $section) {
        
     $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('i')->from('models\Entities\InfoDesk', 'i')
						->orderBy('i.' . $criteria->sortname, $criteria->sortorder)
						->where ('i.section = '.$section) 
						->setFirstResult($criteria->offset)
						->setMaxResults($criteria->limit);


		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {
        
        //$query = $qb->getQuery();

        //if ($data['record_count'] = Paginate::getTotalQueryResults($query)) {

            //$paginateQuery = Paginate::getPaginateQuery($query, $criteria->offset, $criteria->limit);
            //$data['records'] = $paginateQuery->getResult();

            foreach ($data['records'] as $page) {

                $status = $page->getStatus() ? 'check' : 'delete';

                $data['record_items'][] = array(
                    $page->getID(),
                    $page->getPosition(),
                    $page->getName(),  
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('informations/change_status/' . $page->getID()) . '\');">Status</a>', 
                    '<a class="table-icon details" href="' . site_url('informations/details/' . $page->getID()) . '">Detalji</a>', );
            }
        }

        return $data;
    }

    public function getInformationsImages($page_id){
    	
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('i')
            ->from('models\Entities\Images\PageImage', 'i')
            ->where($qb->expr()->eq('i.page', $page_id));
        
        $query = $qb->getQuery();
        
        return $query->getResult();
    }
	
	public function getMaxSectionPosition ($section_id){
        
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(p.position)')
           ->from('models\Entities\InfoDesk', 'p')
           ->where('p.section=' . $section_id);

        $query = $qb->getQuery();
        $maxPos = $query->getResult();
        return $maxPos;
    }
	
    public function getInfoBySection( $section_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
           ->from('models\Entities\InfoDesk', 's')
           ->where('s.section=' . $section_id)
           ->add('orderBy', 's.position ASC');
        
        $query = $qb->getQuery();
        $spec = $query->getResult();
        return $spec;
    }
	
	public function deleteInfo( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('i')
			->from('models\Entities\InfoDesk', 'i')
			->where($qb->expr()->in('i.id', $id_list));
		
		$query = $qb->getQuery();
		$information = $query->getResult();
		
		foreach( $information as $info ) {
			if( $info->getIcon() ) {
				 unlink(SERVER_IMAGE_PATH.'icons/pages/'.$info->getIcon());
            }
            $images = $this->getInformationsImages($info->getID());
			foreach( $images as $image ) {	
            	unlink(SERVER_IMAGE_PATH.'info_desk/'.$image->getName());
				$this->_em->remove($image);
			}
        	$this->_em->remove($info); 
		}
        $this->_em->flush();
    }
 }
 
 /* End of file InfoDeskRepository.php */
 /* Location: ./system/applications/_backend/models/InfoDeskRepository.php */