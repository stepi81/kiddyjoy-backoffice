<div id="content">
    <ul>
        <li>
            <h2>Novi blog - <?= $article_category->getName() ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="articleForm" method="post" action="<?= site_url( 'articles/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">
                        <ul>
							<input type="hidden" value="<?= $category_id ?>" name="category_id" />
                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField wide"><input type="text" class="required" name="send_date" id="send_date" /></span>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" /></span>
                            </li>
                            <li>
                                <label>Kratak opis:</label>
                                <span class="textArea"><textarea name="summary"></textarea></span>
                            </li>
                            <li>
	                            <label>Thumb:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                            </li>
                            <li>
	                            <label>Glavna slika:</label>
	                            <span class="inputField wide"><input type="text" name="main_image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="main_image" />
                                </div>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('articlesGrid', 'articles/listing/'.$category_id) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('articleForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
