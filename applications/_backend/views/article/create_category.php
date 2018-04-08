<div id="content">
    <ul>
        <li>
        	<?php if( $category_id ): ?>
            	<h2>Nova bloh podkategorija - <?= $article_category->getName(); ?></h2>
            <?php else: ?>
            	<h2>Nova blog kategorija</h2>
            <?php endif; ?>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="articleCategoryForm" method="post" action="<?= site_url( 'article/article_categories/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">
                        <ul>
                        	<li> 
                                <input type="hidden" name="category_id" id="category_id" value="<?= $category_id ?>"  />
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="name"  required="required"/></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" id="position" class="only_numbers"/></span>
                            </li>
                            <li>
							    <label>Seo Title:</label>
							    <span class="textArea"><textarea name="seo_title" ></textarea></span>
							</li>
							<li>
							    <label>SEO Keywords:</label>
							    <span class="textArea"><textarea name="seo_keywords" ></textarea></span>
							</li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"/>
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"/>
                                <small>Neaktivna</small>
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
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('articleCategoriesGrid', 'article/article_categories/listing/'.$category_id) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('articleCategoryForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
