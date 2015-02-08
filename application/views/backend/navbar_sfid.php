		<!-- #navbar -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?=site_url('admin/dashboard_sfid')?>"><img src="<?=site_url('assets/images/logo-orange_small.png')?>" class="logo" title="<?=lang('comun.titulo')?>" /></a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?=site_url('admin/login_sfid')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
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
                        <li><a <?=($this->uri->segment(2)==='dashboard_sfid')?'class="active"':''?> href="<?=site_url('admin/dashboard_sfid')?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                        <?php 
                        $incidencias = array('listado_incidencias_sfid','alta_incidencia_sfid');
                        ?>                     
                        <!--
                        <li <?=(in_array($this->uri->segment(2), $incidencias))?'class="active"':''?>>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Gestión incidencias<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li><a <?=($this->uri->segment(2)==='listado_incidencias_sfid')?'class="active"':''?> href="<?=site_url('admin/listado_incidencias_sfid')?>">Listado &raquo;</a></li>
                                <li><a <?=($this->uri->segment(2)==='dashboard_sfid')?'class="active"':''?> href="<?=site_url('admin/alta_incidencia_sfid')?>">Alta &raquo;</a></li>
                            </ul>
                        </li>
                        -->
                        <li><a <?=($this->uri->segment(2)==='ayuda_sfid')?'class="active"':''?> href="<?=site_url('admin/ayuda_sfid')?>"><i class="fa fa-question-circle fa-fw"></i> Ayuda</a> </li>   
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
