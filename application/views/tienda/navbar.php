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
               <li><?php $this->load->view('tienda/infoTienda');?> </li>
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu" style="margin-top:20px;">
                        <?php $estado_incidencias = array("estado_incidencias","detalle_incidencia");
                        $estado_incidencias_inner = array();
                        ?>

                        <li <?=(in_array($this->uri->segment(2), $estado_incidencias)? ' class="active" ' :'')?>>
                            <a href="#"><i class="fa fa-dashboard fa-fw"></i> Estado incidencias <span class="fa arrow"></span></a>

                            <ul class="nav nav-second-level">
                                <li><a <?=($this->uri->segment(3)==='abiertas')?'class="active"':''?> href="<?=site_url('tienda/estado_incidencias/abiertas')?>"> Incidencias abiertas &raquo;</a></li>
                                <li><a <?=($this->uri->segment(3)==='cerradas')?'class="active"':''?> href="<?=site_url('tienda/estado_incidencias/cerradas')?>"> Incidencias cerradas &raquo;</a></li>
                            </ul>

                        </li>
                        <li><a <?=($this->uri->segment(2)==='alta_incidencia')?'class="active"':''?> href="<?=site_url('tienda/alta_incidencia')?>"><i class="fa fa-ticket fa-fw"></i> Alta incidencia</a> </li>
                        <?php
                        /*
                         * En el caso de que acceda una tienda de tipo SMARTSOTRE se le mostrara el menu para poder hacer pedidos y comprobar su estado
                         */
                        if ($this->session->userdata('hacePedidos')){
                            $estado_pedidos = array("pedidos",'detalle_pedido');
                            //$estado_incidencias_inner = array();
                            ?>
                            <li <?=(in_array($this->uri->segment(2), $estado_pedidos)? ' class="active" ' :'')?>>
                                <a href="#"><i class="fa fa-dashboard fa-file-text-o"></i> Estado pedidos <span class="fa arrow"></span></a>

                                <ul class="nav nav-second-level">
                                    <li><a <?=(($this->uri->segment(2)==='pedidos') && ($this->uri->segment(3)==='abiertos'))?'class="active"':''?> href="<?=site_url('tienda/pedidos/abiertos')?>"> Pedidos abiertos &raquo;</a></li>
                                    <li><a <?=(($this->uri->segment(2)==='pedidos') && ($this->uri->segment(3)==='finalizados'))?'class="active"':''?> href="<?=site_url('tienda/pedidos/finalizados')?>"> Pedidos finalizados &raquo;</a></li>
                                    <!--<li><a <?=($this->uri->segment(2)==='alta_pedido')?'class="active"':''?> href="<?=site_url('tienda/alta_pedido')?>"><i class="fa fa-ticket fa-fw"></i> Alta de pedido &raquo;</a></li>-->
                                </ul>

                            </li>
                            <li><a <?=($this->uri->segment(2)==='alta_pedido')?'class="active"':''?> href="<?=site_url('tienda/alta_pedido')?>"><i class="fa fa-ticket fa-fw"></i> Alta pedido</a> </li>
                        <?php } ?>

                        <li <?=($this->uri->segment(2)==='ayuda'||$this->uri->segment(2)==='manuales')?'class="active"':''?>>
                        <a href="#"><i class="fa fa-question-circle fa-fw"></i> Ayuda<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a <?=($this->uri->segment(3)==='1')?'class="active"':''?> href="<?=site_url('tienda/ayuda/1')?>"> Mis solicitudes &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='2')?'class="active"':''?> href="<?=site_url('tienda/ayuda/2')?>"> Alta incidencia &raquo;</a></li>
                            <li><a <?=($this->uri->segment(3)==='3')?'class="active"':''?> href="<?=site_url('tienda/ayuda/3')?>"> Alta incidencia sistema seguridad general del mueble &raquo;</a></li>
                            <!--<li><a <?=($this->uri->segment(3)==='4')?'class="active"':''?> href="<?=site_url('tienda/ayuda/4')?>"> Incidencias frecuentes &raquo;</a></li>-->
                            <li><a <?=($this->uri->segment(2)==='manuales')?'class="active"':''?> href="<?=site_url('tienda/ayuda/5')?>"> Manuales &raquo;</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
		<!-- /#navbar -->
