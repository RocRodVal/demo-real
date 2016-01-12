<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 17/07/2015
 * Time: 12:48
 */

define("IP_LOCAL","192.168.106.16");
define("IP_DEV","85.10.197.10");
define("IP_PRE","");
define("IP_PROD","85.10.199.43");



/** MOSTRAR MENSAJE ENV SEGUN IP, EXCEPTO EN PRODUCCION*/
$ip = $_SERVER["SERVER_ADDR"];
if($ip!=IP_PROD){ // NO ESTA EN PRODUCCIÓN ?>
    <div id="env">
        <h1>
        <?php switch($ip)
        {
            case IP_LOCAL:   echo "LOCAL";   break;
            case IP_DEV:    echo "DEV";     break;
            default:        echo "PRE";     break;
        }?>
        </h1></div>

<?php }