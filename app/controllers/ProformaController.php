<?php
require 'app/models/Proforma.php';
require 'app/models/Inventario.php';
require 'app/models/Clientes.php';
require 'app/models/Turno.php';
require 'app/models/Admin.php';
require 'app/models/Ventas.php';
class ProformaController
{
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $proformas;
    private $inventario;
    private $clientes;
    private $turno;
    private $admin;
    private $ventas;


    public function __construct()
    {
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->proformas = new Proforma();
        $this->inventario = new Inventario();
        $this->clientes = new Clientes();
        $this->turno = new Turno();
        $this->admin = new Admin();
        $this->ventas = new Ventas();

    }

    public function realizar_proforma(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $_SESSION['productos'] = array();
            //LISTAMOS LOS PRODUCTOS
            $productos = $this->inventario->listar_productos_venta();
            //LISTAMOS LOS CLIENTES
            $clientes = $this->proformas->listar_clientes();

            $tiponotacredito = $this->proformas->listAllCredito();
            $tiponotadebito = $this->proformas->listAllDebito();
            $tipo_pago = $this->ventas->listar_tipo_pago();
            $tipos_documento = $this->clientes->listar_documentos();

            $fecha = date('Y-m-d');
            $caja_apertura_fecha = $this->admin->listar_ultima_fecha($fecha);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'proforma/realizar_proforma.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function tabla_proforma(){
        try{
            require _VIEW_PATH_ . 'proforma/tabla_proforma.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<br><br><div style='text-align: center'><h3>Ocurrió Un Error Al Cargar La Informacion</h3></div>";
        }
    }

