<div id="content">
    <ul>
        <li>
            <h2><?= $grid_title ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">
                <?= $grid ?>
                <table id="grid" style="display:none"></table>
            </div>
            <?php if (isset($route)){ ?>    
            <div class="borderTop">
                <span class="button back">
                      <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid($params_id, $route) ?>" />
                </span> 
            </div>
            <?php } ?>
        </li>
    </ul>
</div>