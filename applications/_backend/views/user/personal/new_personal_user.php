 <div id="content">
    <ul>
        <li>
            <h2>Novi privatni korisnik</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adminUserForm" method="post" action="<?= site_url( 'users/personal_user/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm half" style="clear:both">

                        <ul>
                            <li>
                                <label>E-mail:</label>
                                <span class="inputField wide"><input type="text" name="email" class="required" /></span>
                            </li>
                            <li>
                                <label>Lozinka:</label>
                                <span class="inputField wide"><input type="password" name="password" class="required" /></span>
                            </li>
                            <li>
                                <label>Ponovite lozinku:</label>
                                <span class="inputField wide"><input type="password" name="repassword" class="required" /></span>
                            </li>
                            <li>
                                <label>Ime:</label>
                                <span class="inputField wide"><input type="text" name="first_name" class="required" /></span>
                            </li>
                            <li>
                                <label>Prezime:</label>
                                <span class="inputField wide"><input type="text" name="last_name" class="required" /></span>
                            </li>
                            <li>
                                <label>Nadimak:</label>
                                <span class="inputField wide"><input type="text" name="nickname"  /></span>
                            </li>
                            <li>
                                <label>Telefon:</label>
                                <span class="inputField wide"><input type="text" name="phone"  class="required" /></span>
                            </li>
                            <li>
                                <label>Adresa:</label>
                                <span class="inputField wide"><input type="text" name="address"  class="required" /></span>
                            </li>
                            <li>
                                <label>Grad:</label>
                                <span class="inputField wide"><input type="text" id="city" name="city" class="required" /></span>
                            </li>
                            <li>
                                <label>Datum rođenja:</label>
                                <span class="inputField wide"><input type="text" name="date_of_birth" id="date_of_birth" /></span>
                            </li>
                            <li>
                                <label>Avatar:</label>
                                <span class="inputField wide"><input type="text" name="avatar_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="avatar" />
                                </div>
                            </li>
                        </ul>
                    </fieldset>


                    <fieldset class="sectionForm half" style="padding-left: 30px;">
                        <ul>
                            <li>
                                <label>Newsletter:</label>
                                <input type="radio" name="newsletter" value="1"/>
                                <small>Da</small>
                                <input type="radio" name="newsletter" value="0"/>
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Prikaži profil:</label>
                                <input type="radio" name="profile_public" value="1"/>
                                <small>Da</small>
                                <input type="radio" name="profile_public" value="0"/>
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Prikaži listu želja:</label>
                                <input type="radio" name="profile_wishlist" value="1"/>
                                <small>Da</small>
                                <input type="radio" name="profile_wishlist" value="0"/>
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Prikaži listu prijatelja:</label>
                                <input type="radio" name="profile_friends" value="1"/>
                                <small>Da</small>
                                <input type="radio" name="profile_friends" value="0"/>
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Prikaži istoriju kupovine:</label>
                                <input type="radio" name="profile_history" value="1"/>
                                <small>Da</small>
                                <input type="radio" name="profile_history" value="0"/>
                                <small>Ne</small>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'users/personal_user/listing') ?>" />
                             </span> 
                        <span class="button save">
                            <input type="button" value="Kreiraj" onclick="validateForm('adminUserForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>