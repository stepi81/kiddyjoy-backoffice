 <div id="content">
    <ul>
        <li>
            <h2>Novi administrator</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adminUserForm" method="post" action="<?= site_url( 'users/admin_user/save' ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                        	<li>
                            	<label>Grupa:</label>
                                <div class="customComboHolder">
                                    <div>Izaberi</div>
                                    <select name="group_id" class="required">
                                    	<option value="" >Izaberi</option>
                                    	<?php foreach( $groups as $group ): ?>
                                        <option value="<?= $group->getID() ?>"><?= $group->getName() ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label>E-mail:</label>
                                <span class="inputField wide"><input type="text" name="email" class="required email" /></span>
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
                                <label>Telefon:</label>
                                <span class="inputField wide"><input type="text" name="phone" /></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'users/admin_user/listing') ?>" />
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