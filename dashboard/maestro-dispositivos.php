<?php
require ('xcrud/xcrud.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Demo Real Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="css/plugins/timeline.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<!-- favicons -->
	<link rel="shortcut icon" href="../favicon.ico">  
</head>  

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Colapsar navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php"><img src="img/logo-orange_small.png" style="width: 50px; height: 50px; margin-left: 10px;" title="Demo Real Dashboard" /></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Incidencias</strong>
                                        <span class="pull-right text-muted">40% resueltas</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% resueltas (cerradas)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Ver todas</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> Nueva incidencia #234
                                    <span class="pull-right text-muted small">Actualizada hace 4 min.</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> Nueva incidencia #127
                                    <span class="pull-right text-muted small">Actualizada hace 45 min.</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Ver todas las alertas</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Usuario</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Ajustes</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- /.navbar-top-links -->
            
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Dashboard</a>
                        </li>
                        <li class="active">
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Maestros<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="maestro-clientes.php">Clientes &raquo;</a>
                                </li>
                                <li>
                                    <a href="maestro-contactos.php">Contactos &raquo;</a>
                                </li>                                
                                <li>
                                    <a href="#">Puntos de venta <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="pds-tipos.php">Tipos &raquo;</a>
                                        </li>
                                        <li>
                                            <a href="pds-panelados.php">Panelados &raquo;</a>
                                        </li>
                                        <li>
                                            <a href="pds-puntos-de-venta.php">Puntos de venta &raquo;</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>                                 
                                <li>
                                    <a href="maestro-alarmas.php">Alarmas &raquo;</a>
                                </li>                                 
                                <li>
                                    <a href="maestro-displays.php">Displays &raquo;</a>
                                </li>
                                <li>
                                    <a class="active" href="maestro-dispositivos.php">Dispositivos &raquo;</a>
                                </li>                                 
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>                          
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Incidencias<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="alta-incidencia.php">Crear &raquo;</a>
                                </li>
                                <li>
                                    <a href="gestion-incidencia.php">Ver &raquo;</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li> 
                        <li>
                            <a href="auditoria.php"><i class="fa fa-edit fa-fw"></i> Auditorías</a>
                        </li>                           
                        <li>
                            <a href="gestion-instalacion.php"><i class="fa fa-edit fa-fw"></i> Instalaciones</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dispositivos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <?php
                        $xcrud_1 = Xcrud::get_instance();
                        $xcrud_1->table('type_device');
                        $xcrud_1->table_name('Tipos');
                        $xcrud_1->label('type','Tipo');
                        $xcrud_1->columns('type');
                        $xcrud_1->fields('type');
                        echo $xcrud_1->render();
                    ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <?php
                        $xcrud_2 = Xcrud::get_instance();
                        $xcrud_2->table('brand_device');
                        $xcrud_2->table_name('Fabricantes');
                        $xcrud_2->label('brand','Fabricante');
                        $xcrud_2->columns('brand');
                        $xcrud_2->fields('brand');
                        echo $xcrud_2->render();
                    ?>
                </div>
            </div>               
            
            <div class="row">
                <div class="col-lg-12">
                    <?php
                        $xcrud_3 = Xcrud::get_instance();
                        $xcrud_3->table('device');
                        $xcrud_3->table_name('Modelos');
                        $xcrud_3->relation('type_device','type_device','id_type_device','type');
                        $xcrud_3->relation('brand_device','brand_device','id_brand_device','brand');
                        $xcrud_3->change_type('picture_url', 'image');
                        $xcrud_3->label('type_device','Tipo')->label('brand_device','Fabricante')->label('device','Modelo')->label('brand_name','Modelo fabricante')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
                        $xcrud_3->columns('type_device,brand_device,device,status');
                        $xcrud_3->fields('type_device,brand_device,device,brand_name,picture_url,description,status');
                        echo $xcrud_3->render();
                    ?>
                </div>
            </div>                
            
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

</body>
</html>