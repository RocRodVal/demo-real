		<!-- #navbar -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?=site_url('tienda/dashboard')?>"><img src="<?=site_url('assets/images/logo-orange_small.png')?>" class="logo" title="<?=lang('comun.titulo')?>" /></a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
               <li><?php $this->load->view('tienda/infoTienda');?> </li>
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu" style="margin-top:20px;">
                        <li><a <?=($this->uri->segment(2)==='dashboard')?'class="active"':''?> href="<?=site_url('tienda/dashboard')?>"><i class="fa fa-dashboard fa-fw"></i> Mis solicitudes</a></li>
                        <li><a <?=($this->uri->segment(2)==='alta_incidencia')?'class="active"':''?> href="<?=site_url('tienda/alta_incidencia')?>"><i class="fa fa-ticket fa-fw"></i></i> Alta incidencia</a> </li>   
                        <li <?=($this->uri->segment(2)==='ayuda'||$this->uri->segment(2)==='manuales')?'class="active"':''?>>
                        <a href="#"><i class="fa fa-question-circle fa-fw"></i> Ayuda<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a <?=($this->uri->segment(3)==='1')?'class="active"':''?> href="<?=site_url('tienda/ayuda/1')?>"> Mis solicitudes</a></li>
                            <li><a <?=($this->uri->segment(3)==='2')?'class="active"':''?> href="<?=site_url('tienda/ayuda/2')?>"> Alta incidencia</a></li>
                            <li><a <?=($this->uri->segment(3)==='3')?'class="active"':''?> href="<?=site_url('tienda/ayuda/3')?>"> Alta incidencia sistema seguridad general del mueble</a></li>
                            <!--<li><a <?=($this->uri->segment(3)==='4')?'class="active"':''?> href="<?=site_url('tienda/ayuda/4')?>"> Incidencias frecuentes</a></li>-->
                            <li><a <?=($this->uri->segment(2)==='manuales')?'class="active"':''?> href="<?=site_url('tienda/ayuda/5')?>"> Manuales</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
