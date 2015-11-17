<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 01/07/2015
 * Time: 12:01
 */



class Informe_model extends CI_Model

{
    public $num_registros = 0;
    private $rango_meses = array();


    /**
     * @return int
     */
    public function getNumRegistros()
    {
        return $this->num_registros;
    }

    /**
     * @param int $num_registros
     */
    public function setNumRegistros($num_registros)
    {
        $this->num_registros = $num_registros;
    }

    public function __construct()
    {
        $this->load->database();
    }


    public function setRangoMeses($rango_meses)
    {
        if(is_array($rango_meses))
        {
            $this->rango_meses = $rango_meses;
        }
    }
    public function getRangoMeses()
    {
        return $this->rango_meses;
    }





    public function tabla_temporal($data,$limit=NULL)
    {



    }

    public function get_sql_informe_pdv_OLD($data,$limit=NULL)
    {
        $tipo_tienda = (isset($data["tipo_tienda"]) && !empty($data["tipo_tienda"])) ? $data["tipo_tienda"] : NULL;
        $panelado = (isset($data["panelado"])  && !empty($data["panelado"])) ? $data["panelado"] : NULL;
        $territory = (isset($data["territory"])  && !empty($data["territory"])) ? $data["territory"] : NULL;

        $id_display = (isset($data["id_display"])  && !empty($data["id_display"])) ? $data["id_display"] : NULL;

        $id_device = (isset($data["id_device"])  && !empty($data["id_device"])) ? $data["id_device"] : NULL;
        $brand_device = (isset($data["brand_device"])  && !empty($data["brand_device"])) ? $data["brand_device"] : NULL;

        if(is_null($tipo_tienda) && is_null($panelado) && is_null($id_display) && is_null($id_device) && is_null($territory) && is_null($brand_device))
        {
            return NULL;
        }

        /*$this->db->select("pds.id_pds as id_pds, pds.reference as reference, panelado.panelado_abx as panelado_abx, type_pds.pds as tipo_pds,
                        pds.territory as territory, territory.territory as territorio, pds.panelado_pds as panelado_pds, commercial, pds.type_via as type_via, type_via.via as tipo_via,
                        address, zip, city")*/

        $aQuery = array();

        $aQuery["fields"] = array(
                    "pds.reference as reference",
                    "type_pds.pds as tipo_pds",
                    "panelado.panelado_abx as panelado_abx",
                    "pds.territory as territory",
                    "territory.territory as territorio",
                    "pds.panelado_pds as panelado_pds",
                    "commercial",
                    "pds.type_via as type_via",
                    "type_via.via as tipo_via",
                    "address", "zip", "city");

        $aQuery["table"] = " pds ";
        $aQuery["joins"] = array();

        //$this->db->select($sCampos);

        $aQuery["joins"]["type_pds"] = "type_pds.id_type_pds = pds.type_pds";
        $aQuery["joins"]["panelado"] = "panelado.id_panelado=pds.panelado_pds";
        $aQuery["joins"]["territory"] = "territory.id_territory=pds.territory";
        $aQuery["joins"]["type_via"] = "type_via.id_type_via = pds.type_via";


        if(!is_null($id_display)){
            $aQuery["joins"]["displays_pds"]  = "displays_pds.id_pds = pds.id_pds";
            $aQuery["joins"]["display"]  = "display.id_display = displays_pds.id_display";
        }

        if(!is_null($id_device) || !is_null($brand_device)){
            $aQuery["joins"]["devices_pds"]  = "devices_pds.id_pds=pds.id_pds";
            $aQuery["joins"]["device"]  = "device.id_device=devices_pds.id_device";
        }


        if(!is_null($tipo_tienda))  $aQuery["where_in"]["pds.type_pds"] = $tipo_tienda;
        if(!is_null($panelado))     $aQuery["where_in"]["pds.panelado_pds"] = $panelado;
        if(!is_null($id_display))   $aQuery["where_in"]["displays_pds.id_display"] = $id_display;
        if(!is_null($id_device))    $aQuery["where_in"]["devices_pds.id_device"] = $id_device;
        if(!is_null($territory))    $aQuery["where_in"]["pds.territory"] = $territory;
        if(!is_null($brand_device)) $aQuery["where_in"]["device.brand_device"] = $brand_device;


        $aQuery["order_by"]["type_pds.pds"] = "asc";
        $aQuery["order_by"]["panelado.panelado_abx"] = "asc";
        $aQuery["order_by"]["territory.territory"] = "asc";
        $aQuery["order_by"]["pds.reference"] = "asc";

        $aQuery["group_by"] = "reference";




       if(!is_null($limit))
        {
            $aQuery["limit"] = array("ini"=> $limit["ini"], "offset"=>$limit["offset"]);
        }



        return $aQuery;
    }




