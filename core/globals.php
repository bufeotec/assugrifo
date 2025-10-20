<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 17/09/2020
 * Time: 18:04
 */
//En caso el sistema sea consumido por app móviles, se puede inhabilitar
//el acceso a los WS por este medio
define('_MANTENIMIENTO_WS', 0);
//Si el sistema se encuentra en mantenimiento, habilitamos
//aqui para que nadie pueda acceder a la misma
define('_MANTENIMIENTO_WEB', 0);

//Variables globales de uso en todo el sistema
//Establecer Zona Horaria
date_default_timezone_set('America/Lima');
//Definicion de servidor del aplicativo
define('_SERVER_', 'http://127.0.0.1/assugrifo/');
//Definicion de variables para conexion de base de datos
define('_SERVER_DB_', '127.0.0.1');
define('_DB_', 'grifo_bd');
define('_USER_DB_', 'root');
define('_PASSWORD_DB_', '');

//Definicion de clave de desencriptacion
define('_FULL_KEY_','ñklmqz');

//Rutas de Archivos
define('_STYLES_ALL_', 'styles/');
define('_STYLES_ADMIN_', 'styles/admin/');
define('_STYLES_LOGIN_', 'styles/login/');
define('_STYLES_INDEX_', 'styles/inicio/');

define('_JS_','js/');
define('_VIEW_PATH_', 'app/view/');
define('_LIBS_', 'libs/');
//Tiempo de Cookies
//$tiempo_cookie = dias * horas * minutos * segundos;
define('_TIEMPO_COOKIE',1 * 1 * 60 * 60);
//Version
define('_VERSION_','0.1');
define('_MYSITE_','https://bufeotec.com');

//Variables para imagenes y estilos varios
define('_PROD_', false);
define('_TITLE_', 'BUFEO GRIFO');
define('_ICON_', 'media/principales/icono.png');
define('_LOGO_GENERAL', 'media/principales/logo.png');
define('_LOGO_LOGIN', 'media/principales/logo_fondo.png');
define('_LOGO_TICKETERA', 'media/principales/logo_ticketera.png');

define('_EMPRESA_', 'BUFEO TEC S.A.C.');
define('_RUC_', '20604352429');
define('_DIRECCION_CORTA_', 'CAL.SIEMPRE VIVA NRO. 234');
define('_DIRECCION_', 'CAL.SIEMPRE VIVA NRO. 234 LORETO - MAYNAS - IQUITOS');
define('_DIRECCION_UBI_', 'LORETO - MAYNAS - IQUITOS');

define('_CERTIFICADO_', 'certificado_prueba.pfx');
define('_PASS_CERTI_', '12345678');

// Manejo de Errores Personalizado de PHP a Try/Catch
function exception_error_handler($severidad, $mensaje, $fichero, $linea) {
    $cadena =  '[LEVEL]: ' . $severidad . ' IN ' . $fichero . ': ' . $linea . '[MESSAGGE]' . $mensaje . "\n";
    $log = new Log();
    $log->insertar($cadena, "Excepcion No Manejada");
    //echo $cadena;
}