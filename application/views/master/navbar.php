		<!-- #navbar -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?=site_url('master/dashboard')?>"><img src="<?=site_url('assets/images/logo-orange_small.png')?>" class="logo" title="<?=lang('comun.titulo')?>" /></a>
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
                        <li><a <?=($this->uri->segment(2)==='dashboard')?'class="active"':''?> href="<?=site_url('master/dashboard')?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                        <?php               
                        $maestros = array('clientes','contactos','alarmas','dispositivos','muebles','puntos_de_venta');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $maestros))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Maestros<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li><a <?=($this->uri->segment(2)==='clientes')?'class="active"':''?> href="<?=site_url('master/clientes')?>">Clientes &raquo;</a></li> 
                                <li><a <?=($this->uri->segment(2)==='contactos')?'class="active"':''?> href="<?=site_url('master/contactos')?>">Contactos &raquo;</a></li>        
                                <li><a <?=($this->uri->segment(2)==='alarmas')?'class="active"':''?> href="<?=site_url('master/alarmas')?>">Alarmas &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='dispositivos')?'class="active"':''?> href="<?=site_url('master/dispositivos')?>">Dispositivos &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='muebles')?'class="active"':''?> href="<?=site_url('master/muebles')?>">Muebles &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='puntos_de_venta')?'class="active"':''?> href="<?=site_url('master/puntos_de_venta')?>">Puntos de venta &raquo;</a></li>     
                            </ul>
                        </li>
                        <li><a <?=($this->uri->segment(2)==='inventario')?'class="active"':''?> href="<?=site_url('master/inventario')?>"><i class="fa fa-table fa-fw"></i> Depósito</a></li>
                        <?php               
                        $exposicion = array('descripcion','exp_alta_incidencia','exp_alta_incidencia_mueble','exp_alta_incidencia_device','inventarios_panelados','inventarios_planogramas','inventarios');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $exposicion))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Exposición<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li><a <?=($this->uri->segment(2)==='inventarios_planogramas')?'class="active"':''?> href="<?=site_url('master/inventarios_planogramas')?>">Planograma genérico &raquo;</a></li>
                            	<?php
                            	$descripcion = array('descripcion','exp_alta_incidencia','exp_alta_incidencia_mueble','exp_alta_incidencia_device');
                            	?>
                            	<li><a <?=(in_array($this->uri->segment(2), $descripcion))?'class="active"':''?> href="<?=site_url('master/descripcion')?>">Planograma tiendas &raquo;</a></li>
                           	
                            </ul>
                        </li>                        
                        <?php 
                        }
                        ?>
                        <li><a <?=($this->uri->segment(2)==='ayuda')?'class="active"':''?> href="<?=site_url('master/ayuda')?>"><i class="fa fa-question-circle fa-fw"></i> Ayuda</a> </li>   
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
