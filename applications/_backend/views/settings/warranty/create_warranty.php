<div id="content">
    <ul>
        <li>
            <h2>Nova garancija</h2>
            <a href="#" class="collapse">Collapse</a>
            
            <div class="innerContent">
            
                <form id="warrantyForm" method="post" action="<?= site_url( 'settings/warranties/save' ) ?>">
                    <fieldset class="sectionForm">
                    
                        <ul>
                            <li>
                                <label>Trajanje:</label>
                                <span class="inputField wide"><input type="text" name="duration" class="required"/></span>
                           </li>
                           <li>
                                <label>Pozicija:</label>
                                <span class="inputField wide"><input type="text" name="position" id="position"/></span>  
                           </li>
                        </ul>
                        
                    </fieldset>
                    
                    <div class="borderTop">
                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('warrantiesGrid', 'settings/warranties/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('warrantyForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>