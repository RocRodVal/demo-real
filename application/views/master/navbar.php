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

                            <?php $estado_pedidos = array("pedidos","abiertos","finalizados","detalle_pedido"); ?>
                            <li <?=(($this->uri->segment(2)==='pedidos') || ($this->uri->segment(3)==='abiertos') || ($this->uri->segment(3)==='finalizados') || ($this->uri->segment(2)==='detalle_pedido'))?'class="active"':''?>>
                                <a href="#"><i class="fa fa-dashboard fa-file-text-o"></i> Estado pedidos  <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li><a <?=($this->uri->segment(3)==='abiertos')?'class="active"':''?> href="<?=site_url($acceso.'/pedidos/abiertos')?>">Pedidos abiertos &raquo;</a></li>
                                    <li><a <?=($this->uri->segment(3)=='finalizados')?'class="active"':''?> href="<?=site_url($acceso.'/pedidos/finalizados')?>">Pedidos finalizados &raquo;</a></li>
                                </ul>
                            </li>
                        <?php
                            /**
                             * CUADROS DE MANDO
                             *
                             */
                            $cdm = array('cdm_incidencias','cdm_alarmas','cdm_dispositivos_balance','cdm_dispositivos_incidencias','cdm_alarmas_balance','cdm_alarmas_incidencias','cdm_alarmas_consumo');
                            $cdm_alarmas=array('cdm_alarmas_balance','cdm_alarmas_incidencias','cdm_alarmas_consumo');
                            $cdm_dispositivos=array('cdm_dispositivos_balance','cdm_dispositivos_incidencias');
                            $arr_anios = array();
                            $anio_inicial = 2015; $este_anio = date("Y");
                            for($i = $este_anio; $i >= $anio_inicial; $i--) {
                                $arr_anios[] = $i;
                            }
                            ?>
                        <li <?=(in_array($this->uri->segment(2), $cdm))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Cuadro de mando<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li <?=($this->uri->segment(2)==='cdm_incidencias'  && in_array($this->uri->segment(3),$arr_anios))?'class="active"':''?> >
                                    <a href="#"> Resumen incidencias <span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <?php
                                        for($i = $este_anio; $i >= $anio_inicial; $i--){ ?>
                                            <li><a <?=($this->uri->segment(2)==='cdm_incidencias'  && $this->uri->segment(3)==$i)?'class="active"':''?> href="<?=site_url($controlador.'/cdm_incidencias/'.$i)?>"><i class="fa fa-calendar fa-fw"></i> <?=$i?> &raquo;</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li <?=(in_array($this->uri->segment(2),$cdm) && ($this->uri->segment(3)=='') && (in_array($this->uri->segment(2),$cdm_dispositivos)))?'class="active"':''?>>
                                    <a href="#"> Dispositivos <span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li><a <?=($this->uri->segment(2)==='cdm_dispositivos_balance')?'class="active"':''?> href="<?=site_url($controlador.'/cdm_dispositivos_balance/')?>"><i class="fa fa-tasks"></i> Balance &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)==='cdm_dispositivos_incidencias')?'class="active"':''?> href="<?=site_url($controlador.'/cdm_dispositivos_incidencias/')?>"><i class="fa fa-exclamation-triangle"></i> Incidencias &raquo;</a></li>
                                    </ul>
                                </li>
                                <li <?=(in_array($this->uri->segment(2),$cdm) && ($this->uri->segment(3)=='') && (in_array($this->uri->segment(2),$cdm_alarmas)))?'class="active"':''?>>
                                    <a href="#"> Sistemas de seguridad <span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li><a <?=($this->uri->segment(2)==='cdm_alarmas_balance')?'class="active"':''?> href="<?=site_url($controlador.'/cdm_alarmas_balance/')?>"><i class="fa fa-tasks"></i> Balance &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)==='cdm_alarmas_incidencias')?'class="active"':''?> href="<?=site_url($controlador.'/cdm_alarmas_incidencias/')?>"><i class="fa fa-exclamation-triangle"></i> Incidencias &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)==='cdm_alarmas_consumo')?'class="active"':''?> href="<?=site_url($controlador.'/cdm_alarmas_consumo/')?>"><i class="fa fa-bars"></i> An??lisis de consumo &raquo;</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                        <?php
                            /*** CUADROS DE MANDO **/
                            $inf = array('informes',
                                                'informe_pdv',
                                                'informe_planogramas',
                                                    'informe_planograma_mueble_pds',
                                                    'informe_planograma_terminal',
                                                'informe_visual',
                                                    'informe_visual_mueble_sfid',
                                                    'informe_visual_terminal',
                                                    'informe_visual_ficha_terminal',
                                                    'informe_visual_mueble',
                                                'tiendas_tipologia',
                                                'tiendas_fabricante');
                                              //  'informe_sistemas_seguridad'); ?>

                        <li <?=(in_array($this->uri->segment(2), $inf))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-file"></i> Informes <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a <?=($this->uri->segment(2)==='informe_pdv')?'class="active"':''?> href="<?=site_url($controlador.'/informe_pdv')?>"> Puntos de Venta &raquo;</a></li>
                                <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'informe_planogramas',
                                        'informe_planograma_mueble_pds',
                                        'informe_planograma_terminal')))?'class="active"':''?> href="<?=site_url($controlador.'/informe_planogramas')?>"> Planogramas &raquo;</a></li>
                                <li><a <?=(
                                        in_array($this->uri->segment(2),
                                            array('informe_visual',
                                                'informe_visual_mueble_sfid','informe_visual_mueble',
                                                'informe_visual_terminal',
                                                'informe_visual_ficha_terminal')))?'class="active"':''?> href="<?=site_url($controlador.'/informe_visual')?>"> Visual &raquo;</a></li>

                                <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'tiendas_tipologia')))?'class="active"':''?> href="<?=site_url($controlador.'/tiendas_tipologia')?>"> Tiendas por tipolog??a &raquo;</a></li>
                                
                                <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'tiendas_fabricante')))?'class="active"':''?> href="<?=site_url($controlador.'/tiendas_fabricante')?>"> Tiendas por fabricante &raquo;</a></li>
                               <!-- <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'informe_sistemas_seguridad')))?'class="active"':''?> href="<?=site_url($controlador.'/informe_sistemas_seguridad')?>"> Alarmas utilizadas &raquo;</a></li>-->
                            </ul>
                        </li>


                        <?php
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
