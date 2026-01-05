<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 24/10/2018
 * Time: 19:22
 */
require 'app/models/Inventario.php';
require 'app/models/Active.php';
require 'app/models/Correlativo.php';
require 'app/models/Categoria.php';
require 'app/models/UnidadMedida.php';
require 'app/models/Proveedor.php';
require 'app/models/Ventas.php';
require 'app/models/Turno.php';
class InventarioController{
    private $encriptar;
    private $menu;
    private $log;
    private $inventario;
    private $active;
    private $correlativo;
    private $categoria;
    private $nav;
    private $unidad_medida;
    private $validar;
    private $proveedor;
    private $ventas;
    private $turno;
    public function __construct()
    {
        $this->encriptar = new Encriptar();
        //$this->menu = new Menu();
        $this->log = new Log();
        $this->inventario = new Inventario();
        $this->active =  new Active();
        $this->correlativo =  new Correlativo();
        $this->categoria =  new Categoria();
        $this->unidad_medida =  new UnidadMedida();
        $this->validar = new Validar();
        $this->proveedor = new Proveedor();
        $this->ventas = new Ventas();
        $this->turno = new Turno();
    }
    //Vistas

    public function productos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $productos = $this->inventario->listar_productos_();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/productos.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    //Producto
    public function listarproductos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $familia = $this->categoria->listar_familias();

