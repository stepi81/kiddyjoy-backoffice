<?php

/**
 * ...
 * @author Ivan Despic[ Codeion ]
 */

namespace models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class NewsletterRepository extends EntityRepository {

    public function getNewsletter($criteria) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('n')->from('models\Entities\Newsletters', 'n')
           ->orderBy('n.' . $criteria->sortname, $criteria->sortorder)
		   ->setFirstResult($criteria->offset)
           ->setMaxResults($criteria->limit);

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $newsletter) {

                $status = $newsletter->getStatus() ? 'check' : 'delete';
                         $userGroup = $newsletter->getUsersGroup();
                            switch ($userGroup) {
                                case '1':
                                    $userGroup = 'Privatni';
                                    break;
                                case '2':
                                    $userGroup = 'Poslovni';
                                    break;
                                case '3':
                                    $userGroup = 'Newsletter korisnici';
                                    break;
                            }
                $data['record_items'][] = array($newsletter->getID(), 
                                                $newsletter->getSendDate(),
                                                $newsletter->getTemplate(), 
                                                $userGroup, 
                                                $newsletter->getTitle(), 
                                                '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('newsletter/change_status/' . $newsletter->getID()) . '\');">Status</a>', 
                                                '<a class="table-icon details" href="' . site_url('newsletter/details/' . $newsletter->getID()) . '">Detalji</a>', );
            }           
        }

        return $data;
    }

    public function deleteNewsletter($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('n')->from('models\Entities\Newsletters', 'n')->where($qb->expr()->in('n.id', $id_list));

        $query = $qb->getQuery();
        $newsletters = $query->getResult();

        foreach ($newsletters as $newsletter) {
     
            foreach ($newsletter->getImages() as $image) {
                unlink(SERVER_IMAGE_PATH . 'newsletter/' . $image->getName());
            }
            $this->_em->remove($newsletter);   
        }
        $this->_em ->flush();
    }

    public function getNewsletterImages($news_id) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('n')->from('models\Entities\NewsletterImage', 'n')->where($qb->expr()->eq('n.newsletter', $news_id));

        $query = $qb->getQuery();

        return $query->getResult();
    }

}

/* End of file NewsletterRepository.php */
/* Location: ./system/applications/_backend/models/NewsletterRepository.php */
