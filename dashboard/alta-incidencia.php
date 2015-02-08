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
                        <li>
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
                        <li class="active" >
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Incidencias<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a class="active" href="alta-incidencia.php">Crear &raquo;</a>
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Alta incidencias</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            <!-- /.row -->
        
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Cumpliementar el formulario
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form">
                                        <div class="form-group">
                                            <label>SFID</label>
                                            <select class="form-control" id="sfid">
                                                <option value=''>--- Seleccionar ---</option>  
                                                <option value='141'  >ALPA TELECOMUNICACIONES 2006 BARCELONA VALENCIA</option><option value='118'  >ALPA TELECOMUNICACIONES 2006 INDUSTRIA</option><option  value='137'  >ALPA TELECOMUNICACIONES 2006 MERIDIANA</option><option value='119'  >ALPA TELECOMUNICACIONES 2006 ROGENT</option><option value='3'  >ALTABOX</option><option value='95'  >ANEVINIP</option><option value='266'  >ANEVINIP DON BENITO</option><option value='903'  >ANEVINIP VILLAFRANCA</option><option value='67'  >ARAMENA ANDORRA</option><option value='807'  >ARAMENA TARAZONA</option><option value='96'  >AREAPHONE</option><option value='194'  >AREAPHONE CACERES</option><option value='93'  >AREAPHONE HUERTA ROSALES</option><option value='426'  >AREAPHONE LEPE</option><option value='573'  >AREAPHONE MONTIJO</option><option value='599'  >AREAPHONE OLIVENZA</option><option value='94'  >AREAPHONE SAN ROQUE</option><option value='910'  >AREAPHONE VILLANUEVA</option><option value='66'  >BCTEL AMPOSTA</option><option value='178'  >BCTEL BLANES</option><option value='142'  >BCTEL GUIPUZCOA</option><option value='435'  >BCTEL LLORET</option><option value='553'  >BCTEL MATARO PARK</option><option value='224'  >BCTEL PLATJA D'ARO</option><option value='828'  >BCTEL TORREDEMBARRA</option><option value='844'  >BCTEL TORTOSA</option><option value='16'  >CALL & CALL ALBORAYA</option><option value='33'  >CALL & CALL ALDAYA</option><option value='433'  >CALL & CALL LLIRIA</option><option value='617'  >CALL & CALL PAIPORTA</option><option value='643'  >CALL & CALL PICASSENT</option><option value='840'  >CALL & CALL TORRENTE</option><option value='778'  >CASTEL ALCOSA</option><option value='808'  >CASTEL TARIFA</option><option value='437'  >COMTIGO BERCEO</option><option value='274'  >COMTIGO EJEA</option><option value='303'  >COMTIGO FRAGA</option><option value='947'  >COMTIGO PASCUALA PERIE</option><option value='936'  >COMTIGO PASEO TERUEL</option><option value='934'  >COMTIGO SAN JOSE</option><option value='946'  >COMTIGO TORRERO</option><option value='15'  >COMUNICACIONES ERINA ALBERIQUE</option><option value='41'  >COMUNICACIONES ERINA ALGEMESSI</option><option value='42'  >COMUNICACIONES ERINA ALGINET</option><option value='390'  >COMUNICACIONES ERINA LA ALCUDIA</option><option value='923'  >COMUNICACIONES ERINA XATIVA</option><option value='422'  >COMUNICALIA ARTEA</option><option value='187'  >COMUNICALIA AVDA CID</option><option value='111'  >COMUNICALIA CRUCES</option><option value='186'  >COMUNICALIA EL MIRADOR</option><option value='298'  >COMUNICALIA FERROL CARRETERA DE CASTILLA</option><option value='330'  >COMUNICALIA LA CALZADA</option><option value='332'  >COMUNICALIA MAGNUS</option><option value='376'  >COMUNICALIA TXINGUDI</option><option value='407'  >COMUNICAMASI LALIN</option><option value='571'  >COMUNICAMASI MONFORTE</option><option value='594'  >COMUNICAMASI O BARCO</option><option value='770'  >COMUNICAMASI SARRIA</option><option value='7'  >CONEXION ADEJE GRAN SUR</option><option value='763'  >CONEXION ALISAL</option><option value='275'  >CONEXION ASTILLERO</option><option value='167'  >CONEXION AUTONOMIA</option><option value='109'  >CONEXION BARAKALDO</option><option value='130'  >CONEXION BARCELONA CALABRIA</option><option value='708'  >CONEXION BELLAVISTA</option><option value='189'  >CONEXION BURJASSOT</option><option value='208'  >CONEXION CARBALLINO</option><option value='761'  >CONEXION CASTILLA</option><option value='615'  >CONEXION CC BUENAVISTA</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tipo de incidencia</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>Rotura
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">Robo
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <p><em>En el caso de robo adjuntar copia (PDF) de la denuncia.</em></p>    
                                                <label>Denuncia (PDF)</label>
                                                <input type="file">
                                            </div>
                                            <div class="form-group">
                                                <label>Tipo</label>
                                                <select class="form-control" id="type" data-placeholder=''>
                                                    <option value=''>--- Seleccionar ---</option>
                                                    <option value='5'>Alarma</option>
                                                    <option value='6'>Tablet</option>
                                                    <option value='6'>Teléfono</option>                                                    
                                                </select>
                                            </div>                                            
                                            <div class="form-group">
                                                <label>Mueble</label>
                                                <select class="form-control" id="display" data-placeholder=''>
                                                    <option value=''>--- Seleccionar ---</option>
                                                    <option value='5'>Mesa Experiencia (10 uds.)</option>
                                                    <option value='6'>Mesa Experiencia (6 uds.)</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Posicíón</label>
                                                <select class="form-control" id="display" data-placeholder=''>
                                                    <option value=''>--- Seleccionar ---</option>
                                                    <option value='1'>1</option>
                                                    <option value='2'>2</option>
                                                    <option value='3'>3</option>
                                                    <option value='4'>4</option>
                                                    <option value='5'>5</option>
                                                    <option value='6'>6</option>
                                                    <option value='7'>7</option>
                                                    <option value='8'>8</option>
                                                    <option value='9'>9</option>
                                                    <option value='10'>10</option>
                                                </select>
                                            </div>
                                            
                                            <p><img src="img/los-mas-vendidos.png"></p>
                                            
                                            <div class="col-lg-6">
                                            <div class="panel-heading">
                                            <h3>Smartphone Huawei Ascend P7</h3>
                                            </div>
                                            <p><img src="img/phone-1.png" width="300"></p>
                                            </div>
                                            <div class="col-lg-6">
                                            <div class="panel-heading">
                                            <h3>Datos teléfono</h3>
                                            </div>
                                            <p>
                                            <strong>Posición</strong><br>
                                            <pre>1</pre>
                                            <strong>Modelo</strong><br>
                                            <pre>Smartphone Huawei Ascend P7</pre>
                                            <strong>Color</strong><br>
                                            <pre>Blanco</pre>
                                            <strong>IMEI/MAC</strong><br>
                                            <pre>232452536</pre>
                                            <strong>Nº Serie</strong><br>
                                            <pre>HUA354735873953953</pre>
                                            <strong>Barcode</strong><br>
                                            <pre>32342353543636324</pre
                                            <strong>Estado</strong><br>
                                            <pre>OK</pre
                                            <strong>Complementos</strong><br>
                                            <pre>No</pre>
                                            <strong>Comentarios</strong><br>
                                            <pre>Terminal tipo demo del fabricante.   

                                            </pre>
                                            </p>
                                            <!-- /.table-responsive -->
                                            </div>                                              
                                            
                                        </div>                                        
                                        <button type="submit" class="btn btn-default">Envíar</button>
                                        <button type="reset" class="btn btn-default">Cancelar</button>
                                    </form>                                        
                                        <!--
                                        <div class="form-group">
                                            <label>Datos tienda</label>
                                            <input class="form-control">
                                            <p class="help-block">Example block-level help text here.</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Text Input with Placeholder</label>
                                            <input class="form-control" placeholder="Enter text">
                                        </div>
                                        <div class="form-group">
                                            <label>Static Control</label>
                                            <p class="form-control-static">email@example.com</p>
                                        </div>
                                        <div class="form-group">
                                            <label>File input</label>
                                            <input type="file">
                                        </div>
                                        <div class="form-group">
                                            <label>Text area</label>
                                            <textarea class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Checkboxes</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="">Checkbox 1
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="">Checkbox 2
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="">Checkbox 3
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Inline Checkboxes</label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox">1
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox">2
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox">3
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>Radio Buttons</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>Radio 1
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">Radio 2
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3">Radio 3
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Inline Radio Buttons</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadiosInline" id="optionsRadiosInline1" value="option1" checked>1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadiosInline" id="optionsRadiosInline2" value="option2">2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadiosInline" id="optionsRadiosInline3" value="option3">3
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>Selects</label>
                                            <select class="form-control">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Multiple Selects</label>
                                            <select multiple class="form-control">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-default">Submit Button</button>
                                        <button type="reset" class="btn btn-default">Reset Button</button>
                                    </form>
                                    <h1>Disabled Form States</h1>
                                    <form role="form">
                                        <fieldset disabled>
                                            <div class="form-group">
                                                <label for="disabledSelect">Disabled input</label>
                                                <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="disabledSelect">Disabled select menu</label>
                                                <select id="disabledSelect" class="form-control">
                                                    <option>Disabled select</option>
                                                </select>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox">Disabled Checkbox
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Disabled Button</button>
                                        </fieldset>
                                    </form>
                                    <h1>Form Validation States</h1>
                                    <form role="form">
                                        <div class="form-group has-success">
                                            <label class="control-label" for="inputSuccess">Input with success</label>
                                            <input type="text" class="form-control" id="inputSuccess">
                                        </div>
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="inputWarning">Input with warning</label>
                                            <input type="text" class="form-control" id="inputWarning">
                                        </div>
                                        <div class="form-group has-error">
                                            <label class="control-label" for="inputError">Input with error</label>
                                            <input type="text" class="form-control" id="inputError">
                                        </div>
                                    </form>
                                    <h1>Input Groups</h1>
                                    <form role="form">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">@</span>
                                            <input type="text" class="form-control" placeholder="Username">
                                        </div>
                                        <div class="form-group input-group">
                                            <input type="text" class="form-control">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-eur"></i>
                                            </span>
                                            <input type="text" class="form-control" placeholder="Font Awesome Icon">
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" class="form-control">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                        <div class="form-group input-group">
                                            <input type="text" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                    -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->            
            </div>
            <!-- /.container-fluid -->
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

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

</body>

</html>