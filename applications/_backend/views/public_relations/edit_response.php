<div id="content">
    <ul>
        <li>
            <h2>Detalji odgovora</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="commentForm" method="post" action="<?= site_url( 'comments/edit_response/'.$comment->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField short"><input type="text" disabled="true" value="<?= $comment->getDate() ?>" /></span>
                            </li>

                            <li>
                                <label>Komentar:</label>
                                <span class="inputField textArea"><textarea name="message"><?= $comment->getMessage() ?></textarea></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'comments/listing_responses/'.$comment->getComment()->getID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('commentForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>