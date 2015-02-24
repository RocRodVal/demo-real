		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                          <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>SFID / Referencia</th>
                                            <th>Nombre comercial</th>
                                            <th>Ficha</th>
                                            <th>PDF</th>
                                            <th>Auditoría</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="odd gradeX"><td>DHO</td><td>26000975</td><td>ALPA TELECOMUNICACIONES 2006 BARCELONA</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value="" disabled></td><td>Inventariada</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>26000039</td><td>ALPA TELECOMUNICACIONES 2006 INDUSTRIA</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>26000090</td><td>ALPA TELECOMUNICACIONES 2006 MERIDIANA</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>26000048</td><td>ALPA TELECOMUNICACIONES 2006 ROGENT</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value="" disabled></td><td>Inventariada</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>59041108</td><td>ANEVINIP</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value="" disabled></td><td>Inventariada</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>59440329</td><td>ANEVINIP DON BENITO</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>59440251</td><td>ANEVINIP VILLAFRANCA</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>26330003</td><td>ARAMENA ANDORRA</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value="" disabled></td><td>Inventariada</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>26330001</td><td>ARAMENA TARAZONA</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value="" disabled></td><td>Inventariada</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>59444653</td><td>AREAPHONE</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value="" disabled></td><td>Inventariada</td></tr>
                                        <tr class="odd gradeX"><td>DHO</td><td>59440025</td><td>AREAPHONE CACERES</td><td><a href="<?=site_url('master/auditorias_ver')?>">Ver</td><td><a href="<?=site_url('assets/ficha.pdf')?>" target="_blank">PDF</td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>
                                    </tbody>
                                </table>
                                                            <br>
                            <p><button type="button" class="btn btn-warning">Generar auditoría</button></p>
                            </div>
                </div>
                
                
                
            </div>
        </div>
        <!-- /#page-wrapper -->
