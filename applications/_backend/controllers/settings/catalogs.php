<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

use models\Entities\Catalog;

class Catalogs extends MY_Controller {

    public $gridParams=array(
        'id'                 => 'productsGrid',
        'width'              => 'auto', 
        'height'             => 400, 
        'rp'                 => 15, 
        'rpOptions'          => '[10,15,20,25,40]', 
        'pagestat'           => 'Prikaz: {from} do {to} / Ukupno: {total} kataloga.', 
        'pagetext'           => 'Stranica', 
        'outof'              => 'od', 
        'findtext'           => 'Pronađi', 
        'procmsg'            => 'Obrada u toku, molimo sačekajte...', 
        'blockOpacity'       => 0.5, 
        'showTableToggleBtn' => true);

    public function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url'));

        $this->load->helper('flexigrid');
        $this->load->helper('upload');
        $this->load->helper('tinymce');

        $this->load->library('Flexigrid');

        $this->resources['css']=array();
        $this->resources['js']=array();

    }

    public function listing() {

        $this->resources['css'][]='flexigrid';
        $this->resources['js'][]='flexigrid';
        
        $this->gridParams['title']='Pregled svih kataloga';

        $colModel['image']=array('Thumb', 234, FALSE, 'center', 0);
        $colModel['date']=array('Datum', 100, TRUE, 'center', 1);
        $colModel['title']=array('Naslov', 100, TRUE, 'center', 1);
        $colModel['edition']=array('Edicija', 200, TRUE, 'center', 1);
        $colModel['status']=array('Status', 50, TRUE, 'center', 0);
        $colModel['actions']=array('Detalji', 50, FALSE, 'center', 0);

        $buttons[]=array('Novi katalog', 'add', 'grid_commands', site_url("settings/catalogs/create"));
        $buttons[]=array('separator');
        $buttons[]=array('Obriši katalog', 'delete', 'grid_commands', site_url("settings/catalogs/delete"));
        $buttons[]=array('separator');
        $buttons[]=array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[]=array('separator');
        $buttons[]=array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[]=array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        $data['grid']=build_grid_js('grid', site_url("settings/catalogs/grid"), $colModel, 'id', 'DESC', $this->gridParams, $buttons);

        $data['grid_title']="Katalozi";
        $this->_render_view("master/grid_view", $data);
    }

    public function grid() {

        $valid_fields=array('id', 'date', 'title', 'edition', 'status');

        $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'DESC', $valid_fields);
        $criteria=$this->flexigrid->get_criteria();
        $records=$this->em->getRepository('models\Entities\Catalog')->getCatalog($criteria);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }

    public function create() {

        $this->resources['js'][]='checkbox';
        $this->_render_view('settings/catalog/create_catalog');
    }

    public function save() {

        $upload_config['upload_path'] = SERVER_IMAGE_PATH . 'catalogs';
        $upload_config['allowed_types']='png|jpg';
        $upload_config['encrypt_name']=TRUE;

        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);

        if ($this->upload->do_upload('image')) {

            $data=$this->upload->data();
            $image_name=$data['file_name'];
            $image_path=$data['full_path'];
            $this->resize($data);
                        
            $filename = SERVER_PATH . 'download/' . $this->input->post('catalog_name');
                        
            if (file_exists($filename)) {

                $catalog=new Catalog();

                $catalog->setTitle($this->input->post('title'));
                $catalog->setEdition($this->input->post('edition'));
                $catalog->setPDF($this->input->post('catalog_name'));
                $catalog->setImage($image_name);
                $catalog->setStatus($this->input->post('status'));
                $catalog->setDate();

                $this->em->persist($catalog);
                $this->em->flush();

                $data['message']='<p class="message_success">Novi katalog je uspešno postavljen!</p>';
            } else {
                $data['message']='<p class="message_error">Došlo je do greške! Katalog ne postoji na serveru.</p>';
                unlink($image_path);
            }

        } else {
            $data['message']='<p class="message_error">Došlo je do greške! Molimo Vas proverite unetu sliku.</p>';
        }
        $this->resources['js'][]='checkbox';
        $this->_render_view('settings/catalog/create_catalog', $data);
    }

    public function details( $id ) {

        if ($data['catalog']=$this->em->getRepository('models\Entities\Catalog')->find($id)) {
            $this->resources['js'][]='checkbox';
            $this->_render_view('settings/catalog/edit_catalog', $data);
        } else
            show_404();
    }

    public function change_status( $id ) {

        $catalog=$this->em->getRepository('models\Entities\Catalog')->find($id);
        $catalog->getStatus() ? $catalog->setStatus(0) : $catalog->setStatus(1);

        $this->em->flush();

        $this->output->set_output($catalog->getStatus());
    }

    public function edit( $id ) {
            
        if ($data['catalog']=$this->em->getRepository('models\Entities\Catalog')->find($id)) {
           
            $upload_config['upload_path'] = SERVER_IMAGE_PATH . 'catalogs/';
            $upload_config['allowed_types'] = 'png|jpg';
            $upload_config['remove_spaces'] = TRUE;
            $upload_config['encrypt_name'] = TRUE;        
    
            $this->load->library('upload');
        
            $this->upload->initialize($upload_config);

            $old_PDF_name = $data['catalog']->getPDFName();
            $old_image_name = $data['catalog']->getImageName();

            if ($_FILES["image"]["size"] > 0) {
                $this->upload->initialize($upload_config);
                if ($this->upload->do_upload('image')) {    
                    unlink(SERVER_IMAGE_PATH . 'catalogs/' . $old_image_name);
                    $image = $this->upload->data();
                    $this->resize($image);
                    $data['catalog']->setImage($image['file_name']);
                } else {
                    $data['message']='<p class="message_error">Došlo je do greške! Molimo Vas proverite unetu sliku.</p>';
                    goto end;
                }
            }
            $filename = SERVER_PATH . 'download/' . $this->input->post('catalog_name');

            if (file_exists($filename)) {
                   
                    $data['catalog']->setPDF($this->input->post('catalog_name'));
                } else {
                    $data['message']='<p class="message_error">Došlo je do greške! Katalog ne postoji na serveru.</p>';
                    goto end;
                }
            
            $data['catalog']->setTitle($this->input->post('title'));
            $data['catalog']->setEdition($this->input->post('edition'));
            $data['catalog']->setStatus($this->input->post('status'));
            $data['catalog']->setDate();

            $this->em->persist($data['catalog']);
            $this->em->flush();

            $data['message']='<p class="message_success">Sve izmene su uspešno izvršene!</p>';
            end:
            $this->resources['js'][]='checkbox';
            $this->_render_view('settings/catalog/edit_catalog', $data);
        } else
            show_404();
    }

     public function delete() {
        
        $id_list=explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\Catalog')->deleteCatalogs($id_list);
        $this->output->set_output( TRUE );
     }
     
     public function resize($image = NULL) {

        $img_config['image_library']    = 'gd2';
        $img_config['source_image']     = $image['full_path'];
        $img_config['width']            = 135;
        $img_config['height']           = 124;
        $img_config['master_dim']       = $image['image_width']/$image['image_height'] < $img_config['width']/$img_config['height'] ? 'width' : 'height';
        
        $this->load->library('image_lib', $img_config); 

        if ($this->image_lib->resize()){
        
            $this->image_lib->clear();
            
            $crop_config['image_library']    = 'gd2';
            $crop_config['source_image']     = $image['full_path'];
            $crop_config['width']            = 135;
            $crop_config['height']           = 124;
            $crop_config['maintain_ratio']   = FALSE;           
            
            $imageSize = $this->image_lib->get_image_properties($image['full_path'], TRUE);
            
            switch( $img_config['master_dim'] ) {
                    case 'width':
                        $crop_config['y_axis'] = ($imageSize['height'] - $crop_config['height']) / 2;
                        break;
                    case 'height':
                        $crop_config['x_axis'] = ($imageSize['width'] - $crop_config['width']) / 2;
                        break;
                }
            $this->image_lib->initialize($crop_config);
                
            $this->image_lib->crop(); 
       }
    }
}

/* End of file catalogs.php */
/* Location: ./system/applications/_backend/controllers/settings/catalogs.php */
