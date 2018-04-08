<div class="gallery_container" id="<?= $location->getID() ?>">
    <div id="gallery_topborder"></div>
    <div id="gallery_content">
        <ul id="gallery_thumbs" class="sortable">
            <?php
                if( !count( $location->getImages() ) ){
                    echo '<li>';
                    echo '<img src="' . layout_url() . 'gallery/gallery_empty_english.jpg" alt=""/>';
                    echo '</li>';
                }else{
                    foreach($images as $image){

                        echo '<li id="id_' . $image->getId() . '">';
                        echo '<a href="' . APP_URL . 'assets/img/locations/' . $image->getName() . '" class="gallery_thumb" rel="group1">';
                        echo '<img width="280px" height="100px" src="' . APP_URL . 'assets/img/locations/thumb/' . $image->getName() . '" alt="' . $image->getName() . '"/>';
                        echo '</a>';
                        echo '<div class="gallery_delete">
                                        <a href="' . site_url() . 'settings/locations/delete_image/' . $image->getID() . '">
                                            <img src="' . layout_url() . 'gallery/btn-delete.png" alt="Delete"/>
                                        </a>
                             </div>';
                        echo '</li>';

                    }
                }
                    
            ?>
        </ul>
        <div class="gallery_break"></div>
    </div>
    <div id="gallery_buttons">

        <div id="gallery_upload">
            <input id="upload_location_image" name="upload_image" type="file" style="visibility:hidden;"/>
        </div>
        
        <div id="location_gallery_position"><a href="" id="gallery_save"></a></div>
        <div class="gallery_break"></div>
    </div>
    <div id="gallery_bottomborder"></div>
</div>
<div>
    <br/>
    <input id="upload_location_image" name="upload_image" type="file" style="visibility:hidden;"/>
</div>
<div class="borderTop">
    
    <span class="button back">
<input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'settings/locations/listing') ?>" />
    </span>

</div>