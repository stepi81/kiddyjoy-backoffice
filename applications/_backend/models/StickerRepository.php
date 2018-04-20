<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models;

 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 //use DoctrineExtensions\Paginate\Paginate;
 use Doctrine\ORM\Tools\Pagination\Paginator;

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class StickerRepository extends EntityRepository {

    private $relations = array(
        'id'           => 's.id',
        'name'         => 's.name'
    );

    public function getSticker($criteria) {

     $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();
        $qb->select('s')
           ->from('models\Entities\Product\Sticker', 's')
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
               ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );
        }

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);

		if( $data['record_count'] = $data['records']->count() ) {
        //$query = $qb->getQuery();

        //if ($data['record_count'] = Paginate::getTotalQueryResults($query)) {

            //$paginateQuery = Paginate::getPaginateQuery($query, $criteria->offset, $criteria->limit);
            //$data['records'] = $paginateQuery->getResult();

            foreach ($data['records'] as $sticker) {

                $data['record_items'][] = array($sticker->getID(),
                $sticker->getID(),
                '<img border="0" src="' . $sticker->getImageURL() . '">',
                $sticker->getName(),
                '<a class="table-icon details" href="' . site_url('settings/stickers/details/' . $sticker->getID()) . '">Detalji</a>', );
            }
        }

        return $data;
    }

    public function deleteStickers($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s')
           ->from('models\Entities\Product\Sticker', 's')
           ->where($qb->expr()->in('s.id', $id_list));

        $query = $qb->getQuery();
        $stickers = $query->getResult();

        foreach ($stickers as $sticker) {
        	if( file_exists(SERVER_IMAGE_PATH . 'stickers/' . $sticker->getImage()) ) {
        		unlink(SERVER_IMAGE_PATH . 'stickers/' . $sticker->getImage());
        	}
            $this->_em->remove($sticker);
        }

        $this->_em->flush();
    }

 }

 /* End of file StickerRepository.php */
 /* Location: ./system/applications/_backend/models/StickerRepository.php */