    public function get_sql_informe_pdv($data,$limit=NULL)
    {
        $id_tipo = (isset($data["id_tipo"]) && !empty($data["id_tipo"])) ? $data["id_tipo"] : NULL;
        $id_subtipo = (isset($data["id_subtipo"]) && !empty($data["id_subtipo"])) ? $data["id_subtipo"] : NULL;
        $id_segmento = (isset($data["id_segmento"]) && !empty($data["id_segmento"])) ? $data["id_segmento"] : NULL;
        $id_tipologia = (isset($data["id_tipologia"]) && !empty($data["id_tipologia"])) ? $data["id_tipologia"] : NULL;


        $territory = (isset($data["territory"])  && !empty($data["territory"])) ? $data["territory"] : NULL;

        $id_display = (isset($data["id_display"])  && !empty($data["id_display"])) ? $data["id_display"] : NULL;

        $id_device = (isset($data["id_device"])  && !empty($data["id_device"])) ? $data["id_device"] : NULL;
        $brand_device = (isset($data["brand_device"])  && !empty($data["brand_device"])) ? $data["brand_device"] : NULL;

        if(is_null($id_tipo) && is_null($id_subtipo) && is_null($id_segmento) && is_null($id_tipologia)
            && is_null($id_display) && is_null($id_device) && is_null($territory) && is_null($brand_device))
        {
            return NULL;
        }

        /*$this->db->select("pds.id_pds as id_pds, pds.reference as reference, panelado.panelado_abx as panelado_abx, type_pds.pds as tipo_pds,
                        pds.territory as territory, territory.territory as territorio, pds.panelado_pds as panelado_pds, commercial, pds.type_via as type_via, type_via.via as tipo_via,
                        address, zip, city")*/

        $aQuery = array();

        $aQuery["fields"] = array(
            "pds.reference as reference",

            "pds_tipo.titulo as tipo",
            "pds_subtipo.titulo as subtipo",
            "pds_segmento.titulo as segmento",
            "pds_tipologia.titulo as tipologia",

            "pds.territory as territory",
            "territory.territory as territorio",
            "province.province as provincia",

            "commercial",
            "pds.type_via as type_via",
            "type_via.via as tipo_via",
            "address", "zip", "city","province.province as provincia");

        $aQuery["table"] = " pds ";
        $aQuery["joins"] = array();

        //$this->db->select($sCampos);

        $aQuery["joins"]["pds_tipo"]        = "pds_tipo.id = pds.id_tipo";
        $aQuery["joins"]["pds_subtipo"]     = "pds_subtipo.id = pds.id_subtipo";
        $aQuery["joins"]["pds_segmento"]    = "pds_segmento.id = pds.id_segmento";
        $aQuery["joins"]["pds_tipologia"]   = "pds_tipologia.id = pds.id_tipologia";


        $aQuery["joins"]["territory"] = "territory.id_territory=pds.territory";
        $aQuery["joins"]["type_via"] = "type_via.id_type_via = pds.type_via";
        $aQuery["joins"]["province"] = "province.id_province = pds.province";


        if(!is_null($id_display)){
            $aQuery["joins"]["displays_pds"]  = "displays_pds.id_pds = pds.id_pds";
            $aQuery["joins"]["display"]  = "display.id_display = displays_pds.id_display";
        }

        if(!is_null($id_device) || !is_null($brand_device)){
            $aQuery["joins"]["devices_pds"]  = "devices_pds.id_pds=pds.id_pds";
            $aQuery["joins"]["device"]  = "device.id_device=devices_pds.id_device";
        }


        if(!is_null($id_tipo))      $aQuery["where_in"]["pds.id_tipo"] = $id_tipo;
        if(!is_null($id_subtipo))   $aQuery["where_in"]["pds.id_subtipo"] = $id_subtipo;
        if(!is_null($id_segmento))  $aQuery["where_in"]["pds.id_segmento"] = $id_segmento;
        if(!is_null($id_tipologia)) $aQuery["where_in"]["pds.id_tipologia"] = $id_tipologia;


        if(!is_null($id_display))   $aQuery["where_in"]["displays_pds.id_display"] = $id_display;
        if(!is_null($id_device))    $aQuery["where_in"]["devices_pds.id_device"] = $id_device;
        if(!is_null($territory))    $aQuery["where_in"]["pds.territory"] = $territory;
        if(!is_null($brand_device)) $aQuery["where_in"]["device.brand_device"] = $brand_device;


        $aQuery["order_by"]["pds_tipo.titulo"] = "asc";
        $aQuery["order_by"]["pds_subtipo.titulo"] = "asc";
        $aQuery["order_by"]["pds_segmento.titulo"] = "asc";
        $aQuery["order_by"]["pds_tipologia.titulo"] = "asc";

        $aQuery["order_by"]["territory.territory"] = "asc";
        $aQuery["order_by"]["pds.reference"] = "asc";

        $aQuery["group_by"] = "reference";




        if(!is_null($limit))
        {
            $aQuery["limit"] = array("ini"=> $limit["ini"], "offset"=>$limit["offset"]);
        }



        return $aQuery;
    }


