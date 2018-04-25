<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models;

 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class ProductRepository extends EntityRepository {

    private $relations = array(
        'id'               => 'p.id',
        'manufacturer_id'  => 'p.manufacturer_id',
        'vendor'		   => 'p.vendor',
        'status'           => 'p.status',
        'brand'            => 'b.name',
        'name'             => 'p.name',
        'subcategory'      => 's.name',
        'statistic_rating' => 'p.statistic_rating',
        'statistic_votes'  => 'p.statistic_votes',
        'statistic_visits' => 'p.statistic_visits',
        'statistic_sold'   => 'p.statistic_sold',
        'price'		   	   => 'p.price',
    );

    private $video_relations = array(
        'position'     => 'v.position',
        'title'        => 'v.title',
    );

    public function getProducts( $criteria, $group_id ) {

        $price_type = unserialize(PRICE_TYPE);

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
            ->from('models\Entities\Product', 'p')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->leftJoin('p.brand', 'b')
			->leftJoin('p.category', 'c')
			->leftJoin('p.subcategory', 's')
			->andWhere('c.id = :category_id')
			->setParameters(array('category_id' => $group_id));
            //->leftJoin('p.subcategory', 's')
            //->leftJoin('s.parent', 'a')
            //->andWhere('a.id = :subcategory_id')
            //->orWhere('s.id = :group_id')
            //->setParameters (array('subcategory_id' => $group_id, 'group_id'=> $group_id));

            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');
            }

        $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);

        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);

        $data['record_count'] = count($paginator);
        foreach ($paginator as $product) {

			$featured = $product->getFeatured() ? 'check' : 'delete';
            $status = $product->getStatus() ? 'check' : 'delete';
            $status_category_highlight = $product->getHighlightCategory() ? 'category_highlight_on' : 'category_highlight_off';
            $status_subcategory_highlight = $product->getHighlightSubcategory() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';

            if( $product->getImages()->count() ) {
                $gallery_link = '<a href="'.site_url( 'products/gallery/'.$product->getID() ).'"><img border="0" src="'.layout_url('flexigrid/gallery.png').'" onmouseover="this.src=\'http://dev.codeion.com/kiddyjoy/site/assets/img/products/thumb/' . $product->getImages()->first()->getName() . '\'" onmouseout="this.src=\'' . layout_url('flexigrid/gallery.png') . '\'"></a>';
            } else {
                $gallery_link = '<a href="'.site_url( 'products/gallery/'.$product->getID() ).'"><img border="0" src="'.layout_url('flexigrid/gallery.png').'"></a>';
            }

            $data['record_items'][] = array(
                $product->getID(),
                $product->getID(),
                $product->getManufacturerID(),
                //$product->getManufacturerID(),
                $product->getVendor(),
                $product->getBrandName(),
                '<a href="'.$product->getURL().'" target="blank">'.$product->getName().'</a>',
                $product->getSubcategory()->getID() != $group_id ? $product->getSubcategory()->getName(): '/',
                $product->getPrice(),

				'<a href="'.site_url( 'products/gallery/'.$product->getID() ).'"><img border="0" src="'.layout_url('flexigrid/gallery.png').'"></a>',
                '<a href="'.site_url('products/details/'.$product->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                '<a href="'.site_url('products/clone_product_details/'.$product->getID()).'"><img border="0" src="'.layout_url('flexigrid/clone-icon.png').'"></a>',

                $product->getStatisticSold(),
                $product->getStatisticVisits(),
                $product->getStatisticVotes(),
                $product->getStatisticRating(),

                '<a class="table-duoicon '.$status_category_highlight.'" href="javascript:void(0);" onclick="changeCategoryHighlight(this, \''.site_url('products/set_category_highlight/'.$product->getID()).'\');"></a><a class="table-duoicon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_subcategory_highlights/'.$product->getID()).'\');"></a>',
                '<a class="table-icon ' . $featured . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('products/change_featured/' . $product->getID()) . '\');">Featured</a>',
                '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('products/change_status/' . $product->getID()) . '\');">Status</a>',
                '<a class="table-icon comments" href="' . site_url('comments/listing_by_record/1/' . $product->getID()) . '">Komentari</a>',
                '<a href="'.site_url( 'product/bundles/listing/'.$product->getID() ).'"><img border="0" src="'.layout_url( count( $product->getBundles() ) ? 'flexigrid/icon-bundle-y.png' : 'flexigrid/icon-bundle-n.png' ).'"></a>',
                '<a href="'.site_url( 'products/product_sizes_listing/'.$product->getID() ).'"><img border="0" src="'.layout_url('flexigrid/size_icon.png').'"></a>',
                '<a href="'.site_url( 'products/product_colors_listing/'.$product->getID() ).'"><img border="0" src="'.layout_url('flexigrid/color_icon.png').'"></a>',
                '<a href="'.site_url( 'product/videos/listing/'.$product->getID() ).'"><img border="0" src="'.layout_url('flexigrid/icon-video.png').'"></a>',
            );
        }
        return $data;
    }

    public function deleteCategories( $id_list ) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
            ->from('models\Entities\Product\ProductCategory', 'p')
            ->where($qb->expr()->in('p.id', $id_list));

        $query = $qb->getQuery();
        $categories = $query->getResult();

        foreach( $categories as $category ) {

        $this->_em->remove($category);
        }
        $this->_em->flush();
    }

    public function getImagesByProduct($product_id){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('i')
            ->from('models\Entities\Images\ProductImage', 'i')
            ->orderBy('i.position', 'ASC')
            ->where($qb->expr()->eq('i.product', ':product'))
            ->setParameter('product', $product_id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function getMaxImagePosition($product_id){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(i.position)')
            ->from('models\Entities\Images\ProductImage', 'i')
            ->where($qb->expr()->eq('i.product', ':product'))
            ->setParameter('product', $product_id);

        $query = $qb->getQuery();
        return $query->getSingleResult();
    }

    public function getImagesAfterDelete($product_id, $position){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('i')
            ->from('models\Entities\Images\ProductImage', 'i')
            ->orderBy('i.position', 'ASC')
            ->where('i.product = :product')
            ->andWhere('i.position > :position')
            ->setParameters(array('product' => $product_id, 'position' => $position));

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function setImagesPosition($id){

        for( $i=0; $i<sizeof($id); $i++){

            $qb = $this->_em->createQueryBuilder();

            $image = $this->_em->getRepository('models\Entities\Images\ProductImage')->find($id[$i]);
            $image->setPosition( $i + 1 );
            $this->_em->flush();
        }
    }

    public function getParentCollection($parent_id){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('c')
            ->from('models\Entities\Collection\Collection', 'c')
            ->where('c.id = :parent_id')
            ->setParameter('parent_id', $parent_id);

        $query = $qb->getQuery();
        return $query->getSingleResult();
    }

    public function checkBundleProduct( $id ) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
            ->from('models\Entities\Product', 'p')
            ->leftJoin('p.master', 'm')
            ->where('p.status = :status')
            ->andWhere('p.id = :id')
            ->andWhere('m.archive = :archive')
            ->setParameters(array('status' => 1, 'id' => $id, 'archive' => 0));

    	try {
			return $qb->getQuery()->getSingleResult();
		}
		catch( \Doctrine\ORM\NoResultException $e ) {
			return NULL;
		}
    }

    public function getAllCategories () {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('c')->from('models\Entities\Product\Category', 'c');

        $query = $qb->getQuery();
        $category = $query->getResult();
        return $category;
    }

	public function getAllSubcategories () {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s')->from('models\Entities\Product\Subcategory', 's');

        $query = $qb->getQuery();
        $category = $query->getResult();
        return $category;
    }

    public function checkID($id){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
           ->from('models\Entities\Product', 'p')
           ->where('p.id = :id')
           ->setParameters(array('id' => $id));

        $check = $qb->getQuery()->getResult();

        return count($check) ? 'true' : 'false';
    }

    public function getProductVideos( $criteria, $product_id ) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('v')
            ->from('models\Entities\Product\Video', 'v')
            ->orderBy($this->video_relations[$criteria->sortname], $criteria->sortorder)
            ->where('v.product = :product_id')
            ->setParameters(array('product_id' => $product_id));

            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->video_relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');
            }

        $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);

        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);

          $data['record_count'] = count($paginator);
            foreach ($paginator as $video) {

             $data['record_items'][] = array(
                    $video->getID(),
                    $video->getTitle(),
                    $video->getPosition(),
                    '<a href="'.site_url( 'product/videos/details/'.$video->getID() ).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                    );
            }
         return $data;
    }

    public function deleteVideo($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('v')
           ->from('models\Entities\Product\Video', 'v')
           ->where($qb->expr()->in('v.id', $id_list));

        $query = $qb->getQuery();
        $videos = $query->getResult();

        foreach ($videos as $video) {

             $this->_em->remove($video);
        }

        $this->_em->flush();
    }

    public function deleteProductBundles($id_list, $id) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
            ->from('models\Entities\Product', 'p')
            ->where ('p.id = :product')
            ->setParameters (array('product' => $id));

        $query = $qb->getQuery();
        $product = $query->getSingleResult();

        foreach( $id_list as $bundle_id ) {

            $product->getBundles()->removeElement( $this->_em->getReference('models\Entities\Product\Bundle', $bundle_id) );
            $this->_em->flush();
        }
    }

    public function getProductsForAds( $group_id ) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
            ->from('models\Entities\Product', 'p')
            ->leftJoin('p.subcategory', 's')
            ->leftJoin('s.parent', 'a')
            ->andWhere('a.id = :subcategory_id')
            ->orWhere ('s.id = :group_id')
            ->setParameters (array('subcategory_id' => $group_id, 'group_id'=> $group_id));

        $query = $qb->getQuery();
        $products = $query->getResult();
        return $products;
    }

    public function getProductByGroupAndSubcategory( $id_list ) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
           ->from('models\Entities\Product', 'p')
           ->leftJoin('p.subcategory', 's')
           ->leftJoin('s.parent', 'a');

        foreach ($id_list as $group_id) {
            $sub_id = 'sub' . $group_id;
            $gro_id = 'gro' . $group_id;
            $qb->andWhere("a.id = :$sub_id")
               ->orWhere ("s.id = :$gro_id")
               ->setParameters (array($sub_id => $group_id, $gro_id=> $group_id));
        }
        $query = $qb->getQuery();
        $products = $query->getResult();
        $i=0;
        foreach ($products as $product) {
            $i++;
        }
        return $i;
   }

    public function getProductBySubcategory( $id_list ) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
           ->from('models\Entities\Product', 'p')
           ->leftJoin('p.subcategory', 's');

        foreach ($id_list as $subcategory_id) {
            $sub_id = 'sub' . $subcategory_id;
            $qb->andWhere("s.id = :$sub_id")
               ->setParameters (array($sub_id => $subcategory_id));
        }
        $query = $qb->getQuery();
        $products = $query->getResult();
        $i=0;
        foreach ($products as $product) {
            $i++;
        }
        return $i;
   }

   public function searchProducts($brands, $subcategory, $specifications) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select(array(
            'partial p.{id}',
            ))
            ->from('models\Entities\Product', 'p')
			->leftJoin('p.subcategory', 's')
            ->leftJoin('s.parent', 'a')
			->leftJoin('p.master', 'pm')
			->leftJoin('p.filters', 'pf')
			->where($qb->expr()->eq('p.status', 1))
			->andWhere($qb->expr()->eq('pm.archive', 0));

			if( $brands ) {
				$qb->andWhere($qb->expr()->in('pm.brand', $brands));
			}

			if( $subcategory ) {
				$qb->andWhere($qb->expr()->eq('s.id', $subcategory->getID() ))
					->orWhere($qb->expr()->eq('a.id', $subcategory->getID() ));
			}

			if( $specifications ) {
				foreach( $specifications as $filters ) {
					$or = $qb->expr()->orX();
			        foreach( $filters as $item ) {
		            	$or->add( ':item'.$item.' MEMBER OF p.filters' );
		            	$qb->setParameter('item'.$item, $item);
			        }
					$qb->andWhere($or);
				}
			}

		//echo $qb->getQuery()->getSQL();
        $query = $qb->getQuery();
        $products = $query->getResult();

        return count($products) ? $products : NULL;

   }

	public function getOutOfStocks($category)
	{
		$qb = $this->_em->createQueryBuilder();

        $qb->select(array(
            'partial p.{id, name}',
            //'partial m.{id, master_id, name, price}',
            //'partial b.{id, name}',
            'partial c.{id, name}',
            ))
            ->from('models\Entities\Product', 'p')
            ->leftJoin('p.master', 'm')
 	        //->leftJoin('m.stock' , 'st')
            //->leftJoin('m.brand', 'b')
            ->leftJoin('p.category', 'c')
            ->where('c.id = :category_id')
            ->andWhere('p.status  = 1')
   			//->andWhere('m.archive  = 0')
			//->andWhere('st.quantity is null')
			->groupBy('p.id')
            ->setParameters (array('category_id' => $category));

		try
		{
			$query = $qb->getQuery();
	            return $query->getResult();
	        }
	        catch( \Doctrine\ORM\NoResultException $e ) {
	            return null;
	      	}
	}

     public function getExportProducts()
     {
         $qb = $this->_em->createQueryBuilder();

         $qb->select(array(
             'partial p.{id, vendor, manufacturer_id, name, price, old_price, statistic_visits, statistic_votes, statistic_rating, status, promotion}',
             //'partial m.{id, master_id, name, price}',
             'partial b.{id, name}',
             'partial c.{id, name}',
             'partial s.{id, name}'
         ))
             ->from('models\Entities\Product', 'p')
             //->leftJoin('p.master', 'm')
             //->leftJoin('m.stock' , 'st')
             //->leftJoin('m.brand', 'b')
             ->leftJoin('p.category', 'c')
             ->leftJoin('p.subcategory', 's')
             ->leftJoin('p.brand', 'b')
             //->where('c.id = :category_id')
             //->andWhere('p.status  = 1')
             //->andWhere('m.archive  = 0')
             //->andWhere('st.quantity is null')
             ->groupBy('p.id');

         try
         {
             $query = $qb->getQuery();
             return $query->getResult();
         }
         catch( \Doctrine\ORM\NoResultException $e ) {
             return null;
         }
     }

	public function deleteProducts( $id_list ) {

		$qb = $this->_em->createQueryBuilder();

		$qb->select('p')
			->from('models\Entities\Product', 'p')
			->where($qb->expr()->in('p.id', $id_list));

		$query = $qb->getQuery();
		$records = $query->getResult();

		foreach( $records as $record ) {
            $images = $record->getImages();
			foreach( $images as $image ) {
            	unlink( SERVER_PATH . '/assets/img/products/thumb/'.$image->getName() );
		        unlink( SERVER_PATH . '/assets/img/products/small/'.$image->getName() );
		        unlink( SERVER_PATH . '/assets/img/products/large/'.$image->getName() );
		        unlink( SERVER_PATH . '/assets/img/products/medium/'.$image->getName() );
			}
        	$this->_em->remove($record);
		}
        $this->_em->flush();
    }
}


 /* End of file ProductRepository.php */
 /* Location: ./system/applications/_backend/models/ProductRepository.php */