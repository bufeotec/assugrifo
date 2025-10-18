<?php

require 'app/models/Reporte.php';
class ReporteController
{
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;

    private $reporte;

    public function __construct()
    {
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->reporte = new Reporte();
    }

    public function inicio()
    {
        try {
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));

            $fecha = date('Y-m-d');
            $productos = $this->reporte->listar_dia();
            $listar_monto_inicial = $this->reporte->listar_monto_apertura_reporte_dia($fecha);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reportes/reportes.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function reporte_general(){
        try {
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');
            $fecha_i= date('Y-m-d');
            $fecha_f=date('Y-m-d');
            $caja = $this->reporte->listar_cajas();
            $datos = false;
            if(isset($_POST['enviar_fecha'])) {
                $id_caja = $_POST['id_caja_numero'];
                //$id_usuario = $_POST['id_usuario'];
                $fecha_hoy = date('Y-m-d');
                $fecha_i = $_POST['fecha_filtro'];
                $fecha_f = $_POST['fecha_filtro_fin'];
                $fecha_ini_caja = $_POST['fecha_filtro'];
                $fecha_fin_caja = $_POST['fecha_filtro_fin'];
                $fecha_filtro = strtotime($_POST['fecha_filtro']);
                $fecha_filtro_fin = strtotime($_POST['fecha_filtro_fin']);
                $caja_ = $this->reporte->datos_caja_($id_caja);
                //$productos = $this->reporte->reporte_productos($fecha_filtro,$fecha_filtro_fin,$id_caja);

                $datos = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reportes/reporte_general.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function reporte_general_pdf(){
        try{
            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');

            $fecha_i = date('Y-m-d');
            $fecha_f = date('Y-m-d');

            if($_GET['fecha_filtro'] != "" && $_GET['fecha_filtro_fin'] != "" && $_GET['id_caja']){
                $id_caja = $_GET['id_caja'];
                $fecha_i = $_GET['fecha_filtro'];
                $fecha_f = $_GET['fecha_filtro_fin'];
                $fecha_ini_caja = $_GET['fecha_filtro'];
                $fecha_fin_caja = $_GET['fecha_filtro_fin'];
                $fecha_filtro = strtotime($_GET['fecha_filtro']);
                $fecha_filtro_fin = strtotime($_GET['fecha_filtro_fin']);
                $fecha_hoy = $_GET['fecha_filtro'];
                $fecha_fin = $_GET['fecha_filtro_fin'];
                $listar_egresos = $this->reporte->listar_egresos_reporte($id_caja,$fecha_hoy,$fecha_fin);

            }
            require _VIEW_PATH_ . 'reportes/reporte_general_pdf.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function ticket_reporte(){
        try{
            $fecha_i = $_POST['fecha_i'];
            $fecha_f = $_POST['fecha_f'];
            $id_caja = $_POST['id_caja'];

            $fecha_ini_caja = $_POST['fecha_i'];
            $fecha_fin_caja = $_POST['fecha_f'];

            $nueva_fecha_i = date('d-m-Y H:i:s',strtotime($fecha_i));
            $nueva_fecha_f = date('d-m-Y H:i:s',strtotime($fecha_f));
            $fecha_filtro = strtotime($_POST['fecha_i']);
            $fecha_filtro_fin = strtotime($_POST['fecha_f']);

            require _VIEW_PATH_ . 'reportes/ticket_reporte.php';
            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));
    }

    public function ticket_productos(){
        try{
            $fecha_i = $_POST['fecha_i'];
            $fecha_f = $_POST['fecha_f'];
            //$id_usuario = $_POST['id_usuario'];
            $id_caja = $_POST['id_caja'];

            $fecha_ini_caja = $_POST['fecha_i'];
            $fecha_fin_caja = $_POST['fecha_f'];

            $nueva_fecha_i = date('d-m-Y H:i:s',strtotime($fecha_i));
            $nueva_fecha_f = date('d-m-Y H:i:s',strtotime($fecha_f));
            $fecha_filtro = strtotime($_POST['fecha_i']);
            $fecha_filtro_fin = strtotime($_POST['fecha_f']);
            //$cajas_totales = $this->reporte->datos_por_apertura_caja_($fecha_i,$fecha_f);
            //$listar_productos = $this->reporte->reporte_productos($fecha_ini_caja,$fecha_fin_caja,$id_caja_numero);

            require _VIEW_PATH_ . 'reportes/ticket_productos.php';
            $result = 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result, "message")));
    }

    public function reporte_dia()
    {
        try {
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));

            //$fecha = date('Y-m-d');
            //$productos = $this->reporte->listar_dia();
            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');

            //$nueva_fecha_fin_ = date('Y-m-d',strtotime($fecha_filtro."- 1 days"));
            //$listar_monto_inicial = $this->reporte->listar_monto_apertura_reporte_dia_filtro($fecha_filtro,$fecha_filtro_fin);
            //$productos = $this->reporte->listar_dia();

            if(isset($_POST['enviar_fecha'])){
                $fecha_filtro = $_POST['fecha_filtro'];
                $fecha_filtro_fin = $_POST['fecha_filtro_fin'];
                $productos = $this->reporte->listar_dia();
                //$productos_ = $this->reporte->listar_dia_($fecha_filtro,$fecha_filtro_fin);
            }
            $nueva_fecha_fin_ = date('Y-m-d',strtotime($fecha_filtro."- 1 days"));

            $listar_monto_inicial = $this->reporte->listar_monto_apertura_reporte_dia_filtro($fecha_filtro,$fecha_filtro_fin);

            //$listar_monto_inicial = $this->reporte->listar_monto_apertura_reporte_dia_filtro($fecha_filtro,$fecha_filtro_fin);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reportes/reporte_dia.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function reporte_dia_pdf(){
        try{
            $id = $_GET['id'];
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');

            if($_GET['fecha_filtro'] != "" && $_GET['fecha_filtro_fin'] != ""){
                $fecha_filtro = $_GET['fecha_filtro'];
                $fecha_filtro_fin = $_GET['fecha_filtro_fin'];
                //$productos = $this->reporte->listar_dia($fecha_filtro,$fecha_filtro_fin);
                $productos_ = $this->reporte->listar_dia();
            }

            $nueva_fecha_fin_ = date('Y-m-d',strtotime($fecha_filtro."- 1 days"));
            $egreso = $this->reporte->listar_egresos_dia($fecha_filtro,$fecha_filtro_fin);
            $listar_monto_inicial = $this->reporte->listar_monto_apertura_reporte_dia_filtro($fecha_filtro,$fecha_filtro_fin);
            $fecha = date('d-m-Y');
            require _VIEW_PATH_ . 'reportes/reporte_dia_pdf.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function reporte_compras(){
        try{
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));

            $fecha_hoy = date('Y-m-d');

            $productos_stock = $this->reporte->listar_dia_stock($fecha_hoy);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reportes/reporte_compras.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function ingresos_y_egresos(){
        try{

            $fecha_hoy = date('Y-m-d');

            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');
            if(isset($_POST['enviar_fecha'])){
                $fecha_filtro = $_POST['fecha_filtro'];
                $fecha_filtro_fin = $_POST['fecha_filtro_fin'];
                $ventas = $this->reporte->listar_ventas_filtro($fecha_filtro,$fecha_filtro_fin);
                $listar_egresos = $this->reporte->listar_egresos($fecha_filtro,$fecha_filtro_fin);
            }

            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));


            //$listar_monto_inicial = $this->reporte->listar_monto_apertura($fecha_filtro,$fecha_filtro_fin);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reportes/ingresos_y_egresos.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function ingresos_egresos_pdf(){
        try{

            $nueva_fecha = $_POST['fecha'];

            $ventas = $this->reporte->listar_ventas_nuevo_filtro($nueva_fecha);
            $listar_egresos = $this->reporte->listar_egresos_nuevo($nueva_fecha);
            $listar_monto_inicial = $this->reporte->listar_monto_apertura_reporte_dia($nueva_fecha);

            require _VIEW_PATH_ . 'reportes/ingresos_y_egresos_pdf.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function inventario(){
        try{
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $fecha = date('Y-m-d');
            $productos = $this->reporte->listar_productos();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reportes/inventario.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function salidas_stock(){
        try{
            $id_producto = $_POST['id_producto'];
            $fecha_filtro = $_POST['fecha_filtro'];
            $fecha_filtro_fin = $_POST['fecha_filtro_fin'];
            $identificador = $_POST['identificador'];

            //Identificador 1 es agregados
            //Identificador 2 es ventas
            //Identificador 3 es salidas / donaciones
            if($identificador == 1){
                $result = $this->reporte->consultar_agregados($id_producto, $fecha_filtro,$fecha_filtro_fin);
                $result_ = $this->reporte->consultar_agregados_($id_producto, $fecha_filtro,$fecha_filtro_fin);
                $detalle_ = "<label style='color:black;'>Producto:  ".$result_->producto_nombre."</label>";
                $detalle = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Descripción</th>
                                            <th>Fecha de Agregado</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
                foreach ($result as $r){
                    $detalle .= "<tr>
                                <td>". $r->stocklog_added ."</td>
                                <td>". $r->stocklog_description ."</td>
                                <td>". $r->stocklog_date ."</td>
                            </tr>";
                }
                $detalle .= "</tbody></table>";

            }elseif ($identificador == 2){
                $result = $this->reporte->consultar_ventas($id_producto, $fecha_filtro,$fecha_filtro_fin);

                $detalle = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Vendedor</th>
                                            <th>Producto</th>
                                            <th>Documento</th>
                                            <th>Correlativo</th>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Monto</th>
                                            <th>Fecha Venta</th>
                                        </tr>
                                        </thead>
                                        <tbody>";

                foreach ($result as $r){
                    if($r->venta_tipo == "03"){
                        $venta_ = "BOLETA";
                    }
                    $detalle .= "<tr>
                                <td>". $r->persona_nombre.' '.$r->persona_apellido_paterno ."</td>
                                <td>". $r->producto_nombre.' / '.$r->talla_nombre ."</td>
                                <td>". $venta_ ."</td>
                                <td>". $r->venta_correlativo ."</td>
                                <td>". $r->cliente_nombre ."</td>
                                <td>". $r->venta_detalle_cantidad ."</td>
                                <td>". $r->venta_detalle_precio_unitario ."</td>
                                <td>". $r->venta_fecha ."</td>
                            </tr>";
                }
                $detalle .= "</tbody></table>";
            }else{
                $fecha = date('Y-m-d');
                $result = $this->reporte->salidas_stock($id_producto, $fecha_filtro,$fecha_filtro_fin);
                $result_ = $this->reporte->salidas_stock_($id_producto, $fecha_filtro,$fecha_filtro_fin);

                $detalle_ = "<label style='color:black;'>Producto:  ".$result_->producto_nombre."</label>";
                $detalle = " <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th>Origen</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <th>Destino</th>
                                            <th>Fecha Salida</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
                foreach ($result as $r){
                    $detalle .= "<tr>
                                <td>". $r->stockout_origin ."</td>
                                <td>". $r->stockout_description ."</td>
                                <td>". $r->stockout_out ."</td>
                                <td>". $r->stockout_destiny ."</td>
                                <td>". $r->stockout_date ."</td>
                            </tr>";
                }
                $detalle .= "</tbody></table>";
            }
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
        //Retornamos el json
        echo json_encode(array("detalle"=>$detalle,"detalle_"=>$detalle_));
    }

    //FUNCION PARA HACER LA JUGADA SOBRE LAS FECHAS
    public function datos_x_fecha(){
        try{
            $fecha_hoy = $_POST['fecha'];
            $identificador = $_POST['identificador'];

            if($identificador == 0){
                $nueva_fecha = $fecha_hoy;
            }elseif($identificador == 1){
                $nueva_fecha = date('Y-m-d',strtotime($fecha_hoy."- 1 days"));
            }else{
                $nueva_fecha = date('Y-m-d',strtotime($fecha_hoy."+ 1 days"));
            }
            $result = $this->reporte->listar_ventas_nuevo_filtro($nueva_fecha);
            $egresos_for = $this->reporte->listar_egresos_nuevo($nueva_fecha);
            $ingresos_ = $this->reporte->listar_ingresos_nuevo($nueva_fecha);
            $listar_monto_inicial = $this->reporte->listar_monto_apertura_reporte_dia($nueva_fecha);

            $tabla_ingresos = " <center><h5>INGRESOS</h5></center>
                                <table class='table table-bordered table-hover' style='border-color: black'>
                                        <thead>
                                        <tr style='background-color: #ebebeb'>
                                            <th>Fecha</th>
                                            <th>Documento</th>
                                            <th>Correlativo</th>
                                            <th>Ver Venta</th>
                                            <th>Nombre</th>
                                            <th>Monto</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            $ingresos_productos = 0;
            foreach ($result as $r) {
                if($r->venta_tipo == "03"){
                    $venta_ = "BOLETA";
                }elseif($r->venta_tipo == "01"){
                    $venta_ = "FACTURA";
                }
                $styleee = "";
                $importe_total = 0;
                if($r->anulado_sunat == 1){
                    $styleee = "style= 'background: #FA9682;'";
                }else{
                    $importe_total = $r->venta_total;
                }
                $ingresos_productos = $ingresos_productos + $importe_total;
                $ingresos_productos_ = $ingresos_productos ?? 0;
                $tabla_ingresos .= "<tr ".$styleee.">
                                <td>". $r->venta_fecha ."</td>
                                <td>". $venta_ ."</td>
                                <td>". $r->venta_correlativo ."</td>
                                <td><a href='"._SERVER_. 'Ventas/ver_venta/' . $r->id_venta."'> Ver</a></td>
                                <td>". $r->cliente_nombre . $r->cliente_razonsocial."</td>
                                <td>". $r->venta_total ."</td>
                            </tr>";
            }

            $tabla_ingresos .= "<tr><td colspan='5' style='text-align: right'>Total Ingresos Ventas Productos:</td><td style='background-color: #f9f17f'><b> S/.".number_format($ingresos_productos_,2)."</b></td></tr>";
            $tabla_ingresos .= "</tbody></table>";

            $tabla_egresos = " <center><h5>EGRESOS</h5></center>
                                <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr style='background-color: #ebebeb'>
                                            <th>Fecha</th>
                                            <th>Nombre</th>
                                            <th>Importe</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
            $egresos_ = 0;
            foreach ($egresos_for as $r){
                $egresos_ = $egresos_ + $r->egreso_monto;
                //$egresos_ = $egresos_ ?? 0;
                $tabla_egresos .= "<tr>
                                <td>". $r->egreso_fecha_registro ."</td>
                                <td>". $r->egreso_descripcion ."</td>
                                <td>". $r->egreso_monto ."</td>
                            </tr>";
            }
            $tabla_egresos .= "<tr><td colspan='2' style='text-align: right'>Total Egresos:</td><td style='background-color: #f9f17f'><b> S/.".number_format($egresos_,2)."</b></td></tr>";
            $tabla_egresos .= "</tbody></table>";


            $balance_final = $ingresos_productos - $egresos_;

            $suma_caja = $balance_final + $listar_monto_inicial->caja_apertura + $ingresos_->total;
            $tabla_datos = "<table class='table'>
                                <tbody>";

            $tabla_datos .= "<tr>
                                <td style='background-color: #ebebeb; font-weight: bold'>TOTAL INGRESOS VENTAS:</td>
                                <td>S/. ". number_format($ingresos_productos,2)."</td>
                            </tr>
                            <tr>
                                <td style='background-color: #ebebeb; font-weight: bold'>TOTAL EGRESOS CAJA CHICA:</td>
                                <td>S/. ". number_format($egresos_,2) ."</td>
                            </tr>
                            <tr style='border-top: 2px solid green;'>
                                <td style='background-color: #ebebeb; font-weight: bold'>TOTAL INGRESOS CAJA CHICA</td>
                                <td>S/. ".number_format($ingresos_->total,2)."</td>
                            </tr>
                            <tr>
                                <td style='background-color: #ebebeb; font-weight: bold'>MONTO DE APERTURA DE CAJA:</td>
                                <td>S/. ".number_format($listar_monto_inicial->caja_apertura,2)."</td>
                            </tr>
                            <tr style='border-top: 3px solid red;'>
                                <td style='background-color: #ebebeb; font-weight: bold'>TOTAL EN CAJA:</td>
                                <td style='background-color: #f9f17f; font-weight: bold'>S/. ".number_format($suma_caja,2)."</td>
                            </tr>";

            $tabla_datos .= "</tbody></table>";

        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
        //Retornamos el json
        echo json_encode(array("tabla_ingresos"=>$tabla_ingresos,"tabla_egresos"=>$tabla_egresos,"nueva_fecha"=>$nueva_fecha,"tabla_datos"=>$tabla_datos));
    }


}