    public function get_informe_pdv_OLD($data,$ci_pagination=NULL)
    {
        $limit = NULL;
        if(count($data) > $ci_pagination["per_page"])
        {
            $limit["ini"] =  $ci_pagination["n_inicial"];
            $limit["offset"] = $ci_pagination["n_final"];
        }
        $aQuery = $this->get_sql_informe_pdv($data,$limit);
        if(empty($aQuery)) return NULL;

        $query = $this->get_active_record_result($aQuery);

        return $query;
    }


    public function get_informe_pdv($data,$ci_pagination=NULL)
    {
        $limit = NULL;
        if(count($data) > $ci_pagination["per_page"])
        {
            $limit["ini"] =  $ci_pagination["n_inicial"];
            $limit["offset"] = $ci_pagination["n_final"];
        }
        $aQuery = $this->get_sql_informe_pdv($data,$limit);
        if(empty($aQuery)) return NULL;

        $query = $this->get_active_record_result($aQuery);

        return $query;
    }



    public function get_informe_pdv_quantity_OLD($data)
    {
        $query = $this->get_sql_informe_pdv($data);
        if($query === NULL) return 0;
        return count($query->result());
    }


    public function get_informe_pdv_quantity($data)
    {
        $query = $this->get_sql_informe_pdv($data);
        if($query === NULL) return 0;
        return count($query->result());
    }

    public function panelado_tienda($tipo, $id_panelado=NULL)
    {
        /* Incluir los modelos */
        $xcrud = xcrud_get_instance();
        $this->load->model('sfid_model');
        $this->load->model('tienda_model');
        $this->load->model('tienda_model');

        $panelados = $this->tienda_model->get_panelados_maestros_demoreal($tipo);

        $resp = '<option value="" selected="selected">Escoge el panelado...</option>';
        foreach($panelados as $panel){

            $s_selected = (!is_null($id_panelado) && $id_panelado == $panel->id_panelado) ? ' selected="selected" ' : '';
            $resp .= '<option value="'.$panel->id_panelado.'" '.$s_selected.'>'.$panel->panelado.'</option>';
        }
        echo $resp;
    }