    public function ver_proforma(){
        try{
            //Llamamos a la clase del Navbar, que sólo se usa
            // en funciones para llamar vistas y la instaciamos
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            //$productssale = $this->proformas->listar_detalle_proforma($id);
            $fecha_filtro = date('Y-m-d');
            $fecha_filtro_fin = date('Y-m-d');
            $fecha_i = date('Y-m-d');
            $fecha_f = date('Y-m-d');
            if(isset($_POST['enviar_dato'])){
                $fecha_i = $_POST['fecha_filtro'];
                $fecha_f = $_POST['fecha_filtro_fin'];
                $proformas = $this->proformas->listar_proforma($fecha_i,$fecha_f);
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'proforma/ver_proforma.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script>window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function proforma_pdf(){
        try{
            if(!$this->validar->validar_parametro('id', 'GET',true,true,30,'texto',0)){
                throw new Exception('valor no declarado');
            }
            $id = $_GET['id'];

            $fecha = date('d-m-Y H:i:s');

            $listar_pdf = $this->proformas->listar_datos_pdf($id);
            $datos_proforma = $this->proformas->datos_proforma($id);
            require _VIEW_PATH_ . 'proforma/proforma_pdf.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);;
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
                    array_push($_SESSION['productos'], [$_POST['datos_pp'], $_POST['producto'], round($_POST['precio'], 2), $_POST['cantidad'], $_POST['tipo_igv'], $_POST['product_descuento'],$_POST['id_talla'],$_POST['nombre'], $_POST['tipo_mayor_menor']]);
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

    public function editar_descripcion(){
        try{
            if(isset($_POST['id'])){
                $item = $_POST['item'];
                $buscar = $_POST['id'];
                $nuevo_concepto = $_POST['valor_concepto_nuevo'];
                $editar = count($_SESSION['productos']);
                for($i=1; $i < $editar+1; $i++){
                    $union2 = $_SESSION['productos'][$i][0].'_'.$item;
                    if ($i == $item){
                        $j=$i-1;
                        $_SESSION['productos'][$j][1] = $nuevo_concepto;
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


    /*public function editar_descripcion_(){
        try{
            if(isset($_POST['id'])){
                $buscar = $_POST['id'];
                $texto_descripcion = $_POST['texto_descripcion'];
                $editar = count($_SESSION['productos']);
                for($i=0; $i < $editar; $i++){
                    if($_SESSION['productos'][$i][0] == $buscar){
                        $_SESSION['productos'][$i][1] = $texto_descripcion;
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
    }*/

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

    public function guardar_proforma(){
        //Código de error general
        $result = 0;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            //$ok_data = $this->validar->validar_parametro('saleproduct_type', 'POST',false,$ok_data,11,'texto',0);
            //Validacion de datos
            if($ok_data){
                $model = new Proforma();
                if($this->clientes->validar_dni($_POST['client_number'])){
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
                    $model->cliente_numero = $_POST['client_number'];
                    $model->cliente_correo = "";
                    $model->cliente_direccion = $_POST['client_address'];
                    $model->cliente_telefono = $_POST['cliente_telefono'];

                    $return = $this->clientes->guardar($model);
                }
                if($return == 1) {
                    $model = new Proforma();
                    $consulta_cliente = $this->clientes->listar_cliente_x_numerodoc($_POST['client_number']);
                    $model->id_cliente = $consulta_cliente->id_cliente;
                    $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                    $model->id_moneda = $_POST['id_moneda'];
                    $jalar_ultimo_correlativo = $this->proformas->ultimo_correlativo();
                    if(empty($jalar_ultimo_correlativo)){
                        $proforma_correlativo = 1;

                    }else{
                    $jalar_ultimo_correlativo_ = $jalar_ultimo_correlativo->proforma_correlativo;
                    $proforma_correlativo = $jalar_ultimo_correlativo_ + 1;
                    }
                    
                    $model->proforma_correlativo = $proforma_correlativo;
                    $model->proforma_nota = $_POST['proforma_nota'];
                    $model->proforma_fecha_vigencia = $_POST['proforma_fecha_vigencia'];
                    $model->proforma_total = $_POST['saleproduct_total'];
                    $proforma_fecha_generada = date('Y-m-d H:i:s');
                    $model->proforma_fecha_generada = $proforma_fecha_generada;
                    $model->proforma_estado = 1;

                    $guardar = $this->proformas->guardar_proforma($model);

                    if ($guardar == 1) {
                        $id_cliente = $consulta_cliente->id_cliente;
                        $jalar_id = $this->proformas->jalar_id_proforma($proforma_fecha_generada, $id_cliente);
                        $idproforma = $jalar_id->id_proforma;
                        foreach ($_SESSION['productos'] as $p) {
                            $cantidad = $p[3];
                            $precio_unitario = $p[2];
                            $subtotal = round($p[3] * $p[2], 2);

                            $id_producto_precio = $p[0];
                            $model->id_proforma = $idproforma;
                            $model->id_producto_precio = $id_producto_precio;
                            $model->id_medida = 0;
                            $model->proforma_detalle_precio = $precio_unitario;
                            $model->proforma_detalle_producto_cantidad = $cantidad;
                            $model->proforma_detalle_nombre_producto = $p[1];
                            $model->producto_proforma_total_selled = $subtotal;
                            $model->proforma_detalle_mm = $p[8];
                            $model->proforma_detalle_fecha_registro = date('Y-m-d H:i:s');
                            $model->proforma_detalle_estado = 1;

                            $result = $this->proformas->guardar_detalle_proforma($model);
                        }

                    }
                }

            } else {
                //Código 6: Integridad de datos erronea
                $result = 0;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }

        //Retornamos el json
        echo json_encode($result);
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


    public function eliminar_proforma()
        {
            //Array donde vamos a recetar los cambios, en caso hagamos alguno
            $proforma = [];
            //Código de error general
            $result = 2;
            //Mensaje a devolver en caso de hacer consulta por app
            $message = 'OK';
            try {
                $ok_data = true;
                //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
                //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
                $ok_data = $this->validar->validar_parametro('id_proforma', 'POST', true, $ok_data, 11, 'numero', 0);
                //Validacion de datos
                if ($ok_data) {
                    //Eliminamos el receta
                    $eliminar_detalle_proforma = $this->proformas->eliminar_detalle_proforma($_POST['id_proforma']);
                    if($eliminar_detalle_proforma == 1){
                        $result = $this->proformas->eliminar_proforma($_POST['id_proforma']);
                    }
                } else {
                    //Código 6: Integridad de datos erronea
                    $result = 6;
                    $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
                }
            } catch (Exception $e) {
                //Registramos el error generado y devolvemos el mensaje enviado por PHP
                $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
                $message = $e->getMessage();
            }
            //Retornamos el json
            echo json_encode(array("result" => array("code" => $result, "message" => $message, "proforma" => $proforma)));
        }

    public function jalar_venta_mm(){
        try{
            $tipo_mayor_menor = $_POST['tipo_mayor_menor'];
            $id_talla = $_POST['id_tallita'];
            $num = $_POST['num'];
            $resultado = $this->proformas->jalar_valor($id_talla);
            if($tipo_mayor_menor == 1){
                //$valor = $resultado->producto_precio_valor;
                $valor="<input type='text' class='form-control' onchange='onchangeundprice_nuevo(".$num.")' id='producto_precio_valor".$num."' name='producto_precio_valor".$num."' value='".$resultado->producto_precio_valor."'>";
            }else{
                //$valor = $resultado->producto_precio_valor_xmayor;
                $valor="<input type='text' class='form-control' onchange='onchangeundprice_nuevo(".$num.")' id='producto_precio_valor".$num."' name='producto_precio_valor".$num."' value='".$resultado->producto_precio_valor_xmayor."'>";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($valor);
    }

    public function jalar_venta_mm_(){
        try{
            $tipo_mayor_menor = $_POST['tipo_mayor_menor'];
            $id_talla = $_POST['id_tallita'];
            $resultado = $this->proformas->jalar_valor($id_talla);
            if($tipo_mayor_menor == 1){
                $valor = $resultado->producto_precio_valor;
                //$valor="<input type='text' class='form-control' onchange='onchangeundprice_nuevo(".$num.")' id='producto_precio_valor".$num."' name='producto_precio_valor".$num."' value='".$resultado->producto_precio_valor."'>";
            }else{
                $valor = $resultado->producto_precio_valor_xmayor;
                //$valor="<input type='text' class='form-control' onchange='onchangeundprice_nuevo(".$num.")' id='producto_precio_valor".$num."' name='producto_precio_valor".$num."' value='".$resultado->producto_precio_valor_xmayor."'>";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($valor);
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


}





