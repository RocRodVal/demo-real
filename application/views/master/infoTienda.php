<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 13/2/15
 * Time: 8:46
 */

?>
<?php if($this->session->userdata('type') != 9) { ?>
    <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownInfoTienda" data-toggle="dropdown"
                aria-expanded="true">
            <?php echo $reference ?> [<?php echo $id_pds ?>]
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownInfoTienda">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><?php echo $commercial ?><br/>
                    <?php echo $address ?> , <?php echo $zip ?> -  <?php echo $city ?></a></li>
            <li><a href="<?= site_url('master/logout') ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
        </ul>
    </div>
<?php
}
else{?>
    <li><a href="<?= site_url('master/logout') ?>"><i class="fa fa-sign-out fa-fw"></i></a></li>
<?php
}?>