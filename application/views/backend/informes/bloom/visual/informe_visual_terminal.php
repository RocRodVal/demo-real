<!-- #page-wrapper -->
    <div class="row">
        <div class="col-lg-12">
		    <h1 class="page-header"><?php echo $subtitle ?>
		    	<a onclick="history.go(-1);return false;" class="btn btn-danger right">Volver</a>
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
                                    <td align="left">
                                        <p><strong><?php echo $device ?></strong></p>
                                        <h3>Datos teléfono</h3>
                                        <p>
	                                        <strong>Modelo: </strong><?php echo $device ?><br />
	                                        <strong>Modelo de marca: </strong><?php echo $brand_name ?><br />
	                                        <strong>Descripción: </strong><?php echo $description ?><br />
                                        </p>
                                    </td>
                                    <?php
                        if ($picture_url_dev <> '') {
                            ?>
                                        <td><img src="<?= site_url('application/uploads/' . $picture_url_dev . '') ?>"
                                                 width="200" title="<?php echo strtoupper($device) ?>"/></td>
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