 <div id="content">
    <ul>
        <li>
            <h2>Moj nalog</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="userAccountForm" method="post" action="<?= site_url( 'users/account/edit' ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>E-mail:</label>
                                <span class="inputField wide"><input type="text" name="email" class="required email" readonly="readonly" value="<?= $user->getEmail(); ?>" /></span>
                            </li>
                            <li>
                                <label>Lozinka:</label>
                                <span class="inputField wide"><input type="password" name="password" class="required account" /></span>
                            </li>
                            <li>
                                <label>Ponovite lozinku:</label>
                                <span class="inputField wide"><input type="password" class="required account" /></span>
                            </li>
                            <li>
                                <label>Ime:</label>
                                <span class="inputField wide"><input type="text" name="first_name" id="first_name" value="<?= $user->getFirstName(); ?>" /></span>
                            </li>
                            <li>
                                <label>Prezime:</label>
                                <span class="inputField wide"><input type="text" name="last_name" id="last_name" value="<?= $user->getLastName(); ?>" /></span>
                            </li>
                            <li>
                                <label>Telefon:</label>
                                <span class="inputField wide"><input type="text" name="phone" value="<?= $user->getPhone(); ?>" /></span>
                            </li>
                            <li>
                                <label>Datum registracije:</label>
                                <span class="inputField wide"><input type="text" name="registration_date" id="registration_date" class="required" value="<?= isset($user)? $user->getFormatedRegistrationDate() : ''?>"/></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('userAccountForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>