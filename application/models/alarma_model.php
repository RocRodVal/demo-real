<?php

class Alarma_model extends CI_Model {

	public function __construct()	{
		$this->load->database();
	}


	/* TIPOS ALARMAS */
	public function get_tipos_alarmas()
	{
		$query = $this->db->select('*')
				->get('type_alarm');

			return $query->result();
	}

	/* TIPOS ALARMAS */
	public function get_alarmas($tipo='incidencias')
	{
		if ($tipo=='incidencias') {
			$query = $this->db->select('code,brand as fabricante,alarm')
				->join('brand_alarm', 'brand_alarm.id_brand_alarm=alarm.brand_alarm')
				->order_by('alarm ASC')
				->get('alarm');

		}
		else {
			$query = $this->db->select('code,brand as fabricante,alarm')
				->join('brand_alarm', 'brand_alarm.id_brand_alarm=alarm.brand_alarm')
				->where('alarm.elemento_conectado',1)
				->order_by('alarm ASC')
				->get('alarm');
		}
		return $query->result();
	}

	/**
	 * Devuelve objeto de alarmas totales
	 * @return mixed
	 */
	public function get_sistemas_seguridad_totales($tipo='incidencias') {

		if($tipo=='incidencias') {
			$sql = "
            SELECT sum(m.cantidad) as total,a.alarm as alarm,month(h.fecha) as mes
            FROM demoreal.material_incidencias as m
            INNER JOIN historico_temp h ON h.id_incidencia=m.id_incidencia
            INNER JOIN alarm a ON a.id_alarm = m.id_alarm
            WHERE m.id_alarm IS NOT NULL
            GROUP BY a.alarm,mes
            ORDER BY a.alarm ASC";
		} else {
			$sql = "
            SELECT sum(p.cantidad) as total,a.alarm as alarm,month(h.fecha) as mes
            FROM demoreal.pedidos_detalle as p
            INNER JOIN historico_temp h ON h.id_pedido=p.id_pedido
            INNER JOIN alarm a ON a.id_alarm = p.id_alarma
            GROUP BY a.alarm,mes
            ORDER BY a.alarm ASC";
		}
//	INNER JOIN brand_alarm b ON b.id_brand_alarm=a.brand_alarm
		//echo $sql;
		$query_1 = $this->db->query($sql);

		return $query_1->result();
	}

	/**
	 * Devuelve en bruto un array simple [mes]=>valor las alarmas
	 * @param null $array
	 * @return array
	 */
	public function get_array_sistemas_seguridad($array = NULL,$min,$max,$alarmas)
	{
		$data = array();

		if(!empty($array))
		{
			foreach($alarmas as $alarma)  {
				$data[$alarma->alarm]['code'] = $alarma->code;
				$data[$alarma->alarm]['fabricante'] = $alarma->fabricante;
				for ($i=$min;$i<=$max;$i++) {
					foreach($array as $a) {
						if (($a->mes == $i) && ($a->alarm == $alarma->alarm)) {
							$data[$alarma->alarm][$i] = $a->total;
							break;
						} else {
							$data[$alarma->alarm][$i] = "0";
						}
					}
				}
			}
		}
		//print_r($data); exit;
		return $data;
	}
	/*
   * Generar Exportacion de datos con el stock cruzado (NUEVA FUNCION).
   */
	public function exportar_sistemas_seguridad($anio,$tipo='incidencias',$formato="csv") {
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('csv');
		$this->load->helper('download');
		$this->load->model('informe_model');
		$this->load->model('tablona_model');
		$this->load->helper('common');

		// Rango de meses que mostrarán las columnas de la tabla, basándome en el mínimo y máximo mes que hay incidencias, este año.
		$rango_meses = $this->informe_model->get_rango_meses($anio);
		$meses_columna = $this->informe_model->get_meses_columna($rango_meses->min,$rango_meses->max);

		if ($tipo=='incidencias') {$estado="En visita"; }
		else { $estado="Enviado";}

		$this->tablona_model->crear_historicotemp($anio,$estado,$tipo);
		$alarmas=$this->get_alarmas($tipo);
		$resultado = $this->get_sistemas_seguridad_totales($tipo);

		$valor_resultado = $this->get_array_sistemas_seguridad($resultado,$rango_meses->min,$rango_meses->max,$alarmas);

		$arr_titulos = array('Alarma','Código','Fabricante');
		$camp=array('tipo_alarma','code','fabricante');
		foreach($meses_columna as $key => $mes) {
			array_push($arr_titulos,$mes);
			array_push($camp,$key);

		}

		//Preparar el array de datos
		$datos[0]=$arr_titulos;
		$contador=1;
		foreach($valor_resultado as $key=>$campos)
		{
			foreach($camp as $c=>$valor)
			{
				if($c==0){
					$datos[$contador][$c] = $key;
				}else {
					$datos[$contador][$c] = $campos[$valor];
				}
			}
			$contador++;
		}
		//print_r($datos);exit;
		exportar_fichero($formato,$datos,"Alarmas_".$anio."_".$tipo."__".date("d-m-Y"));

	}

	/*
   * Consultar el listado de alarmas que se pueden incluir en los pedidos a realizar por las tiendas SMARTSTORE
	 * Solo se puede hacer pedidos de las alarmas que estén marcadas como elemento_conectado
   */
	public function get_alarmas_pedido() {
		$query = $this->db->select('code,brand as fabricante,alarm,id_alarm,picture_url as imagen')
			->join('brand_alarm','brand_alarm.id_brand_alarm=alarm.brand_alarm')
			->join('type_alarm','type_alarm.id_type_alarm=alarm.type_alarm')
			->where('alarm.elemento_conectado',1)
			->order_by('alarm ASC')
			->get('alarm');

		return $query->result();
	}

	/*
	 * Inserta un pedido en la base de datos
	 */
	public function insert_pedido($data)
	{
		$this->db->insert('pedidos',$data);
		$id=$this->db->insert_id();

		return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);
	}

	/*
	 * Inserta en la base de datos el detale de un pedido
	 */
	public function insert_detalle_pedido($data)
	{
		$this->db->insert('pedidos_detalle',$data);
		$id=$this->db->insert_id();

		return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);
	}
}

?>