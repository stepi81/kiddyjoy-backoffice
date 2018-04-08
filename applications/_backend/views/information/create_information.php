<div id="content">
    <ul>
        <li>
            <h2>Nove informacije</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">
                <form id="informationForm" method="post" action="<?= site_url( 'informations/save/'.$section ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1" />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0" />
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <label>Na naslovnoj strani:</label>
                                <input type="radio" name="featured" value="1" />
                                <small>Istaknuta</small>
                                <input type="radio" name="featured" value="0" />
                                <small>Neistaknuta</small>
                            </li>                            
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="name" class="required" /></span>
                            </li>
                            <!--<li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" /></span>
                            </li>-->
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" class="only_numbers" /></span>
                           	</li> 
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid( 'productsGrid', 'informations/listing/' . $section ) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('informationForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>