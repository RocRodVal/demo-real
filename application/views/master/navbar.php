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
               if ($this->session->userdata('type') == 9)
               {
               ?>
               <li><a href="<?=site_url('master/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>	            
			   <?php 
               }
               else {
			   ?>		            
               <li><?php $this->load->view('backend/infoTienda');?> </li>
			   <?php 
			   }
			   ?>		
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu" style="margin-top:20px;">
                        <?php
                        if ($this->session->userdata('type') == 9)
                        {
                        ?>

                            <?php $estado_incidencias = array("estado_incidencias");
                            $estado_incidencias_inner = array();
                            ?>

                            <li <?=(in_array($this->uri->segment(2), $estado_incidencias)? ' class="active" ' :'')?>>
                                <a href="#"><i class="fa fa-dashboard fa-fw"></i> Estado incidencias <span class="fa arrow"></span></a>

                                <ul class="nav nav-second-level">
                                    <li><a <?=($this->uri->segment(3)==='abiertas')?'class="active"':''?> href="<?=site_url('master/estado_incidencias/abiertas')?>"> Incidencias abiertas &raquo;</a></li>
                                    <li><a <?=($this->uri->segment(3)==='cerradas')?'class="active"':''?> href="<?=site_url('master/estado_incidencias/cerradas')?>"> Incidencias cerradas &raquo;</a></li>
                                </ul>

                            </li>

                        <?php
                            /*
                            <li><a <?=($this->uri->segment(2)==='incidencias')?'class="active"':''?> href="<?=site_url('master/incidencias')?>"><i class="fa fa-wrench fa-fw"></i> Export incidencias</a></li>*/
                        ?>

                        <?php
                        /**
                         * MAESTROS
                         *
                         */
                            /*
                        ?>
                        <?php $maestros = array('clientes','contactos','alarmas','dispositivos','muebles','puntos_de_venta'); ?>
                        <li <?=(in_array($this->uri->segment(2), $maestros))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Datos maestros<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a <?=($this->uri->segment(2)==='alarmas')?'class="active"':''?> href="<?=site_url('master/alarmas')?>"> Alarmas &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='dispositivos')?'class="active"':''?> href="<?=site_url('master/dispositivos')?>"> Dispositivos &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='muebles')?'class="active"':''?> href="<?=site_url('master/muebles')?>"> Muebles &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='puntos_de_venta')?'class="active"':''?> href="<?=site_url('master/puntos_de_venta')?>"> Puntos de venta &raquo;</a></li>     
                            </ul>
                        </li>
                        <?php
                            */
                        /**
                         * CUADROS DE MANDO
                         *
                         */
                        ?>
                        <?php $cdm = array('cdm_incidencias','cdm_tipo_incidencia','cdm_inventario','cdm_alarmas','cdm_dispositivos'); ?>
                        <li <?=(in_array($this->uri->segment(2), $cdm))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Cuadro de mando<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a <?=($this->uri->segment(2)==='cdm_incidencias')?'class="active"':''?> href="<?=site_url('master/cdm_incidencias')?>"> Estado incidencias &raquo;</a></li>
                                <?php /*<li><a <?=($this->uri->segment(2)==='cdm_tipo_incidencia')?'class="active"':''?> href="<?=site_url('master/cdm_tipo_incidencia')?>"> Tipo de incidencia &raquo;</a></li>*/ ?>
                                <?php /*<li><a <?=($this->uri->segment(2)==='cdm_inventario')?'class="active"':''?> href="<?=site_url('master/cdm_inventario')?>"> Inventario/Depósito &raquo;</a></li>*/ ?>
                                <li><a <?=($this->uri->segment(2)==='cdm_dispositivos')?'class="active"':''?> href="<?=site_url('master/cdm_dispositivos')?>"> Dispositivos &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='cdm_alarmas')?'class="active"':''?> href="<?=site_url('master/cdm_alarmas')?>"> Sistemas de seguridad &raquo;</a></li>

                            </ul>
                        </li>

                        <?php /**
                        * CUADROS DE MANDO
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
                                <li><a <?=($this->uri->segment(2)==='informe_pdv')?'class="active"':''?> href="<?=site_url('master/informe_pdv')?>"> Puntos de Venta &raquo;</a></li>
                                <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'informe_planogramas',
                                        'informe_planograma_mueble_pds',
                                        'informe_planograma_terminal')))?'class="active"':''?> href="<?=site_url('master/informe_planogramas')?>"> Planogramas &raquo;</a></li>
                                <li><a <?=(
                                        in_array($this->uri->segment(2),
                                            array('informe_visual',
                                                'informe_visual_mueble_sfid',
                                                'informe_visual_terminal',
                                                'informe_visual_ficha_terminal')))?'class="active"':''?> href="<?=site_url('master/informe_visual')?>"> Visual &raquo;</a></li>
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
                            	<li><a <?=(in_array($this->uri->segment(2), $descripcion))?'class="active"':''?> href="<?=site_url('master/descripcion')?>"> Planograma tienda &raquo;</a></li>                            
                            	<li><a <?=($this->uri->segment(2)==='inventarios_planogramas')?'class="active"':''?> href="<?=site_url('master/inventarios_planogramas')?>"> Planograma mueble &raquo;</a></li>
                            </ul>
                        </li>                        
                        <?php */
                        }
                        $ayuda = array('ayuda','manuales','muebles_fabricantes');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $ayuda))?'class="active"':''?>>
                        <a href="#"><i class="fa fa-question-circle fa-fw"></i> Ayuda<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a <?=($this->uri->segment(3)==='1')?'class="active"':''?> href="<?=site_url('master/ayuda/1')?>"> Mis solicitudes &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='2')?'class="active"':''?> href="<?=site_url('master/ayuda/2')?>"> Alta incidencia &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='3')?'class="active"':''?> href="<?=site_url('master/ayuda/3')?>"> Alta incidencia sistema seguridad general del mueble &raquo;</a></li>
                            <!--<li><a <?=($this->uri->segment(3)==='4')?'class="active"':''?> href="<?=site_url('master/ayuda/4')?>"> Incidencias frecuentes &raquo;</a></li>-->
                            <li><a <?=($this->uri->segment(2)==='manuales')?'class="active"':''?> href="<?=site_url('master/ayuda/5')?>"> Manuales &raquo;</a></li>
                            <li><a <?=($this->uri->segment(2)==='muebles_fabricantes')?'class="active"':''?> href="<?=site_url('master/ayuda/6')?>"> Muebles fabricantes &raquo;</a></li>
                        </ul>
                        </li>                        
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
