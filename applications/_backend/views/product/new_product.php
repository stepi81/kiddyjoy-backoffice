<script type="text/javascript">

    $(document).ready(function(){

        $('.selected_category').text($('#category option:selected').text());
        $('.selected_collection').text($('#collection option:selected').text());
        $('.selected_subcollection').text($('#subcollection option:selected').text());

        /*Display Subcategories item based on a selected Category*/
        $('#category').change(function(){
            $('.selected_collection').text('Sellect'); //Removes selected option and displays default option
            $('#collection').empty(); //Removes any existing options
            var category = $('#category option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('products/get_collections')?>',{category_selection:category}, function(data){
               if(data){
                   $('#collection').append(data);//Inserts newely created list
               }
            });
        });
        
        $('#collection').change(function(){
            $('.selected_subcollection').text('Sellect'); //Removes selected option and displays default option
            $('#subcollection').empty(); //Removes any existing options
            var collection = $('#collection option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('products/get_subcollections')?>',{collection_selection:collection}, function(data){
               if(data){
                   $('#subcollection').append(data);//Inserts newely created list
               }
            });
        });
    });

</script>

<div class="boxHolder">
    <div class="box productContent">
<div id="content">
    <ul>
        <li>
            <h2>New product</h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <form id="productForm" method="post" action="<?= site_url( 'products/save') ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">
                        <ul>
                        <li>
                         <label class="alignLeft">Category:</label>
                                <div class="customComboHolder">
                                    <div class="selected_category">Sellect category</div>
                                    <select name="category" id="category" class="required">
                                        <option value="" selected>Sellect category</option>
                                        <option value="1">Watches</option>
                                        <option value="2">Jewlery</option>
                                        <option value="3">Accessories & Novelties</option>
                                    </select>
                                </div>
                         </li>
                         <li>
                            <label class="alignLeft">Collection:</label>
                                <div class="customComboHolder">
                                    <div class="selected_collection">Sellect collection</div>
                                    <select name="collection" id="collection" class="required">
                                        <option value="" selected>First sellect category</option>

                                    </select>
                                </div>
                         </li>
                         <li>
                            <label class="alignLeft">Subcollection:</label>
                                <div class="customComboHolder">
                                    <div class="selected_subcollection">Sellect subcollection</div>
                                    <select name="subcollection" id="subcollection" >
                                        <option value="" selected>First sellect collection</option>
                                    </select>
                                </div>
                         </li>
                         <li>
                            <label>Style number:</label>
                                <span class="inputField wide"><input type="text" name="style_number" class="required" /></span>
                            </li>
                         <li>
                            <label class="alignLeft">Warranty:</label>
                                <div class="customComboHolder">
                                    <div>Sellect warranty</div>
                                    <select name="warranty" id="warranty" class="required">
                                        <option value="" selected>Warranty</option>
                                        <?php foreach( $warranties as $warranty): ?>
                                            <option value="<?= $warranty->getID() ?>" ><?= $warranty->getDuration() ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                         </li>
                         <li>
                            <label>Status:</label>
                            <input type="radio" name="status" value="1"/>
                            <small>Active</small>
                            <input type="radio" name="status" value="0"/>
                            <small>Deactive</small>
                         </li> 
                         <!--<li>
                                <label>JPEG picture:</label>
                                <span class="inputField wide"><input type="text" class="required" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="jpeg_image" class="required" />
                                </div>
                         </li>     
                         <li>     
                                <label>PNG picture:</label>
                                <span class="inputField wide"><input type="text"  class="required" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="png_image" class="required" />
                                </div>
                         </li>-->
                    </ul>
                    </fieldset>
                    <div class="borderTop">
                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'products/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Snimi" onclick="validateForm('productForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
                    </div>
                </form>
            </div>
        </li>
    </ul>
</div>
</div>
</div>
