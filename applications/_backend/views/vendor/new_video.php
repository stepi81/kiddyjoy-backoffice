<div id="content">
    <ul>
        <li>
            <h2>Novi video klip</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="videoForm" method="post" action="<?= site_url( 'vendors/save_video' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="video_title" /></span>
                            </li>
                        </ul>
                        
                        <ul>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField wide"><input type="text" name="video_position" /></span>
                            </li>
                        </ul>
                        
                        <ul>
                            <li>
                                <label>Kod:</label>
                                <span class="inputField wide"><input type="text" name="video_code" class="required" /></span>
                            </li>
                        </ul>
                        

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'vendors/listing_video') ?>" />
                       
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('videoForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>