<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <a href="<?= site_url('master/dashboard') ?>" class="btn btn-danger right">Volver</a>
            </h1>
        </div>
    </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>
                                        <p><strong><?php echo $device ?></strong></p>
                                        <h3>Datos teléfono</h3>

                                        <p>
                                            <strong>Modelo</strong><br>
                                        <pre><?php echo $device ?></pre>

                                    </td>
                                    <?php
                        if ($picture_url_dev <> '') {
                            ?>
                                        <td><img src="<?= site_url('application/uploads/' . $picture_url_dev . '') ?>"
                                                 width="200" title="<?php echo $device ?>"/></td>
                                    <?php
                        }
                        ?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /#page-wrapper -->