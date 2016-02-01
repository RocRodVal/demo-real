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
     * Creamos una tabla en la BBDD con los datos sobre las intervenciones / incidencias de un año determinado
     */
	public function crear_facturaciontemp($anio) {

		$this->db->query(" DROP TABLE IF EXISTS facturacion_temp; ");
		$this->db->query('
                CREATE TEMPORARY TABLE facturacion_temp AS
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
        /*
         * $this->db->query('SELECT SUM(incidencias) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes
                                                FROM facturacion_temp f
                                                GROUP BY mes');
         */
        $query=$this->db->select('SUM(incidencias) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes')
            ->group_by('mes')
            ->get('facturacion_temp f');

        return $query->result();
    }

    public function get_IncidenciasTipo($anio,$no_cancelada,$tipo){
        /*
         *

/*$this->db->query('SELECT COUNT(id_incidencia) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes,
            ('.$sql_aux.') as total
                                                FROM incidencias f
                                                WHERE YEAR(f.fecha) = "'.$este_anio.'" AND f.tipo_averia = "Avería"
                                                '.$ctrl_no_cancelada.'
                                                GROUP BY mes');

            $this->db->query('SELECT COUNT(id_incidencia) as cantidad,

                                            YEAR(f.fecha) as anio, MONTH(f.fecha) as mes,
                                            ('.$sql_aux.') as total

                                                FROM incidencias f
                                                WHERE YEAR(f.fecha) = "'.$este_anio.'" AND f.tipo_averia = "Robo"
                                                '.$ctrl_no_cancelada.'
                                                GROUP BY mes');
         */
        $sql_aux = 'SELECT COUNT(id_incidencia) FROM incidencias
                        WHERE month(fecha) = mes AND YEAR(fecha) = "'.$anio.'" '.$no_cancelada.' ';


        $query= $this->db->query('SELECT COUNT(id_incidencia) as cantidad,

                                            YEAR(f.fecha) as anio, MONTH(f.fecha) as mes,
                                            ('.$sql_aux.') as total

                                                FROM incidencias f
                                                WHERE YEAR(f.fecha) = "'.$anio.'" AND f.tipo_averia = "'.$tipo.'"
                                                '.$no_cancelada.'
                                                GROUP BY mes');
        return $query->result();
    }


}

?>