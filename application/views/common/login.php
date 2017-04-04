
<?php echo form_open(site_url($controlador). '/login', array('method'=>'post', 'class'=>'content_auto form_login')); ?>

    <?php echo validation_errors(); ?>

    <?php
    if (isset($message))
    {
    ?>
    <div id="infoMessage"><?php echo $message;?></div>
    <?php
    }
    ?>
    <fieldset>
        <div class="form-group">
            <input class="form-control" placeholder="SFID hijo" name="sfid-login" type="text" value="<?=set_value('sfid-login')?>">
        </div>
        <div class="form-group">
            <input class="form-control" placeholder="contraseña" name="password" type="password" value="">
        </div>
        <input type="submit" class="btn btn-lg btn-success btn-block" value="Entrar" />
    </fieldset>
</form>