    public function exportar_informe_pdv($data,$formato="csv")
    {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');



        // Array de títulos y exclusiones de campo para la exportación XLS/CSV
        $arr_titulos = array('SFID','Tipo','Subtipo','Segmento','Tipología','Territorio','Nombre','Tipo Vía','Dirección','CP','Localidad','Provincia');
        $excluir = array('territory','panelado_pds','type_via','');


        $aQuery = $this->get_sql_informe_pdv($data);


        $limit = NULL;
        if(empty($aQuery)) return NULL;

        $query = $this->get_sql_result($aQuery);



        $datos = preparar_array_exportar($query->result(),$arr_titulos,$excluir);

        exportar_fichero($formato,$datos,'Informe_PDV-'.date("d-m-Y").'T'.date("H:i:s"));


    }


    /**
     * Genera una consulta con Active Record en base a un array pasado como parametro, formado por las subpartes de una
     * consulta.
     * @param $aQuery
     *
     */
    function get_active_record_result($aQuery)
    {
        $xcrud = xcrud_get_instance();


        $sCampos = implode(",",$aQuery["fields"]);
        $this->db->select($sCampos);

        if(isset($aQuery["joins"]) && !empty($aQuery["joins"]))         foreach($aQuery["joins"] as $tabla_sec=>$on)        $this->db->join($tabla_sec,$on);
        if(isset($aQuery["where_in"]) && !empty($aQuery["where_in"]))   foreach($aQuery["where_in"] as $campo=>$valores)    $this->db->where_in($campo,$valores);
        if(isset($aQuery["order_by"]) && !empty($aQuery["order_by"]))   foreach($aQuery["order_by"] as $campo=>$orden)      $this->db->order_by($campo,$orden);

        if(isset($aQuery["group_by"]) && !empty($aQuery["group_by"]))   $this->db->group_by($aQuery["group_by"]);

        if(isset($aQuery["limit"])){
            $limites = $aQuery["limit"];
            $ini = (empty($limites["ini"])) ? NULL : $limites["ini"];
            $offset = (empty($limites["offset"])) ? NULL : $limites["offset"];
            $query = $this->db->get($aQuery["table"],$ini,$offset);
        }else{
            $query = $this->db->get($aQuery["table"]);
        }


        return $query->result();

    }



    /**
     * Genera una consulta con Active Record en base a un array pasado como parametro, formado por las subpartes de una
     * consulta.
     * @param $aQuery
     *
     */
    function get_sql_result($aQuery)
    {
        $xcrud = xcrud_get_instance();

        $sSQL = "SELECT ".implode(",",$aQuery["fields"]);
        $sSQL .= (" FROM ".$aQuery["table"]);

        if(isset($aQuery["joins"]) && !empty($aQuery["joins"]))         foreach($aQuery["joins"] as $tabla_sec=>$on)        $sSQL .= (" JOIN ".$tabla_sec." ON ".$on);

        $sSQL .= " WHERE 1=1 ";

        if(isset($aQuery["where_in"]) && !empty($aQuery["where_in"]))   foreach($aQuery["where_in"] as $campo=>$valores)    $sSQL .= (" AND ".$campo .  " IN (". implode(",",$valores).") ");

        if(isset($aQuery["group_by"]) && !empty($aQuery["group_by"]))   $sSQL  .= (" GROUP BY ".$aQuery["group_by"]);

        if(isset($aQuery["order_by"]) && !empty($aQuery["order_by"]))
        {
            $sSQL .= " ORDER BY ";
            foreach($aQuery["order_by"] as $campo=>$orden) $sSQL .= ($campo . " ".$orden.",");

            $sSQL = trim($sSQL,",");
        }


        if(isset($aQuery["limit"])){
            $limites = $aQuery["limit"];
            $ini = (empty($limites["ini"])) ? NULL : $limites["ini"];
            $offset = (empty($limites["offset"])) ? NULL : $limites["offset"];

            if(!is_null($ini) && !is_null($offset)){
                $sSQL .= ("LIMIT ".$ini.",".$offset);
            }
        }

        $query = $this->db->query($sSQL);
        return $query;

    }



