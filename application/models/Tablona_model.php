<?php

class Tablona_model extends CI_Model {


	public function __construct()	{
		$this->load->database();
        //$this->load->model('chat_model');
	}

	public function get_terminales($anio) {
        $query = $this->db->select('SUM(material_incidencias.cantidad) as cantidad,
                                    MONTH(material_incidencias.fecha) as mes,
                                    YEAR(material_incidencias.fecha) as anio')
			->join('incidencias', ' material_incidencias.id_incidencia = incidencias.id_incidencia')
			->where('incidencias.status_pds = "Finalizada" AND YEAR(material_incidencias.fecha) =', $anio)
            ->where('id_alarm IS NULL')
			->group_by('mes')
			->get('material_incidencias');

        return $query->result();

	}

    public function get_alarmas($anio) {
        $query= $this->db->select('SUM(material_incidencias.cantidad) as cantidad,
                                    MONTH(material_incidencias.fecha) as mes,
                                    YEAR(material_incidencias.fecha) as anio')
            ->join('incidencias', ' material_incidencias.id_incidencia = incidencias.id_incidencia')
            ->where('incidencias.status_pds = "Finalizada" AND YEAR(material_incidencias.fecha) =', $anio)
            ->where('id_devices_almacen IS NULL')
            ->group_by('mes')
            ->get('material_incidencias');

        return $query->result();

    }

    /*
     * Creamos una tabla en la BBDD con los datos sobre las intervenciones / incidencias de un a�o determinado
     */
	public function crear_facturaciontemp($anio) {

		$this->db->query(" DROP TABLE IF EXISTS facturacion_temp; ");
		$this->db->query('
                CREATE TEMPORARY TABLE facturacion_temp  AS
                (
                    SELECT f.fecha as fecha, COUNT(f.id_incidencia) AS incidencias,  SUM(f.units_device) AS dispositivos, SUM(f.units_alarma) AS otros
                    FROM facturacion f
                    LEFT JOIN intervenciones ON  f.id_intervencion = intervenciones.id_intervencion
                    WHERE YEAR(f.fecha) = "'.$anio.'"
                    GROUP BY f.id_intervencion
                );
            ');

	}

    public function get_totalIntervenciones(){
        $query=$this->db->select('COUNT(*) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes')
                ->group_by('mes')
                ->get('facturacion_temp f');

        return $query->result();
    }

    public function get_IncidenciasResueltas(){
        $query=$this->db->select('SUM(incidencias) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes')
            ->group_by('mes')
            ->get('facturacion_temp f');

        return $query->result();
    }

    public function get_IncidenciasTipo($anio,$no_cancelada,$tipo){

        $sql_aux = 'SELECT COUNT(id_incidencia) FROM incidencias
                    WHERE month(fecha) = mes AND YEAR(fecha) = "'.$anio.'" '.$no_cancelada.' ';

        $query= $this->db->query('SELECT COUNT(id_incidencia) as cantidad,
                                  YEAR(f.fecha) as anio, MONTH(f.fecha) as mes,count(distinct(id_pds)) as tiendas,
                                  ('.$sql_aux.') as total
                                  FROM incidencias f
                                  WHERE YEAR(f.fecha) = "'.$anio.'" AND f.tipo_averia = "'.$tipo.'"
                                  '.$no_cancelada.'
                                  GROUP BY mes');
        //echo $this->db->last_query();exit;
        return $query->result();
    }

    /*
     * Función para obtener el total de terminales que tienen registrada el alta en almacen de un mes en concreto
     */
    public function getTerminalesAltaHistoricoAlmacen($anio,$mes){

        $sql="select DISTINCT(h.id_devices_almacen) from historico_io h 
              inner join historicoDevicesAlmacen_temp t on t.maxId=h.id_historico_almacen 
              where (h.status='En stock' OR h.status='Televenta' OR h.status='Reservado' OR h.status='Transito' OR h.status='RMA')";
        $query= $this->db->query($sql);

            //echo $this->db->last_query()."<br>";
        return $query->result();

    }

    /*terminales que tenemos en almacen y que no esten registrados en el historico */
    function getTerminalesAlmacen($devices_historico=null){
        $aux="";
        $devices_array=array();
        if(!empty($devices_historico)){
            foreach ($devices_historico as $device){
                   array_push($devices_array,$device->id_devices_almacen);
            }
            $devices = implode(",",$devices_array);
            $aux = " and id_devices_almacen NOT IN($devices)";
        }
        $sql="SELECT distinct(id_devices_almacen)
                from devices_almacen
                where status is not null and (status = 'En stock' OR status ='Transito' OR status='Reservado' 
                OR status='Televenta' OR status='RMA') ".$aux;
        $query = $this->db->query($sql);
        //echo $this->db->last_query()."<br>";
        $devices_almacen = $query->result();
        return $devices_almacen;
    }

    /*terminales de los que tenemos registrada la baja en el historico*/
   /* function  getTerminalesBajaHistoricoAlmacen($anio,$mes){
        $this->crear_historicoDevicesAlmacenTemp($anio,$mes);
       /* $sql="select distinct(historico_io.id_devices_almacen)
                  from historicoDevicesAlmacen_temp
                  inner join historico_io on historicoDevicesAlmacen_temp.maxId = historico_io.id_historico_almacen
                  WHERE historico_io.status = 'Baja' and
                   CASE  when year(historicoDevicesAlmacen_temp.maximafecha) = $anio THEN month(historicoDevicesAlmacen_temp.maximafecha)>$mes
                   ELSE  year(historicoDevicesAlmacen_temp.maximafecha)>$anio END";*/
      /* $sql="select DISTINCT(h.id_devices_almacen) from historico_io h
              inner join historicoDevicesAlmacen_temp t on t.maxId=h.id_historico_almacen
              where h.status='Baja'";
        //  echo $sql."<br>";
        $query = $this->db->query($sql);
        $devices_pds = $query->row_array();
        return $devices_pds;
    }*/
    /*terminales que tenemos en tienda y que no esten registrados en el historico */
    function getTerminalesTienda($devices_historico=null){
        $aux="";
        $devices_array=array();
        if(!empty($devices_historico)){
            foreach ($devices_historico as $device){
                array_push($devices_array,$device->id_devices_pds);
            }
            $devices = implode(",",$devices_array);
            $aux = " and id_devices_pds NOT IN($devices)";
        }
        $sql="SELECT distinct(id_devices_pds)
                from devices_pds
                where (status = 'Alta' OR status ='Incidencia') ".$aux;
       // echo $sql."<br>";
        $query = $this->db->query($sql);
        $devices_pds = $query->result();
        return $devices_pds;
    }

    /*terminales de los que tenemos registrada el alta en el historico*/
    function  getTerminalesAltaHistoricoTienda($anio,$mes){
        $sql="select DISTINCT(h.id_devices_pds) from historico_devicesPDS h 
              inner join historicoDevicesPDS_temp t on t.maxId=h.id
              where (h.status='Alta' OR h.status='Incidencia')";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /*terminales de los que tenemos registrada el alta en el historico*/
    function  getTerminalesBajaHistoricoTienda($anio,$mes){
        //$this->crear_historicoDevicesPDStemp($anio,$mes);
        $sql="select distinct(h.id_devices_pds)
                  from historicoDevicesPDS_temp t
                  inner join historico_devicesPDS h on t.maxId = h.id
                  WHERE (h.status = 'Baja' OR h.status = 'RMA') ";
        //echo $sql."<br>";
        $query = $this->db->query($sql);
        $devices_pds = $query->result();
        return $devices_pds;
    }

    /*
     * Tabla temporal en la BBDD que nos guardara los datos del historico de las incidencias de un año determinado
     * en el momento que pasaron a estado "En visita"
     */
    public function crear_historicotemp($anio,$status,$tipo="incidencias") {

        $this->db->query(" DROP TABLE IF EXISTS historico_temp; ");
        if($tipo=='incidencias') {
            $this->db->query('
                CREATE TEMPORARY TABLE historico_temp (INDEX(id_incidencia)) AS
                (
                    SELECT id_incidencia,MIN(fecha) as fecha ,status_pds
                    FROM historico
                    WHERE YEAR(fecha) = "' . $anio . '" AND status_pds="' . $status . '"
                    GROUP BY id_incidencia
                );
            ');
        }else {
            $this->db->query('
                CREATE TEMPORARY TABLE historico_temp (INDEX(id_pedido)) AS
                (
                    SELECT id_pedido,MIN(fecha) as fecha ,status
                    FROM pedidos_historico
                    WHERE YEAR(fecha) = "' . $anio . '" AND status="' . $status . '"
                    GROUP BY id_pedido
                );
            ');
        }

          //echo $this->db->last_query();
    }


    /*
     * Tabla temporal en la BBDD que nos guardara los datos del historico de los dispositivos de tienda
     */
    public function crear_historicoDevicesPDStemp($anio,$mes) {
        if($mes==12) {
            $anio++;
            $mes="01";
        }else {
            $mes++;
            if($mes<10)
                $mes="0".$mes;
        }
        $fecha =$anio."-".$mes."-01 00:00:00 ";
        $sql ="CREATE TEMPORARY TABLE historicoDevicesPDS_temp (INDEX(id_devices_pds)) AS
              (select DISTINCT(id_devices_pds), max(id) as maxId from historico_devicesPDS
               where fecha<'$fecha' 
              group by id_devices_pds);";

        $this->db->query(" DROP TABLE IF EXISTS historicoDevicesPDS_temp; ");
        $this->db->query($sql);

        //echo $sql."<br>";
    }

    /*
     * Tabla temporal en la BBDD que nos guardara los datos del historico de los dispositivos de almacen
     */
    public function crear_historicoDevicesAlmacenTemp($anio,$mes) {
        if($mes==12) {
            $anio++;
            $mes="01";
        }else {
            $mes++;
            if($mes<10)
                $mes="0".$mes;
        }
        $fecha =$anio."-".$mes."-01 00:00:00 ";

        $sql ="CREATE TEMPORARY TABLE historicoDevicesAlmacen_temp (INDEX(id_devices_almacen)) AS
              (select DISTINCT(id_devices_almacen), max(id_historico_almacen) as maxId from historico_io
               where id_devices_almacen is not NULL  and fecha<'$fecha' 
              group by id_devices_almacen);";

        $this->db->query(" DROP TABLE IF EXISTS historicoDevicesAlmacen_temp; ");
        //echo $this->db->last_query()."<br>";
        $this->db->query($sql);

    }

}

?>