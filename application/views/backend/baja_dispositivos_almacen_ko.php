<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
        </div>
    </div>
    <div class="row">
        <p>&nbsp;</p>
        <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> <?=$mensaje1?> <?=$num?> <?=$mensaje3?> <strong><?=$modelo?></strong>
            <?php if (!empty($mensaje2))
                echo ", ".$mensaje2;
             echo "</p>";
            ?>
    </div>
</div>
<!-- /#page-wrapper -->