 <div id="content">
    <ul>
        <li>
            <h2>Detalji poslovnog korisnika</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adminUserForm" method="post" action="<?= site_url( 'users/business_user/edit/'.$user->getID() ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Master ID:</label>
                                <span class="inputField wide"><input type="text" name="master_id" value="<?= $user->getMasterID(); ?>"/></span>
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
                                <label>Ime kompanije:</label>
                                <span class="inputField wide"><input type="text" name="company_name" class="required" value="<?= $user->getCompanyName(); ?>"/></span>
                            </li>
                            <li>
                                <label>PIB:</label>
                                <span class="inputField wide"><input type="text" name="tax_number" class="required" value="<?= $user->getTaxNumber(); ?>"/></span>
                            </li>
                            <li>
                                <label>Tekući račun:</label>
                                <span class="inputField wide"><input type="text" name="current_account" value="<?= $user->getCurrentAccount(); ?>"/></span>
                            </li>
                            <li>
                                <label>Telefon:</label>
                                <span class="inputField wide"><input type="text" name="phone" value="<?= $user->getPhone(); ?>"/></span>
                            </li>
                            <li>
                                <label>Fax:</label>
                                <span class="inputField wide"><input type="text" name="fax" value="<?= $user->getFax(); ?>"/></span>
                            </li>
                            <li>
                                <label>Osoba za kontakt:</label>
                                <span class="inputField wide"><input type="text" name="contact_person" value="<?= $user->getContactPerson(); ?>"/></span>
                            </li>
                            <li>
                                <label>Adresa:</label>
                                <span class="inputField wide"><input type="text" name="address" value="<?= $user->getAddress(); ?>"/></span>
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
                                <label>Datum registracije:</label>
                                <span class="inputField wide"><input type="text" name="registration_date" id="registration_date" class="required" value="<?php echo isset($user)? $user->getFormatedRegistrationDate() : ''?>"/></span>
                            </li>
                            <li>
                                <label>Poslednji login:</label>
                                <span class="inputField wide"><input type="text" name="last_login_date" id="last_login_date" class="required" value="<?php echo isset($user)? $user->getFormatedLastLoginDate() : ''?>"/></span>
                            </li>
                            <li>
                                <label>Newsletter:</label>
                                <input type="radio" name="newsletter" value="1"<?= $user->getNewsletter() ? ' checked="checked"' : '' ?>/>
                                <small>Da</small>
                                <input type="radio" name="newsletter" value="0"<?= $user->getNewsletter() ? '' : ' checked="checked"' ?>/>
                                <small>Ne</small>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'users/business_user/listing') ?>" />
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
</div>