<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 13/2/15
 * Time: 8:46
 */
?>

    <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownInfoTienda" data-toggle="dropdown" aria-expanded="true">
			<?php echo $commercial ?>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownInfoTienda">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">
            	<?php echo $reference ?> [<?php echo $id_pds ?>]<br/>
            	<?php echo $commercial ?><br />
                <?php echo $address ?>, <?php echo $zip ?> -  <?php echo $city ?></a>
            </li>
            <li><a href="<?= site_url('tienda/logout') ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
        </ul>
    </div>
