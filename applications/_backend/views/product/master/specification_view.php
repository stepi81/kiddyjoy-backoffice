<li>                      
<h2>Specifikacije</h2>
<a href="#" class="collapse">Collapse</a>
  <div class="innerContent">
  <fieldset class="sectionForm">
  <ul>
  <fieldset class="sectionForm half" style="clear:both">
<?php
$specifications_num = floor (count ($specifications)/2);
$i = 0;
foreach ($specifications as $specification): 
    $i++;
    if ($i == $specifications_num + 1 ) { ?> 
</fieldset>
<fieldset class="sectionForm half" style="padding-left: 30px;">
<?php } ?>
<?php if ($specification->getTypeID()==2){ ?>
        <li>
            <label><?= $specification -> getName() ?>:</label>
            <span class="textArea">
                    <textarea name="<?= $specification -> getID() . 't' ?>" ><?php
                        foreach ($textFilter as $z) {
                            if ($z -> getSpecification()->getID() == $specification -> getID()) {
                                echo $z -> getDescription();
                            };
                        }
                    ?></textarea>
            </span>
        </li>
<?php
    goto a;
    }
?>
<li>      
    <label class="alignLeft"><?= $specification->getName()?>:</label>
    <div class="customComboHolder">
        <div>
            <?php 
                foreach ($filters as $z){
                   if ( $z->getSpecification()->getID() == $specification->getID()){
                        $tittle = $z->getName();
                        break;
                   }else{
                        $tittle = 'Odaberite filter';
                   }
                } 
                isset ($tittle) ? '' : $tittle = 'Odaberite filter';
                echo $tittle;  
            ?>
        </div>
        <select name="<?= $specification->getID() . 's'?>" id="<?= $specification->getID() . 's'?>" >
            <option value="" selected="selected">Odaberite filter</option>
            <?php foreach( $specification->getFilters() as $filter ){ ?>
                <option value="<?= $filter->getID() ?>" <?php 
                    foreach ($filters as $x){
                         if ($x->getID() == $filter->getID()) {
                              echo  'selected' ; 
                         }
                    }
                    ?>><?= $filter->getName() ?>
            </option>
            <?php } ?>
        </select>
    </div>
</li>

<?php 
    a:
    endforeach; 
?> 
</fieldset>