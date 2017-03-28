		<!-- #page-wrapper -->
        <div id="page-wrapper">


			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?php echo $title ?></h1>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<?php
					if(!empty($message)) {
						echo $message;
					} else {
					?>
				</div>
				<form action="<?= site_url('admin/insert_almacen') ?>" method="post" class="content_auto form_login" enctype="multipart/form-data">
					<div class="row">
						<div class="col-lg-1"></div>
						<div class="col-lg-5">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group"></div>
											<div class="form-group"></div>
											<div class="form-group">
												<div class="input-group">
													<div class="input-group-addon">
														<label>Terminal</label><code>*</code></div>
														<select name="id_device" id="id_modelo" class="form-control">
														<?php foreach($devices as $device){
															echo '<option value="'.$device->id_device.'"
														'.(($id_device == $device->id_device) ?  ' selected="selected" ' : '' ).'>'.$device->device.'</option>';
														}?>
													</select>
												</div>
												<br>
												<div class="input-group">
													<div class="input-group-addon">
														<label>Estado</label><code>*</code></div>
													<select name="status" id="status" class="form-control">
														<option value="En stock">En stock</option>
														<option value="Baja">Baja</option>
														<option value="RMA">RMA</option>

													</select>
												</div>
												<br>
												<div class="input-group">
													<div class="input-group-addon">
														<label>IMEI </label><code>*</code></div>
													<input class="form-control" name="imei" id="imei" required>
												</div>
												<br>
												<div class="input-group">
													<div class="input-group-addon">
														<label>Serie </label><code>*</code></div>
													<input class="form-control" name="serial" id="serial" required>
												</div>
												<br>
												<div class="input-group">
													<div class="input-group-addon">
														<label>Incid</label><code>*</code></div>
													<input class="form-control" name="id_incidencia" id="id_incidencia" required>
												</div>
												<br>


												<div class="form-group">
													<input type="submit" value="Envíar" name="submit" class="btn btn-success"/>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-5">

						</div>
					</div>
				</form>
				<?php } ?>
			</div>
		</div>
		<!-- /#page-wrapper -->