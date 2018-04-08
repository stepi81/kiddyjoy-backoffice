<div id="content">
    <ul>
        <li>
            <h2>Detalji komentara</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="commentForm" method="post" action="<?= site_url( 'comments/edit/'.$comment->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="active" value="1"<?= $comment->getStatus() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivan</small>
                                <input type="radio" name="active" value="0"<?= $comment->getStatus() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivan</small>
                            </li>
                            <li>
                                <label>Ime korisnika:</label>
                                <span class="inputField wide"><input type="text" disabled="true" name="user_name" class="required" value="<?= $comment->getUserName() ?>" /></span>
                            </li>
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

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'comments/listing') ?>" />
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