<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Demo Real Dashboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="css/plugins/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
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
                                <li class="active">
                                    <a href="#">Puntos de venta <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="pds-tipos.php">Tipos &raquo;</a>
                                        </li>
                                        <li>
                                            <a href="pds-panelados.php">Panelados &raquo;</a>
                                        </li>
                                        <li>
                                            <a class="active" href="pds-puntos-de-venta.php">Puntos de venta &raquo;</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                                <li>
                                    <a href="#">Dispositivos <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="dev-tipos.php">Tipos &raquo;</a>
                                        </li>
                                        <li>
                                            <a href="dev-fabricantes.php">Fabricantes &raquo;</a>
                                        </li>
                                        <li>
                                            <a href="dev-dispositivos.php">Dispositivos &raquo;</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li> 
                                <li>
                                    <a href="#">Alarmas <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="alarm-tipos.php">Tipos &raquo;</a>
                                        </li>
                                        <li>
                                            <a href="alarm-fabricantes.php">Fabricantes &raquo;</a>
                                        </li>
                                        <li>
                                            <a href="alarm-alarmas.php">Alarmas &raquo;</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                                <li>
                                    <a href="maestro-displays.php">Displays &raquo;</a>
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
                        <!--
                        <li>
                            <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                        </li>                        
                        <li>
                            <a href="blank.html"><i class="fa fa-edit fa-fw"></i> Blank Page</a>
                        </li>
                        -->
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
                    <h1 class="page-header">Puntos de venta &raquo; PdS</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Tipo</th>
                                            <th>Panelado</th>
                                            <th>SFID / Referencia</th>
                                            <th>Nombre comercial</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>26000975</td><td>ALPA TELECOMUNICACIONES 2006 BARCELONA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>26000039</td><td>ALPA TELECOMUNICACIONES 2006 INDUSTRIA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>26000090</td><td>ALPA TELECOMUNICACIONES 2006 MERIDIANA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>26000048</td><td>ALPA TELECOMUNICACIONES 2006 ROGENT</td></tr>
                                        <tr class="odd gradeX"><td>FOCUS</td><td>Almacén</td><td>---</td><td>---</td><td>FOCUS ON EMOTIONS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59041108</td><td>ANEVINIP</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>59440329</td><td>ANEVINIP DON BENITO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>59440251</td><td>ANEVINIP VILLAFRANCA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>26330003</td><td>ARAMENA ANDORRA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>26330001</td><td>ARAMENA TARAZONA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59444653</td><td>AREAPHONE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440025</td><td>AREAPHONE CACERES</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440069</td><td>AREAPHONE HUERTA ROSALES</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59440094</td><td>AREAPHONE LEPE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440145</td><td>AREAPHONE MONTIJO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rura</td><td>59440248</td><td>AREAPHONE OLIVENZA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59449142</td><td>AREAPHONE SAN ROQUE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59449308</td><td>AREAPHONE VILLANUEVA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>29993233</td><td>BCTEL AMPOSTA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29990071</td><td>BCTEL BLANES</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>29990042</td><td>BCTEL GUIPUZCOA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29994188</td><td>BCTEL LLORET</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29990032</td><td>BCTEL MATARO PARK</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>29990094</td><td>BCTEL PLATJA D'ARO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>29990198</td><td>BCTEL TORREDEMBARRA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>29990141</td><td>BCTEL TORTOSA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>49440273</td><td>CALL & CALL ALBORAYA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>49446596</td><td>CALL & CALL ALDAYA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>49990078</td><td>CALL & CALL LLIRIA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>49440250</td><td>CALL & CALL PAIPORTA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>49993357</td><td>CALL & CALL PICASSENT</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>49440127</td><td>CALL & CALL TORRENTE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59449320</td><td>CASTEL ALCOSA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59440275</td><td>CASTEL TARIFA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29049994</td><td>COMTIGO BERCEO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>29446938</td><td>COMTIGO EJEA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>29990126</td><td>COMTIGO FRAGA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>29990130</td><td>COMTIGO PASCUALA PERIE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>29043361</td><td>COMTIGO PASEO TERUEL</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29447572</td><td>COMTIGO SAN JOSE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29042494</td><td>COMTIGO TORRERO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>46000057</td><td>COMUNICACIONES ERINA ALBERIQUE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>46000288</td><td>COMUNICACIONES ERINA ALGEMESSI</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>46000028</td><td>COMUNICACIONES ERINA ALGINET</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>46000333</td><td>COMUNICACIONES ERINA LA ALCUDIA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>46000084</td><td>COMUNICACIONES ERINA XATIVA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>79990089</td><td>COMUNICALIA ARTEA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19990213</td><td>COMUNICALIA AVDA CID</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>79990091</td><td>COMUNICALIA CRUCES</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19990098</td><td>COMUNICALIA EL MIRADOR</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>39990119</td><td>COMUNICALIA FERROL</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39990051</td><td>COMUNICALIA LA CALZADA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39990072</td><td>COMUNICALIA MAGNUS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>79990092</td><td>COMUNICALIA TXINGUDI</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39440192</td><td>COMUNICAMASI LALIN</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>39440101</td><td>COMUNICAMASI MONFORTE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39447228</td><td>COMUNICAMASI O BARCO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39046917</td><td>COMUNICAMASI SARRIA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>69440010</td><td>CONEXION ADEJE GRAN SUR</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>39440165</td><td>CONEXION ALISAL</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39445740</td><td>CONEXION ASTILLERO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>79440043</td><td>CONEXION AUTONOMIA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>79440033</td><td>CONEXION BARAKALDO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29440073</td><td>CONEXION BARCELONA CALABRIA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>69440067</td><td>CONEXION BELLAVISTA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>49993591</td><td>CONEXION BURJASSOT</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>39992678</td><td>CONEXION CARBALLINO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39445539</td><td>CONEXION CASTILLA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39440044</td><td>CONEXION CC BUENAVISTA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39440002</td><td>CONEXION CC ODEON</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>19990273</td><td>CONEXION DIEGO LEON</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>69440079</td><td>CONEXION EL TABLERO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>69440105</td><td>CONEXION GRAN TARAJAL</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>29446616</td><td>CONEXION HOSPITALET DEL LLOBREGAT</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39444573</td><td>CONEXION ITM</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>39445417</td><td>CONEXION LAREDO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39444584</td><td>CONEXION MALIAÑO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59440318</td><td>CONEXION MONTILLA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39446951</td><td>CONEXION OVIEDO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>19447971</td><td>CONEXION PASEO DE EXTREMADURA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>39444728</td><td>CONEXION REINOSA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>59445653</td><td>CONEXION SAN JACINTO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19990272</td><td>CONEXION SAN SEBASTIAN DE LOS REYES</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>39440064</td><td>CONEXION SANJURJO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440078</td><td>CONEXION SANLUCAR DE BARRAMEDA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39440030</td><td>CONEXION SERAFIN ESCALANTE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>59440316</td><td>CONEXION TORREDONJIMENO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>79440055</td><td>CONEXION URQUIJO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19449482</td><td>CONEXION VALDEBERNARDO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39993212</td><td>CONEXION VERIN</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39990113</td><td>CONEXION VIGO C SALAMANCA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29440007</td><td>CONEXION VILAMARINA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>39440058</td><td>CONEXION VIVEIRO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>19046348</td><td>CONVERSA ARANJUEZ</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>19990270</td><td>CONVERSA CALLE ILLESCAS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19990268</td><td>CONVERSA CANILLEJAS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19440265</td><td>CONVERSA CASTELLANA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19448115</td><td>CONVERSA CC PLAZA ALUCHE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>19440222</td><td>CONVERSA CIEMPOZUELOS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>19990079</td><td>CONVERSA COSLADA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19440250</td><td>CONVERSA GETAFE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>19440253</td><td>CONVERSA GETAFE CALLE TOLEDO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>19440207</td><td>CONVERSA RIVAS CENTRO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>19440324</td><td>CONVERSA SAN FERNANDO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>19440334</td><td>CONVERSA TOMELLOSO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19448837</td><td>CONVERSA TORREJON</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19990271</td><td>CONVERSA TORREJON EN MEDIO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19440333</td><td>CONVERSA VALDEPEÑAS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>19990269</td><td>CONVERSA VILLAVICIOSA DE ODON</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59448920</td><td>CORIATEL ALMONTE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59440139</td><td>CORIATEL LAS CABEZAS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440237</td><td>CORIATEL LEBRIJA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>59440231</td><td>CORIATEL PILAS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440142</td><td>CORIATEL SAN JUAN</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59447786</td><td>DIGITAL ANDUJAR</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59449542</td><td>DIGITAL ARMILLA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>59445186</td><td>DIGITAL BAILEN</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>59440227</td><td>DIGITAL BERJA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440235</td><td>DIGITAL CARLOS HAYA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59449175</td><td>DIGITAL LA CAROLINA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440072</td><td>DIGITAL MARTOS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29993522</td><td>DIGITAL PHONE ALMOZARA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>29060252</td><td>DIGITAL PHONE CALATAYUD</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>29992799</td><td>DIGITAL PHONE COMPROMISO DE CASPE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>29060230</td><td>DIGITAL PHONE DELICIAS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>25351146</td><td>DIGITAL PHONE ESTELLA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>49990099</td><td>DIGITAL SAN ANTONIO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>49990098</td><td>DIGITAL SANTA EULALIA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>59440225</td><td>DIGITAL SANTA FE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>49990139</td><td>DIGITAL WAP 31 DE DICIEMBRE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>49990152</td><td>DIGITAL WAP ALCUDIA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>49991524</td><td>DIGITAL WAP ALMAZORA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>46530001</td><td>DIGITAL WAP BENALUA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>49992657</td><td>DIGITAL WAP BENICASIM</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>49990141</td><td>DIGITAL WAP BLANQUERNA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>46530036</td><td>DIGITAL WAP CAMACHOS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>46620046</td><td>DIGITAL WAP ELCHE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>49040852</td><td>DIGITAL WAP IBIZA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>49990153</td><td>DIGITAL WAP POLLENÇA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>49990140</td><td>DIGITAL WAP SANTA PONÇA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440114</td><td>DIRECTO MOVIL ESTEPA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>59440055</td><td>DIRECTO MOVIL ESTEPONA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59440133</td><td>DIRECTO MOVIL MAIRENA DEL ALCOR</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>58250015</td><td>DOBLEDIGITO LORA DEL RIO</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>58250008</td><td>DOBLEDIGITO OSUNA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59440111</td><td>DTV ARCOS DE LA FRONTERA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440123</td><td>DTV CHICLANA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440265</td><td>DTV PUERTA EUROPA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo A</td><td>59440269</td><td>DTV TELECOM MORON</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>59440135</td><td>DTV TELECOM UBRIQUE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59440071</td><td>DTV TELECOM UTRERA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>59449564</td><td>DTV TELECOM ZONA ESTE</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo D</td><td>39440187</td><td>DUATEL A GRANXA</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Rural</td><td>39440198</td><td>DUATEL BETANZOS</td></tr>
                                        <tr class="odd gradeX"><td>Orange</td><td>DHO</td><td>Tipo B</td><td>39992067</td><td>DUATEL BOIRO</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->                     
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
    
    <!-- DataTables JavaScript -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>    

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
    </script>    

</body>

</html>