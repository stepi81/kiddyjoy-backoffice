<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 use models\Entities\Product\Subcategory;
 use models\Entities\Product\SubcategoryPriceRange;

 class Subcategories extends MY_Controller {

     public $gridParams = array(
        'id'                    => 'subcategoryGrid',
        'width'                 => 'auto',
        'height'                => 350,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total}.',
        'pagetext'              => 'Stranica',
        'outof'                 => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'               => 'Obrada u toku, molimo sačekajte...',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );

     public function __construct() {

         parent::__construct();

         $this->load->helper(array('form', 'url'));
         $this->load->helper('flexigrid');
		 $this->load->helper('tinymce');

         $this->load->library('Flexigrid');
		 $this->load->library('Cache_Manager');

         $this->resources['css'] = array();
		 $this->resources['js'][] = 'tiny_mce';
     }

     public function groups_listing( $category_id, $message = NULL ) {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
        $this->resources['js'][] = 'checkbox';

        $this->subcollectionGridParams['title']='Grupe';

        $colModel['position'] = array( 'Pozicija', 50, TRUE, 'center', 1 );
		$colModel['id'] = array( 'ID', 50, TRUE, 'center', 1 );
		$colModel['image']  = array( 'Thumb', 154, FALSE, 'center', 0 );
        $colModel['name'] = array( 'Ime', 170, TRUE, 'center', 1 );
		$colModel['highlights'] = array( 'Izdvajamo', 60, FALSE, 'center', 0 );
        $colModel['specification'] = array( 'Specifikacije', 80, FALSE, 'center', 0);
        $colModel['subcategories'] = array( 'Podkategorije', 80, FALSE, 'center', 0);
		$colModel['price_range'] = array( 'Rang cena', 60, FALSE, 'center', 0);
		$colModel['sizes'] = array( 'Veličine', 50, FALSE, 'center', 0);
		//$colModel['brands'] = array( 'Brendovi', 50, FALSE, 'center', 0);
		$colModel['reviews'] = array( 'Utisci', 50, FALSE, 'center', 0);
		$colModel['status'] = array('Status', 50, FALSE, 'center', 0);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        $buttons[] = array('Obriši grupu', 'delete', 'grid_commands', site_url("product/subcategories/group_delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("product/subcategories/groups_grid/" . $category_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);
        $data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($category_id);
        $data['grid_title'] = 'Grupe - ' . $data['category']->getName();
		$data['tinymce'] = build_tinymce_js('other', 500, 400, "");

        If (isset($message)){$data['message'] = $message;}

        $this->_render_view("product/subcategory/create_group", $data);
     }

     public function groups_grid( $category_id ){

         $valid_fields = array( 'name', 'position', 'id' );
         $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Product\Subcategory')->getCategoryGroups($criteria, $category_id);

         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

     public function group_delete() {

        $id_list = explode(',', $this->input->post('items'));
        $number_of_product = $this->em->getRepository('models\Entities\Product')->getProductByGroupAndSubcategory( $id_list );
        if ( $number_of_product == 0){
            foreach ($id_list as $id) {
                $subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($id);
                $subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getGroupsByCategory($subcategory->getCategory()->getID());
                foreach ($subcategories as $ad) {
                    $oldPosition = $ad->getPosition();
                    if ($subcategory->getPosition() < $ad->getPosition()) {
                        $ad->setPosition($oldPosition - 1);
                        $this->em->persist($ad);
                        $this->em->flush();
                    }
                }
            }
            $this->em->getRepository('models\Entities\Product\Subcategory')->deleteGroup($id_list);

			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');

			$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
			$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');

            $this->output->set_output('Uspešno ste obrisali grupu!');
        } else {
            $this->output->set_output("Brisanje grupe obustavljeno! Postoji $number_of_product proizvoda vezanih za grupu ili podkategoriju");
        }
    }

    public function save( $category_id ){

         $subcategory = new Subcategory();

         $subcategory->setName($this->input->post('name'));
		 $subcategory->setDescription($this->input->post('other'));
         $subcategory->setCategory( $this->em->getReference('models\Entities\Product\Category', $category_id));
         $subcategory->setHighlight( 0 );

        $subcategory->setSeoTitle( $this->input->post('seo_title') );
        $subcategory->setSeoKeywords( $this->input->post('seo_keywords') );
        $subcategory->setSeoDescription( $this->input->post('seo_description') );

		 if( $thumb = $this->create_thumb() ) $subcategory->setImage( $thumb );

         $maxPos = $this->em->getRepository('models\Entities\Product\Subcategory')->getMaxGroupPosition($category_id);
         $subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getGroupsByCategory($category_id);

         $position = $this->input->post('position');
         $maxPosition = $maxPos[0][1];

         if ($position) {
            if ($position > $maxPosition) {
                $position=$maxPosition + 1;
            } else {
                foreach ($subcategories as $ad) {
                    $adNowPosition = $ad->getPosition();
                    if ($position <= $adNowPosition) {
                        $ad->setPosition($adNowPosition + 1);
                        $this->em->persist($ad);
                        $this->em->flush();
                    }
                }
            }
        } else {
            $position=$maxPosition + 1;
        }
         $subcategory->setPosition($position);

		 if( $thumb = $this->create_thumb() ) $subcategory->setImage( $thumb );

         $this->em->persist($subcategory);
         $this->em->flush();
         $message='<p class="message_success"  style="width: 250px; padding: 8px 5px;">Nova podkategorija je dodata!</p>';

		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');

		 $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
		 $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');

         $this->groups_listing( $category_id, $message );
     }

     public function details($subcategory_id, $message=NULL) {

        if ($data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id)) {
            $data['message'] = $message;
			$data['tinymce'] = build_tinymce_js('other', 500, 400, "");
            $this->_render_view('product/subcategory/edit_group', $data);
        } else
            show_404();
     }

     public function edit($subcategory_id) {

         if($data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id)) {

            $data['subcategory']->setName( $this->input->post('name') );
			$data['subcategory']->setDescription($this->input->post('other'));

             $data['subcategory']->setSeoTitle( $this->input->post('seo_title') );
             $data['subcategory']->setSeoKeywords( $this->input->post('seo_keywords') );
             $data['subcategory']->setSeoDescription( $this->input->post('seo_description') );

			if( $thumb = $this->create_thumb($data['subcategory']->getImage()) ) $data['subcategory']->setImage( $thumb );

            $oldPosition = $data['subcategory']->getPosition();
            $subcategoryPosition = $this->input->post('position');

            $maxPos = $this->em->getRepository('models\Entities\Product\Subcategory')->getMaxGroupPosition($data['subcategory']->getCategory()->getID());
            $subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getGroupsByCategory($data['subcategory']->getCategory()->getID());

            $maxPosition = $maxPos[0][1];

            if ($subcategoryPosition <= $oldPosition) {
                foreach ($subcategories as $ad) {
                    if ($ad->getPosition() >= $subcategoryPosition && $ad->getPosition() < $oldPosition)
                        $ad->setPosition($ad->getPosition() + 1);
                    $this->em->persist($ad);
                    $this->em->flush();
                }
            } else {
                if ($subcategoryPosition >= $maxPosition) {
                    $subcategoryPosition = $maxPosition;
                }
                foreach ($subcategories as $ad) {
                    if ($ad->getPosition() <= $subcategoryPosition && $ad->getPosition() > $oldPosition)
                        $ad->setPosition($ad->getPosition() - 1);
                    $this->em->persist($ad);
                    $this->em->flush();
                }
            }
              $data['subcategory']->setPosition($subcategoryPosition);

              $this->em->persist($data['subcategory']);
              $this->em->flush();
              $data['message'] = '<p class="message_success"  style="width: 373px; padding: 8px 5px;">Sve izmene su uspešno sačuvane!</p>';
			  $data['tinymce'] = build_tinymce_js('other', 500, 400, "");

			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');

			  $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
			  $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');

              $this->_render_view('product/subcategory/edit_group', $data);
       } else show_404();
    }

    public function listing( $parent_id, $message=NULL ) {

		//$this->session->set_userdata('review_specifications_back_button', $this->navigation_manager->backToGrid('review_specifications_back_button', 'product/subcategories/listing/'.$parent_id));

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
        $this->resources['js'][] = 'checkbox';

        $this->subcollectionGridParams['title']='Podkategorije';

        $colModel['position'] = array( 'Pozicija', 50, TRUE, 'center', 1 );
		$colModel['id'] = array( 'ID', 50, TRUE, 'center', 1 );
		$colModel['image']  = array( 'Thumb', 234, FALSE, 'center', 0 );
        $colModel['name'] = array( 'Ime', 200, TRUE, 'center', 1 );
		$colModel['highlights'] = array( 'Izdvajamo', 60, FALSE, 'center', 0 );
        $colModel['specification'] = array( 'Specifikacije', 100, FALSE, 'center', 0);
		$colModel['price_range'] = array( 'Rang cena', 80, FALSE, 'center', 0);
		$colModel['sizes'] = array( 'Veličine', 50, FALSE, 'center', 0);
		//$colModel['brands'] = array( 'Brendovi', 50, FALSE, 'center', 0);
		$colModel['reviews'] = array( 'Utisci', 50, FALSE, 'center', 0);
		$colModel['status'] = array('Status', 50, FALSE, 'center', 0);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);

        $buttons[] = array('Obriši podkategoriju', 'delete', 'grid_commands', site_url("product/subcategories/subcategory_delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("product/subcategories/grid/" . $parent_id), $colModel, 'position', 'ASC', $this->gridParams, $buttons);
        $data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($parent_id);
        $data['grid_title'] = 'Podkategorije:: ' . $data['subcategory']->getName();
		$data['tinymce'] = build_tinymce_js('other', 500, 400, "");

        If (isset($message)){$data['message'] = $message;}

        $this->_render_view("product/subcategory/create_subcategory", $data);
     }

     public function grid( $parent_id ){

         $valid_fields = array( 'name', 'position', 'id' );
         $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Product\Subcategory')->getCategorySubcategories($criteria, $parent_id);

         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

     public function save_subcategory( $parent_id ){

         $subcategory = new Subcategory();

         $subcategory->setName($this->input->post('name'));
		 $subcategory->setDescription($this->input->post('other'));
         $subcategory->setParent( $this->em->getReference('models\Entities\Product\Subcategory', $parent_id) );
         $subcategory->setCategory( $this->em->getReference('models\Entities\Product\Category', $this->input->post('category_id')));
         $subcategory->setHighlight( 0 );

         $subcategory->setSeoTitle( $this->input->post('seo_title') );
         $subcategory->setSeoKeywords( $this->input->post('seo_keywords') );
         $subcategory->setSeoDescription( $this->input->post('seo_description') );

         /*$maxPos = $this->em->getRepository('models\Entities\Product\Subcategory')->getMaxSubcategoryPosition($this->input->post('category_id'));
         $subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getSubcategoryByCategory($this->input->post('category_id'));*/

         $maxPos = $this->em->getRepository('models\Entities\Product\Subcategory')->getMaxSubcategoryPosition($parent_id);
         $subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getSubcategoryByCategory($parent_id);

         $position = $this->input->post('position');
         $maxPosition = $maxPos[0][1];

         if ($position) {
            if ($position > $maxPosition) {
                $position=$maxPosition + 1;
            } else {
                foreach ($subcategories as $ad) {
                    $adNowPosition = $ad->getPosition();
                    if ($position <= $adNowPosition) {
                        $ad->setPosition($adNowPosition + 1);
                        $this->em->persist($ad);
                        $this->em->flush();
                    }
                }
            }
        } else {
            $position=$maxPosition + 1;
        }
         $subcategory->setPosition($position);
		 if( $thumb = $this->create_thumb() ) $subcategory->setImage( $thumb );

         $this->em->persist($subcategory);
         $this->em->flush();
         $message='<p class="message_success"  style="width: 250px; padding: 8px 5px;">Nova podkategorija je dodata!</p>';

		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');

		 $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
		 $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
		 $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');

         $this->listing( $parent_id, $message );
     }

     public function subcategory_details($subcategory_id, $message=NULL) {

        if ($data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id)) {
            $data['message'] = $message;
			$data['tinymce'] = build_tinymce_js('other', 500, 400, "");

            $this->_render_view('product/subcategory/edit_subcategory', $data);
        } else show_404();
     }

     public function edit_subcategory($subcategory_id) {

         if($data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id)) {

            $data['subcategory']->setName( $this->input->post('name') );
			$data['subcategory']->setDescription($this->input->post('other'));

             $data['subcategory']->setSeoTitle( $this->input->post('seo_title') );
             $data['subcategory']->setSeoKeywords( $this->input->post('seo_keywords') );
             $data['subcategory']->setSeoDescription( $this->input->post('seo_description') );

			if( $thumb = $this->create_thumb($data['subcategory']->getImage()) ) $data['subcategory']->setImage( $thumb );

            $oldPosition = $data['subcategory']->getPosition();
            $subcategoryPosition = $this->input->post('position');

            //$maxPos = $this->em->getRepository('models\Entities\Product\Subcategory')->getMaxSubcategoryPosition($data['subcategory']->getCategory()->getID());
            //$subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getSubcategoryByCategory($data['subcategory']->getCategory()->getID());

            $maxPos = $this->em->getRepository('models\Entities\Product\Subcategory')->getMaxSubcategoryPosition($data['subcategory']->getParent()->getID());
         	$subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getSubcategoryByCategory($data['subcategory']->getParent()->getID());

            $maxPosition = $maxPos[0][1];

            if ($subcategoryPosition <= $oldPosition) {
                foreach ($subcategories as $ad) {
                    if ($ad->getPosition() >= $subcategoryPosition && $ad->getPosition() < $oldPosition)
                        $ad->setPosition($ad->getPosition() + 1);
                    $this->em->persist($ad);
                    $this->em->flush();
                }
            } else {
                if ($subcategoryPosition >= $maxPosition) {
                    $subcategoryPosition = $maxPosition;
                }
                foreach ($subcategories as $ad) {
                    if ($ad->getPosition() <= $subcategoryPosition && $ad->getPosition() > $oldPosition)
                        $ad->setPosition($ad->getPosition() - 1);
                    $this->em->persist($ad);
                    $this->em->flush();
                }
            }
              $data['subcategory']->setPosition($subcategoryPosition);

              $this->em->persist($data['subcategory']);
              $this->em->flush();
              $data['message'] = '<p class="message_success"  style="width: 373px; padding: 8px 5px;">Sve izmene su uspešno sačuvane!</p>';
			  $data['tinymce'] = build_tinymce_js('other', 500, 400, "");

			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');

			  $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
			  $this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
			  $this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');

              $this->_render_view('product/subcategory/edit_subcategory', $data);
       } else show_404();
    }

    public function subcategory_delete() {

        $id_list = explode(',', $this->input->post('items'));

        $number_of_product = $this->em->getRepository('models\Entities\Product')->getProductBySubcategory( $id_list );
        if ( $number_of_product == 0){

	        foreach ($id_list as $id) {
	            $subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($id);

	            //$subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getSubcategoryByCategory($subcategory->getCategory()->getID());
	            $subcategories = $this->em->getRepository('models\Entities\Product\Subcategory')->getSubcategoryByCategory($subcategory->getParent()->getID());
	            foreach ($subcategories as $ad) {
	                $oldPosition=$ad->getPosition();
	                if ($subcategory->getPosition() < $ad->getPosition()) {
	                    $ad->setPosition($oldPosition - 1);
	                    $this->em->persist($ad);
	                    $this->em->flush();
	                }
	            }
	        }
	        $this->em->getRepository('models\Entities\Product\Subcategory')->deleteSubcategory($id_list);
	        $this->output->set_output('Uspešno ste obrisali podkategoriju!');

			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategories');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getCategoryMenus');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getSubcategoryBrands');

			$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getCategoryMenus');
			$this->cache_manager->deleteCache('EcomCatalog_MenuRepository_getMenuSubcategoryBrands');
			$this->cache_manager->deleteCache('EcomCatalog_CatalogRepository_getMenuAds');

        }else {
            $this->output->set_output("Brisanje grupe obustavljeno! Postoji $number_of_product proizvoda vezanih za podkategoriju");
        }
    }

	 private function create_thumb( $thumb = NULL ) {

		if( !$_FILES['thumb']['size'] ) return $thumb;

		$upload_config['encrypt_name'] 		= FALSE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'icons/subcategories/';
        $upload_config['allowed_types'] 	= 'gif|jpg|png';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;

		$this->load->library('upload');

        $this->upload->initialize($upload_config);

		if( $this->upload->do_upload('thumb') ) {

            $image_data = $this->upload->data();

			$resize_config['image_library'] 	= 'gd2';
			$resize_config['source_image']		= $image_data['full_path'];
			$resize_config['width']				= 135;
			$resize_config['height'] 			= 124;
			$resize_config['maintain_ratio']	= TRUE;
			$resize_config['master_dim']		= $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';

			$this->load->library('image_lib', $resize_config);

			if ( $this->image_lib->resize() ) {

				if( $thumb ) unlink( SERVER_IMAGE_PATH.'icons/subcategories/'.$thumb );

				$this->image_lib->clear();

				$crop_config['image_library']	= 'gd2';
				$crop_config['source_image']	= $image_data['full_path'];
				$crop_config['width']			= 135;
				$crop_config['height'] 			= 124;
				$crop_config['maintain_ratio'] 	= FALSE;

				$imageSize = $this->image_lib->get_image_properties($image_data['full_path'], TRUE);

				switch( $resize_config['master_dim'] ) {
					case 'width':
						$crop_config['y_axis'] = ($imageSize['height'] - $crop_config['height']) / 2;
						break;
					case 'height':
						$crop_config['x_axis'] = ($imageSize['width'] - $crop_config['width']) / 2;
						break;
				}
				$this->image_lib->initialize($crop_config);

				if ( $this->image_lib->crop() ) {
					$this->image_lib->clear();
				}
				return $image_data['file_name'];
			}
			else return NULL;
        }
		else return NULL;
	 }

	 public function price_ranges( $subcategory_id ) {

		$this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
        $this->resources['js'][] = 'checkbox';

        $this->subcollectionGridParams['title'] = 'Rang cena';

        $this->session->set_userdata('price_range_subcategory_id', $subcategory_id);

        $colModel['price_range'] = array( 'Rang', 150, TRUE, 'center', 1 );

        $buttons[] = array('Obriši rang', 'delete', 'grid_commands', site_url("product/subcategories/delete_price_range"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("product/subcategories/price_range_grid/" . $subcategory_id), $colModel, 'min_price', 'ASC', $this->gridParams, $buttons);
        $data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id);
        $data['grid_title'] = 'Rangovi cena - ' . $data['subcategory']->getName();
        If (isset($message)){$data['message'] = $message;}
        $this->_render_view("product/subcategory/create_subcategory_price_range", $data);
	}

	public function price_range_grid( $subcategory_id ){

		 $this->session->set_userdata('price_range_subcategory_id', $subcategory_id);

         $valid_fields = array( 'amount' );
         $this->flexigrid->validate_post($this->gridParams['id'], 'min_price', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Product\SubcategoryPriceRange')->getPriceRanges($criteria, $subcategory_id);

         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

	public function save_price_range() {

		 if( $this->input->post('range') && $this->input->post('range') != 0 ) {

			 $ranges = $this->em->getRepository('models\Entities\Product\SubcategoryPriceRange')->getRangesBySubcategory($this->input->post('subcategory_id'));

			 $this->session->set_userdata('price_range_subcategory_id', $this->input->post('subcategory_id'));

			 $max_prices = array();
			 $max_prices[] = (int) $this->input->post('range');
			 foreach( $ranges as $range ) {
			 	if( $range->getMaxPrice() != 0 ) $max_prices[] = $range->getMaxPrice();
			 }

			 $max_prices = array_unique( $max_prices );

			 sort( $max_prices );

			 $this->em->getRepository('models\Entities\Product\SubcategoryPriceRange')->deleteRangesBySubcategory($this->input->post('subcategory_id'));

			 foreach( $max_prices as $key => $price ) {
			 	if( $key == 0 ) {
			 		$min_price = 0;
					$max_price = $price;
			 	} else {
			 		$min_price = $max_prices[$key-1];
					$max_price = $price;
			 	}

				$price_range = new SubcategoryPriceRange();

		        $price_range->setMinPrice($min_price);
				$price_range->setMaxPrice($max_price);
		        $price_range->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('subcategory_id')) );

		        $this->em->persist($price_range);
			 }

			 $min_price = $max_prices[ count($max_prices) - 1 ];
			 $max_price = 0;

			 $price_range = new SubcategoryPriceRange();

	         $price_range->setMinPrice($min_price);
			 $price_range->setMaxPrice($max_price);
	         $price_range->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('subcategory_id')) );

	         $this->em->persist($price_range);

			 $this->em->flush();

	         $message = '<p class="message_success"  style="width: 250px; padding: 8px 5px;">Rang cene je dodat!</p>';

		 } else {
		 	$message = '<p class="message_error"  style="width: 250px; padding: 8px 5px;">Rang cene mora biti razlicit od 0!</p>';

		 }
         $this->price_ranges( $this->input->post('subcategory_id'), $message );
	}

	public function delete_price_range() {

         $id_list = explode(',', $this->input->post('items'));

		 $subcategory_id = $this->session->userdata('price_range_subcategory_id');

		 $ranges = $this->em->getRepository('models\Entities\Product\SubcategoryPriceRange')->getRangesBySubcategory($subcategory_id);

		 $max_prices = array();
		 $max_prices[] = $this->input->post('range');
		 foreach( $ranges as $range ) {
		 	if( $range->getMaxPrice() != 0 ) $max_prices[] = $range->getMaxPrice();
		 }

		 $max_prices = array_diff( $max_prices, $id_list );

		 sort( $max_prices );

		 $this->em->getRepository('models\Entities\Product\SubcategoryPriceRange')->deleteRangesBySubcategory($subcategory_id);

		 foreach( $max_prices as $key => $price ) {

			if( $price != 0 ) {
			 	if( $key == 0 ) {
			 		$min_price = 0;
					$max_price = $price;
			 	} else {
			 		$min_price = $max_prices[$key-1];
					$max_price = $price;
			 	}

				$price_range = new SubcategoryPriceRange();

		        $price_range->setMinPrice($min_price);
				$price_range->setMaxPrice($max_price);
		        $price_range->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $subcategory_id) );

		        $this->em->persist($price_range);
			}
		 }

		 $min_price = $max_prices[ count($max_prices) - 1 ];
		 $max_price = 0;

		 if( $min_price != 0 ) {
			 $price_range = new SubcategoryPriceRange();

	         $price_range->setMinPrice($min_price);
			 $price_range->setMaxPrice($max_price);
	         $price_range->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $subcategory_id) );

	         $this->em->persist($price_range);

			 $this->em->flush();
		 }

         $this->output->set_output('Uspešno ste obrisali rangove cena!');
    }

	public function change_subcategory_status( $id ) {

		$record = $this->em->getRepository('models\Entities\Product\Subcategory')->find($id);
        $record->getStatus() ? $record->setStatus(0) : $record->setStatus(1);

        $this->em->flush();

        $this->output->set_output($record->getStatus());
	}

	public function subcategory_brand_listing( $subcategory_id, $message = NULL ) {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';
		$this->resources['js'][] = 'checkbox';

        $this->gridParams['title']='Pregled brendova';

        $colModel['name'] = array( 'Ime', 200, TRUE, 'center', 1 );

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("product/subcategories/subcategory_brands_grid/" . $subcategory_id), $colModel, 'name', 'ASC', $this->gridParams);
		$data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id);
		//$data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->getSubcategoryBrands($data['subcategory']);
		$data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();


        $data['grid_title'] = 'Brendovi - ' . $data['subcategory']->getName() ;
        If (isset($message)){$data['message'] = $message;}
        $this->_render_view("product/subcategory/create_brands", $data);
     }

     public function subcategory_brands_grid( $subcategory_id ){

         $valid_fields = array( 'name' );
         $this->flexigrid->validate_post($this->gridParams['id'], 'name', 'ASC', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Product\Subcategory')->getSubcategoryBrands($criteria, $subcategory_id);

         $this->session->unset_userdata('edit_visited');
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

	 public function set_brands() {


		if($data['subcategory'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($this->input->post('subcategory_id'))) {

			  $data['subcategory']->getBrands()->clear();

			  foreach( $this->input->post('brand_list') as $key => $value ) {
              	$data['subcategory']->setBrand( $this->em->getReference('models\Entities\Product\Brand', $value) );
			  }

			  $this->em->persist($data['subcategory']);
              $this->em->flush();

			  redirect( 'product/subcategories/subcategory_brand_listing/'.$this->input->post('subcategory_id') );

         } else show_404();

	 }
}