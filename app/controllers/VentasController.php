<?php
require 'app/models/Ventas.php';
require 'app/models/Inventario.php';
require 'app/models/Clientes.php';
require 'app/models/Correlativo.php';
require 'app/models/Turno.php';
require 'app/models/Admin.php';
require 'app/models/Nmletras.php';
require 'app/models/ApiFacturacion.php';
require 'app/models/GeneradorXML.php';
class VentasController
{
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $ventas;
    private $inventario;
    private $clientes;
    private $correlativo;
    private $turno;
    private $admin;
    private $apiFacturacion;
    private $numLetra;
    private $generadorXML;

    public function __construct()
    {
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();

        $this->ventas = new Ventas();
        $this->inventario = new Inventario();
        $this->clientes = new Clientes();
        $this->correlativo = new Correlativo();
        $this->turno = new Turno();
        $this->admin = new Admin();
        $this->generadorXML= new GeneradorXML();
        $this->apiFacturacion= new ApiFacturacion();
        $this->numLetra = new Nmletras();
    }

    public function venta_rapida(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $_SESSION['productos'] = array();
            //LISTAMOS LOS PRODUCTOS
            $productos = $this->inventario->listar_productos_venta();
            //LISTAMOS LOS CLIENTES
            $clientes = $this->ventas->listar_clientes();

            $tiponotacredito = $this->ventas->listAllCredito();
            $tiponotadebito = $this->ventas->listAllDebito();
            $tipo_pago = $this->ventas->listar_tipo_pago();
            $tipos_documento = $this->clientes->listar_documentos();


            $fecha = date('Y-m-d');
            $caja_apertura_fecha = $this->admin->listar_ultima_fecha($fecha);
            if($caja_apertura_fecha == true){
                require _VIEW_PATH_ . 'header.php';
                require _VIEW_PATH_ . 'navbar.php';
                require _VIEW_PATH_ . 'ventas/realizar_venta_rapida.php';
                require _VIEW_PATH_ . 'footer.php';
            }else{
                echo "<script language=\"javascript\">alert(\"Para realizar una venta debes aperturar Caja. Redireccionando Al Inicio\");</script>";
                echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            }
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function realizar_venta(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $_SESSION['productos'] = array();
            //LISTAMOS LOS PRODUCTOS
            $productos = $this->inventario->listar_productos_venta();
            //LISTAMOS LOS CLIENTES
            $clientes = $this->ventas->listar_clientes();

            $tiponotacredito = $this->ventas->listAllCredito();
            $tiponotadebito = $this->ventas->listAllDebito();
            $tipo_pago = $this->ventas->listar_tipo_pago();
            $tipos_documento = $this->clientes->listar_documentos();


            $fecha = date('Y-m-d');
            $caja_apertura_fecha = $this->admin->listar_ultima_fecha($fecha);
            if($caja_apertura_fecha == true){
                require _VIEW_PATH_ . 'header.php';
                require _VIEW_PATH_ . 'navbar.php';
                require _VIEW_PATH_ . 'ventas/realizar_venta.php';
                require _VIEW_PATH_ . 'footer.php';
            }else{
                echo "<script language=\"javascript\">alert(\"Para realizar una venta debes aperturar Caja. Redireccionando Al Inicio\");</script>";
                echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            }
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function listar_pre_venta(){
        try{
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $ventas_ = $this->ventas->listar_datos_pre_venta();
            //Hacemos el require de los archivos a usar en las vistas
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/listado_pre_venta.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function cobrar_venta(){
        try{
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $ventas_cobrar = $this->ventas->listar_datos_venta($id);
            $ventas_detalle = $this->ventas->listar_datos_venta_detalle($id);
            //LISTAMOS LOS CLIENTES
            $clientes = $this->ventas->listar_clientes();
            $tiponotacredito = $this->ventas->listAllCredito();
            $tiponotadebito = $this->ventas->listAllDebito();
            $tipo_pago = $this->ventas->listar_tipo_pago();
            $tipos_documento = $this->clientes->listar_documentos();
            //Hacemos el require de los archivos a usar en las vistas
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/cobrar_venta.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function tabla_productos(){
        try{
            require _VIEW_PATH_ . 'ventas/tabla_productos.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<br><br><div style='text-align: center'><h3>Ocurrió Un Error Al Cargar La Informacion</h3></div>";
        }
    }

    public function historial_ventas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            $ventas_cant = $this->ventas->listar_ventas_sin_enviar();

            if(isset($_POST['enviar_registro'])){
                $query = "SELECT * FROM ventas v inner join clientes c on v.id_cliente = c.id_cliente inner join monedas mo
                        on v.id_moneda = mo.id_moneda INNER JOIN usuarios u on v.id_usuario = u.id_usuario 
                        inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago 
                        where v.venta_estado_sunat = 0 ";
                $select = "";
                $where = true;
                if($_POST['tipo_venta']!=""){
                    $where = true;
                    $select = $select . " and v.venta_tipo = '" . $_POST['tipo_venta'] . "'";
                    $tipo_venta = $_POST['tipo_venta'];
                }

                if($_POST['fecha_inicio'] != "" AND $_POST['fecha_final'] != ""){
                    $where = true;
                    $select = $select . " and DATE(v.venta_fecha) between '" . $_POST['fecha_inicio'] ."' and '" . $_POST['fecha_final'] ."'";
                    $fecha_ini = $_POST['fecha_inicio'];
                    $fecha_fin = $_POST['fecha_final'];
                }

                if($where){
                    $datos = true;
                    $order = " order by v.venta_fecha asc";
                    $query = $query . $select . $order;
                    $ventas = $this->ventas->listar_ventas($query);
                }

                /*if($_POST['tipo_venta']!="" && $_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_todo($_POST['tipo_venta'],$_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }elseif($_POST['tipo_venta']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_tipo($_POST['tipo_venta'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                }elseif($_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_estado($_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }else{
                    $ventas = $this->ventas->listar_ventas_filtro_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);
                }*/
                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_ventas.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_ventas_enviadas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            if(isset($_POST['enviar_registro'])){
                $query = "SELECT * FROM ventas v inner join clientes c on v.id_cliente = c.id_cliente inner join monedas mo
                        on v.id_moneda = mo.id_moneda INNER JOIN usuarios u on v.id_usuario = u.id_usuario 
                        inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago 
                        where v.venta_estado_sunat = 1 and  v.venta_tipo in ('01', '03', '07', '08', '10')";
                $select = "";
                $where = true;
                if($_POST['tipo_venta']!=""){
                    $where = true;
                    $select = $select . " and v.venta_tipo = '" . $_POST['tipo_venta'] . "'";
                    $tipo_venta = $_POST['tipo_venta'];
                }

                if($_POST['fecha_inicio'] != "" AND $_POST['fecha_final'] != ""){
                    $where = true;
                    $select = $select . " and DATE(v.venta_fecha) between '" . $_POST['fecha_inicio'] ."' and '" . $_POST['fecha_final'] ."'";
                    $fecha_ini = $_POST['fecha_inicio'];
                    $fecha_fin = $_POST['fecha_final'];
                }

                if($where){
                    $datos = true;
                    $order = " order by v.venta_fecha asc";
                    $query = $query . $select . $order;
                    $ventas = $this->ventas->listar_ventas($query);
                }

                /*if($_POST['tipo_venta']!="" && $_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_todo($_POST['tipo_venta'],$_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }elseif($_POST['tipo_venta']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_tipo($_POST['tipo_venta'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                }elseif($_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_estado($_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }else{
                    $ventas = $this->ventas->listar_ventas_filtro_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);
                }*/
                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_ventas_enviadas.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function imprimir_ticket_pdf(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if ($id == 0) {
                throw new Exception('ID Sin Declarar');
            }

            $dato_venta = $this->ventas->listar_venta_x_id_pdf($id);
            $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($id);
            $fecha_hoy = date('d-m-Y H:i:s');
            $ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";

            if ($dato_venta->venta_tipo == "03") {
                $tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                if($dato_venta->cliente_numero == "11111111"){
                    $documento = "DNI:                        SIN DOCUMENTO";
                }else{
                    $documento = "DNI:                        $dato_venta->cliente_numero";
                }
            } else if ($dato_venta->venta_tipo == "01") {
                $tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "RUC:                      $dato_venta->cliente_numero";
            } else if ($dato_venta->venta_tipo == "07") {
                $tipo_comprobante = "NOTA DE CRÉDITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "DOCUMENTO: $dato_venta->cliente_numero";
            } else {
                $tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "DOCUMENTO: $dato_venta->cliente_numero";
            }
            $importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
            $arrayImporte = explode(".", $dato_venta->venta_total);
            $montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
            //$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
            $dato_impresion = 'DATOS DE IMPRESIÓN:';
            require _VIEW_PATH_ . 'ventas/imprimir_ticket_pdf.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    public function envio_resumenes_diario(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = '';
            if(isset($_POST['enviar_registro'])){
                $query = "SELECT * FROM ventas v inner join clientes c on v.id_cliente = c.id_cliente inner join monedas mo
                        on v.id_moneda = mo.id_moneda INNER JOIN usuarios u on v.id_usuario = u.id_usuario 
                        inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago 
                        where v.venta_estado_sunat = 0 and v.venta_tipo <> '01' and v.tipo_documento_modificar <> '01'
                        and v.venta_tipo_envio <> 1";
                $select = "";
                $where = true;
                $tipo_venta = $_POST['tipo_venta'];

                if($_POST['fecha_inicio'] != "" ){
                    $where = true;
                    $select = $select . " and DATE(v.venta_fecha) = '" . $_POST['fecha_inicio'] ."'";
                    $fecha_ini = $_POST['fecha_inicio'];
                    //$fecha_fin = $_POST['fecha_final'];
                }

                if($where){
                    $datos = true;
                    $order = " order by v.venta_fecha asc";
                    $query = $query . $select . $order;
                    $ventas = $this->ventas->listar_ventas($query);
                }

                /*if($_POST['tipo_venta']!="" && $_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_todo($_POST['tipo_venta'],$_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }elseif($_POST['tipo_venta']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_tipo($_POST['tipo_venta'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                }elseif($_POST['estado_cpe']!=""){
                    $ventas = $this->ventas->listar_ventas_filtro_estado($_POST['estado_cpe'],$_POST['fecha_inicio'], $_POST['fecha_final']);
                    $tipo_venta = $_POST['tipo_venta'];
                    $estado_cpe = $_POST['estado_cpe'];
                }else{
                    $ventas = $this->ventas->listar_ventas_filtro_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);
                }*/
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/envio_resumen_diario.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_resumen_diario(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            if(isset($_POST['enviar_registro'])){

                $resumen = $this->ventas->listar_resumen_diario_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);

                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_resumen_diario.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_bajas_facturas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');
            if(isset($_POST['enviar_registro'])){

                $bajas = $this->ventas->listar_comunicacion_baja_fecha($_POST['fecha_inicio'], $_POST['fecha_final']);

                $fecha_ini = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_final'];
                $filtro = true;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_bajas_facturas.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function generar_nota(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $_SESSION['productos'] = array();
            $venta = $this->ventas->listar_venta($id);
            $detalle_venta = $this->ventas->listar_detalle_ventas($id);
            $tipo_pago = $this->ventas->listar_tipo_pago();
            $productos = $this->inventario->listar_productos_();
            //LISTAMOS LOS CLIENTES
            $clientes = $this->ventas->listar_clientes();
            $tipos_documento = $this->clientes->listar_documentos();

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/generar_nota.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function ver_detalle_resumen(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $resumen = $this->ventas->listar_resumen_diario_x_id($id);
            $detalle_resumen = $this->ventas->listar_resumen_diario_detalle_x_id($id);


            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/ver_detalle_resumen.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function ver_venta(){
        try{
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            //creamos la vartiable que recibimos por metodo GET
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $sale = $this->ventas->listar_venta($id);
            $productssale = $this->ventas->listar_detalle_ventas($id);
            //Hacemos el require de los archivos a usar en las vistas
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/ver_venta.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function ver_guia(){
        try{
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            //creamos la vartiable que recibimos por metodo GET
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $guia = $this->ventas->listar_guia_remision_x_id($id);
            $guia_detalle = $this->ventas->listar_detalle_guia_para_xml($id);
            if(empty($guia->id_cliente)){
                $venta = [];
            } else {
                $venta = $this->ventas->listar_venta_x_id($guia->id_venta);
            }
            $cliente = $this->ventas->listar_clienteventa_x_id($guia->id_cliente);
            $ubigeo = $this->ventas->list_ubigeo();
            $conductor = explode(' // ', $guia->guia_nombre_cond);
            //Hacemos el require de los archivos a usar en las vistas
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/ver_guia.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    //VISTA PARA LA GUIA DE REMISION
    public function generar_guia(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'] ?? 0;
            if($id == 0){
                throw new Exception('ID Sin Declarar');
            }
            $serie = $this->ventas->serie_guia('09');
            $sale = $this->ventas->listar_venta($_GET['id']);
            $productssale = $this->ventas->listar_detalle_ventas($_GET['id']);
            $ubigeo = $this->ventas->list_ubigeo();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/generar_guia.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function generar_guia_remitente(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $serie = $this->ventas->serie_guia('09');
            /*$sale = $this->ventas->listar_venta($_GET['id']);
            $productssale = $this->ventas->listar_detalle_ventas($_GET['id']);*/
            $ubigeo = $this->ventas->list_ubigeo();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/generar_guia_remitente.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function guia_pdf(){
        try{
            $id = $_GET['id'] ?? 0;
            if ($id == 0) {
                throw new Exception('ID Sin Declarar');
            }
            $sale = $this->ventas->seach_guia($id);
            $productssale = $this->ventas->list_details_sale($sale->id_guia);
            require _VIEW_PATH_ . 'Ventas/guia_pdf.php';
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function nueva_guia_pdf(){
        try{
            $id = $_GET['id'] ?? 0;
            if ($id == 0) {
                throw new Exception('ID Sin Declarar');
            }
            $sale = $this->ventas->seach_guia_($id);
            $productssale = $this->ventas->list_details_sale($sale->id_guia);
            require _VIEW_PATH_ . 'Ventas/guia_pdf.php';
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_guias(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $filtro = false;
            $fecha_ini = date('2022-01-01');
            $fecha_fin = date('Y-m-d');

            if (isset($_POST['fecha_inicio'])){$fecha_ini=$_POST['fecha_inicio'];}
            if (isset($_POST['fecha_final'])){$fecha_fin=$_POST['fecha_final'];}

            $guias = $this->ventas->list_guias();
            $cant_guia = count($guias);

            if(isset($_POST['enviar_registro'])){
                $guias = $this->ventas->list_guias_asend($fecha_ini,$fecha_fin);
                $filtro = true;
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_guias.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_guias_enviadas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $filtro = false;
            $fecha_ini = date('Y-m-d');
            $fecha_fin = date('Y-m-d');

            if (isset($_POST['fecha_inicio'])){$fecha_ini=$_POST['fecha_inicio'];}
            if (isset($_POST['fecha_final'])){$fecha_fin=$_POST['fecha_final'];}


            if(isset($_POST['enviar_registro'])){
                $guias = $this->ventas->list_guias_send($fecha_ini, $fecha_fin);
                $filtro = true;
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/historial_guias_enviadas.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    //FUNCIONES
    public function addproduct(){
        try{
            if(isset($_POST['datos_pp']) && isset($_POST['producto']) && isset($_POST['precio']) && isset($_POST['cantidad']) && isset($_POST['tipo_igv'])){
                $repeat = false;
                foreach($_SESSION['productos'] as $p){
                    if($_POST['codigo'] == $p[0]){
                        $repeat = false;
                    }
                }
                if(!$repeat){
                    $nombre = $_POST['producto'].' '.$_POST['nombre'];
                    array_push($_SESSION['productos'], [$_POST['datos_pp'], $_POST['producto'], round($_POST['precio'], 6), $_POST['cantidad'], $_POST['tipo_igv'], $_POST['product_descuento'],$_POST['id_talla'],$_POST['nombre'],$nombre,$_POST['num']]);
                    $result = 1;
                } else {
                    $result = 3;
                }
            } else {
                $result = 2;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo $result;
    }

    public function editar_cantidad_tabla(){
        try{
            if(isset($_POST['id'])){
                $buscar = $_POST['id'];
                $valor_nueva_cantidad = $_POST['valor_nueva_cantidad'];
                $editar = count($_SESSION['productos']);
                for($i=0; $i < $editar; $i++){
                    if($_SESSION['productos'][$i][0] == $buscar){
                        $_SESSION['productos'][$i][3] = $valor_nueva_cantidad;
                    }
                }
                $_SESSION['productos'] = array_values($_SESSION['productos']);
                $result = 1;
            }

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo json_encode($result);
    }

    function editar_concepto(){
        try{
            if(isset($_POST['id'])){
                $buscar = $_POST['id'];
                $item = $_POST['item'];
                $union = $_POST['union']; //126_3
                $nuevo_concepto = $_POST['valor_concepto_nuevo'];
                $editar = count($_SESSION['productos']);
                for($i=1; $i < $editar+1; $i++){
                    $union2 = $_SESSION['productos'][$i][0].'_'.$item;
                    if ($i == $item){
                        $j=$i-1;
                        $_SESSION['productos'][$j][8] = $nuevo_concepto;

                    }
                }
                $_SESSION['productos'] = array_values($_SESSION['productos']);
                $result = 1;
            }

        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo json_encode($result);
    }

    public function eliminar_producto(){
        try{
            if(isset($_POST['codigo'])){
                $buscar = $_POST['codigo'];
                $totalar = count($_SESSION['productos']);
                for($i=0; $i < $totalar; $i++){
                    if($_SESSION['productos'][$i][0] == $buscar){
                        unset($_SESSION['productos'][$i]);
                    }
                }
                $_SESSION['productos'] = array_values($_SESSION['productos']);
                $result = 1;
            } else {
                $result = 2;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo $result;
    }

    public function guardar_venta(){
        //Código de error general
        $return = 0;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //Validacion de datos
            if($ok_data){
                $model = new Ventas();
                if($this->clientes->validar_dni($_POST['cliente_number'])){
                    //Código 5: DNI duplicado
                    $return = 1;
                    $message = "Ya existe un cliente con este DNI registrado";
                } else{
                    $model->cliente_razonsocial = "";
                    $model->cliente_nombre = "";
                    if($_POST['select_tipodocumento'] == 4){
                        $model->cliente_razonsocial = $_POST['cliente_name'];
                    }else{
                        $model->cliente_nombre = $_POST['cliente_name'];
                    }
                    $model->id_tipodocumento = $_POST['select_tipodocumento'];
                    $model->cliente_numero = $_POST['cliente_number'];
                    $model->cliente_correo = "";
                    $model->cliente_direccion = $_POST['cliente_direccion'];
                    $model->cliente_telefono = $_POST['cliente_telefono'];

                    $return = $this->clientes->guardar($model);
                }
                if($return == 1){
                    $consulta_cliente = $this->clientes->listar_cliente_x_numerodoc($_POST['cliente_number']);
                    $model->id_cliente = $consulta_cliente->id_cliente;
                    $model->id_moneda = $_POST['tipo_moneda'];
                    $model->id_caja = 1;
                    //$model->venta_nombre = $_POST['client_name'];
                    //$model->venta_direccion = $_POST['saleproduct_direccion'];
                    $model->id_tipo_pago =  $_POST['id_tipo_pago'];
                    $model->forma_pago = $_POST['forma_pago'];
                    $producto_venta_tipo = $_POST['saleproduct_type'];
                    $model->venta_tipo =  $producto_venta_tipo;
                    //obtener serie con el id
                    $serie_ = $this->ventas->listar_correlativos_x_serie($_POST['serie']);
                    $model->venta_serie = $serie_->serie;
                    $model->venta_correlativo = $_POST['correlativo'];

                    $producto_venta_fecha = date("Y-m-d H:i:s");
                    $model->venta_fecha = $producto_venta_fecha;
                    $model->venta_estado = 1;
                    $model->id_usuario_cobro = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                    $id_venta = $_POST['id_venta'];
                    $model->id_venta = $id_venta;

                    $return = $this->ventas->cobrar_venta($model);
                    //AQUI RESTARIA STOCK
                    if($return==1){
                        /* Si la venta se contiene cuota Aca se guarda  */
                        if ($_POST['forma_pago'] == 'CREDITO') {
                            //GUARDAR EN BASE DE DATOS
                            $forma_pago = $_POST['forma_pago'];
                            $contenido_cuota = $_POST['contenido_cuota'];
                            $conteo = 1;
                            if (count_chars($contenido_cuota) > 0) {
                                $filas = explode('/./.', $contenido_cuota);
                                if (count($filas) > 0) {
                                    for ($i = 0; $i < count($filas) - 1; $i++) {
                                        $modelDSI = new Ventas();
                                        $celdas = explode('-.-.', $filas[$i]);
                                        $modelDSI->id_ventas = $id_venta;
                                        $modelDSI->id_tipo_pago = $_POST['id_tipo_pago'];

                                        $modelDSI->conteo = $conteo;
                                        $modelDSI->venta_cuota_numero = $celdas[0];
                                        $modelDSI->venta_cuota_fecha = $celdas[1];
                                        $this->ventas->guardar_cuota_venta($modelDSI);
                                        $conteo++;
                                    }
                                }
                            }
                            //FIN - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                        }
                        /* Fin de guardar cuotas */
                        $cantidad = $this->ventas->jalar_valores($id_venta);
                        foreach ($cantidad as $c){
                            $reducir = $c->venta_detalle_cantidad;
                            $id_talla = $c->id_talla;
                            $id_producto = $c->id_producto;
                            //$id_producto = $this->ventas->listar_id_producto_productoprecio($id_producto_precio);
                            $this->ventas->restar_stock_talla($reducir,$id_talla);
                            $this->ventas->guardar_stock_nuevo($reducir, $id_producto);
                            $return = 1;
                        }
                    }
                    if($return == 1){
                        $return = $this->ventas->actualizarCorrelativo_x_id_Serie($_POST['serie'],$_POST['correlativo']);
                        //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                        include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                        $venta = $this->ventas->listar_venta($id_venta);
                        $detalle_venta =$this->ventas->listar_detalle_ventas($id_venta);
                        $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                        $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
                        //INICIO - CREACION QR
                        $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
                        $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                            $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                            $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
                        $ruta = 'libs/ApiFacturacion/imagenqr/';
                        $ruta_qr = $ruta.$nombre_qr.'.png';
                        QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
                        //FIN - CREACION QR
                        if($venta->venta_tipo == "03"){
                            $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
                        }elseif($venta->venta_tipo == "01"){
                            $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
                        }elseif($venta->venta_tipo == "07"){
                            $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                            $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                        }else{
                            $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                            $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);

                        }
                        if($cliente->id_tipodocumento == "4"){
                            $cliente_nombre = $cliente->cliente_razonsocial;
                        }else{
                            $cliente_nombre = $cliente->cliente_nombre;
                        }
                        if($return == 1){
                            require _VIEW_PATH_ . 'ventas/ticket_electronico.php';
                        }
                        $return = 1;
                    }
                    else {
                        $return = 2;
                    }
                }
            } else {
                //Código 6: Integridad de datos erronea
                $return = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $return, "message" => $message)));
    }

    public function guardar_pre_venta(){
        //Código de error general
        $result = 0;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //Validacion de datos
            if($ok_data){
                $model = new Ventas();
                $model->venta_tipo_envio = "0";
                //$consulta_cliente = $this->clientes->listar_cliente_x_numerodoc($_POST['cliente_number']);
                $id_turno = $this->turno->listar();
                $model->id_cliente = 1;
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                $model->id_usuario = $id_usuario;
                $model->id_turno = $id_turno->id_turno;
                $model->id_caja = 1;
                //$model->venta_nombre = $_POST['client_name'];
                //$model->venta_direccion = $_POST['saleproduct_direccion'];
                //$producto_venta_tipo = $_POST['saleproduct_type'];
                //$model->venta_tipo =  $producto_venta_tipo;
                $model->id_tipo_pago =  $_POST['id_tipo_pago'];
                //obtener serie con el id
                $serie_ = $this->ventas->listar_correlativos_x_serie($_POST['serie']);
                //$model->venta_serie = $serie_->serie;
                //$model->venta_correlativo = $_POST['correlativo'];
                $model->venta_tipo_moneda = $_POST['tipo_moneda'];
                $producto_venta_correlativo = 1;
                $model->producto_venta_correlativo = $producto_venta_correlativo;
                $model->producto_venta_totalgratuita = $_POST['saleproduct_gratuita'];
                $model->producto_venta_totalexonerada = $_POST['saleproduct_exonerada'];
                $model->producto_venta_totalinafecta = $_POST['saleproduct_inafecta'];
                $model->producto_venta_totalgravada = $_POST['saleproduct_gravada'];
                $model->producto_venta_totaligv = $_POST['saleproduct_igv'];
                $model->producto_venta_icbper = $_POST['saleproduct_icbper'];
                $model->producto_venta_total = $_POST['saleproduct_total'];
                if(empty($_POST['vuelto_'])){
                    $model->producto_venta_vuelto = 0;
                }else{
                    $model->producto_venta_vuelto = $_POST['vuelto_'];
                }
                if(empty($_POST['pago_con_'])){
                    $model->producto_venta_pago = 0;
                }else{
                    $model->producto_venta_pago = $_POST['pago_con_'];
                }
                if(empty($_POST['des_global'])){
                    $model->producto_venta_des_global = 0;
                }else{
                    $model->producto_venta_des_global = $_POST['des_global'];
                }
                if(empty($_POST['des_total'])){
                    $model->producto_venta_des_total = 0;
                }else{
                    $model->producto_venta_des_total = $_POST['des_total'];
                }
                $producto_venta_fecha = date("Y-m-d H:i:s");
                $model->producto_venta_fecha = $producto_venta_fecha;
                $model->tipo_documento_modificar = $_POST['Tipo_documento_modificar'];
                $model->serie_modificar = $_POST['serie_modificar'];
                $model->numero_modificar = $_POST['numero_modificar'];
                $model->notatipo_descripcion = $_POST['notatipo_descripcion'];
                $model->venta_estado = 0;
                $guardar = $this->ventas->guardar_pre_venta($model);

                if($guardar == 1){
                    $jalar_id = $this->ventas->jalar_id_venta($producto_venta_fecha,$id_usuario);
                    $idventa = $jalar_id->id_venta;
                }

                if($idventa != 0) { //despues de registrar la venta se sigue a registrar el detalle
                    $fecha_bolsa = date("Y");
                    if ($fecha_bolsa == "2020"){
                        $impuesto_icbper = 0.20;
                    } else if ($fecha_bolsa == "2021"){
                        $impuesto_icbper = 0.30;
                    } else if ($fecha_bolsa == "2022") {
                        $impuesto_icbper = 0.40;
                    } else{
                        $impuesto_icbper = 0.50;
                    }
                    $igv_porcentaje = 0.18;
                    $ICBPER = 0;
                    foreach ($_SESSION['productos'] as $p){
                        $cantidad = $p[3];
                        $precio_unitario = $p[2];
                        $descuento_item = $p[5];
                        $factor_porcentaje = 1;
                        $porcentaje=0;
                        $igv_detalle=0;
                        if($p[4] == 10){
                            $igv_detalle = $p[2]*$p[3]*$igv_porcentaje;
                            $factor_porcentaje = 1+ $igv_porcentaje;
                            $porcentaje = $igv_porcentaje * 100;
                        }
                        $subtotal = $precio_unitario * $cantidad;
                        if($p[5] > 0){
                            $subtotal = $subtotal - $descuento_item;
                        }
                        $id_producto_precio = $p[0];
                        $model->id_venta = $idventa;
                        $model->id_producto_precio = $id_producto_precio;
                        $model->venta_detalle_valor_unitario = $precio_unitario;
                        $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                        $model->venta_detalle_nombre_producto = $p[1]." / ".$p[7];
                        $model->venta_detalle_cantidad = $cantidad;
                        $model->venta_detalle_total_igv = $igv_detalle;
                        $model->venta_detalle_porcentaje_igv = $porcentaje;
                        $model->venta_detalle_valor_total = $subtotal;
                        $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                        $model->venta_detalle_descuento = $descuento_item;
                        //$id_talla = $p[7];

                        $return = $this->ventas->guardar_detalle_venta($model);
                        /*if($guardar_detalle == 1) {
                            $reducir = $cantidad;

                            $id_producto = $this->ventas->listar_id_producto_productoprecio($id_producto_precio);
                            $this->ventas->restar_stock_talla($reducir,$id_talla);
                            $this->ventas->guardar_stock_nuevo($reducir, $id_producto);
                            $return = 1;
                        } else {
                            $return = 2;
                        }*/
                    }
                    /*if($guardar_detalle == 1){
                        $return = $this->ventas->actualizarCorrelativo_x_id_Serie($_POST['serie'],$_POST['correlativo']);
                        //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                        include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                        $venta = $this->ventas->listar_venta($idventa);
                        $detalle_venta =$this->ventas->listar_detalle_ventas($idventa);
                        $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                        $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
                        //INICIO - CREACION QR
                        $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
                        $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                            $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                            $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
                        $ruta = 'libs/ApiFacturacion/imagenqr/';
                        $ruta_qr = $ruta.$nombre_qr.'.png';
                        QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
                        //FIN - CREACION QR
                        if($venta->venta_tipo == "03"){
                            $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
                        }elseif($venta->venta_tipo == "01"){
                            $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
                        }elseif($venta->venta_tipo == "07"){
                            $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                            $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                        }else{
                            $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                            $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);

                        }
                        if($cliente->id_tipodocumento == "4"){
                            $cliente_nombre = $cliente->cliente_razonsocial;
                        }else{
                            $cliente_nombre = $cliente->cliente_nombre;
                        }
                        /*if($return == 1){
                            require _VIEW_PATH_ . 'ventas/ticket_electronico.php';
                        }*/
                }

            } else {
                //Código 6: Integridad de datos erronea
                $return = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $return, "message" => $message, "idventa"=>$idventa)));
    }

    public function guardar_venta_rapida(){
        //Código de error general
        $result = 0;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //Validacion de datos
            if($ok_data){
                $model = new Ventas();
                if($this->clientes->validar_dni($_POST['cliente_number'])){
                    //Código 5: DNI duplicado
                    $return = 1;
                    $message = "Ya existe un cliente con este DNI registrado";
                } else{
                    $model->cliente_razonsocial = "";
                    $model->cliente_nombre = "";
                    if($_POST['select_tipodocumento'] == 4){
                        $model->cliente_razonsocial = $_POST['cliente_name'];
                    }else{
                        $model->cliente_nombre = $_POST['cliente_name'];
                    }
                    $model->id_tipodocumento = $_POST['select_tipodocumento'];
                    $model->cliente_numero = $_POST['cliente_number'];
                    $model->cliente_correo = "";
                    $model->cliente_direccion = $_POST['cliente_direccion'];
                    $model->cliente_telefono = $_POST['cliente_telefono'];

                    $return = $this->clientes->guardar($model);
                }
                if($return == 1){
                    $model->venta_tipo_envio = "0";
                    if(isset($_POST['id_venta'])){
                        $id_venta_ = $_POST['id_venta'];
                        $dato_venta = $this->ventas->listar_venta_x_id($id_venta_);
                        $model->venta_tipo_envio= $dato_venta->venta_tipo_envio;
                    }
                    $consulta_cliente = $this->clientes->listar_cliente_x_numerodoc($_POST['cliente_number']);
                    $id_turno = $this->turno->listar();
                    $model->id_cliente = $consulta_cliente->id_cliente;
                    $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                    $model->id_turno = $id_turno->id_turno;
                    $model->id_caja = 1;
                    //$model->venta_nombre = $_POST['client_name'];
                    //$model->venta_direccion = $_POST['saleproduct_direccion'];
                    $producto_venta_tipo = $_POST['saleproduct_type'];
                    $model->venta_tipo =  $producto_venta_tipo;
                    $model->id_tipo_pago =  $_POST['id_tipo_pago'];
                    $model->forma_pago =  $_POST['forma_pago'];
                    //obtener serie con el id
                    $serie_ = $this->ventas->listar_correlativos_x_serie($_POST['serie']);
                    $model->venta_serie = $serie_->serie;
                    $model->venta_correlativo = $_POST['correlativo'];
                    $model->venta_tipo_moneda = $_POST['tipo_moneda'];
                    $producto_venta_correlativo = 1;
                    $model->producto_venta_correlativo = $producto_venta_correlativo;
                    $model->producto_venta_totalgratuita = $_POST['saleproduct_gratuita'];
                    $model->producto_venta_totalexonerada = $_POST['saleproduct_exonerada'];
                    $model->producto_venta_totalinafecta = $_POST['saleproduct_inafecta'];
                    $model->producto_venta_totalgravada = $_POST['saleproduct_gravada'];
                    $model->producto_venta_totaligv = $_POST['saleproduct_igv'];
                    $model->producto_venta_icbper = $_POST['saleproduct_icbper'];
                    $model->producto_venta_total = $_POST['saleproduct_total'];
                    if(empty($_POST['vuelto_'])){
                        $model->producto_venta_vuelto = 0;
                    }else{
                        $model->producto_venta_vuelto = $_POST['vuelto_'];
                    }
                    if(empty($_POST['pago_con_'])){
                        $model->producto_venta_pago = 0;
                    }else{
                        $model->producto_venta_pago = $_POST['pago_con_'];
                    }
                    if(empty($_POST['des_global'])){
                        $model->producto_venta_des_global = 0;
                    }else{
                        $model->producto_venta_des_global = $_POST['des_global'];
                    }
                    if(empty($_POST['des_total'])){
                        $model->producto_venta_des_total = 0;
                    }else{
                        $model->producto_venta_des_total = $_POST['des_total'];
                    }
                    $producto_venta_fecha = date("Y-m-d H:i:s");
                    $model->producto_venta_fecha = $producto_venta_fecha;
                    $model->tipo_documento_modificar = $_POST['Tipo_documento_modificar'];
                    $model->serie_modificar = $_POST['serie_modificar'];
                    $model->numero_modificar = $_POST['numero_modificar'];
                    $model->notatipo_descripcion = $_POST['notatipo_descripcion'];
                    $model->venta_estado = 1;
                    $model->id_usuario_cobro = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                    $model->venta_nota_dato = $_POST['venta_nota_dato'];
                    $guardar = $this->ventas->guardar_venta($model);

                    if($guardar == 1){
                        $id_cliente = $consulta_cliente->id_cliente;
                        $jalar_id = $this->ventas->jalar_id_venta($producto_venta_fecha,$id_cliente);
                        $idventa = $jalar_id->id_venta;
                    }
                    //Según el tipo de venta, lo ponemos como enviado a SUNAT
                    if($producto_venta_tipo == '10'){
                        $this->ventas->poner_enviado($idventa);
                    }

                    if($idventa != 0) { //despues de registrar la venta se sigue a registrar el detalle
                        /* Si la venta se contiene cuota Aca se guarda  */
                        if ($_POST['forma_pago'] == 'CREDITO') {
                            //GUARDAR EN BASE DE DATOS
                            $forma_pago = $_POST['forma_pago'];
                            $contenido_cuota = $_POST['contenido_cuota'];
                            $conteo = 1;
                            if (count_chars($contenido_cuota) > 0) {
                                $filas = explode('/./.', $contenido_cuota);
                                if (count($filas) > 0) {
                                    for ($i = 0; $i < count($filas) - 1; $i++) {
                                        $modelDSI = new Ventas();
                                        $celdas = explode('-.-.', $filas[$i]);
                                        $modelDSI->id_ventas = $idventa;
                                        $modelDSI->id_tipo_pago = $_POST['id_tipo_pago'];

                                        $modelDSI->conteo = $conteo;
                                        $modelDSI->venta_cuota_numero = $celdas[0];
                                        $modelDSI->venta_cuota_fecha = $celdas[1];
                                        $this->ventas->guardar_cuota_venta($modelDSI);
                                        $conteo++;
                                    }
                                }
                            }
                            //FIN - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                        }
                        /* Fin de guardar cuotas */
                        $fecha_bolsa = date("Y");
                        if ($fecha_bolsa == "2020"){
                            $impuesto_icbper = 0.20;
                        } else if ($fecha_bolsa == "2021"){
                            $impuesto_icbper = 0.30;
                        } else if ($fecha_bolsa == "2022") {
                            $impuesto_icbper = 0.40;
                        } else{
                            $impuesto_icbper = 0.50;
                        }
                        $igv_porcentaje = 0.18;
                        $ICBPER = 0;
                        foreach ($_SESSION['productos'] as $p){
                            $cantidad = $p[3];
                            $precio_unitario = $p[2];
                            if(empty($p[5])){
                                $descuento_item = 0;
                            }else{
                                $descuento_item = 0;
                            }
                            $factor_porcentaje = 1;
                            $porcentaje=0;
                            $igv_detalle=0;
                            if($p[4] == 10){
                                $igv_detalle = $p[2]*$p[3]*$igv_porcentaje;
                                $factor_porcentaje = 1+ $igv_porcentaje;
                                $porcentaje = $igv_porcentaje * 100;
                            }
                            $subtotal = $precio_unitario * $cantidad;
                            if($p[5] > 0){
                                $subtotal = $subtotal - $descuento_item;
                            }
                            $id_producto_precio = $p[0];
                            $model->id_venta = $idventa;
                            $model->id_producto_precio = $id_producto_precio;
                            $model->venta_detalle_valor_unitario = $precio_unitario;
                            $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                            $model->venta_detalle_nombre_producto = $p[8];
                            $model->venta_detalle_cantidad = $cantidad;
                            $model->venta_detalle_total_igv = $igv_detalle;
                            $model->venta_detalle_porcentaje_igv = $porcentaje;
                            $model->venta_detalle_valor_total = $subtotal;
                            $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                            $model->venta_detalle_descuento = $descuento_item;
                            $model->venta_detalle_stock = $_POST['movimiento_stock'];
                            $model->venta_detalle_movimiento_stock = $cantidad * -1;
                            $id_talla = $p[6];

                            $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                            if($guardar_detalle == 1) {
                                //$id_producto = $this->ventas->listar_id_producto_productoprecio($id_producto_precio);
                                $stock = $this->ventas->contar_stock_talla($id_talla);
                                $this->ventas->actualizar_stock_talla($stock, $id_talla);
                                $return = 1;
                            } else {
                                $return = 2;
                            }
                        }
                        if($return == 1){
                            $return = $this->ventas->actualizarCorrelativo_x_id_Serie($_POST['serie'],$_POST['correlativo']);
                            //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                            include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                            $venta = $this->ventas->listar_venta($idventa);
                            if(empty($venta->venta_nota_dato)){
                                $datoto  = '---';
                            }else{
                                $datoto = $venta->venta_nota_dato;
                            }
                            $detalle_venta =$this->ventas->listar_detalle_ventas($idventa);
                            $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                            $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
                            //INICIO - CREACION QR
                            $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
                            $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                                $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                                $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
                            $ruta = 'libs/ApiFacturacion/imagenqr/';
                            $ruta_qr = $ruta.$nombre_qr.'.png';
                            QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
                            //FIN - CREACION QR
                            if($venta->venta_tipo == "03"){
                                $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
                            }elseif($venta->venta_tipo == "01"){
                                $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
                            }elseif($venta->venta_tipo == "07"){
                                $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                                $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                            }else{
                                $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                                $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);

                            }
                            if($cliente->id_tipodocumento == "4"){
                                $cliente_nombre = $cliente->cliente_razonsocial;
                            }else{
                                $cliente_nombre = $cliente->cliente_nombre;
                            }
                            if($return == 1){
                                require _VIEW_PATH_ . 'ventas/ticket_electronico.php';
                            }
                            $return = 1;
                        }
                    }else {
                        $return = 2;
                    }
                }
            } else {
                //Código 6: Integridad de datos erronea
                $return = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $return, "message" => $message, "idventa"=>$idventa)));
    }

    public function guardar_venta_rapida_(){
        //Código de error general
        $result = 0;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //Validacion de datos
            if($ok_data){
                $model = new Ventas();
                if($this->clientes->validar_dni($_POST['cliente_number'])){
                    //Código 5: DNI duplicado
                    $return = 1;
                    $message = "Ya existe un cliente con este DNI registrado";
                } else{
                    $model->cliente_razonsocial = "";
                    $model->cliente_nombre = "";
                    if($_POST['select_tipodocumento'] == 4){
                        $model->cliente_razonsocial = $_POST['cliente_name'];
                    }else{
                        $model->cliente_nombre = $_POST['cliente_name'];
                    }
                    $model->id_tipodocumento = $_POST['select_tipodocumento'];
                    $model->cliente_numero = $_POST['cliente_number'];
                    $model->cliente_correo = "";
                    $model->cliente_direccion = $_POST['cliente_direccion'];
                    $model->cliente_telefono = $_POST['cliente_telefono'];

                    $return = $this->clientes->guardar($model);
                }
                if($return == 1){
                    $model->venta_tipo_envio = "0";
                    if(isset($_POST['id_venta'])){
                        $id_venta_ = $_POST['id_venta'];
                        $dato_venta = $this->ventas->listar_venta_x_id($id_venta_);
                        $model->venta_tipo_envio= $dato_venta->venta_tipo_envio;
                    }
                    $consulta_cliente = $this->clientes->listar_cliente_x_numerodoc($_POST['cliente_number']);
                    $id_turno = $this->turno->listar();
                    $model->id_cliente = $consulta_cliente->id_cliente;
                    $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                    $model->id_turno = $id_turno->id_turno;
                    $model->id_caja = 1;
                    //$model->venta_nombre = $_POST['client_name'];
                    //$model->venta_direccion = $_POST['saleproduct_direccion'];
                    $producto_venta_tipo = $_POST['saleproduct_type'];
                    $model->venta_tipo =  $producto_venta_tipo;
                    $model->id_tipo_pago =  $_POST['id_tipo_pago'];
                    $model->forma_pago =  $_POST['forma_pago'];
                    //obtener serie con el id
                    $serie_ = $this->ventas->listar_correlativos_x_serie($_POST['serie']);
                    $model->venta_serie = $serie_->serie;
                    $model->venta_correlativo = $_POST['correlativo'];
                    $model->venta_tipo_moneda = $_POST['tipo_moneda'];
                    $producto_venta_correlativo = 1;
                    $model->producto_venta_correlativo = $producto_venta_correlativo;
                    $model->producto_venta_totalgratuita = $_POST['saleproduct_gratuita'];
                    $model->producto_venta_totalexonerada = $_POST['saleproduct_exonerada'];
                    $model->producto_venta_totalinafecta = $_POST['saleproduct_inafecta'];
                    $model->producto_venta_totalgravada = $_POST['saleproduct_gravada'];
                    $model->producto_venta_totaligv = $_POST['saleproduct_igv'];
                    $model->producto_venta_icbper = $_POST['saleproduct_icbper'];
                    $model->producto_venta_total = $_POST['saleproduct_total'];
                    if(empty($_POST['vuelto_'])){
                        $model->producto_venta_vuelto = 0;
                    }else{
                        $model->producto_venta_vuelto = $_POST['vuelto_'];
                    }
                    if(empty($_POST['pago_con_'])){
                        $model->producto_venta_pago = 0;
                    }else{
                        $model->producto_venta_pago = $_POST['pago_con_'];
                    }
                    if(empty($_POST['des_global'])){
                        $model->producto_venta_des_global = 0;
                    }else{
                        $model->producto_venta_des_global = $_POST['des_global'];
                    }
                    if(empty($_POST['des_total'])){
                        $model->producto_venta_des_total = 0;
                    }else{
                        $model->producto_venta_des_total = $_POST['des_total'];
                    }
                    $producto_venta_fecha = date("Y-m-d H:i:s");
                    $model->producto_venta_fecha = $producto_venta_fecha;
                    $model->tipo_documento_modificar = $_POST['Tipo_documento_modificar'];
                    $model->serie_modificar = $_POST['serie_modificar'];
                    $model->numero_modificar = $_POST['numero_modificar'];
                    $model->notatipo_descripcion = $_POST['notatipo_descripcion'];
                    $model->venta_estado = 1;
                    $model->id_usuario_cobro = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                    $model->venta_nota_dato = $_POST['venta_nota_dato'];
                    $guardar = $this->ventas->guardar_venta($model);

                    if($guardar == 1){
                        $id_cliente = $consulta_cliente->id_cliente;
                        $jalar_id = $this->ventas->jalar_id_venta($producto_venta_fecha,$id_cliente);
                        $idventa = $jalar_id->id_venta;
                    }

                    if($idventa != 0) { //despues de registrar la venta se sigue a registrar el detalle
                        /* Si la venta se contiene cuota Aca se guarda  */
                        if ($_POST['forma_pago'] == 'CREDITO') {
                            //GUARDAR EN BASE DE DATOS
                            $forma_pago = $_POST['forma_pago'];
                            $contenido_cuota = $_POST['contenido_cuota'];
                            $conteo = 1;
                            if (count_chars($contenido_cuota) > 0) {
                                $filas = explode('/./.', $contenido_cuota);
                                if (count($filas) > 0) {
                                    for ($i = 0; $i < count($filas) - 1; $i++) {
                                        $modelDSI = new Ventas();
                                        $celdas = explode('-.-.', $filas[$i]);
                                        $modelDSI->id_ventas = $idventa;
                                        $modelDSI->id_tipo_pago = $_POST['id_tipo_pago'];

                                        $modelDSI->conteo = $conteo;
                                        $modelDSI->venta_cuota_numero = $celdas[0];
                                        $modelDSI->venta_cuota_fecha = $celdas[1];
                                        $this->ventas->guardar_cuota_venta($modelDSI);
                                        $conteo++;
                                    }
                                }
                            }
                            //FIN - GUARDAR LAS CUOTAS SI LA VENTA ES A CRÉDITO
                        }
                        /* Fin de guardar cuotas */
                        $fecha_bolsa = date("Y");
                        if ($fecha_bolsa == "2020"){
                            $impuesto_icbper = 0.20;
                        } else if ($fecha_bolsa == "2021"){
                            $impuesto_icbper = 0.30;
                        } else if ($fecha_bolsa == "2022") {
                            $impuesto_icbper = 0.40;
                        } else{
                            $impuesto_icbper = 0.50;
                        }
                        $igv_porcentaje = 0.18;
                        $ICBPER = 0;
                        foreach ($_SESSION['productos'] as $p){
                            $cantidad = $p[3];
                            $precio_unitario = $p[2];
                            if(empty($p[5])){
                                $descuento_item = 0;
                            }else{
                                $descuento_item = 0;
                            }
                            $factor_porcentaje = 1;
                            $porcentaje=0;
                            $igv_detalle=0;
                            if($p[4] == 10){
                                $igv_detalle = $p[2]*$p[3]*$igv_porcentaje;
                                $factor_porcentaje = 1+ $igv_porcentaje;
                                $porcentaje = $igv_porcentaje * 100;
                            }
                            $subtotal = $precio_unitario * $cantidad;
                            if($p[5] > 0){
                                $subtotal = $subtotal - $descuento_item;
                            }
                            $id_producto_precio = $p[0];
                            $model->id_venta = $idventa;
                            $model->id_producto_precio = $id_producto_precio;
                            $model->venta_detalle_valor_unitario = $precio_unitario;
                            $model->venta_detalle_precio_unitario = $precio_unitario * $factor_porcentaje;
                            $model->venta_detalle_nombre_producto = $p[1];
                            $model->venta_detalle_cantidad = $cantidad;
                            $model->venta_detalle_total_igv = $igv_detalle;
                            $model->venta_detalle_porcentaje_igv = $porcentaje;
                            $model->venta_detalle_valor_total = $subtotal;
                            $model->venta_detalle_total_price = $subtotal * $factor_porcentaje;
                            $model->venta_detalle_descuento = 0;
                            $model->venta_detalle_stock = 0;
                            $model->venta_detalle_descuento = $descuento_item;
                            $id_talla = $p[6];

                            $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                            if($guardar_detalle == 1) {
                                $reducir = $cantidad;
                                $id_producto = $this->ventas->listar_id_producto_productoprecio($id_producto_precio);
                                $this->ventas->restar_stock_talla($reducir,$id_talla);
                                $this->ventas->guardar_stock_nuevo($reducir, $id_producto);
                                $return = 1;
                            } else {
                                $return = 2;
                            }
                        }
                        if($return == 1){
                            $return = $this->ventas->actualizarCorrelativo_x_id_Serie($_POST['serie'],$_POST['correlativo']);
                            //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
                            include('libs/ApiFacturacion/phpqrcode/qrlib.php');
                            $venta = $this->ventas->listar_venta($idventa);
                            if(empty($venta->venta_nota_dato)){
                                $datoto  = '---';
                            }else{
                                $datoto = $venta->venta_nota_dato;
                            }
                            $detalle_venta =$this->ventas->listar_detalle_ventas($idventa);
                            $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                            $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
                            //INICIO - CREACION QR
                            $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
                            $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                                $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                                $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
                            $ruta = 'libs/ApiFacturacion/imagenqr/';
                            $ruta_qr = $ruta.$nombre_qr.'.png';
                            QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
                            //FIN - CREACION QR
                            if($venta->venta_tipo == "03"){
                                $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
                            }elseif($venta->venta_tipo == "01"){
                                $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
                            }elseif($venta->venta_tipo == "07"){
                                $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                                $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                            }else{
                                $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                                $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);

                            }
                            if($cliente->id_tipodocumento == "4"){
                                $cliente_nombre = $cliente->cliente_razonsocial;
                            }else{
                                $cliente_nombre = $cliente->cliente_nombre;
                            }
                            if($return == 1){
                                require _VIEW_PATH_ . 'ventas/ticket_electronico.php';
                            }
                            $return = 1;
                        }
                    }else {
                        $return = 2;
                    }
                }
            } else {
                //Código 6: Integridad de datos erronea
                $return = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $return, "message" => $message, "idventa"=>$idventa)));
    }

    public function search_by_barcode(){
        try{
            if(isset($_POST['product_barcode'])){
                $product = $this->ventas->search_by_barcode($_POST['product_barcode']);
                $result = $product;
                if(empty($result)){
                    $result = 2;
                } else {
                    $result = $result->producto_nombre . '|' . $result->talla_nombre . '|' . $result->talla_stock . '|' . $result->id_producto_precio . '|' . $result->producto_precio_valor . '|' . $result->medida_codigo_unidad . '|' . $result->producto_precio_codigoafectacion. '|' . $result->id_talla;
                }
            } else {
                $result = 2;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo $result;
    }
    public function consultar_serie_nota(){
        try{
            $concepto = $_POST['concepto'];
            $series = "";
            $correlativo = "";
            if($concepto == "LISTAR_SERIE"){
                $tipo_documento_modificar = $_POST['tipo_documento_modificar'];
                if($tipo_documento_modificar == "01" && $_POST['tipo_venta'] == "07"){
                    $id_serie = 5;
                }elseif($tipo_documento_modificar == "03" && $_POST['tipo_venta'] == "07"){
                    $id_serie = 6;
                }elseif($tipo_documento_modificar == "01" && $_POST['tipo_venta'] == "08"){
                    $id_serie = 7;
                }elseif($tipo_documento_modificar == "03" && $_POST['tipo_venta'] == "08"){
                    $id_serie = 8;
                }
                $series = $this->ventas->listarSerie_NC_x_id($_POST['tipo_venta'], $id_serie);
                /*if($_POST['tipo_venta'] == "07"){
                    $series = $this->pedido->listarSerie_NC_factura($_POST['tipo_venta']);

                    if($tipo_documento_modificar == "01"){
                        $id =
                        $series = $this->pedido->listarSerie_NC_factura($_POST['tipo_venta']);
                    }else{
                        $series = $this->pedido->listarSerie($_POST['tipo_venta']);
                    }
                }else{

                }*/

                //$series = $this->pedido->listarSerie($_POST['tipo_venta']);
            }else{
                $correlativo_ = $this->ventas->listar_correlativos_x_serie($_POST['id_serie']);
                $correlativo = $correlativo_->correlativo + 1;
            }
            //$series = $this->pedido->listarSerie($_POST['tipo_venta']);
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        $respuesta = array("serie" => $series, "correlativo" =>$correlativo);
        echo json_encode($respuesta);
    }
    public function consultar_serie(){
        try{
            $concepto = $_POST['concepto'];
            $series = "";
            $correlativo = "";
            if($concepto == "LISTAR_SERIE"){
                $series = $this->ventas->listarSerie($_POST['tipo_venta']);
            }else{
                $correlativo_ = $this->ventas->listar_correlativos_x_serie($_POST['id_serie']);
                $correlativo = $correlativo_->correlativo + 1;
            }
            //$series = $this->pedido->listarSerie($_POST['tipo_venta']);
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        $respuesta = array("serie" => $series, "correlativo" =>$correlativo);
        echo json_encode($respuesta);
    }
    public function tipo_nota_descripcion(){
        try{
            //$id_producto = $_POST['id_producto'];
            //$result = $this->pedido->listar_precio_producto($id_producto);
            $tipo_comprobante = $_POST['tipo_comprobante'];
            if($tipo_comprobante != ""){
                if($tipo_comprobante == "07"){
                    $dato_nota = $this->ventas->listar_descripcion_segun_nota_credito();
                    $nota = "Tipo Nota de Crédito";
                }else{
                    $dato_nota = $this->ventas->listar_descripcion_segun_nota_debito();
                    $nota = "Tipo Nota de Débito";
                }

                $nota_descripcion = "<label>".$nota."</label>";
                $nota_descripcion .= "<select class='form-control' id='notatipo_descripcion'>";
                foreach ($dato_nota as $dn){
                    $nota_descripcion.= "<option value='".$dn->codigo."'>".$dn->tipo_nota_descripcion."</option>";
                }
                $nota_descripcion .= "</select>";
            }

        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($nota_descripcion);
    }
    public function ticket_electronico(){
        try{

            $id = $_POST['id'];
            //INICIO - LISTAR COLUMNAS PARA TICKET DE VENTA
            include('libs/ApiFacturacion/phpqrcode/qrlib.php');

            $venta = $this->ventas->listar_venta($id);
            if($venta->venta_nota_dato == NULL){
                $datoto = '---';
            }else{
                $datoto = $venta->venta_nota_dato;
            }
            $detalle_venta =$this->ventas->listar_detalle_ventas($id);
            $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
            $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
            //INICIO - CREACION QR
            $nombre_qr = $empresa->empresa_ruc. '-' .$venta->venta_tipo. '-' .$venta->venta_serie. '-' .$venta->venta_correlativo;
            $contenido_qr = $empresa->empresa_ruc.'|'.$venta->venta_tipo.'|'.$venta->venta_serie.'|'.$venta->venta_correlativo. '|'.
                $venta->venta_totaligv.'|'.$venta->venta_total.'|'.date('Y-m-d', strtotime($venta->venta_fecha)).'|'.
                $cliente->tipodocumento_codigo.'|'.$cliente->cliente_numero;
            $ruta = 'libs/ApiFacturacion/imagenqr/';
            $ruta_qr = $ruta.$nombre_qr.'.png';
            //QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
            //FIN - CREACION QR
            if($venta->venta_tipo == "03"){
                $venta_tipo = "BOLETA DE VENTA ELECTRÓNICA";
            }elseif($venta->venta_tipo == "01"){
                $venta_tipo = "FACTURA DE VENTA ELECTRÓNICA";
            }elseif($venta->venta_tipo == "07"){
                $venta_tipo = "NOTA DE CRÉDITO ELECTRÓNICA";
                $motivo = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
            }else{
                $venta_tipo = "NOTA DE DÉBITO ELECTRÓNICA";
                $motivo = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);

            }
            if($cliente->id_tipodocumento == "4"){
                $cliente_nombre = $cliente->cliente_razonsocial;
            }else{
                $cliente_nombre = $cliente->cliente_nombre;
            }
            $result = 1;
            require _VIEW_PATH_ . 'ventas/ticket_electronico.php';

        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result)));

    }
    public function excel_ventas_enviadas(){
        try{
            $usuario_nombre = $this->encriptar->desencriptar($_SESSION['p_n'],_FULL_KEY_);
            $usuario_apellido = $this->encriptar->desencriptar($_SESSION['p_p'],_FULL_KEY_);
            $usuario_materno = $this->encriptar->desencriptar($_SESSION['p_m'],_FULL_KEY_);
            $usuario = $usuario_nombre. ' ' .$usuario_apellido. ' ' .$usuario_materno;

            $tipo_venta = $_GET['tipo_venta'];
            $fecha_ini = $_GET['fecha_inicio'];
            $fecha_fin = $_GET['fecha_final'];

            if($fecha_ini != "" && $fecha_fin != ""){
                $fecha_vacio = "DESDE EL ".date('m-d-Y', strtotime($fecha_ini))." HASTA EL ".date('m-d-Y', strtotime($fecha_fin));
            }else{
                $fecha_vacio = utf8_decode("FECHA SIN LÍMITE");
            }

            $query = "SELECT * FROM ventas v inner join clientes c on v.id_cliente = c.id_cliente inner join monedas mo
                        on v.id_moneda = mo.id_moneda INNER JOIN usuarios u on v.id_usuario = u.id_usuario inner join
                        personas p on u.id_persona = p.id_persona
                        inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago 
                        where v.venta_estado_sunat = 1 ";
            $select = "";
            $where = true;
            if ($tipo_venta != "") {
                $where = true;
                $select = $select . " and v.venta_tipo = '" . $tipo_venta . "'";
                $tipo_venta_a = $_GET['tipo_venta'];
            }

            if ($fecha_ini != "" and $fecha_fin != "") {
                $where = true;
                $select = $select . " and DATE(v.venta_fecha) between '" . $_GET['fecha_inicio'] . "' and '" . $_GET['fecha_final'] . "'";
                $fecha_ini = $_GET['fecha_inicio'];
                $fecha_fin = $_GET['fecha_final'];
            }

            if ($where) {
                $datos = true;
                $order = " order by v.venta_fecha asc";
                $query = $query . $select . $order;
                $ventas = $this->ventas->listar_ventas($query);
            }

            $fecha_ini = $_GET['fecha_inicio'];
            $fecha_fin = $_GET['fecha_final'];
            $filtro = true;

            if($tipo_venta_a == "03"){
                $tipo_comprobante = "BOLETA";
            }elseif ($tipo_venta_a == "01"){
                $tipo_comprobante = "FACTURA";
            }elseif($tipo_venta_a == "07"){
                $tipo_comprobante = "NOTA DE CRÉDITO";
            }elseif($tipo_venta_a == "08"){
                $tipo_comprobante = "NOTA DE DÉBITO";
            }else{
                $tipo_comprobante = "TODOS";
            }

            $fecha_hoy = date("d-m-y");
            $nombre_excel = 'historial_de_ventas_enviadas' . '_' . $fecha_hoy;

            //creamos el archivo excel
            header( "Content-Type: application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition: attachment; filename=".$nombre_excel.".xls");
            require _VIEW_PATH_ . 'ventas/excel_ventas_enviadas.php';
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
    }
    public function crear_xml_enviar_sunat(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_venta = $_POST['id_venta'];
                $venta = $this->ventas->listar_soloventa_x_id($id_venta);
                $detalle_venta = $this->ventas->listar_detalle_ventas($id_venta);
                $empresa = $this->ventas->listar_empresa_x_id_empresa($venta->id_empresa);
                $cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
                //$producto = $this->ventas->listar_producto_x_id($detalle_venta->id_producto);
                //ASIGAMOS NOMBRE AL ARCHIVO XML
                $nombre = $empresa->empresa_ruc.'-'.$venta->venta_tipo.'-'.$venta->venta_serie.'-'.$venta->venta_correlativo;
                $ruta = "libs/ApiFacturacion/xml/";
                if($venta->venta_forma_pago == "CREDITO"){
                    $cuotas = $this->ventas->listar_cuotas_x_venta($id_venta);
                }else{
                    $cuotas = '';
                }
                //validamos el tipo de comprobante para crear su archivo XML
                if($venta->venta_tipo == '01' || $venta->venta_tipo == '03'){
                    $this->generadorXML->CrearXMLFactura($ruta.$nombre, $empresa, $cliente, $venta, $detalle_venta, $cuotas);
                }else{
                    $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_venta($id_venta);
                    if ($venta->venta_tipo == '07'){

                        $descripcion_nota = $this->ventas->listar_tipo_notaC_x_codigo($venta->venta_codigo_motivo_nota);
                        $this->generadorXML->CrearXMLNotaCredito($ruta.$nombre, $empresa, $cliente, $venta, $detalle_venta,$descripcion_nota);
                    }else{
                        $descripcion_nota = $this->ventas->listar_tipo_notaD_x_codigo($venta->venta_codigo_motivo_nota);
                        $this->generadorXML->CrearXMLNotaDebito($ruta.$nombre, $empresa, $cliente, $venta, $detalle_venta,$descripcion_nota);
                    }
                }
                //SE PROCEDE A FIRMAR EL XML CREADO
                $result = $this->apiFacturacion->EnviarComprobanteElectronico($empresa,$nombre,"libs/ApiFacturacion/","libs/ApiFacturacion/xml/","libs/ApiFacturacion/cdr/", $id_venta);
                //FIN FACTURACION ELECTRONICA
                if($result == 1){
                    $result = $this->ventas->guardar_estado_de_envio_venta($id_venta, '1', '1');
                }

            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function crear_enviar_resumen_sunat(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $fecha = $_POST['fecha'];
                $ventas = $this->ventas->listar_venta_x_fecha($fecha, '01');
                //CONTROLAMOS VARIOS ENVIOS EL MISMO DIAS
                $serie = date('Ymd');
                $fila_serie = $this->ventas->listar_serie_resumen('RC');

                //$correlativo = 1;
                if($fila_serie->serie != $serie){
                    //$result = $this->ventas->actualizar_serie_resumen('RC', $serie);
                    $correlativo = 1;
                }else{
                    $correlativo = $fila_serie->correlativo + 1;
                }

                if($result == 1){
                    //$result = $this->ventas->actualizar_correlativo_resumen('RC', $correlativo);
                    if($result == 1){
                        $cabecera = array(
                            "tipocomp"		=>"RC",
                            "serie"			=>$serie,
                            "correlativo"	=>$correlativo,
                            "fecha_emision" =>date('Y-m-d'),
                            "fecha_envio"	=>date('Y-m-d')
                        );
                        //$cabecera = $this->ventas->listar_serie_resumen('RC');
                        $items = $ventas;
                        $ruta = "libs/ApiFacturacion/xml/";
                        $emisor = $this->ventas->listar_empresa_x_id_empresa('1');
                        $nombrexml = $emisor->empresa_ruc.'-'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

                        //CREAMOS EL XML DEL RESUMEN
                        $this->generadorXML->CrearXMLResumenDocumentos($emisor, $cabecera, $items, $ruta.$nombrexml, $fecha);

                        $result = $this->apiFacturacion->EnviarResumenComprobantes($emisor,$nombrexml,"libs/ApiFacturacion/","libs/ApiFacturacion/xml/");
                        $ticket = $result['ticket'];
                        $message = $result['mensaje'];
                        if($result['result'] == 1){
                            $ruta_xml = $ruta.$nombrexml.'.XML';
                            $guardar_resumen =$this->ventas->guardar_resumen_diario($fecha,$cabecera['serie'],$cabecera['correlativo'],$ruta_xml,'1',$result['mensaje'],$result['ticket']);
                            if($guardar_resumen == 1){
                                if($fila_serie->serie != $serie){
                                    $this->ventas->actualizar_serie_resumen('RC', $serie);
                                    //$correlativo = 1;
                                }
                                //$this->ventas->actualizar_serie_resumen('RC', $serie);
                                $this->ventas->actualizar_correlativo_resumen('RC', $correlativo);
                                $id_resumen = $this->ventas->listar_envio_resumen_x_ticket($result['ticket']);
                                foreach ($items as $i) {
                                    $guardar_resumen_detalle = $this->ventas->guardar_resumen_diario_detalle($id_resumen->id_envio_resumen,$i->id_venta);
                                    if($i->anulado_sunat == "1" && $i->venta_condicion_resumen == "1"){
                                        $result = $this->ventas->guardar_estado_de_envio_venta($i->id_venta, '2', '0');
                                        $this->ventas->editar_venta_condicion_resumen_anulado_x_venta($i->id_venta, '3');
                                    }else{
                                        $result = $this->ventas->guardar_estado_de_envio_venta($i->id_venta, '2', '1');
                                    }
                                }
                                if($result == 1){
                                    $result = $this->apiFacturacion->ConsultarTicket($emisor, $cabecera, $ticket,"libs/ApiFacturacion/cdr/", 1);

                                }

                            }
                        }elseif($result['result'] == 4){
                            $result = 4;
                            $message = $result['mensaje'];
                        }elseif($result['result'] == 3){
                            $result = 3;
                        }
                    }
                }


            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function consultar_ticket_resumen(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_resumen = $_POST['id_resumen_diario'];
                $resumen_diario = $this->ventas->listar_resumen_diario_x_id($id_resumen);
                $serie = $resumen_diario->envio_resumen_serie;
                $correlativo = $resumen_diario->envio_resumen_correlativo;
                $ticket = $resumen_diario->envio_resumen_ticket;

                if(!empty($resumen_diario)){
                    //$result = $this->ventas->actualizar_correlativo_resumen('RC', $correlativo);
                    if($result == 1){
                        $cabecera = array(
                            "tipocomp"		=>"RC",
                            "serie"			=>$serie,
                            "correlativo"	=>$correlativo,
                            "fecha_emision" =>date('Y-m-d'),
                            "fecha_envio"	=>date('Y-m-d')
                        );
                        //$cabecera = $this->ventas->listar_serie_resumen('RC');

                        $ruta = "libs/ApiFacturacion/xml/";
                        $emisor = $this->ventas->listar_empresa_x_id_empresa('1');
                        $nombrexml = $emisor->empresa_ruc.'-'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

                        $result = $this->apiFacturacion->ConsultarTicket($emisor, $cabecera, $ticket,"libs/ApiFacturacion/cdr/", 1);

                    }
                }


            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function comunicacion_baja(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_venta = $_POST['id_venta'];

                //$fecha = $_POST['fecha'];
                //$ventas = $this->ventas->listar_venta_x_fecha($fecha, '03');
                //CONTROLAMOS VARIOS ENVIOS EL MISMO DIAS
                $serie = date('Ymd');
                $fila_serie = $this->ventas->listar_serie_resumen('RA');
                $venta = $this->ventas->listar_venta_x_id($id_venta);

                //$correlativo = 1;
                if($fila_serie->serie != $serie){
                    //$result = $this->ventas->actualizar_serie_resumen('RA', $serie);
                    $correlativo = 1;
                }else{
                    $correlativo = $fila_serie->correlativo + 1;
                }

                if($result == 1){
                    //$result = $this->ventas->actualizar_correlativo_resumen('RA', $correlativo);
                    if($result == 1){
                        $cabecera = array(
                            "tipocomp"		=>"RA",
                            "serie"			=>$serie,
                            "correlativo"	=>$correlativo,
                            "fecha_emision" =>date('Y-m-d'),
                            "fecha_envio"	=>date('Y-m-d')
                        );
                        //$cabecera = $this->ventas->listar_serie_resumen('RA');
                        $items = $venta;
                        $ruta = "libs/ApiFacturacion/xml/";
                        $emisor = $this->ventas->listar_empresa_x_id_empresa('1');
                        $nombrexml = $emisor->empresa_ruc.'-'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

                        //CREAMOS EL XML DEL RESUMEN
                        $this->generadorXML->CrearXmlBajaDocumentos($emisor, $cabecera, $items, $ruta.$nombrexml);

                        $result = $this->apiFacturacion->EnviarResumenComprobantes($emisor,$nombrexml,"libs/ApiFacturacion/","libs/ApiFacturacion/xml/");
                        $ticket = $result['ticket'];
                        if($result['result'] == 1){
                            $id_user = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                            $ruta_xml = $ruta.$nombrexml.'.XML';
                            $guardar_anulacion =$this->ventas->guardar_venta_anulacion(date('Y-m-d', strtotime($venta->venta_fecha)),$cabecera['serie'],$cabecera['correlativo'],$ruta_xml,$result['mensaje'],$id_venta,$id_user,$result['ticket']);
                            if($guardar_anulacion == 1){
                                if($fila_serie->serie != $serie){
                                    $result = $this->ventas->actualizar_serie_resumen('RA', $serie);
                                }
                                $this->ventas->actualizar_correlativo_resumen('RA', $correlativo);
                                $result = $this->ventas->editar_estado_venta_anulado($id_venta);
                                if($result == 1){
                                    $result = $this->apiFacturacion->ConsultarTicket($emisor, $cabecera, $ticket,"libs/ApiFacturacion/cdr/",2);
                                }

                            }
                        }elseif($result['result'] == 4){
                            $result = 4;
                            $message = $result['mensaje'];
                        }elseif($result['result'] == 3){
                            $result = 3;
                        }
                    }
                }


            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function anular_boleta_cambiarestado(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_venta = $_POST['id_venta'];
                $estado = $_POST['estado'];
                if(isset($_POST['comprobante'])){
                    $id_guia = $_POST['id_guia'];
                    $result = $this->ventas->actualizar_guia_anulado($id_guia);
                }else{
                    $result = $this->ventas->actualizar_venta_anulado($id_venta,$estado);
                }

            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function anular_nota_venta(){
        //Código de error general
        $result = 1;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_venta = $_POST['id_venta'];
                $result = $this->ventas->actualizar_nota_venta_anulado($id_venta);
                if($result == 1){
                    //Acá se anulan los items al anular la nota de venta
                    $detalles = $this->ventas->buscar_detalle_venta($id_venta);
                    foreach ($detalles as $detalle) {
                        $this->ventas->anular_movimiento_detalle($detalle->id_venta_detalle);
                        $stock = $this->ventas->contar_stock_talla($detalle->id_talla);
                        $this->ventas->actualizar_stock_talla($stock, $detalle->id_talla);
                    }
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCIONES PARA LAS TALLAS
    public function jalar_datos_talla(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        try{
            $id_talla = $_POST['id_talla'];
            $num = $_POST['num'];
            $result = $this->ventas->jalar_datos_talla($id_talla);

            $datos_stock = "<input class='form-control' value='".$result->talla_stock."'  id='talla_stock".$num."' name='talla_stock'>";
            $datos_ta = $result->producto_precio_codigoafectacion;
            $datos_pp = $result->id_producto_precio;
            $datos_cod_barra = "<input class='form-control' value='".$result->talla_codigo_barra."'  id='talla_codigo_barra".$num."' name='talla_codigo_barra'>";
            $datos_precio = "<input class='form-control' value='".$result->producto_precio_valor."' onchange='onchangeundprice_nuevo(".$num.")'  id='producto_precio_valor".$num."' name='producto_precio_valor'>";
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("datos_stock" => $datos_stock, "cod_barra" => $datos_cod_barra,"precio"=>$datos_precio,"datos_ta"=>$datos_ta,"datos_pp"=>$datos_pp));
    }

    public function eliminar_pre_venta(){
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            if($ok_data){
                $id_venta = $_POST['id_venta'];
                $result = $this->ventas->eliminar_detalle_pre_venta($id_venta);
                if($result==1){
                    $result = $this->ventas->eliminar_venta_total($id_venta);
                }
            }else {
                $result = 6;
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //FUNCION PARA EL PDF EN IMPRESORA
    public function imprimir_ticket_pdf_A4(){
        try{
            include('libs/ApiFacturacion/phpqrcode/qrlib.php');
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_));
            $idventa = $_GET["id"];
            $ruta_guardado="";
            $dato_venta = $this->ventas->listar_venta_x_id_pdf($idventa);
            $motivos = $this->ventas->motivos($idventa);
            if($dato_venta->venta_nota_dato == NULL){
                $dato_pdf = '--';
            }else{
                $dato_pdf = $dato_venta->venta_nota_dato;
            }
            if($dato_venta->id_mesa != "-02"){
                $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_pdf($idventa);
            }else{
                $detalle_venta = $this->ventas->listar_venta_detalle_x_id_venta_venta($idventa);
            }
            $cuotas = "";
            if($dato_venta->venta_forma_pago == "CREDITO"){
                $cuotas = $this->ventas->listar_cuotas_x_venta($idventa);
            }
            $fecha_hoy = $dato_venta->venta_fecha;
            $ruta_qr = "libs/ApiFacturacion/imagenqr/$dato_venta->empresa_ruc-$dato_venta->venta_tipo-$dato_venta->venta_serie-$dato_venta->venta_correlativo.png";
            $dnni="DNI";
            $cliente = $this->ventas->listar_clienteventa_x_id($dato_venta->id_cliente);
            if (!file_exists($ruta_qr)) {
                //INICIO - CREACION QR
                $nombre_qr = $dato_venta->empresa_ruc . '-' . $dato_venta->venta_tipo . '-' . $dato_venta->venta_serie . '-' . $dato_venta->venta_correlativo;
                $contenido_qr = $dato_venta->empresa_ruc . '|' . $dato_venta->venta_tipo . '|' . $dato_venta->venta_serie . '|' . $dato_venta->venta_correlativo . '|' .
                    $dato_venta->venta_totaligv . '|' . $dato_venta->venta_total . '|' . date('Y-m-d', strtotime($dato_venta->venta_fecha)) . '|' .
                    $cliente->tipodocumento_codigo . '|' . $cliente->cliente_numero;
                $ruta = 'libs/ApiFacturacion/imagenqr/';
                $ruta_qr = $ruta . $nombre_qr . '.png';
                QRcode::png($contenido_qr, $ruta_qr, 'H - mejor', '3');
                //FIN - CREACION QR
            }

            if ($dato_venta->venta_tipo == "03") {
                $tipo_comprobante = "BOLETA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                if($dato_venta->cliente_numero == "11111111"){
                    $documento = "SIN DOCUMENTO";
                }else{
                    $documento = "$dato_venta->cliente_numero";
                }
            }else if ($dato_venta->venta_tipo == "01") {
                $dnni="RUC";
                $tipo_comprobante = "FACTURA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            } else if ($dato_venta->venta_tipo == "07") {
                $tipo_comprobante = "NOTA DE CRÉDITO\n"."DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
                $motivo = $motivos->tipo_nota_descripcion;
                $documento_relacionado = "Comprobante ".$motivos->serie_modificar."-".$motivos->correlativo_modificar;

            } else if ($dato_venta->venta_tipo == "20") {
                $tipo_comprobante = "NOTA DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            } else if ($dato_venta->venta_tipo == "10") {
                $tipo_comprobante = "NOTA DE VENTA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            } else {
                $tipo_comprobante = "NOTA DE DÉBITO DE VENTA ELECTRONICA";
                $serie_correlativo = $dato_venta->venta_serie."-".$dato_venta->venta_correlativo;
                $documento = "$dato_venta->cliente_numero";
            }
            $importe_letra = $this->numLetra->num2letras(intval($dato_venta->venta_total));
            $arrayImporte = explode(".", $dato_venta->venta_total);
            $montoLetras = $importe_letra . ' con ' . $arrayImporte[1] . '/100 ' . $dato_venta->moneda;
            //$qrcode = $dato_venta->pago_seriecorrelativo . '-' . $tiempo_fecha[0] . '.png';
            $dato_impresion = 'DATOS DE IMPRESIÓN:';
            require _VIEW_PATH_ . 'ventas/imprimir_ticket_pdf_A4.php';
        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
    }

    //NUEVAS FUNCIONES PARA GUIA DE REMISION
    public function guardar_guia(){
        try{
            $model = new Ventas();

            if($_POST['caja'] != 39){
                $id_venta= $_POST['venta'];
                $caja= 1;
                if(isset($_POST['venta'])){
                    $dato_venta = $this->ventas->listar_soloventa_x_id($_POST['venta']);
                    $tipo_comprobante = $dato_venta->venta_tipo;
                    $venta_serie = $dato_venta->venta_serie;
                    $venta_correlactivo = $dato_venta->venta_correlativo;
                }else{
                    $id_venta = null;
                    $tipo_comprobante = '';
                    $venta_serie = '';
                    $venta_correlactivo = '';
                }
                if(isset($_POST['id_cliente'])){
                    $datos_cliente= $this->ventas->cliente_id($_POST['id_cliente']);
                    $id_cliente = $datos_cliente->id_cliente;

                    if($datos_cliente->id_tipodocumento == 4){
                        $nombre_destinatario = $datos_cliente->cliente_razonsocial;
                        $tipo_destinatario = 6;
                    }else{
                        $nombre_destinatario = $datos_cliente->cliente_nombre;
                        $tipo_destinatario = 1;
                    }
                    $numero_destinatario = $datos_cliente->cliente_numero;
                }else{
                    if($this->clientes->validar_dni($_POST['cliente_number'])){
                        //Código 5: DNI duplicado
                        $return = 1;
                        $message = "Ya existe un cliente con este DNI registrado";
                    } else{
                        $model->cliente_razonsocial = "";
                        $model->cliente_nombre = "";
                        if($_POST['select_tipodocumento'] == 4){
                            $model->cliente_razonsocial = $_POST['cliente_name'];
                        }else{
                            $model->cliente_nombre = $_POST['cliente_name'];
                        }
                        $model->id_tipodocumento = $_POST['select_tipodocumento'];
                        $model->cliente_numero = $_POST['cliente_number'];
                        $model->cliente_correo = "";
                        $model->cliente_direccion = $_POST['cliente_direccion'];
                        $model->cliente_telefono = $_POST['cliente_telefono'];

                        $return = $this->clientes->guardar($model);
                    }
                    if($return == 1) {
                        $consulta_cliente = $this->clientes->listar_cliente_x_numerodoc($_POST['cliente_number']);
                        $id_cliente = $consulta_cliente->id_cliente;

                        if($consulta_cliente->id_tipodocumento == 4){
                            $nombre_destinatario = $consulta_cliente->cliente_razonsocial;
                            $tipo_destinatario = 6;
                        }else{
                            $nombre_destinatario = $consulta_cliente->cliente_nombre;
                            $tipo_destinatario = 1;
                        }
                        $numero_destinatario = $consulta_cliente->cliente_numero;
                    }

                }
            } else {
                $id_venta = null;
                $id_cliente = null;
                $caja = null;
            }

            //DATOS DE COMPROBANTE RELACIONADO
            $model->id_venta= $id_venta;
            $model->tipo_comprobante= $tipo_comprobante;
            $model->venta_serie= $venta_serie;
            $model->venta_correlactivo= $venta_correlactivo;
            //DATOS DE CLIENTE
            $model->id_cliente = $id_cliente;

            $model->caja= $caja;
            $remision_tipo_comprobante= '09';
            $serie = $this->ventas->serie_guia($remision_tipo_comprobante);
            $model->remision_tipo_comprobante= $remision_tipo_comprobante;
            $model->serie= $serie->serie;
            $model->correlativo= $serie->correlativo+1;
            $model->usuario=$this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
            $model->fecha_emision = $_POST['fecha_emision'];
            $model->estado=0;
            $model->motivo_tras=$_POST['motivo_tras'];
            $model->tipo_trans= $_POST['tipo_trans'];
            $model->fecha_tansp=$_POST['fecha_tansp'];
            $model->peso_bruto=$_POST['peso_bruto'];
            $model->peso_unidad_medida=$_POST['peso_unidad_medida'];
            $model->numero_bultos=$_POST['numero_bultos'];
            $model->tipo_documento_trans=$_POST['tipo_documento_trans'];
            $model->numero_doc_trans=$_POST['numero_doc_trans'];
            $model->denominacion_trans =$_POST['denominacion_trans'];
            $model->num_placa_trans=$_POST['num_placa_trans'];
            //SOLO SE LLENA SI EL TIPO DE TRASLADO ES PUBLICO
            if($_POST['tipo_trans'] == '02'){
                $model->tipo_documento_con=$_POST['tipo_documento_con'];
                $model->numero_doc_con=$_POST['numero_doc_con'];
                $model->nombre_con=$_POST['nombre_con'];
                $model->licencia_con=$_POST['licencia_con'];
            }else{
                $model->tipo_documento_con=null;
                $model->numero_doc_con=null;
                $model->nombre_con=null;
                $model->licencia_con=null;
            }
            $model->ubigeo_partida=$_POST['ubigeo_partida'];
            $model->direccion_partida=$_POST['direccion_partida'];
            $model->cod_establec_partida=$_POST['cod_establec_partida'];
            $model->ubigeo_llegada=$_POST['ubigeo_llegada'];
            $model->direccion_llegada=$_POST['direccion_llegada'];
            $model->cod_establec_llegada=$_POST['cod_establec_llegada'];
            $model->observacion=$_POST['observacion'];
            $mt_codigo = microtime(true);
            $model->mt = $mt_codigo;
            if($_POST['caja'] != 39){
                $model->nombre_destinatario = $nombre_destinatario;
                $model->numero_destinatario = $numero_destinatario;
                $model->tipo_destinatario = $tipo_destinatario;
            } else {
                $model->nombre_destinatario = $_POST['destinatario_ruc'];
                $model->numero_destinatario = $_POST['destinatario_nombre'];
                $model->tipo_destinatario = $_POST['id_doc_destinatario'];
            }

            if(empty($_POST['destinatario_ruc'])){
                $model->guia_proveedor_nombre = null;
                $model->guia_proveedor_ruc = null;
            } else {
                $model->guia_proveedor_nombre = $_POST['proveedor_nombre'];
                $model->guia_proveedor_ruc = $_POST['proveedor_ruc'];
            }

            $model->guia_remision_tipo = $remision_tipo_comprobante;
            //COMPROBANTE RELACIONADO

            $guardar = $this->ventas->guardar_guia($model);
            //$guardar = 1;
            if ($guardar==1){
                $guia = $this->ventas->listar_guia_remision_x_mt($mt_codigo);
                if($_POST['caja'] != 39){
                    $details = $this->ventas->listar_detalle_ventas($id_venta);
                    $result = 1;
                    foreach ($details as $d){
                        if ($result==1){
                            $id_guia_remision = $guia->id_guia;
                            $modelDSI = new Ventas();
                            $modelDSI->id_guia = $id_guia_remision;
                            $modelDSI->guia_remision_detalle_cod = $d->id_producto_precio;
                            $modelDSI->guia_remision_detalle_descripcion = $d->venta_detalle_nombre_producto;
                            $modelDSI->guia_remision_detalle_um = 'UNIDAD';
                            $modelDSI->guia_remision_detalle_cantidad = $d->venta_detalle_cantidad;
                            $modelDSI->guia_remision_detalle_precio = $d->venta_detalle_precio_unitario;
                            $result = $this->ventas->guardar_guia_remision_detalle($modelDSI);
                        }else{
                            $result = 7;
                            $this->ventas->eliminar_guia_remision_detalle_x_id_guia($id_guia_remision);
                            $this->ventas->eliminar_guia_remision_x_id_guia($id_guia_remision);
                        }

                    }
                } else {
                    $result = 1;

                    $id_guia_remision = $guia->id_guia;
                    $modelDSI = new Ventas();
                    $modelDSI->id_guia = $id_guia_remision;
                    $modelDSI->guia_remision_detalle_cod = null;
                    $modelDSI->guia_remision_detalle_descripcion = "GASOLINA 84";
                    $modelDSI->guia_remision_detalle_um = 'GALON';
                    $modelDSI->guia_remision_detalle_cantidad = $_POST['cantidad_gas'];
                    $modelDSI->guia_remision_detalle_precio = '15.2';
                    $result = $this->ventas->guardar_guia_remision_detalle($modelDSI);
                }


                //INICIO - GUARDAR DETALLE GUIA
                /*$contenido = json_decode($_POST['contenido']);
                $id_guia_remision = $guia->id_guia;*/
                /*$result = 1;*/
                /*for ($i = 0; $i<count($contenido); $i++){
                    if($result == 1){
                        $modelDSI = new Ventas();
                        $modelDSI->id_guia = $id_guia_remision;
                        $modelDSI->guia_remision_detalle_cod = $contenido[$i]->id_producto_precio;
                        $modelDSI->guia_remision_detalle_descripcion = $contenido[$i]->venta_detalle_nombre_producto;
                        $modelDSI->guia_remision_detalle_um = $contenido[$i]->producto_medida;
                        $modelDSI->guia_remision_detalle_cantidad = $contenido[$i]->venta_detalle_cantidad;
                        $result = $this->ventas->guardar_guia_remision_detalle($modelDSI);
                    }else{
                        //si hay error al guardar detalle debe borrar todo lo registrado anteriormente
                        $result = 7;
                        $this->ventas->eliminar_guia_remision_detalle_x_id_guia($id_guia_remision);
                        $this->ventas->eliminar_guia_remision_x_id_guia($id_guia_remision);
                    }
                }*/
                //FIN - GUARDAR DETALLE GUIA
                if($result == 1){
                    $id_serie= $serie->id_serie;
                    $result=$this->ventas->actualizar_correlativo_guia($id_serie);
                    $result=$this->ventas->actualizar_correlativo_guia_v($id_venta);
                }
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo json_encode(array("result" => array("code" => $result)));
    }

    //PARA GUIAS DE REMISION XML
    public function crear_xml_guia_enviar_sunat(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_guia = $_POST['id_guia'];
                $this->log->insertar($id_guia, get_class($this).'|'.__FUNCTION__);
                $guia = $this->ventas->listar_guia_remision_x_id($id_guia);
                $partida = $this->ventas->list_ubigeo_x_cod($guia->guia_ubigeo_par);
                $llegada = $this->ventas->list_ubigeo_x_cod($guia->guia_ubigeo_llega);
                $venta = $this->ventas->listar_soloventa_x_id($guia->id_venta);
                $detalle_guia = $this->ventas->listar_detalle_guia_para_xml($id_guia);
                $empresa = $this->ventas->listar_empresa_x_id_empresa(1);
                //$cliente = $this->ventas->listar_clienteventa_x_id($venta->id_cliente);
                //$producto = $this->ventas->listar_producto_x_id($detalle_venta->id_producto);
                //ASIGAMOS NOMBRE AL ARCHIVO XML
                $nombre = $empresa->empresa_ruc.'-'.$guia->guia_remision_tipo.'-'.$guia->guia_serie.'-'.$guia->guia_correlativo;
                $ruta = "libs/ApiFacturacion/xml/";
                //validamos el tipo de comprobante para crear su archivo XML
                if($guia->guia_remision_tipo == '09' || $guia->guia_remision_tipo == '31'){
                    $this->generadorXML->CrearXmlGuiaRemision($ruta.$nombre, $empresa, $guia, $detalle_guia, $venta);
                }
                //SE PROCEDE A FIRMAR EL XML CREADO
                $result = $this->apiFacturacion->EnviarGuiaRemision_nuevo($empresa,$nombre,"libs/ApiFacturacion/","libs/ApiFacturacion/xml/","libs/ApiFacturacion/cdr/", $guia);
                //FIN FACTURACION ELECTRONICAº
                if($result == 1){
                    $result = $this->ventas->guardar_estado_de_envio_guia($id_guia, '1');
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 16;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function consultar_ticket_guia(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('id_comanda_detalle', 'POST',true,$ok_data,11,'texto',0);

            //Validacion de datos
            if($ok_data) {
                $id_guia = $_POST['id'];
                $this->log->insertar($id_guia, get_class($this).'|'.__FUNCTION__);
                $guia = $this->ventas->listar_guia_remision_x_id($id_guia);

                $empresa = $this->ventas->listar_empresa_x_id_empresa(1);
                //ASIGAMOS NOMBRE AL ARCHIVO XML
                $nombre = $empresa->empresa_ruc.'-'.$guia->guia_remision_tipo.'-'.$guia->guia_serie.'-'.$guia->guia_correlativo;
                $ruta = "libs/ApiFacturacion/xml/";

                //SE PROCEDE A CONSULTAR
                $result = $this->apiFacturacion->Consultar_ticket_GuiaRemision($empresa,$nombre,"libs/ApiFacturacion/","libs/ApiFacturacion/xml/","libs/ApiFacturacion/cdr/", $guia);
                //FIN FACTURACION ELECTRONICAº
                if($result == 1){
                    $result = $this->ventas->guardar_estado_de_envio_guia($id_guia, '1');
                }
            }else {
                //Código 6: Integridad de datos erronea
                $result = 16;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

}