    public function get_rango_meses($anio=NULL)
    {
        if(is_null($anio)) $anio = date("Y");
        $rango_meses = $this->db->query("SELECT MONTH(MIN(fecha)) as min, MONTH(MAX(fecha)) as max FROM incidencias WHERE YEAR(fecha)='$anio'")->row();

        $this->setRangoMeses($rango_meses);
        return $rango_meses;
    }

    public function get_meses_columna($min = 1,$max = 12)
    {
        $meses_columna = array();
        for($i = $min; $i<= $max; $i++)
        {
            $meses_columna[$i] = nombre_mes($i, 1, 2000);
        }
        return $meses_columna;
    }


    public function get_dias_operativos_mes($rango_meses=NULL, $anio = NULL)
    {
        $resultado = NULL;
        $dias_operativos = array();
        if(is_null($anio)) $anio = date("Y");

        if(!is_null($rango_meses))
        {
            for($i = $rango_meses->min; $i <= $rango_meses->max; $i++)
            {
                $dias_op = contar_dias_excepto($i,$anio,array('Sun','Sat'),date('d'));
                if($dias_op <= 0) $dias_op = 1;
                $dias_operativos[$i] = $dias_op;
            }
            $resultado = $dias_operativos;
        }


        return $resultado;
    }


    public function get_total_array($array = NULL)
    {
        if(is_null($array)) $array = array();
        $total = 0;

        foreach($array as $dias_mes)
        {
            $total += $dias_mes;
        }

        return $total;

    }

    /**
     * Recorre el array de incidencias y acumula el total para devolverlo al final.
     * @param null $array_incidencias
     * @return int
     */
    public function get_total_cdm_incidencias($array_incidencias = NULL)
    {
        $total = 0;

        if(!is_null($array_incidencias))
        {
            foreach($array_incidencias as $incidencias)
            {
                $total += $incidencias->total_incidencias;
            }
        }
        return $total;
    }

    /**
     * Devuelve en bruto un array simple [mes]=>valor las incidencias
     * @param null $array_incidencias
     * @return array
     */
    public function get_array_incidencias_totales($array_incidencias = NULL)
    {
        $data_inc = array();
        if(!is_null($array_incidencias))
        {
            foreach($array_incidencias as $inc)
            {
                $data_inc[$inc->mes] = $inc->total_incidencias;
            }
        }
        return $data_inc;
    }

    public function get_medias($array_num=NULL, $array_denom=NULL, $rango_meses = NULL)
    {
        $array_resultado = array();

        if(!is_null($array_num) && !is_null($array_denom) && count($array_num) == count($array_denom) && !is_null($rango_meses))
        {
            for($i = $rango_meses->min; $i <= $rango_meses->max ; $i++)
            {
                $resultado_mes = round($array_num[$i] / $array_denom[$i]);
                $array_resultado[$i] = $resultado_mes;
            }
        }
        return $array_resultado;
    }

