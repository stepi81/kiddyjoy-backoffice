<div id="content">
    <ul>
        <li>
            <h2>Detalji kategorije</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="categoryForm" method="post" action="<?= site_url( 'product/categories/edit/'.$category->getID()) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                        	<?php if( $category->getImageURL() ): ?>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $category->getImageURL() ?>" alt="KiddyJoy" /></span>
                            </li>
                            <?php endif; ?>
                            <li>    
                                <label>Ime kategorije:</label>
                                <span class="inputField wide"><input type="text" name="name" class="required" value="<?= $category->getName() ?>"/></span>
                            </li>
                            <li>
	                            <label>Slika:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                            </li>
                        </ul>
                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('categoriesGrid', 'product/categories/listing/') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('categoryForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
