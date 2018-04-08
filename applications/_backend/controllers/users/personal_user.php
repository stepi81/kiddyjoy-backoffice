<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

require_once 'abstract_user.php';

use models\Entities\User\Customer\Personal;

class Personal_User extends Abstract_User {

    public function __construct() {

        parent::__construct();
        
        $this->resources['js'] = array('checkbox');

        $this->type = USER_TYPE_PERSONAL;
    }

    public function listing() {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled privatnih korisnika';

        $colModel['first_name'] = array('Ime', 204, TRUE, 'center', 1);
        $colModel['last_name'] = array('Prezime', 204, TRUE, 'center', 1);
        $colModel['nick_name'] = array('Nadimak', 204, TRUE, 'center', 1);
        $colModel['email'] = array('E-mail', 200, TRUE, 'center', 1);
        $colModel['phone'] = array('Telefon', 120, TRUE, 'center', 1);
        $colModel['city'] = array('Grad', 204, TRUE, 'center', 1);
        $colModel['registration_date'] = array('Datum registracije', 120, TRUE, 'center', 1);
        $colModel['points'] = array('Poeni', 80, TRUE, 'center', 1);
        $colModel['actions'] = array('Detalji', 80, FALSE, 'center', 0);
		$colModel['friends'] = array('Prijatelji', 80, FALSE, 'center', 0);

        $buttons[] = array('Novi korisnik', 'add', 'grid_commands', site_url("users/personal_user/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši korisnika', 'delete', 'grid_commands', site_url("users/personal_user/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        
        $data['grid'] = build_grid_js('grid', site_url("users/personal_user/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);
        $data['grid_title'] = "Privatni korisnici";

        $this->_render_view("master/grid_view", $data);
    }

    public function save() {
        
        $this->resources['css'] = array('theme','datepicker');
       
        $user = new Personal();
        $user->setEmail( $this->input->post('email') );
        $user->setPassword( $this->input->post('password') );
        $user->setFirstName( $this->input->post('first_name') );
        $user->setLastName( $this->input->post('last_name') );
        $user->setNickname( $this->input->post('nickname') );
		$user->setPoints( 0 );
        if( $this->input->post('date_of_birth') ) {
            $user->setDateOfBirth(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('date_of_birth')))));
        }

        //if( $this->input->post('avatar_name') ) {
            $user->setAvatar( $this->create_avatar() );    
        //}
		
        $this->input->post('newsletter') ? $user->setNewsletter( $this->input->post('newsletter') ) : $user->setNewsletter( NULL );
        $this->input->post('profile_public') ? $user->setProfilePublic( $this->input->post('profile_public') ) : $user->setProfilePublic( NULL );
        $this->input->post('profile_history') ? $user->setProfileHistory( $this->input->post('profile_history') ) : $user->setProfileHistory( NULL );
        $this->input->post('profile_wishlist') ? $user->setProfileWishlist( $this->input->post('profile_wishlist') ) : $user->setProfileWishlist( NULL );
        $this->input->post('profile_friends') ? $user->setProfileFriends( $this->input->post('profile_friends') ) : $user->setProfileFriends( NULL );
        
        $user->setPhone( $this->input->post('phone') );
        $user->setAddress( $this->input->post('address') );
    
        if (preg_match('#\((.*?)\)#', $this->input->post('city'), $match)){
            
            if($this->em->getRepository('models\Entities\User\Customer')->getCitiesCheck($match[1])){
                $user->setPostalCode( $this->em->getReference('models\Entities\PostalCode', $match[1]) );

                try {
                    
                    $this->em->persist($user);
                    $this->em->flush();
                    
                    $data['message'] = '<p class="message_success">Novi privatni korisnik je uspešno kreiran!</p>';
                }
                catch( PDOException $e ) {
                    $data['message'] = '<p class="message_error">E-mail adresa već postoji u našoj bazi!</p>';
                }
                
            } else {
                $data['message'] = '<p class="message_error">Proverite uneti grad!</p>';
            }
             
        } else {
            $data['message'] = '<p class="message_error">Proverite uneti grad!</p>';
        } 
        $this->_render_view( 'user/personal/new_personal_user', $data );
    }

    public function edit($id) {
        
        $this->resources['css'] = array('theme','datepicker','flexigrid');
	$this->resources['js'][] = 'flexigrid';

        if ($data['user'] = $this->em->getRepository('models\Entities\User\Customer\Personal')->find($id)) {

            // TODO server validation

            //$data['user']->setEmail($this->input->post('email'));
            $data['user']->setPhone($this->input->post('phone'));
            $data['user']->setFirstName($this->input->post('first_name'));
            $data['user']->setLastName($this->input->post('last_name'));
            $data['user']->setAddress($this->input->post('address'));
            if( $this->input->post('password') ) $data['user']->setPassword( $this->input->post('password') );
            $data['user']->setNickname( $this->input->post('nickname') );
            if( $this->input->post('date_of_birth') ) {
                $data['user']->setDateOfBirth(new \DateTime(date( 'Y-m-d', strtotime( $this->input->post('date_of_birth')))));
            }
            //if( $this->input->post('avatar_name') ) {
                $data['user']->setAvatar( $this->create_avatar( $data['user']->getAvatar() ) );    
            //}
            
            //$data['user']->setPoints( $this->input->post('points') );
            
            $this->input->post('newsletter') ? $data['user']->setNewsletter( $this->input->post('newsletter') ) : $data['user']->setNewsletter( NULL );
            $this->input->post('profile_public') ? $data['user']->setProfilePublic( $this->input->post('profile_public') ) : $data['user']->setProfilePublic( NULL );
            $this->input->post('profile_history') ? $data['user']->setProfileHistory( $this->input->post('profile_history') ) : $data['user']->setProfileHistory( NULL );
            $this->input->post('profile_wishlist') ? $data['user']->setProfileWishlist( $this->input->post('profile_wishlist') ) : $data['user']->setProfileWishlist( NULL );
            $this->input->post('profile_friends') ? $data['user']->setProfileFriends( $this->input->post('profile_friends') ) : $data['user']->setProfileFriends( NULL );
            
            if ( preg_match('#\((.*?)\)#', $this->input->post('city'), $match)) {
                
                if($this->em->getRepository('models\Entities\User\Customer')->getCitiesCheck($match[1])) {
                
                    $data['user']->setPostalCode( $this->em->getReference('models\Entities\PostalCode', $match[1]) );
                
                    $this->em->persist($data['user']);
                    $this->em->flush();
    
                    $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
                } else {
                    $data['message'] = '<p class="message_error">Proverite uneti grad!</p>';  
                }
                   
            }else{
                $data['message'] = '<p class="message_error">Proverite uneti grad!</p>';  
            }
            
	    $data['grid'] = $this->shopping_listing( $id );
	    
            $this->_render_view('user/personal/edit_personal_user', $data);
        } 
        else show_404();
    }
    
    private function create_avatar( $avatar = NULL ) {
         
        if( !$_FILES['avatar']['size'] ) return $avatar;
        
        $upload_config['encrypt_name']         = TRUE;
        $upload_config['upload_path']         = SERVER_IMAGE_PATH.'users/large/';
        $upload_config['allowed_types']     = 'gif|jpg|png';
        $upload_config['max_size']            = '2048';
        $upload_config['remove_spaces']     = TRUE;
        
        $this->load->library('upload');
        
        $this->upload->initialize($upload_config);
        
        if( $this->upload->do_upload('avatar') ) {
            
            $image_data = $this->upload->data();
			
            copy($image_data['full_path'],SERVER_IMAGE_PATH.'users/small/'.$image_data['file_name']);
			
            $resize_config['image_library']     = 'gd2';
            $resize_config['source_image']        = $image_data['full_path'];
            $resize_config['width']                = 184;
            $resize_config['height']             = 184;
            $resize_config['maintain_ratio']    = TRUE;
            $resize_config['master_dim']        = $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
            
            $this->load->library('image_lib', $resize_config);
            
            if ( $this->image_lib->resize() ) {
                
                if( $avatar ) unlink( SERVER_IMAGE_PATH.'users/large/'.$avatar );
                
                $this->image_lib->clear();
                
                $crop_config['image_library']    = 'gd2';
                $crop_config['source_image']    = $image_data['full_path'];
                $crop_config['width']            = 184;
                $crop_config['height']             = 184;
                $crop_config['maintain_ratio']     = FALSE;
                
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
                
                //return $image_data['file_name'];
            }
            else return NULL;
        }
        else return NULL;
            
            $resize_config['image_library']     = 'gd2';
            $resize_config['source_image']        = SERVER_IMAGE_PATH.'users/small/'.$image_data['file_name'];//$image_data['full_path'];
            $resize_config['width']                = 43;
            $resize_config['height']             = 43;
            $resize_config['maintain_ratio']    = TRUE;
            $resize_config['master_dim']        = $image_data['image_width']/$image_data['image_height'] < $resize_config['width']/$resize_config['height'] ? 'width' : 'height';
            
			$this->image_lib->initialize($resize_config);

            if ( $this->image_lib->resize() ) {

		    if( $avatar ) unlink( SERVER_IMAGE_PATH.'users/small/'.$avatar );
                    
                $this->image_lib->clear();
                
                $crop_config['image_library']    = 'gd2';
                $crop_config['source_image']    = SERVER_IMAGE_PATH.'users/small/'.$image_data['file_name'];//$image_data['full_path'];
                $crop_config['width']            = 43;
                $crop_config['height']             = 43;
                $crop_config['maintain_ratio']     = FALSE;
                
                $imageSize = $this->image_lib->get_image_properties(SERVER_IMAGE_PATH.'users/small/'.$image_data['file_name'], TRUE);
                
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
                
                //return $image_data['file_name'];
            }
            else return NULL;

        return $image_data['file_name'];
    }


    public function friends($id) {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled prijatelja';

        $colModel['first_name'] = array('Ime', 204, TRUE, 'center', 1);
        $colModel['last_name'] = array('Prezime', 204, TRUE, 'center', 1);
        $colModel['nick_name'] = array('Nadimak', 204, TRUE, 'center', 1);
        $colModel['email'] = array('E-mail', 200, TRUE, 'center', 1);
        $colModel['phone'] = array('Telefon', 120, TRUE, 'center', 1);
        $colModel['city'] = array('Grad', 204, TRUE, 'center', 1);
        $colModel['date'] = array('Datum registracije', 120, TRUE, 'center', 1);
        $colModel['actions'] = array('Detalji', 80, FALSE, 'center', 0);
		$colModel['friends'] = array('Prijatelji', 80, FALSE, 'center', 0);

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        
        $data['grid'] = build_grid_js('grid', site_url("users/personal_user/gridFriends/".$id), $colModel, 'id', 'ASC', $this->gridParams);
        $data['grid_title'] = "Lista prijatelja";

		//echo var_dump($this->em->getRepository('models\Entities\User\Customer\UsersFriends')->find($id)->getFriend()->getFirstName());
		//echo var_dump($this->em->getRepository('models\Entities\User\Customer\Personal')->find($id)->getFriends());
		
		//$user = $this->em->getRepository('models\Entities\User\Customer\Personal')->find($id);
		//foreach( $user->getFriends() as $friend ) echo $friend->getFirstName();
		

        $this->_render_view("master/grid_view", $data);
    }
    
}

/* End of file admin_user.php */
/* Location: ./system/applications/_backend/controllers/users/personal_user.php */
