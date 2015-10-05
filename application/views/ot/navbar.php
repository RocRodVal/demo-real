		<!-- #navbar -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php /*<a href="<?=site_url('ot/dashboard')?>"><img src="<?=site_url('assets/images/logo-orange_small.png')?>" class="logo" title="<?=lang('comun.titulo')?>" /></a>*/?>
                <a href="<?=site_url($entrada)?>"><img src="<?=site_url('assets/images/logo-orange_small.png')?>" class="logo" title="<?=lang('comun.titulo')?>" /></a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
               <?php
               if ($this->session->userdata('type') == 11)
               {
               ?>
               <li><a href="<?=site_url('ot/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
			   <?php 
               }
               ?>
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu" style="margin-top:20px;">
                        <?php
                        if ($this->session->userdata('type') == 11)
                        {
                        ?>
                        <?php
                        /**
                         * CUADROS DE MANDO
                         *
                         */
                        ?>
                        <?php $cdm = array('cdm_incidencias','cdm_tipo_incidencia','cdm_inventario','cdm_alarmas','cdm_dispositivos'); ?>
                            <li <?=(in_array($this->uri->segment(2), $cdm))?'class="active"':''?>>
                                <a href="#"><i class="fa fa-sitemap fa-fw"></i> Cuadro de mando<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <?php /*<li><a <?=($this->uri->segment(2)==='cdm_tipo_incidencia')?'class="active"':''?> href="<?=site_url('master/cdm_tipo_incidencia')?>"> Tipo de incidencia &raquo;</a></li>*/ ?>
                                    <?php /*<li><a <?=($this->uri->segment(2)==='cdm_inventario')?'class="active"':''?> href="<?=site_url('master/cdm_inventario')?>"> Inventario/Depósito &raquo;</a></li>*/ ?>
                                    <li><a <?=($this->uri->segment(2)==='cdm_dispositivos')?'class="active"':''?> href="<?=site_url('ot/cdm_dispositivos')?>"> Dispositivos &raquo;</a></li>
                                </ul>
                            </li>



                        <?php /**
                        * INFORMES
                        *
                        */
                        ?>
                        <?php $inf = array('informes',
                                                'informe_pdv',
                                                'informe_planogramas',
                                                    'informe_planograma_mueble_pds',
                                                    'informe_planograma_terminal',
                                                'informe_visual',
                                                    'informe_visual_mueble_sfid',
                                                    'informe_visual_terminal',
                                                    'informe_visual_ficha_terminal'); ?>

                        <li <?=(in_array($this->uri->segment(2), $inf))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-file"></i> Informes <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">

                                <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'informe_planogramas',
                                        'informe_planograma_mueble_pds',
                                        'informe_planograma_terminal')))?'class="active"':''?> href="<?=site_url('ot/informe_planogramas')?>"> Planogramas &raquo;</a></li>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->