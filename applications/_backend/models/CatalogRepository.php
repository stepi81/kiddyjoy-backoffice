<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

namespace models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CatalogRepository extends EntityRepository {

    private $relations = array(
        'id'           => 'c.id',
        'title'        => 'c.title',
        'status'       => 'c.status',   
        'date'         => 'c.date',
        'edition'      => 'c.edition',
       ); 

    public function getCatalog($criteria) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('c') 
           ->from('models\Entities\Catalog', 'c') 
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $catalog) {

                $status = $catalog->getStatus() ? 'check' : 'delete';

                $data['record_items'][] = array(
                    $catalog->getID(), 
                    '<img border="0" src="' . $catalog->getImage() . '">', 
                    $catalog->getFormatedDate(), 
                    $catalog->getTitle(), 
                    $catalog->getEdition(), 
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('settings/catalogs/change_status/' . $catalog->getID()) . '\');">Status</a>', 
                    '<a class="table-icon details" href="' . site_url('settings/catalogs/details/' . $catalog->getID()) . '">Detalji</a>', );
            }
        }

        return $data;
    }

    public function deleteCatalogs($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('c') 
           ->from('models\Entities\Catalog', 'c') 
           ->where($qb->expr()->in('c.id', $id_list));

        $query = $qb->getQuery();
        $catalogs = $query->getResult();

        foreach ($catalogs as $catalog) {
            if (unlink(SERVER_PATH . 'assets/img/catalogs/' . $catalog->getImageName()) && unlink(SERVER_PATH . 'download/' . $catalog->getPDFName())) {
                $this->_em->remove($catalog);
            }
        }

        $this->_em->flush();
    }

}

/* End of file CatalogRepository.php */
/* Location: ./system/applications/_backend/models/CatalogRepository.php */