    /**
     * Devuelve objeto de incidencias totales, correspondientes a la primera fila de la tablona
     * @param $anio
     * @param string $s_where
     * @return mixed
     */
    public function get_cmd_incidencias_totales($anio,$s_where="")
    {
        if(!is_null($anio)) $anio = date("Y");

        $query_1 = $this->db->query("
            SELECT YEAR(incidencias.fecha) AS anio, MONTH(incidencias.fecha) AS mes, COUNT(*) AS total_incidencias
            FROM incidencias
            WHERE 1 = 1 $s_where AND YEAR(incidencias.fecha) = '".$anio."'
            GROUP BY anio, mes");

        return $query_1->result();
    }


    /**
     * Genera y devuelve un array de resultado de incidencias filtradas
     * @param $i_mes
     */
    public function get_cdm_incidencias($anio, $i_mes)
    {
        $query = $this->db->select("

        ");

    }


    /**
     * Devuelve objeto de incidencias agrupadas por Estado inc, año y mes
     * @param $anio
     * @param string $s_where
     * @return mixed
     */
    public function get_cmd_incidencias_totales_estado($anio, $s_where= "")
    {
        if(is_null($anio)) $anio = date("Y");
        $query = $this->db->query("
                                SELECT incidencias.status_pds,
                                YEAR(incidencias.fecha) AS anio,
								MONTH(incidencias.fecha) AS mes,
								COUNT(*) AS total_incidencias
								FROM incidencias
								WHERE 1=1
								 $s_where
								AND YEAR(incidencias.fecha) ='".$anio."'
								GROUP BY status_pds, anio, mes
								ORDER BY status_pds ASC, mes ASC
								 ");
        return $query->result();
    }

    /**
     * Crea un array para mostrar en la vista, para incidencias mes por estados
     * @param $array_incidencias
     * @param $meses_columna
     */
    public function get_cmd_incidencias_estado($titulo_incidencias_estado, $array_incidencias, $meses_columna)
    {
        $incidencias_estado = array();

        foreach($array_incidencias as $key=>$value)
        {
            if(!array_key_exists($value->status_pds,$incidencias_estado))
            {
                $incidencias_estado[$value->status_pds] = array();
            }
            $incidencias_estado[$value->status_pds][$value->mes] = $value->total_incidencias;
        }

        foreach($meses_columna as $id_mes=>$mes)
        {

            // Rellenamos con 0 cuando no hay incidencias para ese mes
            foreach($titulo_incidencias_estado as $id_titulo_estado=>$estado)
            {
                // Creamos el índice por estado de incidencia, si no existe...
                if(!array_key_exists($estado->estado,$incidencias_estado)) $incidencias_estado[$estado->estado] = array();
                // Creamos el índice de mes, en las incidencias por estado.
                if(!array_key_exists($id_mes,$incidencias_estado[$estado->estado])) $incidencias_estado[$estado->estado][$id_mes] = 0;
                // Ordenamos el array por estado.
                ksort($incidencias_estado[$estado->estado]);
            }
        }

        return $incidencias_estado;
    }




    public function crear_incidencias_historico_finalizadas($anio = NULL)
    {
        if(is_null($anio)) $anio = date("Y");
        $this->db->query(" DROP TABLE IF EXISTS historico_temp;");
        $this->db->query(" UPDATE incidencias SET fecha_cierre = DATE_ADD(fecha, INTERVAL 2 day) WHERE status_pds = 'Finalizada' && fecha_cierre = '0000-00-00 00:00:00'; ");
        $this->db->query(" UPDATE incidencias SET fecha_cierre = DATE_ADD(fecha, INTERVAL 2 day) WHERE status_pds = 'Finalizada' && fecha_cierre IS NULL; ");

        $this->db->query(" CREATE TEMPORARY TABLE IF NOT EXISTS historico_temp(INDEX(id_incidencia))
                            AS (
                                   SELECT h.id_incidencia, i.fecha as fecha_entrada, MAX(h.fecha) as fecha_proceso,
                                    i.fecha_cierre,  h.status_pds, h.status
                                    FROM historico h
                                    JOIN incidencias i ON h.id_incidencia = i.id_incidencia
                                    WHERE 	YEAR(i.fecha) = '".$anio."'
                                        AND ( h.status_pds != 'Cancelada' && i.status_pds != 'Cancelada')
                                        AND ( h.status_pds = 'En proceso' || i.status_pds = 'Finalizada')
                                    GROUP BY id_incidencia
                            );
            ");

    }

    public function finalizadas_menos_72($anio=NULL,$meses_columna)
    {
        if(is_null($anio)) $anio = date("Y");
        $this->crear_incidencias_historico_finalizadas($anio);

        $sql = "
            SELECT COUNT(id_incidencia)  as cantidad, YEAR(fecha_entrada) as anio, MONTH(fecha_entrada) as mes FROM historico_temp
            WHERE (workdaydiff(fecha_proceso,fecha_cierre)) < 3
            GROUP BY anio, mes;
            ";
        $query = $this->db->query($sql)->result();

        $resultado = $this->rellenar_con_ceros($anio,$query,$meses_columna);
        return $resultado;
    }


    public function finalizadas_mas_72($anio=NULL,$meses_columna)
    {
        if(is_null($anio)) $anio = date("Y");
        $this->crear_incidencias_historico_finalizadas($anio);


        $sql  = "
                   SELECT COUNT(id_incidencia) as cantidad, YEAR(fecha_entrada) as anio, MONTH(fecha_entrada) as mes FROM historico_temp
                    WHERE (workdaydiff(fecha_proceso,fecha_cierre)) >= 3
                    GROUP BY anio, mes; ";

        $query = $this->db->query($sql)->result();
        $resultado = $this->rellenar_con_ceros($anio,$query,$meses_columna);
        return $resultado;
    }




    public function rellenar_con_ceros($anio, $elementos, $meses_columna)
    {
        // Rellenamos con 0 los meses del rango que no tienen incidencias...
        $index = 0;
        $r_elementos = array();
        foreach($meses_columna as $id_mes => $mes)
        {

            $existe = NULL;
            foreach($elementos as $clave=>$valor)
            {
                if($valor->mes == $id_mes)
                {
                    $existe = $valor; break;
                }
            }
            if(!is_null($existe))
            {
                $r_elementos[] = $valor;
            }
            else
            {
                $elemento = new StdClass();
                $elemento->cantidad = 0;
                $elemento->mes = $id_mes;
                $elemento->anio = $anio;

                $r_elementos[] = $elemento;
            }

            $index++;
        }

        return $r_elementos;
    }



    public function exportar_cdm_incidencias($anio = NULL, $mes = NULL,$status= NULL,$menos72=NULL)
    {
        if(is_null($anio)) $anio = date("Y");
        if(is_null($mes)) $mes = date("m");

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');


        $aConditions = array();
        $aConditions[] = " AND (incidencias.status_pds != 'Cancelada' && incidencias.status != 'Cancelada') ";
        $aConditions[] = " AND YEAR(fecha) = '$anio' ";
        $aConditions[] = " AND MONTH(fecha) = '$mes' ";


        $aJoins = array();
        $aFields = array();

        $sTitleFilename = "CDM_Incidencias_";
        $sFiltrosFilename = ($mes."-".$anio);

        if(!is_null($status)){
            $aConditions[] = " AND incidencias.status_pds = $status ";
            $sFiltrosFilename .= (!is_null($status)) ? "_status-".$status : "";
        }

        if(!is_null($menos72)) {
            $aConditions[] = " AND incidencias.status_pds = $status ";
            $sFiltrosFilename .= ($menos72==1) ? "_menos-72h" : "_mas-72h";

            $this->informe_model->crear_incidencias_historico_finalizadas($anio);

            $aFields[] = "";
            $aJoins[] = " LEFT JOIN historico_temp ON historico_temp.id_incidencia = incidencias.id_incidencia ";


            if($menos72 == 1)
            {
                $aConditions[] = " AND  (workdaydiff(historico_temp.fecha_proceso,historico_temp.fecha_cierre)) < 3 ";
            }
            else
            {
                $aConditions[] = " AND  (workdaydiff(historico_temp.fecha_proceso,historico_temp.fecha_cierre)) >= 3 ";
            }

        }
        $sFiltrosFilename .= "___";



        // Array de títulos de campo para la exportación XLS/CSV
        $arr_titulos = array('Id incidencia','SFID','Fecha','Elemento','Territorio','Fabricante','Mueble','Terminal','Supervisor','Provincia','Tipo avería',
            'Texto 1','Texto 2','Texto 3','Parte PDF','Denuncia','Foto 1','Foto 2','Foto 3','Contacto','Teléfono','Email',
            'Id. Operador','Intervención','Estado','Última modificación','Estado Sat');
        $excluir = array('fecha_cierre','fabr');

        $sql = 'SELECT incidencias.id_incidencia, pds.reference as `SFID`, incidencias.fecha, incidencias.fecha_cierre,

                            (CASE incidencias.alarm_display WHEN 1 THEN ( CONCAT("Mueble: ",
                                (CASE ISNULL(display.display) WHEN TRUE THEN "Retirado" ELSE display.display END)
                            )) ELSE (CONCAT("Dispositivo: ",
                                (CASE ISNULL(device.device) WHEN TRUE THEN "Retirado" ELSE device.device END)
                            )) END) as elemento,

                            device.brand_device as fabr,
                            territory.territory as `Territorio`,
                            ';

        $sql .= ' (SELECT brand_device.brand from brand_device  WHERE id_brand_device = fabr ) as `Fabricante`,';
        $sql .= 'display.display as mueble, device.device as terminal, pds_supervisor.titulo as supervisor, province.province as provincia,incidencias.tipo_averia,';
        $sql .='
                           REPLACE(REPLACE(incidencias.description_1,CHAR(10),CHAR(32)),CHAR(13),CHAR(32)) as description_1,
                            REPLACE(REPLACE(incidencias.description_2,CHAR(10),CHAR(32)),CHAR(13),CHAR(32))  as description_2,
                            REPLACE(REPLACE(incidencias.description_3,CHAR(10),CHAR(32)),CHAR(13),CHAR(32))  as description_3,
                            incidencias.parte_pdf, incidencias.denuncia, incidencias.foto_url, incidencias.foto_url_2,
                            incidencias.foto_url_3, incidencias.contacto, incidencias.phone, incidencias.email,incidencias.id_operador,
                            incidencias.intervencion,';

        $sql .= 'incidencias.status_pds as `Estado PDS`';
        $sql = rtrim($sql,",");
        $sql .= implode(",",$aFields);

        $sql .='FROM incidencias

                LEFT OUTER JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
                LEFT OUTER JOIN display ON displays_pds.id_display = display.id_display
                LEFT OUTER JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
                LEFT OUTER JOIN device ON devices_pds.id_device = device.id_device
                LEFT OUTER JOIN type_device ON device.type_device = type_device.id_type_device

                LEFT OUTER JOIN pds ON incidencias.id_pds = pds.id_pds
                LEFT OUTER JOIN territory ON territory.id_territory=pds.territory
                LEFT OUTER JOIN brand_device ON device.brand_device = brand_device.id_brand_device

                LEFT JOIN pds_supervisor ON pds.id_supervisor= pds_supervisor.id
                LEFT JOIN province ON pds.province= province.id_province ';

        foreach($aJoins as $join)
        {
            $sql .= $join;
        }


        $sql .= ' WHERE 1 = 1
                ';
                // Añadimos las condiciones
                foreach($aConditions as $cond)
                {
                    $sql .= $cond;
                }
        $sql .= " ORDER BY fecha DESC";


        $query = $this->db->query($sql);


        $datos = preparar_array_exportar($query->result(),$arr_titulos,$excluir);

        exportar_fichero("xls",$datos,$sTitleFilename.$sFiltrosFilename.date("d-m-Y")."T".date("H:i:s")."_".date("d-m-Y"));

    }

}
