<div id="content">
    <ul>
        <li>
            <h2><?= $grid_title ?></h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <fieldset class="sectionForm half">
                    <div class="innerContent">
		                <?= $grid ?>
		                <table id="grid" style="display:none"></table>
    		        </div>
            	</fieldset>
             	<fieldset class="sectionForm half" style="padding-left: 30px; padding-top: 20px;">
                 <form method="post" action="<?= site_url( 'product/subcategories/save/' . $category->getID() ) ?>" id="subcategoryForm" enctype="multipart/form-data">
                         <ul>
                           <li style="padding-top: 0px; width:200px;">
                                <label><b>Grupa:</b></label>
                                <span class="inputField wide"><input type="text" name="name" class="required"></span>
                            </li>
                            <li style="padding-top: 0px; width:200px;">
                                <label>Pozicija grupe:</label>
                                <span class="inputField short"><input type="text" name="position" value="" class="only_numbers" /></span>
                           </li>
                           <li style="padding-top: 0px; width:200px;">
	                            <label>Slika:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile" style="margin-left: 125px">
                                    <input type="file" name="thumb" />
                                </div>
                           </li>
                           <li>
			                    <label>Opis:</label><br /><br /><br />
			                    <?= $tinymce ?>
			                    <textarea name="other" id="other"></textarea>
			               </li>
                             <li style="padding-top: 0px; width:200px;">
                                 <label><b>Seo naslov:</b></label>
                                 <span class="inputField wide"><input type="text" name="seo_title"></span>
                             </li>
                             <li style="padding-top: 0px; width:200px;">
                                 <label>Seo keywords:</label>
                                 <span class="textArea"><textarea name="seo_keywords"></textarea></span>
                             </li>
                             <li style="padding-top: 0px; width:200px;">
                                 <label>Seo opis:</label>
                                 <span class="textArea"><textarea name="seo_description"></textarea></span>
                             </li>
                        </ul>
                    </form>
                </fieldset>
                <div class="borderTop">

                    <span class="button back">
                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('categoriesGrid', 'product/categories/listing') ?>" />
                    </span>
                    <span class="button save">
                        <input type="button" value="Snimi" onclick="validateForm('subcategoryForm')"/>
                    </span>
                    <br>
                    <li>
                        <?php if( isset($message) ) echo $message; ?>
                    </li>

                </div>
            </div>
        </li>
    </ul>
</div>
