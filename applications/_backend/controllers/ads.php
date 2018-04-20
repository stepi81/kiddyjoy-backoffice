<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

use models\Entities\Advertising\Ad;
use models\Entities\Advertising\AdCampaing;
use models\Entities\Advertising\LinkType;
use models\Entities\Product;
use models\Entities\Product\Category;

class Ads extends MY_Controller {

    public $gridParams=array(
        'id'   => 'productsGrid',
        'width' => 'auto',
        'height' => 600,
        'rp' => 15,
        'rpOptions' => '[10,15,20,25,40]',
        'pagestat' => 'Prikaz: {from} do {to} / Ukupno: {total} reklama.',
        'pagetext' => 'Stranica',
        'outof' => 'od',
        'findtext' => 'Pronađi',
        'procmsg' => 'Obrada u toku molimo sačekajte...',
        'blockOpacity' => 0.5,
        'showTableToggleBtn' => true
    );

    public function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url'));

        $this->load->helper('flexigrid');
        $this->load->helper('upload');
        $this->load->helper('tinymce');

        $this->load->library('Flexigrid');

        $this->resources['css'] = array('datepicker');
        $this->resources['js'] = array('swfobject');

    }

    public function listing( $category_id ) {

        $ads_conf = unserialize(ADS_CONF);

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

		if($ads_conf[$category_id]['category'] == 'product')
        	$this->gridParams['title'] = 'Proizvod reklame - pregled';
		else $this->gridParams['title'] = ucfirst($ads_conf[$category_id]['category']) . ' ' . 'reklame - pregled';

        $category_id == 5 ?  $colModel['category'] = array('Meni kategorija', 150, TRUE, 'center', 1) : $colModel['position']=array('Pozicija', 50, TRUE, 'center', 1);
        $colModel['image'] = array('Thumb', 351, FALSE, 'center', 0);
        $colModel['price']  = array( 'Cena', 100, FALSE, 'center', 0 );
        $colModel['title'] = array('Ime reklame', 150, TRUE, 'center', 1);
        $colModel['status'] = array('Status', 50, TRUE, 'center', 0);
		$colModel['statusmobile'] = array('Status mobile', 50, TRUE, 'center', 0);
        $colModel['actions'] = array('Detalji', 50, FALSE, 'center', 0);
        $category_id == 6 ? $colModel['type'] = array('Tip reklame', 150, FALSE, 'center', 0) : '';

        $buttons[] = array('Nova reklama', 'add', 'grid_commands', site_url("ads/create/".$category_id));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši reklamu', 'delete', 'grid_commands', site_url("ads/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("ads/grid/" . $category_id), $colModel, 'id', 'ASC', $this->gridParams, $buttons);


        if ($ads_conf[$category_id]['category'] == 'filter') {
            $ads_conf[$category_id]['category'] = 'Body footer';
        } elseif ($ads_conf[$category_id]['category'] == 'filter_small') {
            $ads_conf[$category_id]['category'] = 'Body slide';
        }

		if($ads_conf[$category_id]['category'] == 'product')
        	$data['grid_title'] = 'Proizvod reklame';
		else $data['grid_title'] = ucfirst($ads_conf[$category_id]['category']) . ' ' . 'reklame';

        $this->_render_view("master/grid_view", $data);
    }

    public function grid( $category_id ) {

        $valid_fields=array('title', 'position', 'status');

        $this->flexigrid->validate_post($this->gridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Advertising\Ad')->getAd($criteria, $category_id);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }

    public function create( $category ) {

        $ads_conf = unserialize(ADS_CONF);
		$data['category_id'] = $category;
        $data['route_id'] = $ads_conf[$category]['route_id'];
        $data['category'] = $ads_conf[$category]['category'];

        if ($data['category'] == 'filter') {
            $data['category'] = 'Body footer';
        } elseif ($data['category'] == 'filter_small') {
            $data['category'] = 'Body slide';
        }

        $data['link_types'] = $this->em->getRepository('models\Entities\Advertising\LinkType')->getAllLinkTypes();
        $data['product_categories'] = $this->em->getRepository('models\Entities\Product')->getAllCategories();
        $data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();
        $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
        $data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
		$data['sections'] = unserialize(MENU_SECTIONS);
		$data['submenu_sections'] = unserialize(SUBMENU_SECTIONS);
        $this->resources['js'][] = 'checkbox';
        $this->_render_view('ads/create_ads', $data);
    }

    public function save() {

        $category = $this->input->post('category');
        $position = $this->input->post('position');
        $route_id = $this->input->post('route_id');

        $maxAd = $this->em->getRepository('models\Entities\Advertising\Ad')->getMaxAdPosition($route_id);
        $ads = $this->em->getRepository('models\Entities\Advertising\Ad')->getAdsByCategory($route_id);

        $maxPosition = $maxAd[0][1];

        if ($position) {
            if ($position >= $maxPosition) {
                $position = $maxPosition + 1;
            } else {
                foreach ($ads as $ad) {
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
		if( ( $this->input->post('vendor_id') == 10 || $this->session->userdata('application_id') == 10 ) ) {
        	$upload_config['upload_path'] = SERVER_IMAGE_PATH . 'ads/' . $category . '/mobile';
		} else {
			$upload_config['upload_path'] = SERVER_IMAGE_PATH . 'ads/' . $category;
		}
        $upload_config['allowed_types'] = 'png|jpg|swf|gif';
        $upload_config['remove_spaces'] = TRUE;
        $upload_config['encrypt_name'] = TRUE;

        $this->load->library('upload');

        $this->upload->initialize($upload_config);
        if ($this->upload->do_upload('image')) {

            $data=$this->upload->data();

            $image_type = $this->input->post('source_type');
            if( $this->input->post('source_type') != 2 ) {
            	if(($this->input->post('vendor_id') || $this->session->userdata('application_id')) && $category == 'slideshow')
					if( $this->input->post('vendor_id') == 10 || $this->session->userdata('application_id') == 10 ) {
						$this->resize($data, 'slideshow_mobile');
					} else {
                		$this->resize($data, 'slideshow_vendor');
					}
				else
					$category == 'popup' ? '' : $this->resize($data, $category);
            }
            $image_name = $data['file_name'];
            $image_path = $data['full_path'];

            $ad = new Ad();

            $ad->setTitle($this->input->post('title'));

			if( $this->input->post('start_date') ) {
	            $ad->setStartDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('start_date')))));
	        }

			if( $this->input->post('end_date') ) {
	            $ad->setEndDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('end_date')))));
	        }

            if ($this->input->post('link_type')){
                $ad->setLinkType($this->em->getReference('models\Entities\Advertising\LinkType', $this->input->post('link_type')));
            }

			if($this->session->userdata('application_id'))
				$ad->setVendor($this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id')));
			else {
		        if ($this->input->post('vendor_id')){
		            $ad->setVendor($this->em->getReference('models\Entities\Vendor', $this->input->post('vendor_id')));
		        }
			}

			if( $this->session->userdata('application_id') == 10 || $this->input->post('vendor_id') == 10 ) {
				$ad->setAPPID(3);
			} else {
				$ad->setAPPID(1);
			}

            $ad->setPosition($position);
            $ad->setSource($image_name);

			/*if ($this->upload->do_upload('image_mobile')) {

				$data_mobile = $this->upload->data();

				$this->resize($data_mobile, $category.'_mobile');

				$image_mobile_name = $data_mobile['file_name'];
            	$image_mobile_path = $data_mobile['full_path'];

				$ad->setSourceMobile($image_mobile_name);
			}*/
			if($_FILES["image_mobile"]) {
                if ($_FILES["image_mobile"]["size"] > 0) {

                    $upload_config['upload_path'] = SERVER_IMAGE_PATH . 'ads/' . $category . '/mobile/';

                    $upload_config['allowed_types'] = 'png|jpg|swf|gif';
                    $upload_config['remove_spaces'] = TRUE;
                    $upload_config['encrypt_name'] = TRUE;

                    $this->load->library('upload');

                    $this->upload->initialize($upload_config);

                    if ($this->upload->do_upload('image_mobile')) {

                        $image_mobile = $this->upload->data();

                        $this->resize($image_mobile, $category . '_mobile');

                        $ad->setSourceMobile($image_mobile['file_name']);
                    }
                }
            }

            $ad->setSourceType($image_type);
            $ad->setStatus($this->input->post('status'));
			$ad->setStatusMobile($this->input->post('status_mobile'));
            $ad->setAdsLink($this->input->post('link'));
            $ad->setCategoryId($this->em->getReference('models\Entities\Advertising\AdCategory', $route_id));


            if ($this->input->post('subcategory') != ""){
                $products = $this->em->getRepository('models\Entities\Product')->findBy(array('subcategory' => $this->input->post('subcategory')));
                foreach ($products as $product){
                         $ad->setProduct($this->em->getReference('models\Entities\Product', $product->getID()));
                }
            } else {
                if ($this->input->post('group') != "") {
                    $products = $this->em->getRepository('models\Entities\Product')->getProductsForAds( $this->input->post('group') );
                    foreach ($products as $product){
                            $ad->setProduct($this->em->getReference('models\Entities\Product', $product->getID()));
                    }
                }
            }

            if ($this->input->post('brand') !=''){

				$ad->getProducts()->clear();

                $query = $this->em->createQuery('SELECT p FROM models\Entities\Product p JOIN p.master m WHERE m.brand = :brand');
                $query->setParameter('brand', $this->input->post('brand'));
                $productsByBrand = $query->getResult();

                if (isset ($products) && count ($products) !=0){
                     foreach ($products as $product){
                         $productsArray[] = $product->getID();
                     }
                     foreach ($productsByBrand as $productByBrand ){
                         $productsByBrandArray[] = $productByBrand->getID();
                     }
                     $results = array_intersect($productsByBrandArray, $productsArray );

                     foreach ($results as $result){
                         $ad->setProduct($this->em->getReference('models\Entities\Product', $result));
                     }
                 } else {
                     foreach ($productsByBrand as $productByBrand){
                        $ad->setProduct($this->em->getReference('models\Entities\Product', $productByBrand->getID()));
                     }
                 }
             }

            /*if( $route_id == 8 ) {
                if( count( array_filter( array_unique(( $this->input->post( 'product_id' ) )) ) ) == $this->em->getRepository('models\Entities\Advertising\Ad')->getAdProducts($this->input->post( 'product_id'))) {
                    foreach( array_filter( array_unique( $this->input->post( 'product_id' ) ) ) as $value ) {
                        $ad->setProduct($this->em->getReference('models\Entities\Product', $value));
                    }
                }
            }*/


			/*if(( $route_id == 9 ) || ( $route_id == 10 )) {

				if( $route_id == 10 ) {
					$ad->setText($this->input->post( 'text_banner' ));
					$ad->setMinPrice($this->input->post( 'min_price' ) ? $this->input->post( 'min_price' ) : NULL);
					$ad->setMaxPrice($this->input->post( 'max_price' ) ? $this->input->post( 'max_price' ) : NULL);
				}

				if( $this->input->post( 'filter_ad_category' ) != '' )
					$ad->setCategory( $this->em->getReference('models\Entities\Product\Category', $this->input->post( 'filter_ad_category' )) );

				if($this->input->post( 'filter_ad_subcategory' ) == '')
					if( $this->input->post( 'filter_ad_group' ) != '' )
						$ad->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post( 'filter_ad_group' )) );

				if( $this->input->post( 'filter_ad_subcategory' ) != '' )
					$ad->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post( 'filter_ad_subcategory' )) );
			}*/


            $this->em->persist($ad);
            $this->em->flush();

            if ($this->input->post('subcategory') != "" || $this->input->post('group') != "" || $this->input->post('brand') != ""){
                $ad_campaing = new AdCampaing;
                $ad_campaing -> setAD($this->em->getReference('models\Entities\Advertising\Ad', $ad->getID()));
                if ($this->input->post('subcategory') != ""){
                        $ad_campaing->setCategory( $this->em->getReference('models\Entities\Product\Category', $this->input->post('product_category')));
                        $ad_campaing->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('subcategory')));
                } else {
                    if ($this->input->post('group') != "") {
                        $ad_campaing->setCategory( $this->em->getReference('models\Entities\Product\Category', $this->input->post('product_category')));
                        $ad_campaing->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('group')));
                    }
                }
                if ($this->input->post('brand') != ""){
                        $ad_campaing->setBrand( $this->em->getReference('models\Entities\Product\Brand', $this->input->post('brand')));
                }
                $this->em->persist($ad_campaing);
                $this->em->flush();
            }

            $data['message'] = "<p class='message_success'>Nova reklama je uspešno postavljena!</p>";
        } else {
            $data['message'] = "<p class='message_error'>Došlo je do greške! Molimo Vas proverite unetu reklamu.</p>";
        }
        $data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();
        $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
        $data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
        $data['link_types'] = $this->em->getRepository('models\Entities\Advertising\LinkType')->getAllLinkTypes();
        $data['route_id'] = $route_id;
        $data['category'] = $category;

        if ($data['category'] == 'filter') {
            $data['category'] = 'Body footer';
        } elseif ($data['category'] == 'filter_small') {
            $data['category'] = 'Body slide';
        }

		$data['sections'] = unserialize(MENU_SECTIONS);
		$data['submenu_sections'] = unserialize(SUBMENU_SECTIONS);
        $this->resources['js'][] = 'checkbox';
        $this->_render_view('ads/create_ads', $data);
    }

    public function details( $id ) {

        $data['category_id'] = $id;
        if ($data['ad'] = $this->em->getRepository('models\Entities\Advertising\Ad')->find($id)) {

			if ($data['ad']->getSubcategory()) $data['specifications'] = $data['ad']->getSubcategory()->getSpecifications();
			else $data['specifications'] = array();

            if( $data['ad']->getLinkType() && $data['ad']->getLinkType()->getID() == 1 ) {
                 $product = $this->em->getRepository('models\Entities\Product')->find($data['ad']->getAdsLink());
                 $data['product_price'] = $product->getMaster()->getPrice();
            }

            $data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();
            $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
            $data['subcategories'] = $this->em->getRepository('models\Entities\Product\Subcategory')->findAll();
            $data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
			$data['sections'] = unserialize(MENU_SECTIONS);
			$data['submenu_sections'] = unserialize(SUBMENU_SECTIONS);

            $data['link_types'] = $this->em->getRepository('models\Entities\Advertising\LinkType')->getAllLinkTypes();
            $data['product_categories'] = $this->em->getRepository('models\Entities\Product')->getAllCategories();
            $category = $data['ad']->getCategoryId();
            $data['route_id'] = $category;
            $ads_conf = unserialize(ADS_CONF);
            $data['category'] = $ads_conf[$category]['category'];

            if ($data['category'] == 'filter') {
                $data['category'] = 'Body footer';
            } elseif ($data['category'] == 'filter_small') {
                $data['category'] = 'Body slide';
            }

            $this->resources['js'][]='checkbox';
            /*if( $data['ad']->getCategoryId() == 8 ) {
                try {
                     $data['product_ids'] = array();
                     $product_list = $data['ad']->getProducts();
                     foreach( $product_list as $product ) {
                         $data['product_ids'][] = $product->getID();
                     }
                }
                catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                    $data['product_ids'] = array();
                }
            }
			if( ($data['ad']->getCategoryId() == 9) || ($data['ad']->getCategoryId() == 10) ) {
			}*/
            $this->_render_view('ads/edit_ads', $data);
        } else
            show_404();
    }

    public function change_status( $id ) {

        $ad=$this->em->getRepository('models\Entities\Advertising\Ad')->find($id);
        $ad->getStatus() ? $ad->setStatus(0) : $ad->setStatus(1);

        $this->em->flush();

        $this->output->set_output($ad->getStatus());
    }

	public function change_status_mobile( $id ) {

        $ad = $this->em->getRepository('models\Entities\Advertising\Ad')->find($id);
        $ad->getStatusMobile() ? $ad->setStatusMobile(0) : $ad->setStatusMobile(1);

        $this->em->flush();

        $this->output->set_output($ad->getStatusMobile());
    }

    public function check_id(){

        $check = $this->em->getRepository('models\Entities\Product')->checkID($this->input->post('id'));
        $this->output->set_output($check);
    }

    public function edit( $id ) {

        if ($data['ad'] = $this->em->getRepository('models\Entities\Advertising\Ad')->find($id)) {

            $route_id = $this->input->post('route_id');
            $position = $this->input->post('position');
            $category = $this->input->post('category');
            $old_position = $this->input->post('old_position');

            $maxAd = $this->em->getRepository('models\Entities\Advertising\Ad')->getMaxAdPosition($route_id);
            $ads = $this->em->getRepository('models\Entities\Advertising\Ad')->getAdsByCategory($route_id);

            $maxPosition = $maxAd[0][1];

            if ($position <= $old_position) {
                foreach ($ads as $ad) {
                    if ($ad->getPosition() >= $position && $ad->getPosition() < $old_position)
                        $ad->setPosition($ad->getPosition() + 1);
                    $this->em->persist($ad);
                    $this->em->flush();
                }
            } else {
                if ($position >= $maxPosition) {
                    $position=$maxPosition;
                }
                foreach ($ads as $ad) {
                    if ($ad->getPosition() <= $position && $ad->getPosition() > $old_position)
                        $ad->setPosition($ad->getPosition() - 1);
                    $this->em->persist($ad);
                    $this->em->flush();
                }
            }
            $old_image_name=$data['ad']->getImageName();
            if ($_FILES["image"]["size"] > 0) {

                if( $this->input->post('vendor_id') == 10 || $this->session->userdata('application_id') == 10 ) {
		        	$upload_config['upload_path'] = SERVER_IMAGE_PATH . 'ads/' . $category . '/mobile/';
				} else {
					$upload_config['upload_path'] = SERVER_IMAGE_PATH . 'ads/' . $category;
				}

                $upload_config['allowed_types'] = 'png|jpg|swf|gif';
                $upload_config['remove_spaces'] = TRUE;
                $upload_config['encrypt_name'] = TRUE;

                $this->load->library('upload');

                $this->upload->initialize($upload_config);

                if ($this->upload->do_upload('image')) {

					if( $this->input->post('vendor_id') == 10 || $this->session->userdata('application_id') == 10 ) {
                    	unlink(SERVER_PATH . '/assets/img/ads/' . $category . '/mobile/' . $old_image_name);
					} else {
						unlink(SERVER_PATH . '/assets/img/ads/' . $category . '/' . $old_image_name);
					}
                    $image = $this->upload->data();
                    if( $this->input->post('source_type') != 2 ) {
                    	if(($this->input->post('vendor_id') || $this->session->userdata('application_id')) && $category == 'slideshow')
                			if( $this->input->post('vendor_id') == 10 || $this->session->userdata('application_id') == 10 ) {
								$this->resize($image, 'slideshow_mobile');
							} else {
		                		//$this->resize($data, 'slideshow_vendor');
								$this->resize($image, 'slideshow_vendor');
							}
						else
                        	$category == 'popup' ? '' : $this->resize($image, $category);
                    }
                    $data['ad']->setSource($image['file_name']);
                } else {
                    $data['message']='<p class="message_error">Došlo je do greške! Molimo Vas proverite unetu sliku.</p>';
                    goto end;
                }
            }


			$old_mobile_image_name = $data['ad']->getImageMobileName();
            if ($_FILES["image_mobile"]["size"] > 0) {

		        $upload_config['upload_path'] = SERVER_IMAGE_PATH . 'ads/' . $category . '/mobile/';

                $upload_config['allowed_types'] = 'png|jpg|swf|gif';
                $upload_config['remove_spaces'] = TRUE;
                $upload_config['encrypt_name'] = TRUE;

                $this->load->library('upload');

                $this->upload->initialize($upload_config);

                if ($this->upload->do_upload('image_mobile')) {

					if(file_exists(SERVER_IMAGE_PATH . 'ads/' . $category . '/mobile/' . $old_mobile_image_name) )	{
                    	unlink(SERVER_PATH . '/assets/img/ads/' . $category . '/mobile/' . $old_mobile_image_name);
					}

                    $image_mobile = $this->upload->data();

					$this->resize($image_mobile, $category.'_mobile');

                    $data['ad']->setSourceMobile($image_mobile['file_name']);
                } else {
                    $data['message']='<p class="message_error">Došlo je do greške! Molimo Vas proverite unetu sliku.</p>';
                    goto end;
                }
            }


            $image_type = $this->input->post('source_type');
            $data['ad']->setSourceType($image_type);
            $data['ad']->setTitle($this->input->post('title'));

			if( $this->input->post('start_date') ) {
	            $data['ad']->setStartDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('start_date')))));
	        } else {
	        	$data['ad']->setStartDate(NULL);
	        }

			if( $this->input->post('end_date') ) {
	            $data['ad']->setEndDate(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('end_date')))));
	        } else {
	        	$data['ad']->setEndDate(NULL);
	        }

            $data['ad']->setAdsLink($this->input->post('link'));
            $data['ad']->setPosition($position);

            if ($this->input->post('link_type')){
                $data['ad']->setLinkType($this->em->getReference('models\Entities\Advertising\LinkType', $this->input->post('link_type')));
            }else{
                $data['ad']->setLinkType(NULL);
            }

			if($this->session->userdata('application_id'))
					$data['ad']->setVendor($this->em->getReference('models\Entities\Vendor', $this->session->userdata('application_id')));
				else {
	            if ($this->input->post('vendor_id')){
	                $data['ad']->setVendor($this->em->getReference('models\Entities\Vendor', $this->input->post('vendor_id')));
	            }else{
	                $data['ad']->setVendor(NULL);
	            }
			}

            $data['ad']->setStatus($this->input->post('status'));
			$data['ad']->setStatusMobile($this->input->post('status_mobile'));

            /*if( $data['ad']->getCategoryId() == 8 ) {
                if ($this->input->post( 'product_id' ) != ''){
                    if( count( array_filter( array_unique(( $this->input->post( 'product_id' ) )) ) ) == $this->em->getRepository('models\Entities\Advertising\Ad')->getAdProducts($this->input->post( 'product_id' )) ) {
                        $data['ad']->getProducts()->clear();

                        foreach( array_filter( array_unique(( $this->input->post( 'product_id' ) )) ) as $value ) {
                            $data['ad']->setProduct($this->em->getReference('models\Entities\Product', $value));
                            $data['message']='<p class="message_success">Reklama je uspesno izmenjena.</p>';
                        }
                    } else {
                        $data['message']='<p class="message_error">Doslo je do greske prilikom unosa ID Proizvoda, proverite ID listu.</p>';
                    }
                }
            }

			if(( $data['ad']->getCategoryId() == 9 ) || ( $data['ad']->getCategoryId() == 10 )) {

				if( $data['ad']->getCategoryId() == 10 ) {
					$data['ad']->setText($this->input->post( 'text_banner' ));
					$data['ad']->setMinPrice($this->input->post( 'min_price' ) ? $this->input->post( 'min_price' ) : NULL);
					$data['ad']->setMaxPrice($this->input->post( 'max_price' ) ? $this->input->post( 'max_price' ) : NULL);
				}

				if( $this->input->post( 'filter_ad_category' ) != '' )
					$ad->setCategory( $this->em->getReference('models\Entities\Product\Category', $this->input->post( 'filter_ad_category' )) );

				if($this->input->post( 'filter_ad_subcategory' ) == '')
					if( $this->input->post( 'filter_ad_group' ) != '' )
						$ad->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post( 'filter_ad_group' )) );

				if( $this->input->post( 'filter_ad_subcategory' ) != '' )
					$ad->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post( 'filter_ad_subcategory' )) );


				if ($this->input->post('ad_filters') != ''){
					$data['ad']->getFilters()->clear();
                    foreach(array_filter(array_unique(($this->input->post('ad_filters')))) as $value) {
                        $data['ad']->setFilter($this->em->getReference('models\Entities\Product\Filter', $value));
                    }
                 }
			}*/

            $this->em->persist($data['ad']);
            $this->em->flush();

			if( $data['ad']->getLinkType() && $data['ad']->getLinkType()->getID() == 1 ) {
                 $product = $this->em->getRepository('models\Entities\Product')->find($data['ad']->getAdsLink());
                 $data['product_price'] = $product->getMaster()->getPrice();
            }

            if ( $this->input->post('subcategory') != "" || $this->input->post('group') != "" || $this->input->post('brand') !='') {
                 $data['ad']->getProducts()->clear();
            }

            if ($this->input->post('subcategory') != ""){

                $products = $this->em->getRepository('models\Entities\Product')->findBy(array('subcategory' => $this->input->post('subcategory')));
                foreach ($products as $product){
                	$data['ad']->setProduct($this->em->getReference('models\Entities\Product', $product->getID()));
                }
            } else {

                if ($this->input->post('group') != "") {
                    $products = $this->em->getRepository('models\Entities\Product')->getProductsForAds( $this->input->post('group') );
                    foreach ($products as $product){
                    	$data['ad']->setProduct($this->em->getReference('models\Entities\Product', $product->getID()));
                    }
                }
            }

            if ($this->input->post('brand') !=''){

                $ad->getProducts()->clear();

                $query = $this->em->createQuery('SELECT p FROM models\Entities\Product p JOIN p.master m WHERE m.brand = :brand');
                $query->setParameter('brand', $this->input->post('brand'));
                $productsByBrand = $query->getResult();

                if (isset ($products)){
                     foreach ($products as $product){
                         $productsArray[] = $product->getID();
                     }
                     foreach ($productsByBrand as $productByBrand ){
                         $productsByBrandArray[] = $productByBrand->getID();
                     }
                     $results = array_intersect($productsByBrandArray, $productsArray );
                    foreach ($results as $result){
                         $data['ad']->setProduct($this->em->getReference('models\Entities\Product', $result));
                    }
                 } else {
                     foreach ($productsByBrand as $productByBrand){
                        $data['ad']->setProduct($this->em->getReference('models\Entities\Product', $productByBrand->getID()));
                     }
                 }
            }

            if ($this->input->post('subcategory') != "" || $this->input->post('group') != "" || $this->input->post('brand') != "" ){

                    if (!$ad_campaing = $this->em->getRepository('models\Entities\Advertising\AdCampaing')->findOneBy(array('ad' => $id))) {
                            $ad_campaing = new AdCampaing;
                            $ad_campaing -> setAD($this->em->getReference('models\Entities\Advertising\Ad', $ad->getID()));
                    }
                        if ($this->input->post('subcategory') != ""){
                                $ad_campaing->setCategory( $this->em->getReference('models\Entities\Product\Category', $this->input->post('product_category')));
                                $ad_campaing->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('subcategory')));
                        } else {
                            if ($this->input->post('group') != "") {
                                $ad_campaing->setCategory( $this->em->getReference('models\Entities\Product\Category', $this->input->post('product_category')));
                                $ad_campaing->setSubcategory( $this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('group')));
                            }
                        }
                        if ($this->input->post('brand') != ""){
                                $ad_campaing->setBrand( $this->em->getReference('models\Entities\Product\Brand', $this->input->post('brand')));
                        }
                    $this->em->persist($ad_campaing);
                    $this->em->flush();
                }

            end:
            $data['link_types'] = $this->em->getRepository('models\Entities\Advertising\LinkType')->getAllLinkTypes();
            $data['product_categories'] = $this->em->getRepository('models\Entities\Product')->getAllCategories();
            $data['route_id'] = $route_id;
            $data['category'] = $category;

            if ($data['category'] == 'filter') {
                $data['category'] = 'Body footer';
            } elseif ($data['category'] == 'filter_small') {
                $data['category'] = 'Body slide';
            }

            $data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();
            $data['vendors'] = $this->em->getRepository('models\Entities\Vendor')->findAll();
            $data['subcategories'] = $this->em->getRepository('models\Entities\Product\Subcategory')->findAll();
            $data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
			$data['sections'] = unserialize(MENU_SECTIONS);
			$data['submenu_sections'] = unserialize(SUBMENU_SECTIONS);

			if ($data['ad']->getSubcategory()) $data['specifications'] = $data['ad']->getSubcategory()->getSpecifications();
			else $data['specifications'] = array();

            /*if( $data['ad']->getCategoryId() == 8 ) {
                try {
                     $data['product_ids'] = array();
                     $product_list = $data['ad']->getProducts();
                     foreach( $product_list as $product ) {
                         $data['product_ids'][] = $product->getID();
                     }
                }
                catch( \Doctrine\ORM\EntityNotFoundException $e ) {
                    echo $e->getMessage();
                    $data['product_ids'] = array();
                }
            }*/

            $this->resources['js'][] = 'checkbox';
            $this->_render_view( 'ads/edit_ads', $data );
        } else {
            show_404();
        }
    }

    public function delete() {

        $id_list=explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $ad = $this->em->getRepository('models\Entities\Advertising\Ad')->find($id);
            $ads = $this->em->getRepository('models\Entities\Advertising\Ad')->getAdsByCategory($ad->getCategoryId());
            foreach ($ads as $ad_data) {
                $oldPosition = $ad_data->getPosition();
                if ($ad->getPosition() < $ad_data->getPosition()) {
                    $ad_data->setPosition($oldPosition - 1);
                    $this->em->persist($ad_data);
                    $this->em->flush();
                }
            }
            /*if( $ad->getCategoryId() == 8 ) {
                $ad->getProducts()->clear();
            }*/

        }
        $this->em->getRepository('models\Entities\Advertising\Ad')->deleteAd($id_list);
        $this->output->set_output(TRUE);
    }

    public function checkExistence(){

         $this->output->set_output($this->em->getRepository('models\Entities\Advertising\Ad')->checkForExistence($this->input->post('product_id')));
    }

    public function resize($image = NULL, $category = NULL) {

        switch ($category) {

            case 'slideshow':
                $width = 985;
                $height = 340;
                break;

			case 'slideshow_vendor':
                $width = 985;
                $height = 340;
                break;

			case 'slideshow_mobile':
                $width = 640;
                $height = 360;
                break;

			case 'body_mobile':
                $width = 640;
                $height = 360;
                break;

            case 'footer':
                $width = 285;
                $height = 127;
                break;

            case 'central':
                //$width = $image['image_width'];
                //$height = $image['image_height'];
				$width = 983;
                $height = 208;
                break;

			case 'body':
                //$width = $image['image_width'];
                //$height = $image['image_height'];
				$width = 983;
                $height = 208;
                break;

            case 'product':
                $width = 432;
                $height = $image['image_height'];
                break;

            case 'menu':
                $width = 164;
                $height = 380;
                break;

            case 'top':
                $width = 984;
                $height = 221;
                break;

			case 'filter':
                $width = null;
                $height = null;
                break;

			case 'filter_small':
                $width = null;
                $height = null;
                break;

            default:
                $width = null;
                $height = null;
                break;
        }

        if($width) {
            $img_config['image_library'] = 'gd2';
            $img_config['source_image'] = $image['full_path'];
            $img_config['width'] = $width;
            $img_config['height'] = $height;
            $img_config['master_dim'] = $image['image_width'] / $image['image_height'] < $width / $height ? 'width' : 'height';

            $this->load->library('image_lib', $img_config);

            if ($this->image_lib->resize()) {

                //echo 'usao u resize';

                $this->image_lib->clear();

                $crop_config['image_library'] = 'gd2';
                $crop_config['source_image'] = $image['full_path'];
                $crop_config['width'] = $width;
                $crop_config['height'] = $height;
                $crop_config['maintain_ratio'] = FALSE;

                $imageSize = $this->image_lib->get_image_properties($image['full_path'], TRUE);

                switch ($img_config['master_dim']) {
                    case 'width':
                        $crop_config['y_axis'] = ($imageSize['height'] - $height) / 2;
                        break;
                    case 'height':
                        $crop_config['x_axis'] = ($imageSize['width'] - $width) / 2;
                        break;
                }
                $this->image_lib->initialize($crop_config);

                $this->image_lib->crop();

            }
        }
   }
}

/* End of file ads.php */
/* Location: ./system/applications/_backend/controllers/ads.php */
