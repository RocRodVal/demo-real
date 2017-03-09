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
               if ($this->session->userdata('type') == 12)
               {
               ?>
               <li><a href="<?=site_url('territorio/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>	            
			   <?php } ?>

            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu" style="margin-top:20px;">
                        <?php
                        if ($this->session->userdata('type') == 12)
                        {
                        ?>

                            <?php $estado_incidencias = array("estado_incidencias","detalle_incidencia");
                            $estado_incidencias_inner = array();
                            ?>

                            <li <?=(in_array($this->uri->segment(2), $estado_incidencias)? ' class="active" ' :'')?>>
                                <a href="#"><i class="fa fa-dashboard fa-fw"></i> Estado incidencias <span class="fa arrow"></span></a>

                                <ul class="nav nav-second-level">
                                    <li><a <?=($this->uri->segment(3)==='abiertas')?'class="active"':''?> href="<?=site_url('territorio/estado_incidencias/abiertas')?>"> Incidencias abiertas &raquo;</a></li>
                                    <li><a <?=($this->uri->segment(3)==='cerradas')?'class="active"':''?> href="<?=site_url('territorio/estado_incidencias/cerradas')?>"> Incidencias cerradas &raquo;</a></li>
                                </ul>

                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
