<div id="content">
    <ul>
        <li>
            <h2>Novi Newsletter</h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <form id="newsletterForm" method="post" action="<?= site_url( 'newsletter/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label class="alignLeft">Korisnici:</label>
                                <div class="customComboHolder">
                                    <div>Privatni</div>
                                    <select name="users_group" id="users_group" >
                                        <option value="1" selected="selected" >Privatni</option>
                                        <option value="2" >Poslovni</option>
                                        <option value="3" >Newsletter korisnici</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required"/></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'newsletter/listing') ?>" />
                        </span> 

                        <span class="button save">
                            <input type="button" value="Nastavi" onclick="validateForm('newsletterForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>
            </div>
        </li>
    </ul>
</div>