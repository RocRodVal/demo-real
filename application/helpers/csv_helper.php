<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
* CSV Helpers
* Inspiration from PHP Cookbook by David Sklar and Adam Trachtenberg
*
* @author        Jérôme Jaglale
* @link        http://maestric.com/en/doc/php/codeigniter_csv
*/

// ------------------------------------------------------------------------

/**
* Array to CSV
*
* download == "" -> return CSV string
* download == "toto.csv" -> download file toto.csv
*/
if ( ! function_exists('array_to_csv'))
{
    function array_to_csv($array, $download = "")
    {
        if ($download != "")
        {
            header('Content-Type: application/csv;charset:utf-8');
            header('Content-Disposition: attachement; filename="' . $download . '.csv"');
        }

        ob_start();
        $f = fopen('php://output', 'w') or show_error("Can't open php://output");
        $n = 0;
        foreach ($array as $line)
        {
            $n++;
            if ( ! fputcsv($f, $line))
            {
                show_error("Can't write line $n: $line");
            }
        }
        fclose($f) or show_error("Can't close php://output");
        $str = ob_get_contents();
        ob_end_clean();

        if ($download == "")
        {
            return $str;
        }
        else
        {
            echo $str;
        }
    }
}

// ------------------------------------------------------------------------

/**
* Query to CSV
*
* download == "" -> return CSV string
* download == "toto.csv" -> download file toto.csv
*/
if ( ! function_exists('query_to_csv'))
{
    function query_to_csv($query, $headers = TRUE, $download = "")
    {
        if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
        {
            show_error('invalid query');
        }

        $array = array();

        if ($headers)
        {
            $line = array();
            foreach ($query->list_fields() as $name)
            {
                $line[] = $name;
            }
            $array[] = $line;
        }

        foreach ($query->result_array() as $row)
        {
            $line = array();
            foreach ($row as $item)
            {
                $line[] = $item;
            }
            $array[] = $line;
        }

        echo array_to_csv($array, $download);
    }
}


/**
 * Función que genera una excel en base al array pasado como parámetro, y con el filename pasado como
 * segundo param.
 */

if(!function_exists("array_to"))
{

    function array_to($format="xls", $array_facturacion, $filename = 'export')
    {

        switch($format)
        {
            case "xlsx":
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $ext = $format;
                $writer = 'Excel2007';
                break;

            case "xls":
                $mime = 'application/vnd.ms-excel';
                $writer = 'Excel5';
                $ext = $format;
                break;

            default:
                echo 'Helper CSV: array_to - Unknown Format conversion.'; die();
                break;

        }

        $CI = &get_instance();
        $CI->load->library("PHPExcel");


        $doc = new PHPExcel();
        $doc->setActiveSheetIndex(0);

        $doc->getActiveSheet()->fromArray($array_facturacion, null, 'A1');
        header('Content-Type: '.$mime);
        header('Content-Disposition: attachment;filename="' . $filename . '.'.$ext.'"');
        header('Cache-Control: max-age=0');

        // Do your stuff here
        $writer = PHPExcel_IOFactory::createWriter($doc, $writer);

        $writer->save('php://output');

    }
}




/**
 * Prepara un array para la exportación. Recibe por un lado un result de una query con los datos, y un array con los títulos.
 * En el array excluir se indicarán, si procede, los campos de BD que se deben excluir del array final.
 */

if(!function_exists("preparar_array_exportar"))
{
    function  preparar_array_exportar($query_result,$arr_titulos,$excluir=array())
    {

        $datos[0] = $arr_titulos;
        foreach($query_result as $key=>$campos)
        {
            foreach($campos as $campo=>$valor)
            {
                if(!in_array($campo,$excluir)) $datos[$key+1][$campo] = $valor;
            }
        }

        return $datos;
    }
}

/**
 * Función que eengloba las dos anteriors, pudiendo especificar si es CSV, XLs.. como param
 */

if(!function_exists("exportar_fichero"))
{
    function  exportar_fichero($formato="csv",$data,$filename="export")
    {
        switch($formato)
        {
            case "xls":
                array_to('xls',$data, 'Demo_Real-' . $filename);
                exit; break;

            case "xlsx":
                array_to('xlsx',$data, 'Demo_Real-' . $filename);
                exit; break;

            default:
            case "csv":
                $delimiter = ",";
                $newline = "\r\n";
                echo array_to_csv($data,'Demo_Real-' . $filename);
                break;
        }
    }
}


/* End of file csv_helper.php */
/* Location: ./system/helpers/csv_helper.php */