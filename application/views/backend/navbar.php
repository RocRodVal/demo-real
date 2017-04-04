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
               <li><a href="<?=site_url($acceso.'/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>	            
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu" style="margin-top:20px;">
                        <?php $estado_incidencias = array("estado_incidencias","incidencias_exp","cdm_incidencias","operar_incidencia",'incidencias','update_incidencia_materiales');
                           // $cdm = array('cdm_incidencias');
                            $estado_incidencias_inner = array();
                            $arr_anios = array();
                            $anio_inicial = 2015; $este_anio = date("Y");
                            for($i = $este_anio; $i >= $anio_inicial; $i--) {
                                $arr_anios[] = $i;
                            }
                        ?>

                        <li <?=(in_array($this->uri->segment(2), $estado_incidencias)? ' class="active" ' :'')?>>
                            <a href="#"><i class="fa fa-dashboard fa-fw"></i> Estado incidencias <span class="fa arrow"></span></a>

                            <ul class="nav nav-second-level">
                                <li><a <?=((($this->uri->segment(2)==='estado_incidencias') && ($this->uri->segment(3)==='abiertas'))
                                        || ($this->uri->segment(2)=='update_incidencia_materiales')) ?'class="active"':''?>
                                        href="<?=site_url($acceso.'/estado_incidencias/abiertas')?>"> Incidencias abiertas &raquo;</a></li>
                                <li><a <?=(($this->uri->segment(2)==='estado_incidencias') && ($this->uri->segment(3)==='cerradas'))?'class="active"':''?> href="<?=site_url($acceso.'/estado_incidencias/cerradas')?>"> Incidencias cerradas &raquo;</a></li>
                              <?php /*  <li><a <?=($this->uri->segment(3)==='incidencias')?'class="active"':''?> href="<?=site_url($acceso.'/incidencias')?>"> Exportar todas &raquo;</a></li> */ ?>
                                <li><a <?=($this->uri->segment(2)==='incidencias_exp')?'class="active"':''?> href="<?=site_url($acceso.'/incidencias_exp')?>"> Exportar todas &raquo;</a></li>
                                <li <?=($this->uri->segment(2)==='cdm_incidencias')?'class="active"':''?>>
                                    <a href="#">Resumen por año<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li <?=($this->uri->segment(2)==='cdm_incidencias'  && in_array($this->uri->segment(3),$arr_anios))?'class="active"':''?> >

                                            <?php
                                            for($i = $este_anio; $i >= $anio_inicial; $i--){ ?>
                                                <li><a <?=($this->uri->segment(2)==='cdm_incidencias'  && $this->uri->segment(3)==$i)?'class="active"':''?> href="<?=site_url($acceso.'/cdm_incidencias/'.$i)?>">
                                                <i class="fa fa-calendar fa-fw"></i> <?=$i?></a></li>
                                        <?php } ?>
                                        </li>
                                    </ul>
                                </li>
                                <li><a <?=($this->uri->segment(2)==='incidencias') ?'class="active"':''?> href="<?=site_url('inventario/incidencias')?>">Incidencias dispositivo &raquo;</a></li>
                            </ul>
                        </li>
                        <?php $estado_pedidos = array("pedidos","abiertos","finalizados","operar_pedido","imprimir_pedido"); ?>
                        <li <?=(($this->uri->segment(2)==='pedidos') || ($this->uri->segment(3)==='abiertos') || ($this->uri->segment(3)==='finalizados') || ($this->uri->segment(2)==='operar_pedido') || ($this->uri->segment(2)==='imprimir_pedido'))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-dashboard fa-file-text-o"></i> Estado pedidos  <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a <?=($this->uri->segment(3)==='abiertos')?'class="active"':''?> href="<?=site_url($acceso.'/pedidos/abiertos')?>">Pedidos abiertos &raquo;</a></li>
                                <li><a <?=($this->uri->segment(3)=='finalizados')?'class="active"':''?> href="<?=site_url($acceso.'/pedidos/finalizados')?>">Pedidos finalizados &raquo;</a></li>
                            </ul>
                        </li>
                        <?php
                        $maestros = array('clientes','contactos','alarmas','dispositivos','muebles','puntos_de_venta','categorias_pdv','razones_parada','soluciones_ejecutadas');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $maestros))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Maestros<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li><a <?=($this->uri->segment(2)==='clientes')?'class="active"':''?> href="<?=site_url($acceso.'/clientes')?>"> Empresas &raquo;</a></li> 
                                <li><a <?=($this->uri->segment(2)==='contactos')?'class="active"':''?> href="<?=site_url($acceso.'/contactos')?>"> Contactos &raquo;</a></li>        
                                <li><a <?=($this->uri->segment(2)==='alarmas')?'class="active"':''?> href="<?=site_url($acceso.'/alarmas')?>"> Alarmas &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='dispositivos')?'class="active"':''?> href="<?=site_url($acceso.'/dispositivos')?>"> Dispositivos &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='muebles')?'class="active"':''?> href="<?=site_url($acceso.'/muebles')?>"> Muebles &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='puntos_de_venta')?'class="active"':''?> href="<?=site_url($acceso.'/puntos_de_venta')?>"> Puntos de venta &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='categorias_pdv')?'class="active"':''?> href="<?=site_url($acceso.'/categorias_pdv')?>"> Categorías PdV &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='razones_parada')?'class="active"':''?> href="<?=site_url($acceso.'/razones_parada')?>"> Razones de parada &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='soluciones_ejecutadas')?'class="active"':''?> href="<?=site_url($acceso.'/soluciones_ejecutadas')?>"> Soluciones ejecutadas &raquo;</a></li>
                            </ul>
                        </li>

                        <?php               
                        $almacenes = array('almacen','inventario_dispositivos','dispositivos_almacen','alta_dispositivos_almacen','alta_dispositivos_ok','baja_dispositivos_almacen','baja_dispositivos_ok','baja_dispositivos_ko','alarmas_en_almacen','diario_almacen',
                            'informe_sistemas_seguridad','balance','alarmas_almacen','dispositivos_tiendas','muebles_tiendas','dispositivos_recogida','recepcion_incidencia','insert_almacen');
                        ?>                      
                        <li <?=(in_array($this->uri->segment(2), $almacenes))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-cubes fa-fw"></i> Almacén<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li <?=(($this->uri->segment(2)==='alarmas_almacen') || ($this->uri->segment(2)==='informe_sistemas_seguridad') || ($this->uri->segment(2)==='alarmas_en_almacen'))?'class="active"':''?>>
                                    <a href="#">Sistemas de seguridad  <span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li><a <?=($this->uri->segment(2)==='alarmas_almacen')?'class="active"':''?> href="<?=site_url($acceso.'/alarmas_almacen')?>"><i class="fa fa-tasks"></i> Gestión &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)=='informe_sistemas_seguridad')?'class="active"':''?> href="<?=site_url($acceso.'/informe_sistemas_seguridad')?>"><i class="fa fa-bars"></i> Análisis de consumo &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)==='alarmas_en_almacen')?'class="active"':''?> href="<?=site_url('inventario/alarmas_en_almacen')?>"><i class="fa fa-rss"></i> Alarmas almacen &raquo;</a></li>
                                    </ul>
                                </li>
                                <li><a <?=(($this->uri->segment(1)==='inventario') && ($this->uri->segment(2)==='balance')) ?'class="active"':''?> href="<?=site_url('inventario/balance')?>">Balance &raquo;</a></li>
                                <li <?=(($this->uri->segment(2)==='inventario_dispositivos') || ($this->uri->segment(2)==='dispositivos_almacen') || ($this->uri->segment(2)==='dispositivos_recogida') || ($this->uri->segment(2)==='dispositivos_tiendas')
                                    || ($this->uri->segment(2)==='alta_dispositivos_almacen')|| ($this->uri->segment(2)==='alta_dispositivos_ok') || ($this->uri->segment(2)==='baja_dispositivos_almacen') || ($this->uri->segment(2)==='baja_dispositivos_ko')
                                    || ($this->uri->segment(2)==='baja_dispositivos_ok') || ($this->uri->segment(2)==='recepcion_incidencia') || $this->uri->segment(2)==='insert_almacen')?'class="active"':''?>>
                                    <a href="#">Dispositivos  <span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li><a <?=($this->uri->segment(2)==='recepcion_incidencia' || $this->uri->segment(2)==='insert_almacen')?'class="active"':''?> href="<?=site_url($acceso.'/recepcion_incidencia')?>"><i class="fa fa-tasks"></i> Recepcion Inc &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)==='inventario_dispositivos')?'class="active"':''?> href="<?=site_url($acceso.'/inventario_dispositivos')?>"><i class="fa fa-tasks"></i> Inventario &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)==='dispositivos_almacen')?'class="active"':''?> href="<?=site_url('inventario/dispositivos_almacen')?>"><i class="fa fa-mobile-phone"></i> En almacen &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)==='dispositivos_recogida')?'class="active"':''?> href="<?=site_url('inventario/dispositivos_recogida')?>"><i class="fa fa-download"></i> Pendientes recogida &raquo;</a></li>
                                        <li><a <?=($this->uri->segment(2)==='dispositivos_tiendas')?'class="active"':''?> href="<?=site_url('inventario/dispositivos_tiendas')?>"><i class="fa fa-phone-square"></i> En tienda &raquo;</a></li>
                                        <li><a <?=(in_array($this->uri->segment(2),array('alta_dispositivos_almacen','alta_dispositivos_ok')))?'class="active"':''?> href="<?=site_url($acceso.'/alta_dispositivos_almacen')?>"><i class="fa fa-check"></i> Alta masiva &raquo;</a></li>
                                        <li><a <?=(in_array($this->uri->segment(2),array('baja_dispositivos_almacen','baja_dispositivos_ok','baja_dispositivos_ko')))?'class="active"':''?> href="<?=site_url($acceso.'/baja_dispositivos_almacen')?>"><i class="fa fa-times"></i> Baja masiva &raquo;</a></li>
                                    </ul>
                                </li>

                                <li><a <?=($this->uri->segment(2)==='muebles_tiendas') ?'class="active"':''?> href="<?=site_url('inventario/muebles_tiendas')?>">Muebles tiendas &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='diario_almacen')?'class="active"':''?> href="<?=site_url($acceso.'/diario_almacen')?>"> Diario almacén &raquo;</a></li>
                        	</ul>
                        </li>

                        <?php               
                        $exposicion = array('inventario_tienda','inventario_muebles');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $exposicion))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-building-o fa-fw"></i> Exposición<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                             	<li><a <?=($this->uri->segment(2)==='inventario_muebles')?'class="active"':''?> href="<?=site_url($acceso.'/inventario_muebles')?>"> Inventario muebles &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='inventario_tienda')?'class="active"':''?> href="<?=site_url($acceso.'/inventario_tienda')?>"> Inventario dispositivos &raquo;</a></li>
                            </ul>
                        </li>
                        <?php $facturaciones = array('facturacion','facturacion_intervencion','facturacion_fabricanteM'); ?>
                        <li <?=(in_array($this->uri->segment(2),$facturaciones)) ? 'class="active"' : ''; ?>>
                            <a href="#"><i class="fa fa-money fa-fw"></i> Facturación<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a <?=($this->uri->segment(2)==='facturacion')?'class="active"':''?> href="<?=site_url($acceso.'/facturacion')?>"> Intervenciones &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='facturacion_intervencion')?'class="active"':''?> href="<?=site_url($acceso.'/facturacion_intervencion')?>"> Proveedores &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='facturacion_fabricanteM')?'class="active"':''?> href="<?=site_url($acceso.'/facturacion_fabricanteM')?>"> Fabricante Mueble &raquo;</a></li>
                            </ul>
                        </li>

                        <?php
                        //$operaciones = array('operaciones','apertura_pdv','cierre_pdv','cambio_sfid','incidencias_exp','reset_incidencia_status','backup','anadir_mueble_sfid');
                        $operaciones = array('operaciones','apertura_pdv','cierre_pdv','cambio_sfid','reset_incidencia_status');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $operaciones))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Operaciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <!--<li><a <?=($this->uri->segment(2)==='operaciones')?'class="active"':''?> href="<?=site_url($acceso.'/operaciones')?>"> Operaciones &raquo;</a></li>-->
                                <li><a <?=($this->uri->segment(2)==='apertura_pdv')?'class="active"':''?> href="<?=site_url($acceso.'/apertura_pdv')?>"> Apertura PdV &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='cierre_pdv')?'class="active"':''?> href="<?=site_url($acceso.'/cierre_pdv')?>"> Cierre PdV &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='cambio_sfid')?'class="active"':''?> href="<?=site_url($acceso.'/cambio_sfid')?>"> Cambio de SFID &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='reset_incidencia_status')?'class="active"':''?> href="<?=site_url($acceso.'/reset_incidencia_status')?>"> Reset incidencia &raquo;</a></li>

                            </ul>
                        </li>

                        <?php
                        //$masivas = array('operaciones','anadir_mueble_sfid','cierre_pdv','cambio_sfid','incidencias','incidencias_exp','reset_incidencia_status','informe_backup','anadir_mueble_sfid');
                        $masivas = array('informe_backup','anadir_mueble_sfid');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $masivas))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Operaciones masivas<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a <?=($this->uri->segment(2)==='informe_backup')?'class="active"':''?> href="<?=site_url($acceso.'/informe_backup')?>"> Informes para Backup &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='anadir_mueble_sfid')?'class="active"':''?> href="<?=site_url($acceso.'/anadir_mueble_sfid')?>"> Añadir mueble a SFID(s) &raquo;</a></li>
                            </ul>
                        </li>

                        <?php /**
                         * CUADROS DE MANDO
                         *
                         */
                        ?>
                        <?php $inf = array('informes',
                            'cdm_estado_incidencias',
                            'informe_pdv',
                            'informe_planogramas',
                            'informe_planograma_mueble_pds',
                            'informe_planograma_terminal',
                            'informe_visual',
                            'informe_visual_mueble_sfid',
                            'informe_visual_terminal',
                            'informe_visual_ficha_terminal',
                            'tiendas_tipologia',
                            'tiendas_fabricante'

                        ); ?>

                        <li <?=(in_array($this->uri->segment(2), $inf))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-file"></i> Informes <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                              <?php /*  <li><a <?=($this->uri->segment(2)==='cdm_estado_incidencias')?'class="active"':''?> href="<?=site_url($acceso.'/cdm_estado_incidencias')?>"> Estado incidencias &raquo;</a></li>*/?>
                                <li><a <?=($this->uri->segment(2)==='informe_pdv')?'class="active"':''?> href="<?=site_url($acceso.'/informe_pdv')?>"> Puntos de Venta &raquo;</a></li>
                                <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'informe_planogramas',
                                            'informe_planograma_mueble_pds',
                                            'informe_planograma_terminal')))?'class="active"':''?> href="<?=site_url($acceso.'/informe_planogramas')?>"> Planogramas &raquo;</a></li>
                                <li><a <?=(
                                    in_array($this->uri->segment(2),
                                        array('informe_visual',
                                            'informe_visual_mueble_sfid',
                                            'informe_visual_terminal',
                                            'informe_visual_ficha_terminal')))?'class="active"':''?> href="<?=site_url($acceso.'/informe_visual')?>"> Visual &raquo;</a></li>
                                <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'tiendas_tipologia')))?'class="active"':''?> href="<?=site_url($acceso.'/tiendas_tipologia')?>"> Tiendas por tipología &raquo;</a></li>

                                <li><a <?=(in_array($this->uri->segment(2),
                                        array( 'tiendas_fabricante')))?'class="active"':''?> href="<?=site_url($acceso.'/tiendas_fabricante')?>"> Tiendas por fabricante &raquo;</a></li>
                            </ul>
                        </li>

                        <?php 
                        $ayuda = array('ayuda','manuales','muebles_fabricantes');
                        ?>	                        
                        <li <?=(in_array($this->uri->segment(2), $ayuda))?'class="active"':''?>>
                        <a href="#"><i class="fa fa-question-circle fa-fw"></i> Ayuda<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a <?=($this->uri->segment(3)==='1')?'class="active"':''?> href="<?=site_url($acceso.'/ayuda/1')?>"> Mis solicitudes &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='2')?'class="active"':''?> href="<?=site_url($acceso.'/ayuda/2')?>"> Alta incidencia &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='3')?'class="active"':''?> href="<?=site_url($acceso.'/ayuda/3')?>"> Alta incidencia sistema seguridad general del mueble &raquo;</a></li>
                            <!--<li><a <?=($this->uri->segment(3)==='4')?'class="active"':''?> href="<?=site_url($acceso.'/ayuda/4')?>"> Incidencias frecuentes &raquo;</a></li>-->
                            <li><a <?=($this->uri->segment(2)==='manuales')?'class="active"':''?> href="<?=site_url($acceso.'/ayuda/5')?>"> Manuales &raquo;</a></li>
                            <li><a <?=($this->uri->segment(2)==='muebles_fabricantes')?'class="active"':''?> href="<?=site_url($acceso.'/ayuda/6')?>"> Muebles fabricantes &raquo;</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
