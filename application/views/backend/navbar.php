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
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?=site_url('admin/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Buscar...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                        </li>
                        <?php
                        if ($this->session->userdata('type') == 9)
                        {
                        ?>                                                 
                        <li><a <?=($this->uri->segment(2)==='dashboard')?'class="active"':''?> href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                        <?php               
                        $maestros = array('clientes','contactos','alarmas','dispositivos','muebles','puntos_de_venta');
                        ?>
                        <li <?=(in_array($this->uri->segment(2), $maestros))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Maestros<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li><a <?=($this->uri->segment(2)==='clientes')?'class="active"':''?> href="<?=site_url('admin/clientes')?>">Clientes &raquo;</a></li> 
                                <li><a <?=($this->uri->segment(2)==='contactos')?'class="active"':''?> href="<?=site_url('admin/contactos')?>">Contactos &raquo;</a></li>        
                                <li><a <?=($this->uri->segment(2)==='alarmas')?'class="active"':''?> href="<?=site_url('admin/alarmas')?>">Alarmas &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='dispositivos')?'class="active"':''?> href="<?=site_url('admin/dispositivos')?>">Dispositivos &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='muebles')?'class="active"':''?> href="<?=site_url('admin/muebles')?>">Muebles &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='puntos_de_venta')?'class="active"':''?> href="<?=site_url('admin/puntos_de_venta')?>">Puntos de venta &raquo;</a></li>     
                            </ul>
                        </li>
                        <li><a <?=($this->uri->segment(2)==='auditorias')?'class="active"':''?> href="<?=site_url('admin/auditorias')?>"><i class="fa fa-list-alt fa-fw"></i> Auditorías</a> </li>
                        <li><a <?=($this->uri->segment(1)==='intervencion')?'class="active"':''?> href="<?=site_url('intervencion/')?>"><i class="fa fa-list-ul fa-fw"></i> Intervenciones</a> </li>
                        <li><a <?=($this->uri->segment(2)==='incidencias')?'class="active"':''?> href="<?=site_url('admin/incidencias')?>"><i class="fa fa-list-ul fa-fw"></i> Incidencias</a> </li>
                        <li><a <?=($this->uri->segment(2)==='almacen')?'class="active"':''?> href="<?=site_url('admin/almacen')?>"><i class="fa  fa-table fa-fw"></i> Almacén</a> </li>
                        <li><a <?=($this->uri->segment(2)==='inventarios')?'class="active"':''?> href="<?=site_url('admin/inventarios')?>"><i class="fa  fa-table fa-fw"></i> Inventarios</a> </li>
                        <li><a <?=($this->uri->segment(2)==='facturacion')?'class="active"':''?> href="<?=site_url('admin/facturacion')?>"><i class="fa fa-money fa-fw"></i> Facturación</a> </li>
                        <li><a <?=($this->uri->segment(2)==='operaciones')?'class="active"':''?> href="<?=site_url('admin/operaciones')?>"><i class="fa fa-wrench fa-fw"></i> Operaciones</a> </li>
                        <?php 
                        }
                        else 
                        {
                        ?>
                        <li><a <?=($this->uri->segment(2)==='dashboard')?'class="active"':''?> href="<?=site_url('admin/dashboard_pds')?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                        <?php	
                        }	
                        ?>
                        <li><a <?=($this->uri->segment(2)==='ayuda')?'class="active"':''?> href="<?=site_url('admin/ayuda')?>"><i class="fa fa-question-circle fa-fw"></i> Ayuda</a> </li>   
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
