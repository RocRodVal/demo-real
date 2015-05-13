		<!-- #navbar -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?=site_url('admin/dashboard')?>"><img src="<?=site_url('assets/images/logo-orange_small.png')?>" class="logo" title="<?=lang('comun.titulo')?>" /></a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
               <li><a href="<?=site_url('admin/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>	            
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu" style="margin-top:20px;">
                        <li><a <?=($this->uri->segment(2)==='dashboard')?'class="active"':''?> href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                        <!--<li><a <?=($this->uri->segment(2)==='carga_datos_dispositivo')?'class="active"':''?> href="<?=site_url('admin/carga_datos_dispositivo')?>"><i class="fa fa-retweet fa-fw"></i> Carga datos dispositivo</a></li>-->
                        <!--<li><a <?=($this->uri->segment(2)==='material_retorno')?'class="active"':''?> href="<?=site_url('admin/material_retorno')?>"><i class="fa fa-mobile fa-fw"></i> Material retorno</a></li>-->
                        <?php               
                        $maestros = array('clientes','contactos','alarmas','dispositivos','muebles','puntos_de_venta');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $maestros))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Maestros<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li><a <?=($this->uri->segment(2)==='clientes')?'class="active"':''?> href="<?=site_url('admin/clientes')?>">Empresas &raquo;</a></li> 
                                <li><a <?=($this->uri->segment(2)==='contactos')?'class="active"':''?> href="<?=site_url('admin/contactos')?>">Contactos &raquo;</a></li>        
                                <li><a <?=($this->uri->segment(2)==='alarmas')?'class="active"':''?> href="<?=site_url('admin/alarmas')?>">Alarmas &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='dispositivos')?'class="active"':''?> href="<?=site_url('admin/dispositivos')?>">Dispositivos &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='muebles')?'class="active"':''?> href="<?=site_url('admin/muebles')?>">Muebles &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='puntos_de_venta')?'class="active"':''?> href="<?=site_url('admin/puntos_de_venta')?>">Puntos de venta &raquo;</a></li>     
                            </ul>
                        </li>
                        <!--<li><a <?=($this->uri->segment(2)==='auditorias')?'class="active"':''?> href="<?=site_url('admin/auditorias')?>"><i class="fa fa-list-alt fa-fw"></i> Auditorías</a></li>-->
                        <!--<li><a <?=($this->uri->segment(1)==='intervencion')?'class="active"':''?> href="<?=site_url('intervencion')?>"><i class="fa fa-cog fa-fw"></i> Intervenciones</a></li>-->
                        <li><a <?=($this->uri->segment(1)==='inventario')?'class="active"':''?> href="<?=site_url('inventario')?>"><i class="fa fa-table fa-fw"></i> Depósito</a></li>
                        <?php               
                        $almacenes = array('almacen','alta_dispositivos_almacen','baja_dispositivos_almacen','alarmas_almacen');
                        ?>                      
                        <li <?=(in_array($this->uri->segment(2), $almacenes))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-cubes fa-fw"></i> Almacén<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">                        
                           		<li><a <?=($this->uri->segment(2)==='almacen')?'class="active"':''?> href="<?=site_url('admin/almacen')?>">Inventario &raquo;</a></li>
                           		<li><a <?=($this->uri->segment(2)==='alta_dispositivos_almacen')?'class="active"':''?> href="<?=site_url('admin/alta_dispositivos_almacen')?>">Alta masiva dispositivos &raquo;</a></li>
                           		<li><a <?=($this->uri->segment(2)==='baja_dispositivos_almacen')?'class="active"':''?> href="<?=site_url('admin/baja_dispositivos_almacen')?>">Baja masiva dispositivos &raquo;</a></li>
                           		<li><a <?=($this->uri->segment(2)==='alarmas_almacen')?'class="active"':''?> href="<?=site_url('admin/alarmas_almacen')?>">Gestión alarmas &raquo;</a></li>
                        	</ul>
                        </li>		
                        <?php               
                        $exposicion = array('descripcion','exp_alta_incidencia','exp_alta_incidencia_mueble','exp_alta_incidencia_device','inventarios_panelados','inventarios_planogramas','inventarios','listado_panelados');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $exposicion))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-building-o fa-fw"></i> Exposición<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li><a <?=($this->uri->segment(2)==='inventarios_panelados')?'class="active"':''?> href="<?=site_url('admin/inventarios_panelados')?>">Panelado genérico &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='inventarios_planogramas')?'class="active"':''?> href="<?=site_url('admin/inventarios_planogramas')?>">Planograma genérico &raquo;</a></li>
                            	<?php
                            	$descripcion = array('descripcion','exp_alta_incidencia','exp_alta_incidencia_mueble','exp_alta_incidencia_device');
                            	?>
                            	<li><a <?=(in_array($this->uri->segment(2), $descripcion))?'class="active"':''?> href="<?=site_url('admin/descripcion')?>">Planograma tiendas &raquo;</a></li>
                             	<li><a <?=($this->uri->segment(2)==='inventarios')?'class="active"':''?> href="<?=site_url('admin/inventarios')?>">Inventarios tiendas &raquo;</a></li>
                            </ul>
                        </li>                        
                        <li><a <?=($this->uri->segment(2)==='facturacion')?'class="active"':''?> href="<?=site_url('admin/facturacion')?>"><i class="fa fa-money fa-fw"></i> Facturación</a></li>
                        <?php               
                        $operaciones = array('operaciones','cambio_sfid','incidencias');
                        ?>                      
                        <li <?=(in_array($this->uri->segment(2), $operaciones))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Operaciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level"> 
                        		<!--<li><a <?=($this->uri->segment(2)==='operaciones')?'class="active"':''?> href="<?=site_url('admin/operaciones')?>">Operaciones &raquo;</a></li>-->
                        		<li><a <?=($this->uri->segment(2)==='cambio_sfid')?'class="active"':''?> href="<?=site_url('admin/cambio_sfid')?>">Cambio de SFID &raquo;</a></li>                     		
                        		<li><a <?=($this->uri->segment(2)==='incidencias')?'class="active"':''?> href="<?=site_url('admin/incidencias')?>">Export incidencias &raquo;</a></li>                                               
                        	</ul>
                        </li>	                        
                        <li <?=($this->uri->segment(2)==='ayuda')?'class="active"':''?>>
                        <a href="#"><i class="fa fa-question-circle fa-fw"></i> Ayuda<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a <?=($this->uri->segment(3)==='1')?'class="active"':''?> href="<?=site_url('admin/ayuda/1')?>">Mis solicitudes &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='2')?'class="active"':''?> href="<?=site_url('admin/ayuda/2')?>">Alta incidencia &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='3')?'class="active"':''?> href="<?=site_url('admin/ayuda/3')?>">Alta incidencia sistema seguridad general del mueble &raquo;</a></li>
                            <!--<li><a <?=($this->uri->segment(3)==='4')?'class="active"':''?> href="<?=site_url('admin/ayuda/4')?>">Incidencias frecuentes &raquo;</a></li>-->
                            <li><a <?=($this->uri->segment(2)==='manuales')?'class="active"':''?> href="<?=site_url('admin/ayuda/5')?>">Manuales &raquo;</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
