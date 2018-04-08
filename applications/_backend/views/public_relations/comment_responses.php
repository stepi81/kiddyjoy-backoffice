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
                 <form method="post" action="<?= site_url( 'comments/insert_response/' . $comment_id ) ?>" id="responsesForm" enctype="multipart/form-data">
                     <ul>
                         <li>
                             <label style="width:50px">Aktivan:</label>
                             <p style="padding-top: 10px; width: 20px">
                            <?php
                               $status = $comment -> getStatus() ? 'check' : 'delete';
                             echo '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('comments/change_status/' . $comment->getID()) . '\');">Status</a>'; 
                            ?>
                            </p>
                            </li>
                            
                            <?php switch( get_class($comment)) {
	                
						            case 'models\Entities\Comment\ProductComment':?>
						            
								            <li style="width:400px;">
				                            	<b>Proizvod:</b><br />                                                       
				                           		<a href="<?= $comment->getProduct()->getURL() ?>" target="_blank"><?php print_r($comment->getProduct()->getName()) ?></a>
				                           	</li>

								    <?php break;
											 
											case 'models\Entities\Comment\NewsComment': ?>
											
											<li style="width:400px;">
				                            	<b>Proizvod:</b><br />                                                       
				                           		<a href="<?= $comment->getNews()->getFrontURL() ?>" target="_blank"><?php print_r($comment->getNews()->getTitle()) ?></a>
				                           	</li>
											
								    <?php break;
											 
											case 'models\Entities\Comment\ArticleComment': ?>
											
											<li style="width:400px;">
				                            	<b>Proizvod:</b><br />                                                       
				                           		<a href="<?= $comment->getArticle()->getFrontURL() ?>" target="_blank"><?php print_r($comment->getArticle()->getTitle()) ?></a>
				                           	</li>
											
								   <?php break;
						    } ?>
						        
                           	<li style="padding-top: 5px; width:400px;">
                                Komentar:<br />
                            	<?= $comment->getMessage() ?>
                            </li><br />
                            <li>
                                <h3>Nov odgovor:</h3> 
                                <span class="textArea"><textarea type="text" class="required" name="message"></textarea></span>
                            </li>
                            <li>
                                <div>
                                    <span class="button back">
                                      
                                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'comments/listing') ?>" />
                                    </span>
                                    <span class="button save">
                                        <input type="button" value="Sačuvaj" onclick="validateForm('responsesForm')" id="saveRsponse" /> 
                                    </span>
                                    <br>
                                    <li>
                                        <?php if( isset($message) ) echo $message; ?>
                                    </li>
                                </div>
                            </li>
                        </ul>
                    </form>
                </fieldset>
            </div>
        </li>
    </ul>
</div>
