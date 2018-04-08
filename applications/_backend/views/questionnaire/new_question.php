<script type="text/javascript">

    $(document).ready(function(){

    //  preloadImages([
      //      "<?= layout_url('field-wide-error.png') ?>"
       // ]);

        /* Custom checkboxes and radio buttons */
     //   $('input[type=checkbox], input[type=radio]').checkBox();

        /* Allow only numbers in form field */
        $(".only_numbers").ForceNumericOnly();

        /* Create additional answers */
        var append_input = '<li class="answer">\n\
               <label>Odgovor 1:</label>\n\
               <span class="inputField wide"><input type="text" name="answer[]" id="answer_1" class="required"/></span>\n\
               <a href="javascript:void(0)" onClick="add_new_answer(1,this)"><img src="<?= layout_url('dodaj.png')?>" alt="Dodaj" style="margin-top: 12px;"/></li>';

        $('#answer_type').change(function(){
           if($('#answer_type option:selected').val() == 2){
               $('ul.question_form').append(append_input);
           }else{
               $('li.answer').each(function(){
                  $(this).remove();
               });
           }
        });
    });

    function add_new_answer(counter, prev_obj){
        counter = counter+1;
        var append_input = '<li class="answer">\n\
                       <label>Odgovor '+counter+':</label>\n\
                       <span class="inputField wide"><input type="text" name="answer[]" id="answer_'+counter+'" class="required"/></span>\n\
                       <a href="javascript:void(0)" onClick="add_new_answer('+counter+',this)"><img src="<?= layout_url('dodaj.png')?>" alt="Dodaj" style="margin-top: 12px;"/></li>';

        $('ul.question_form').append(append_input);
        $(prev_obj).remove(); //Removes add button on previus object
    }

</script>
<div id="content">
    <ul>
        <li>
            <h2>Novo pitanje</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <?= $this->session->flashdata('questions') ?>

                <form method="post" action="<?= site_url( 'inquiry/questions/save/' . $questionnaire_id ) ?>" id="questionForm">
                    <fieldset class="sectionForm">
                    <ul class="question_form">
                        <li>
                            <label class="alignLeft">Tip pitanja:</label>
                            <div class="customComboHolder">
                                <div>Bez ponudjenih odgovora</div>
                                <select name="answer_type" id="answer_type" >
                                    <option value="3" selected="selected">Bez ponudjenih odgovora</option>
                                    <option value="2" >Sa ponudjenim odgovorima</option>
                                    <option value="1" >DA-NE</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <label>Pozicija:</label>
                            <span class="inputField short"><input type="text" name="position" id="position" class="only_numbers"/></span>
                        </li>
                        <li>
                            <label>Pitanje:</label>
                            <span class="inputField wide"><input type="text" name="text" id="text" class="required"/></span>
                        </li>
                    </ul>

                    <div class="borderTop">

                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'inquiry/questions/listing/' . $questionnaire_id) ?>" />
                        </span>
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('questionForm')" id="saveQuestion" />
                        </span>

                    </div>
                    </fieldset>
                </form>

            </div>

        </li>

    </ul>
</div>