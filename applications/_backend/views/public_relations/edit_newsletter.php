<script type="text/javascript">
function preview_message() {
        //Collect information
        var template = $('#template option:selected').val();
        var subject = $('#title').val();
        var message = $('#page_ifr').contents().find('#tinymce').html();//HAck to get content from iframe (TinyMCE)
        
        $.post('<?= site_url('newsletter/preview_newsletter')?>', {template:template, subject:subject, message:message}, function(data){
            newwindow=window.open('','Message preview','menubar=1,width=980,height=580');
            newwindow.document.write(data);
            newwindow.document.close();
        })
}
</script>
<div id="content">
    <ul>
        <li>
            <h2>Novi Newsletter</h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <form id="newsletterForm" method="post" action="<?php echo isset($newsletter) ?  site_url( 'newsletter/edit/' . $newsletter->getID()) : site_url( 'newsletter/edit')?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label class="alignLeft">Korisnici:</label>
                                <div class="customComboHolder">
                                    <div><?php echo $userGroup; ?></div>
                                    <select name="users_group" id="users_group" >
                                        <option value="1" <?= $userGroup=='Privatni'? 'selected':''?> >Privatni</option>
                                        <option value="2" <?= $userGroup=='Poslovni'? 'selected':''?>>Poslovni</option>
                                        <option value="3" <?= $userGroup=='Newsletter korisnici'? 'selected':''?>>Newsletter korisnici</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" id="title" class="required" value="<?php echo isset($newsletter)? $newsletter->getTitle() : ''?>"/></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1" <?= $newsletter->getStatus() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0" <?= $newsletter->getStatus() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <label>Upload slika:</label>
                                <?= $plupload ?>
                                <div id="uploader" style="width: 632px;" ><p>Vaš browser nema Flash, Silverlight, Gears, BrowserPlus ili HTML5 podršku.</p></div>
                            </li>  
                            <li>
                                <label>Poruka:</label>
                                <?= $tinymce ?>
                                <textarea name="message" id="page"><?php  echo isset($newsletter)? $newsletter->getMessage() : '' ?></textarea>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'newsletter/listing') ?>" />
                        </span> 
                        <span class="button preview">
                            <input type="button" value="Preview" onclick="preview_message()"/>
                        </span>
                        <span class="button save">
                            <input type="button" value="Pošalji" onclick="validateForm('newsletterForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>