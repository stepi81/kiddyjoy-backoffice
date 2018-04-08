 <div id="content">
 
    <ul>
        <li>
            <h2>Detalji privatnog korisnika</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adminUserForm" method="post" action="<?= site_url( 'users/personal_user/edit/'.$user->getID() ) ?>" enctype="multipart/form-data">
                    
                    <fieldset class="sectionForm half" style="clear:both">

                        <ul>
                            <li>
                                <label>Poeni:</label>
                                <span class="inputField wide"><input type="text" name="points" class="points" value="<?= $user->getPoints(); ?>"/></span>
                            </li>
                            <li>
                                <label>E-mail:</label>
                                <span class="inputField wide"><input type="text" name="email" class="required" readonly="readonly" value="<?= $user->getEmail(); ?>"/></span>
                            </li>
                            <li>
                                <label>Lozinka:</label>
                                <span class="inputField wide"><input type="password" name="password" class="required account" /></span>
                            </li>
                            <li>
                                <label>Ponovite lozinku:</label>
                                <span class="inputField wide"><input type="password" name="repassword" class="required account" /></span>
                            </li>
                            <li>
                                <label>Ime:</label>
                                <span class="inputField wide"><input type="text" name="first_name" class="required" value="<?= $user->getFirstName(); ?>"/></span>
                            </li>
                            <li>
                                <label>Prezime:</label>
                                <span class="inputField wide"><input type="text" name="last_name" class="required" value="<?= $user->getLastName(); ?>"/></span>
                            </li>
                            <li>
                                <label>Nadimak:</label>
                                <span class="inputField wide"><input type="text" name="nickname" value="<?= $user->getNickname(); ?>" /></span>
                            </li>
                            <li>
                                <label>Telefon:</label>
                                <span class="inputField wide"><input type="text" name="phone" class="required" value="<?= $user->getPhone(); ?>" /></span>
                            </li>
                            <li>
                                <label>Adresa:</label>
                                <span class="inputField wide"><input type="text" name="address" class="required" value="<?= $user->getAddress(); ?>" /></span>
                            </li>
                            <li>
                                <label>Grad:</label>
                                <?php if( $user->getCityName() != '' ) { ?>
                                <span class="inputField wide"><input type="text" name="city" class="required" id="city" value="<?= $user->getCityName() . '(' .  $user->getCityCode() . ')'; ?>" /></span>
                                <?php } else { ?>
                                <span class="inputField wide"><input type="text" name="city" class="required" id="city" value="" /></span>
                                <?php } ?>
                            </li>
                            <li>
                                <label>Datum rođenja:</label>
                                <span class="inputField wide"><input type="text" name="date_of_birth" id="date_of_birth" value="<?php echo isset($user)? $user->getFormatedDateOfBirth() : ''?>"/></span>
                            </li>
                            <li>
                                <label>Datum registracije:</label>
                                <span class="inputField wide"><input type="text" name="registration_date" id="registration_date" class="required" value="<?php echo isset($user)? $user->getFormatedRegistrationDate() : ''?>"/></span>
                            </li>
                            <?php if( $user->getFormatedLastLoginDate() != '30/11/-0001 00:00' ) { ?>
                            <li>
                                <label>Poslednji login:</label>
                                <span class="inputField wide"><input type="text" name="last_login_date" id="last_login_date" class="required" value="<?php echo isset($user)? $user->getFormatedLastLoginDate() : ''?>"/></span>
                            </li>
                            <?php } ?>
                            <li>
                                <label>Avatar:</label>
                                <span class="inputField wide"><input type="text" name="avatar_name" disabled="disabled" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="avatar" />
                                </div>
                            </li>
                        </ul>
                    </fieldset>


                    <fieldset class="sectionForm half" style="padding-left: 30px;">
                        <ul>
                        	<li>
                                <img src="<?= $user->getAvatarURL() ?>" alt="KiddyJoy Avatar" />
                            </li>
                            <li>
                                <label>Newsletter:</label>
                                <input type="radio" name="newsletter" value="1" <?= $user->getNewsletter() ? ' checked="checked"' : '' ?> />
                                <small>Da</small>                               
                                <input type="radio" name="newsletter" value="0" <?= $user->getNewsletter() ? '' : ' checked="checked"' ?> />
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Prikaži profil:</label>
                                <input type="radio" name="profile_public" value="1" <?= $user->getProfilePublic() ? ' checked="checked"' : '' ?> />
                                <small>Da</small>
                                <input type="radio" name="profile_public" value="0" <?= $user->getProfilePublic() ? '' : ' checked="checked"' ?> />
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Prikaži listu želja:</label>
                                <input type="radio" name="profile_wishlist" value="1" <?= $user->getProfileWishlist() ? ' checked="checked"' : '' ?> />
                                <small>Da</small>
                                <input type="radio" name="profile_wishlist" value="0" <?= $user->getProfileWishlist() ? '' : ' checked="checked"' ?> />
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Prikaži listu prijatelja:</label>
                                <input type="radio" name="profile_friends" value="1" <?= $user->getProfileFriends() ? ' checked="checked"' : '' ?> />
                                <small>Da</small>
                                <input type="radio" name="profile_friends" value="0" <?= $user->getProfileFriends() ? '' : ' checked="checked"' ?> />
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Prikaži istoriju kupovine:</label>
                                <input type="radio" name="profile_history" value="1" <?= $user->getProfileHistory() ? ' checked="checked"' : '' ?> />
                                <small>Da</small>
                                <input type="radio" name="profile_history" value="0" <?= $user->getProfileHistory() ? '' : ' checked="checked"' ?> />
                                <small>Ne</small>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'users/personal_user/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('adminUserForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
    
    <ul>
        <li>
            <h2>Istorijat kupovine</h2>
            <a href="#" class="collapse">Collapse</a>
			
            <div class="innerContent">
            	<label>Poeni:</label>
            	<span class="inputField short"><input type="text" class="points" value="<?= $user->getPoints(); ?>" disabled="disabled"/></span>
                <?= $grid ?>
                <table id="grid" style="display:none"></table>
            </div>
        </li>
    </ul>
    
</div>
