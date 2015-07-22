    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 24/06/2015
 * Time: 16:25
 */



    /**
     * Callback para el xcrud, previo a actualización de alarma masiva.
     */
    function historico_IO_alarmas_before_update($postdata, $primary)

    {
        $id_alarm = $primary;      // Clave primaria del XCrud
        $id_client = $postdata->get("client_alarm");

        $CI =& get_instance();
        $query = $CI->db->query("SELECT units as unidades_previas FROM alarm WHERE id_alarm=$id_alarm");

        $unidades_actuales = $postdata->get('units');
        $unidades_previas = $query->row()->unidades_previas;


        $incremento = $unidades_actuales - $unidades_previas; // Valor positivo: entrada, Valor negativo: salida.

        $fecha = time();
        $sql = "INSERT INTO historico_IO (id_alarm, id_client, unidades) VALUES($id_alarm,$id_client,$incremento)";
        $CI->db->query($sql);

    }

