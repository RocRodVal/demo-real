		<!-- #navbar -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?=site_url($entrada)?>"><img src="<?=site_url('assets/images/logo-orange_small.png')?>" class="logo" title="<?=lang('comun.titulo')?>" /></a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
               <?php
               if ($this->session->userdata('type') == 13)
               {
               ?>
               <li><a href="<?=site_url($controlador.'/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>	            
			   <?php 
               }
               else {}
			   ?>		
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu" style="margin-top:20px;">
                        <?php
                        if ($this->session->userdata('type') == 13)
                        {
                        ?>

                            <?php $estado_incidencias = array("estado_incidencias","detalle_incidencia");
                            $estado_incidencias_inner = array();
                            ?>

                            <li <?=(in_array($this->uri->segment(2), $estado_incidencias)? ' class="active" ' :'')?>>
                                <a href="#"><i class="fa fa-dashboard fa-fw"></i> Estado incidencias <span class="fa arrow"></span></a>

                                <ul class="nav nav-second-level">
                                    <li><a <?=($this->uri->segment(3)==='abiertas')?'class="active"':''?> href="<?=site_url($controlador.'/estado_incidencias/abiertas')?>"> Incidencias abiertas &raquo;</a></li>
                                    <li><a <?=($this->uri->segment(3)==='cerradas')?'class="active"':''?> href="<?=site_url($controlador.'/estado_incidencias/cerradas')?>"> Incidencias cerradas &raquo;</a></li>
                                </ul>

                            </li>


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
                                        'informe_planograma_terminal')))?'class="active"':''?> href="<?=site_url($controlador.'/informe_planogramas')?>"> Planogramas &raquo;</a></li>
                               <?php /* <li><a <?=(
                                        in_array($this->uri->segment(2),
                                            array('informe_visual',
                                                'informe_visual_mueble_sfid',
                                                'informe_visual_terminal',
                                                'informe_visual_ficha_terminal')))?'class="active"':''?> href="<?=site_url($controlador.'/informe_visual')?>"> Visual &raquo;</a></li>*/?>
                            </ul>
                        </li>


                        <?php               
                        /*$exposicion = array('descripcion','exp_alta_incidencia','exp_alta_incidencia_mueble','exp_alta_incidencia_device','inventarios_panelados','inventarios_planogramas','inventarios');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $exposicion))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Exposición<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<?php
                            	$descripcion = array('descripcion','exp_alta_incidencia','exp_alta_incidencia_mueble','exp_alta_incidencia_device');
                            	?>
                            	<li><a <?=(in_array($this->uri->segment(2), $descripcion))?'class="active"':''?> href="<?=site_url($controlador.'/descripcion')?>"> Planograma tienda &raquo;</a></li>                            
                            	<li><a <?=($this->uri->segment(2)==='inventarios_planogramas')?'class="active"':''?> href="<?=site_url($controlador.'/inventarios_planogramas')?>"> Planograma mueble &raquo;</a></li>
                            </ul>
                        </li>                        
                        <?php */
                        }
                        $ayuda = array('ayuda','manuales','muebles_fabricantes');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $ayuda))?'class="active"':''?>>
                        <a href="#"><i class="fa fa-question-circle fa-fw"></i> Ayuda<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a <?=($this->uri->segment(3)==='1')?'class="active"':''?> href="<?=site_url($controlador.'/ayuda/1')?>"> Mis solicitudes &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='2')?'class="active"':''?> href="<?=site_url($controlador.'/ayuda/2')?>"> Alta incidencia &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='3')?'class="active"':''?> href="<?=site_url($controlador.'/ayuda/3')?>"> Alta incidencia sistema seguridad general del mueble &raquo;</a></li>
                            <!--<li><a <?=($this->uri->segment(3)==='4')?'class="active"':''?> href="<?=site_url($controlador.'/ayuda/4')?>"> Incidencias frecuentes &raquo;</a></li>-->
                            <li><a <?=($this->uri->segment(2)==='manuales')?'class="active"':''?> href="<?=site_url($controlador.'/ayuda/5')?>"> Manuales &raquo;</a></li>
                            <li><a <?=($this->uri->segment(2)==='muebles_fabricantes')?'class="active"':''?> href="<?=site_url($controlador.'/ayuda/6')?>"> Muebles fabricantes &raquo;</a></li>
                        </ul>
                        </li>                        
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
