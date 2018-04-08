 <div id="content">
    <ul>
        <li>
            <h2>Novi poslovni korisnik</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adminUserForm" method="post" action="<?= site_url( 'users/business_user/save' ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Master ID:</label>
                                <span class="inputField wide"><input type="text" name="master_id" /></span>
                            </li>
                            <li>
                                <label>E-mail:</label>
                                <span class="inputField wide"><input type="text" name="email" class="required"/></span>
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
                                <label>Ime kompanije:</label>
                                <span class="inputField wide"><input type="text" name="company_name" class="required" /></span>
                            </li>
                            <li>
                                <label>PIB:</label>
                                <span class="inputField wide"><input type="text" name="tax_number" class="required" /></span>
                            </li>
                            <li>
                                <label>Tekući račun:</label>
                                <span class="inputField wide"><input type="text" name="current_account" /></span>
                            </li>
                            <li>
                                <label>Telefon:</label>
                                <span class="inputField wide"><input type="text" name="phone" class="required" /></span>
                            </li>
                            <li>
                                <label>Fax:</label>
                                <span class="inputField wide"><input type="text" name="fax" class="required" /></span>
                            </li>
                            <li>
                                <label>Osoba za kontakt:</label>
                                <span class="inputField wide"><input type="text" name="contact_person" class="required" /></span>
                            </li>
                            <li>
                                <label>Adresa:</label>
                                <span class="inputField wide"><input type="text" name="address" class="required" /></span>
                            </li>
                            <li>
                                <label>Grad:</label>
                                <span class="inputField wide"><input type="text" id="city" name="city" class="required" /></span>
                            </li>
                            <li>
                                <label>Newsletter:</label>
                                <input type="radio" name="newsletter" value="1"/>
                                <small>Da</small>
                                <input type="radio" name="newsletter" value="0"/>
                                <small>Ne</small>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                           
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'users/business_user/listing') ?>" />
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