            if(isset($_POST['enviar_dato'])){
                $id_familia = $_POST['id_familia'];
                $productos = $this->inventario->listar_productos($id_familia);
                $familia_ = $this->categoria->listar_familias_($id_familia);
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/listarproductos2.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function listar_tipo_productos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'];

            $datos = $this->inventario->sacar_datos($id);
            $tipos = $this->inventario->listar_tipo_producto($id);
            $proveedor = $this->proveedor->listar();
            $unimedida = $this->unidad_medida->listAllactivo();
            $codigoafectacion = $this->unidad_medida->listar_codigo_afectacion();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/listar_tipo_productos.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function agregar_producto(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $categoria = $this->categoria->listar_categorias();
            $familia = $this->categoria->listar_familias();
            $unimedida = $this->unidad_medida->listAllactivo();
            $proveedor = $this->proveedor->listar();
            $codigoafectacion = $this->unidad_medida->listar_codigo_afectacion();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/agregar.php';
            require _VIEW_PATH_ . 'footer.php';

        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function editar_producto(){
        try{
            $id = $_GET['id'];
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $categoria = $this->categoria->listar_categorias();
            $producto = $this->inventario->listProductwithprice($id);
            $unimedida = $this->unidad_medida->listAllactivo();
            $proveedor = $this->proveedor->listar();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/editar.php';
            require _VIEW_PATH_ . 'footer.php';
        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function productForsale(){
        try{
            $idp = $_GET['id'];
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $products = $this->inventario->listProductsforsale($idp);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/listproductsale.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function addProductforsale(){
        try{
            $idp = $_GET['id'];
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $product = $this->inventario->listProductname($idp);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/addproductsale.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function kardex_item()
    {
        try{
            $idp = $_GET['id'];
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $fecha_fin = date('Y-m-d');
            $fecha_inicio = date('Y-m-d', strtotime($fecha_fin . ' - 7 days'));
            $fecha_stock_final = date('Y-m-d', strtotime($fecha_fin . ' + 1 days'));
            if(!empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])){
                $fecha_inicio = $_GET['fecha_inicio'];
                $fecha_fin = $_GET['fecha_fin'];
            }
            if(empty($_GET['id'])){
                echo "<script language=\"javascript\">alert(\"ID no declarado\");</script>";
                echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
            }
            $recurso_inicial = $this->inventario->stock_talla_fecha($_GET['id'], $fecha_inicio);
            $recursos_almacen = $this->inventario->stock_movimientos_talla($_GET['id'], $fecha_inicio, $fecha_fin);
            $stock_final = $this->inventario->stock_talla_fecha($_GET['id'], $fecha_stock_final);

            $producto = $this->inventario->listar_info_producto_talla($_GET['id']);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/kardex_item.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function editProductforsale(){
        try{
            $idp = $_GET['id'];
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $productprice = $this->inventario->listProductprice($idp);
            $product = $this->inventario->listProductname($productprice->id_product);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/editproductsale.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function agregar_stock(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'];
            $correlativo = $this->correlativo->listar();
            $fechahoy = date("Y-m-d");
            $producto = $this->inventario->listar_producto_($id);
            $proveedor = $this->proveedor->listar();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/agregar_stock.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function salida_stock(){
        try{
            $id = $_GET['id'];
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $producto = $this->inventario->listar_producto_($id);
            $correlativo = $this->correlativo->listar();
            $fechahoy = date('Y-m-d');
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'inventario/salida_stock.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    //Funciones
    //Guardar Edicion o Nuevos Productos

    public function guardar_producto_nuevo(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if($ok_data){
                $model = new Inventario();
                $model->id_categoria = $_POST['id_categoria'];
                $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $model->producto_nombre = $_POST['producto_nombre'];
                $model->producto_descripcion =$_POST['producto_descripcion'];
                $model->producto_stock = 0;
                $model->producto_creacion = date("Y-m-d H:i:s");
                $model->producto_estado = 1;
                $model->producto_codigo = microtime(true);

                $result = $this->inventario->guardar_only_producto($model);

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

    public function guardar_producto_precio(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_categoria', 'POST',true,$ok_data,11,'numero',0);
            //$ok_data = $this->validar->validar_parametro('producto_codigo_barra', 'POST',false,$ok_data,100,'texto',0);
            $ok_data = $this->validar->validar_parametro('producto_nombre', 'POST',true,$ok_data,100,'texto',0);
            //$ok_data = $this->validar->validar_parametro('producto_precio_valor', 'POST',true,$ok_data,11,'texto',0);
            //$ok_data = $this->validar->validar_parametro('id_proveedor', 'POST',true,$ok_data,11,'numero',0);

            //Validacion de datos
            if($ok_data){
                //Creamos el modelo y ingresamos los datos a guardar
                $model = new Inventario();
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                if(isset($_POST['id_producto'])){
                    $model->id_producto = $_POST['id_producto'];
                    $model->id_categoria = $_POST['id_categoria'];
                    $model->id_usuario = $id_usuario;
                    //$model->producto_codigo_barra = $_POST['producto_codigo_barra'];
                    $model->producto_nombre = $_POST['producto_nombre'];

                    if(empty($_POST['producto_stock'])){
                        $model->producto_stock = 0;
                    }else{
                        $model->producto_stock = $_POST['producto_stock'];
                    }
                    $result = $this->inventario->save($model);

                    /*if($result == 1){
                        $id_producto_precio = $this->inventario->getIdProductSaleForIdProduct($_POST['id_producto']);
                        $model2 = new Inventario();
                        $model2->id_producto_precio = $id_producto_precio;
                        $model2->id_proveedor = $_POST['id_proveedor'];
                        $model2->producto_precio_valor = $_POST['producto_precio_valor'];

                        $result = $this->inventario->guardar_precio($model2);
                    }*/
                } else {
                    $microtime = microtime(true);
                    $model->id_categoria = $_POST['id_categoria'];
                    $model->id_usuario = $id_usuario;
                    $model->producto_nombre = $_POST['producto_nombre'];
                    $model->producto_codigo_barra = $_POST['producto_codigo_barra'];
                    if(empty($_POST['producto_stock'])){
                        $model->producto_stock = 0;
                    }else{
                        $model->producto_stock = $_POST['producto_stock'];
                    }
                    $dato_creacion = date("Y-m-d H:i:s");
                    $model->producto_creacion = $dato_creacion;
                    $model->producto_estado = 1;
                    $model->producto_codigo = $microtime;
                    $result = $this->inventario->save($model);

                    if($result == 1){
                        $id_nuevo_producto = $this->inventario->jalar_producto($microtime);
                        $model2 = new Inventario();
                        $model2->id_producto = $id_nuevo_producto;
                        $model2->id_proveedor = $_POST['id_proveedor'];
                        $model2->id_medida = $_POST['id_medida'];
                        $model2->producto_precio_codigoafectacion = $_POST['id_tipoafectacion'];
                        $model2->producto_precio_unidad = 1;
                        $model2->producto_precio_valor = $_POST['producto_precio_valor'];
                        $model2->producto_precio_valor_xmayor = $_POST['producto_precio_valor_xmayor'];
                        if(empty($_POST['producto_precio_compra'])){
                            $model2->producto_precio_compra = 0;
                        }else{
                            $model2->producto_precio_compra = $_POST['producto_precio_compra'];
                        }

                        $result = $this->inventario->guardar_precio($model2);
                    }

                }
                if($result == 1){
                    //$id_producto_ = $this->inventario->getProductID($_POST['producto_nombre']);
                    $id_producto = $_POST['id_producto'];
                    $id_talla = "";
                    $fecha = date('Y-m-d H:i:s');
                    $inserStock = $this->inventario->setStockNew($id_producto,$id_talla, $fecha, $_POST['producto_stock']);
                }

            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //Agregar Nuevo Stock Productos
    public function editar_stock(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_producto', 'POST',true,$ok_data,11,'numero',0);

            //Validacion de datos
            if($ok_data){
                $id_talla = $_POST['id_talla'];
                $stock_nuevo = floatval($_POST['stock_nuevo']);
                $codigo_serie = 15;
                //Acá tengo que hacer la función para simular un registro de venta y con eso guardar el registro y el producto

                //Codigo de Inicio de Registro de Formulario de Stock
                $id_turno = $this->turno->listar();
                $model = new Ventas();
                $model->id_cliente = 1;
                $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                $model->id_turno = $id_turno->id_turno;
                $model->id_caja = 1;
                $model->venta_tipo =  'OI';
                $model->id_tipo_pago =  3;
                $model->forma_pago =  'CONTADO';
                //obtener serie con el id
                $serie_ = $this->ventas->listar_correlativos_x_serie($codigo_serie);
                $model->venta_serie = $serie_->serie;
                $model->venta_correlativo = $serie_->correlativo + 1;
                $model->venta_tipo_moneda = 1;
                /*$producto_venta_correlativo = 1;
                $model->producto_venta_correlativo = $producto_venta_correlativo;*/
                $model->producto_venta_totalgratuita = 0;
                $model->producto_venta_totalexonerada = 0;
                $model->producto_venta_totalinafecta = 0;
                $model->producto_venta_totalgravada = 0;
                $model->producto_venta_totaligv = 0;
                $model->producto_venta_icbper = 0;
                $model->producto_venta_total = 0;
                $model->producto_venta_vuelto = 0;
                $model->producto_venta_pago = 0;
                $model->producto_venta_des_global = 0;
                $model->producto_venta_des_total = 0;
                $producto_venta_fecha = date("Y-m-d H:i:s");
                $model->producto_venta_fecha = $producto_venta_fecha;
                $model->tipo_documento_modificar = null;
                $model->serie_modificar = null;
                $model->numero_modificar = null;
                $model->notatipo_descripcion = null;
                $model->venta_tipo_envio = 4;
                $model->venta_estado = 1;
                $model->id_usuario_cobro = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                $model->venta_nota_dato = $_POST['stocklog_description'];
                $guardar = $this->ventas->guardar_venta($model);
                if($guardar == 1){
                    $id_cliente = 1;
                    $jalar_id = $this->ventas->jalar_id_venta($producto_venta_fecha,$id_cliente);
                    $idventa = $jalar_id->id_venta;
                    //Buscar detalle del producto
                    $producto_detalle = $this->inventario->consultar_producto_precio_talla($_POST['id_talla']);
                    $this->ventas->poner_enviado($idventa);
                    $model->id_venta = $idventa;
                    $model->id_producto_precio = $producto_detalle->id_producto_precio;
                    $model->venta_detalle_valor_unitario = 0;
                    $model->venta_detalle_precio_unitario = 0;
                    $model->venta_detalle_nombre_producto = $producto_detalle->producto_nombre . ' ' . $producto_detalle->talla_nombre;
                    $model->venta_detalle_cantidad = $stock_nuevo;
                    $model->venta_detalle_total_igv = 0;
                    $model->venta_detalle_porcentaje_igv = 0;
                    $model->venta_detalle_valor_total = 0;
                    $model->venta_detalle_total_price = 0;
                    $model->venta_detalle_descuento = 0;
                    $model->venta_detalle_stock = 1;
                    $model->venta_detalle_movimiento_stock = $stock_nuevo;

                    $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                    if($guardar_detalle == 1) {
                        $stock = $this->ventas->contar_stock_talla($id_talla);
                        $this->ventas->actualizar_stock_talla($stock, $id_talla);
                        $result = 1;
                        $this->ventas->actualizarCorrelativo_x_id_Serie($codigo_serie,$serie_->correlativo + 1);
                    }
                }
                //Fin de Registro de Formulario de Stock
                /*$stock = $this->ventas->contar_stock_talla($id_talla);
                $this->ventas->actualizar_stock_talla($stock, $id_talla);

                $stock_nuevo_talla = $this->inventario->sumar_stock_talla($stock_nuevo,$id_talla);
                if($stock_nuevo_talla==1){
                    $result = $this->inventario->guardar_stock_general($stock_nuevo,$id_producto);
                }
                if($result == 1){
                    $model = new Inventario();
                    $date = date('Y-m-d H:i:s');
                    $model->stocklog_guide = $_POST['stocklog_guide'];
                    $model->stocklog_description = $_POST['stocklog_description'];
                    $model->stocklog_added = $_POST['stock_nuevo'];
                    $model->id_producto = $_POST['id_producto'];
                    $model->id_talla = $_POST['id_talla'];
                    $model->id_proveedor = 1;
                    $model->stocklog_precio_compra_producto = $_POST['producto_precio_valor'];
                    $model->stocklog_date = $date;
                    $model->id_turno = 1;
                    $stock_log = $this->inventario->save_stocklog($model);
                }
                if($stock_log == 1){
                    $result = $this->correlativo->updatecorrelativeIn();
                }*/
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //Registrar Salida de Stock de Producto
    public function salidastock(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_producto', 'POST',true,$ok_data,11,'numero',0);

            //Validacion de datos
            if($ok_data){
                $id_producto = $_POST['id_producto'];
                $id_talla = $_POST['id_talla'];
                $producto_stock = $_POST['stockout_out'];
                $codigo_serie = 16;

                //Codigo de Inicio de Registro de Formulario de Stock
                $id_turno = $this->turno->listar();
                $model = new Ventas();
                $model->id_cliente = 1;
                $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                $model->id_turno = $id_turno->id_turno;
                $model->id_caja = 1;
                $model->venta_tipo =  'OS';
                $model->id_tipo_pago =  3;
                $model->forma_pago =  'CONTADO';
                //obtener serie con el id
                $serie_ = $this->ventas->listar_correlativos_x_serie($codigo_serie);
                $model->venta_serie = $serie_->serie;
                $model->venta_correlativo = $serie_->correlativo + 1;
                $model->venta_tipo_moneda = 1;
                /*$producto_venta_correlativo = 1;
                $model->producto_venta_correlativo = $producto_venta_correlativo;*/
                $model->producto_venta_totalgratuita = 0;
                $model->producto_venta_totalexonerada = 0;
                $model->producto_venta_totalinafecta = 0;
                $model->producto_venta_totalgravada = 0;
                $model->producto_venta_totaligv = 0;
                $model->producto_venta_icbper = 0;
                $model->producto_venta_total = 0;
                $model->producto_venta_vuelto = 0;
                $model->producto_venta_pago = 0;
                $model->producto_venta_des_global = 0;
                $model->producto_venta_des_total = 0;
                $producto_venta_fecha = date("Y-m-d H:i:s");
                $model->producto_venta_fecha = $producto_venta_fecha;
                $model->tipo_documento_modificar = null;
                $model->serie_modificar = null;
                $model->numero_modificar = null;
                $model->notatipo_descripcion = null;
                $model->venta_tipo_envio = 4;
                $model->venta_estado = 1;
                $model->id_usuario_cobro = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
                $model->venta_nota_dato = $_POST['stockout_description'];
                $guardar = $this->ventas->guardar_venta($model);
                if($guardar == 1){
                    $id_cliente = 1;
                    $jalar_id = $this->ventas->jalar_id_venta($producto_venta_fecha,$id_cliente);
                    $idventa = $jalar_id->id_venta;
                    //Buscar detalle del producto
                    $this->ventas->poner_enviado($idventa);
                    $producto_detalle = $this->inventario->consultar_producto_precio_talla($_POST['id_talla']);

                    $model->id_venta = $idventa;
                    $model->id_producto_precio = $producto_detalle->id_producto_precio;
                    $model->venta_detalle_valor_unitario = 0;
                    $model->venta_detalle_precio_unitario = 0;
                    $model->venta_detalle_nombre_producto = $producto_detalle->producto_nombre . ' ' . $producto_detalle->talla_nombre;
                    $model->venta_detalle_cantidad = $producto_stock;
                    $model->venta_detalle_total_igv = 0;
                    $model->venta_detalle_porcentaje_igv = 0;
                    $model->venta_detalle_valor_total = 0;
                    $model->venta_detalle_total_price = 0;
                    $model->venta_detalle_descuento = 0;
                    $model->venta_detalle_stock = 1;
                    $model->venta_detalle_movimiento_stock = $producto_stock * -1;

                    $guardar_detalle = $this->ventas->guardar_detalle_venta($model);
                    if($guardar_detalle == 1) {
                        $stock = $this->ventas->contar_stock_talla($id_talla);
                        $this->ventas->actualizar_stock_talla($stock, $id_talla);
                        $result = 1;
                        $this->ventas->actualizarCorrelativo_x_id_Serie($codigo_serie,$serie_->correlativo + 1);
                    }
                }

                /*$result = $this->inventario->saveoutProductstock($producto_stock, $id_talla);
                if($result==1){
                    $result = $this->inventario->restar_stock_general($producto_stock,$id_producto);
                }
                if($result == 1){
                    $model = new Inventario();
                    $date = date('Y-m-d H:i:s');
                    $model->id_producto = $_POST['id_producto'];
                    $model->id_talla = $_POST['id_talla'];
                    $model->id_turno = 1;
                    $model->stockout_guide = $_POST['stockout_guide'];
                    $model->stockout_out = $_POST['stockout_out'];
                    $model->stockout_description = $_POST['stockout_description'];
                    $model->stockout_destiny = $_POST['stockout_destiny'];
                    $model->stockout_ruc = $_POST['stockout_ruc'];
                    $model->stockout_origin = $_POST['stockout_origin'];
                    $model->stockout_date = $date;
                    $stockout = $this->inventario->salida_stock($model);
                }
                if($stockout == 1){
                    $result = $this->correlativo->updatecorrelativeOut();
                }*/
            }else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }  catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    //Deshabilitar Productos
    public function eliminar_producto(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_producto', 'POST',true,$ok_data,11,'numero',0);
           // $ok_data = $this->validar->validar_parametro('id_productforsale', 'POST',true,$ok_data,11,'numero',0);
            //Validacion de datos
            if($ok_data) {
                $id_producto = $_POST['id_producto'];

                $result = $this->inventario->eliminar_producto($id_producto);
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }


    //FUNCION PARA QUITAR UN PRODUCTO
    public function quitar_producto(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_producto', 'POST',true,$ok_data,11,'numero',0);
            $ok_data = $this->validar->validar_parametro('id_producto_precio', 'POST',true,$ok_data,11,'numero',0);
            // $ok_data = $this->validar->validar_parametro('id_productforsale', 'POST',true,$ok_data,11,'numero',0);
            //Validacion de datos
            if($ok_data) {
                $id_producto = $_POST['id_producto'];
                $id_producto_precio = $_POST['id_producto_precio'];

                $eliminar_precio = $this->inventario->eliminar_producto_precio($id_producto_precio);
                if($eliminar_precio == 1){
                    $eliminar_start = $this->inventario->quitar_producto($id_producto);
                }
                if($eliminar_start == 1){
                    $result = $this->inventario->borrar_todo($id_producto);
                }

            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    //Guardar Edicion o Nuevos Alquileres
    public function saveRent(){
        try{
            $model = new Inventario();
            if(isset($_POST['id_rent'])){
                $model->id_rent = $_POST['id_rent'];
                $model->rent_name = $_POST['rent_name'];
                $model->rent_description = $_POST['rent_description'];
                $model->rent_timeminutes = $_POST['rent_timeminutes'];
                $model->rent_cost = $_POST['rent_cost'];
                $result = $this->inventario->saveRent($model);
            } else {
                $model->rent_name = $_POST['rent_name'];
                $model->rent_description = $_POST['rent_description'];
                $model->rent_timeminutes = $_POST['rent_timeminutes'];
                $model->rent_cost = $_POST['rent_cost'];
                $result = $this->inventario->saveRent($model);
            }

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        echo $result;
    }
    //Borrar Alquiler
    public function deleteRent(){
        try{
            $id_rent = $_POST['id'];
            $result = $this->inventario->deleteRent($id_rent);

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        echo $result;
    }
    //Guardar Edicion o Nuevos Objetos
    public function saveObject(){
        try{
            $model = new Inventario();
            if(isset($_POST['id_object'])){
                $model->id_object = $_POST['id_object'];
                $model->object_name = $_POST['object_name'];
                $model->object_description = $_POST['object_description'];
                $model->object_total = $_POST['object_total'];
                $result = $this->inventario->saveObject($model);
            } else {
                $model->object_name = $_POST['object_name'];
                $model->object_description = $_POST['object_description'];
                $model->object_total = $_POST['object_total'];
                $result = $this->inventario->saveObject($model);
            }

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        echo $result;
    }
    //Borrar Objeto
    public function deleteObject(){
        try{
            $id_object = $_POST['id'];
            $result = $this->inventario->deleteObject($id_object);

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }

        echo $result;
    }
    //FUNCION PARA LLENAR EL MODAL DE INVENTARIO POR PRODUCTO
    public function consultar_datos(){
        try{
            $id_producto = $_POST['id_producto'];

            $jalar_agregados = $this->inventario->consultar_agregados_inve($id_producto);
            $jalar_agregados_ = $this->inventario->consultar_agregados_inve_($id_producto);

            $detalle_agregados_ = "<label style='color:black;'>".$jalar_agregados_->producto_nombre."</label>";
            $detalle_agregados = " <table class='table table-bordered' width='100%'>
                                    <thead class='text-capitalize'>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Descripción</th>
                                        <th>Fecha de Agregado</th>
                                    </tr>
                                    </thead>
                                    <tbody>";
            foreach ($jalar_agregados as $r){
                $detalle_agregados .= "<tr>
                            <td>". $r->stocklog_added ."</td>
                            <td>". $r->stocklog_description ."</td>
                            <td>". $r->stocklog_date ."</td>
                        </tr>";
            }
            $detalle_agregados .= "</tbody></table>";



            $jalar_ventas = $this->inventario->consultar_ventas_inve($id_producto);
            $valor_total = $this->inventario->valor_total($id_producto);
            $detalle_ventas_ = "<label class='form-control' style='color: red'>TOTAL: S/. ".$valor_total->total."</label>";
            $detalle_ventas = " <table class='table table-bordered' width='100%'>
                                    <thead class='text-capitalize'>
                                    <tr>
                                        <th>Vendedor</th>
                                        <th>Documento</th>
                                        <th>Correlativo</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>P. Unit</th>
                                        <th>Monto</th>
                                        <th>Fecha Venta</th>
                                    </tr>
                                    </thead>
                                    <tbody>";

            foreach ($jalar_ventas as $r){
                if($r->venta_tipo == "03"){
                    $venta_ = "BOLETA";
                }
                $detalle_ventas .= "<tr>
                            <td>". $r->persona_nombre.' '.$r->persona_apellido_paterno."</td>
                            <td>". $venta_ ."</td>
                            <td>". $r->venta_correlativo ."</td>
                            <td>". $r->cliente_nombre ."</td>
                            <td>". $r->venta_detalle_cantidad ."</td>
                            <td>". $r->venta_detalle_precio_unitario ."</td>
                            <td>". $r->venta_detalle_importe_total ."</td>
                            <td>". $r->venta_fecha ."</td>
                        </tr>";
            }
            $detalle_ventas .= "</tbody></table>";



            $salidas_stock = $this->inventario->salidas_stock_inve($id_producto);
            $salidas_stock_ = $this->inventario->salidas_stock_inve_($id_producto);

            //$detalle_salida_stock_ = "<label style='color:black;'>".$salidas_stock_->producto_nombre."</label>";
            $detalle_salida_stock = " <table class='table table-bordered' width='100%'>
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
            foreach ($salidas_stock as $r){
                $detalle_salida_stock .= "<tr>
                            <td>". $r->stockout_origin ."</td>
                            <td>". $r->stockout_description ."</td>
                            <td>". $r->stockout_out ."</td>
                            <td>". $r->stockout_destiny ."</td>
                            <td>". $r->stockout_date ."</td>
                        </tr>";
            }
            $detalle_salida_stock .= "</tbody></table>";

        }catch (Throwable $e) {
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"" . _SERVER_ . "\";</script>";
        }
        //Retornamos el json
        echo json_encode(array("detalle_agregados"=>$detalle_agregados,"detalle_agregados_"=>$detalle_agregados_,"detalle_ventas"=>$detalle_ventas,
            "detalle_ventas_"=>$detalle_ventas_,"detalle_salida_stock"=>$detalle_salida_stock));
    }

    public function jalar_categorias(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app

        try{
            $id_familia = $_POST['id_familia'];
            $result = $this->categoria->jalar_categorias($id_familia);

            $datos_categoria = "<select class='form-control' id='id_categoria' name='id_categoria'>";
            $datos_categoria.="<option value=''>Seleccionar</option>";
            foreach($result as $c){
                $datos_categoria.="<option value='". $c->id_categoria."'>". $c->categoria_nombre."</option>";
            }
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode($datos_categoria);
    }

    public function guardar_tipo_productos(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if($ok_data) {
                $model = new Inventario();
                if(isset($_POST['id_talla']) && $_POST['id_talla'] != ""){
                    $model->id_talla = $_POST['id_talla'];
                }
                $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $model->id_producto = $_POST['id_producto'];
                $model->talla_nombre = $_POST['talla_nombre'];
                $model->talla_codigo_barra = $_POST['talla_codigo_barra'];
                $model->talla_stock = $_POST['talla_stock'];
                $model->talla_fecha_registro = date('Y-m-d H:i:s');
                $model->talla_estado = 1;
                $micro = microtime(true);
                $model->talla_microtime = $micro;

                $result = $this->inventario->guardar_tipo_productos($model);
                if($result==1) {
                    $model2 = new Inventario();
                    $jalar_id_talla = $this->inventario->jalar_id_talla($micro);
                    $model2->id_talla = $jalar_id_talla->id_talla;
                    $model2->id_proveedor = $_POST['id_proveedor'];
                    $model2->id_medida = $_POST['id_medida'];
                    $model2->producto_precio_codigoafectacion = $_POST['id_tipoafectacion'];
                    $model2->producto_precio_valor = $_POST['producto_precio_valor'];
                    $model2->producto_precio_valor_xmayor = $_POST['producto_precio_valor_xmayor'];
                    $model2->producto_precio_estado = 1;

                    $result = $this->inventario->guardar_precio($model2);
                }
                if($result == 1) {
                    $id_producto = $_POST['id_producto'];
                    $sumar_stock = $_POST['talla_stock'];
                    $result = $this->inventario->guardar_stock_general($sumar_stock,$id_producto);
                }
            }else{
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }Catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }


    public function guardar_tipo_productos_e(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if($ok_data) {
                $model = new Inventario();
                $model->id_talla = $_POST['id_talla'];
                $model->talla_nombre = $_POST['talla_nombre'];
                //$model->id_producto = $_POST['id_producto'];

                $result = $this->inventario->guardar_tipo_productos($model);
                if($result==1){
                    $precio_nuevo = $_POST['producto_precio_valor_ca'];
                    $result = $this->inventario->update_precio($precio_nuevo,$_POST['id_talla']);
                }

            }else{
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }Catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }


    public function eliminar_talla_producto(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if($ok_data) {
                $id_talla = $_POST['id_talla'];

                $result = $this->inventario->eliminar_talla($id_talla);

            }else{
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }Catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

}