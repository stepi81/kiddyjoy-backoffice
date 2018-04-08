<div id="content">
    <ul>
        <li>
            <h2>Nov način plaćanja</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">
            	
            	<form id="paymentForm" method="post" action="<?= site_url( 'cart/payments/save' ) ?>" enctype="multipart/form-data">

	                <fieldset class="sectionForm">
	
	                    <ul>
	                    	<li>
	                            <label>Plugin:</label>
	                            <span class="inputField wide"><input type="text" name="plugin" class="required" /></span>
	                        </li>
	                        <li>
	                            <label>Naziv:</label>
	                            <span class="inputField wide"><input type="text" name="title" class="required" /></span>
	                        </li>
	                        <li>
	                            <label>Opis:</label>
	                            <span class="textArea"><textarea name="description"></textarea></span>
	                        </li>
	                        <li>
	                        	<label>Status:</label>
	                            <input readonly="readonly" type="radio" name="status" value="1" />
	                            <small>Aktivna</small>
	                            <input readonly="readonly" type="radio" name="status" value="0" />
	                            <small>Neaktivna</small>
	                        </li>
	                        <li>
	                            <label>Slika:</label>
	                            <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="icon" />
                                </div>
                            </li>
	                    </ul>
	
	                </fieldset>
	
	                <div class="borderTop">
	
	                    <span class="button back">
	                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('cartPaymentGrid', 'cart/payments/listing') ?>" />
	                    </span> 
						<span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('paymentForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
						
	                </div>
	                
	        	</form>

            </div>
        </li>
    </ul>
</div>
