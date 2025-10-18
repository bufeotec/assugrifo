<?php
require 'app/models/Active.php';
require 'app/models/Proveedor.php';
class ProveedorController
{
    private $encriptar;
    private $menu;
    private $log;
    private $active;
    private $nav;
    private $validar;
    private $proveedor;

    public function __construct()
    {
        $this->encriptar = new Encriptar();
        //$this->menu = new Menu();
        $this->log = new Log();
        $this->active = new Active();
        $this->validar = new Validar();
        $this->proveedor = new Proveedor();
    }

    public function agregar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'proveedores/agregar.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function listar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $proveedores = $this->proveedor->listar();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'proveedores/listar.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function editar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id = $_GET['id'];
            $proveedor = $this->proveedor->listar_proveedor($id);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'proveedores/editar.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }


    //FUNCIONES

    public function guardar_proveedor(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('proveedor_nombre', 'POST',true,$ok_data,200,'texto',0);
            $ok_data = $this->validar->validar_parametro('proveedor_documento_identidad', 'POST',true,$ok_data,20,'texto',0);
            //$ok_data = $this->validar->validar_parametro('proveedor_telefono', 'POST',true,$ok_data,20,'numero',0);
            //$ok_data = $this->validar->validar_parametro('proveedor_direccion', 'POST',true,$ok_data,200,'texto',0);
            //$ok_data = $this->validar->validar_parametro('proveedor_correo', 'POST',true,$ok_data,100,'email',0);

            //Validacion de datos
            if($ok_data){
                if(isset($_POST['id_proveedor'])){
                    $model = new Proveedor();
                    $model->proveedor_nombre = $_POST['proveedor_nombre'];
                    $model->proveedor_documento_identidad = $_POST['proveedor_documento_identidad'];
                    $model->proveedor_telefono = $_POST['proveedor_telefono'];
                    $model->proveedor_direccion = $_POST['proveedor_direccion'];
                    $model->proveedor_correo = $_POST['proveedor_correo'];
                    $model->id_proveedor = $_POST['id_proveedor'];

                    $result = $this->proveedor->guardar($model);
                }else{
                    $model = new Proveedor();
                    $date = date('Y-m-d H:i:s');
                    $model->proveedor_nombre = $_POST['proveedor_nombre'];
                    $model->proveedor_documento_identidad = $_POST['proveedor_documento_identidad'];
                    $model->proveedor_telefono = $_POST['proveedor_telefono'];
                    $model->proveedor_direccion = $_POST['proveedor_direccion'];
                    $model->proveedor_correo = $_POST['proveedor_correo'];
                    $model->proveedor_fecha_registro = $date;
                    $model->proveedor_estado = 1;

                    $result = $this->proveedor->guardar($model);
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

    public function eliminar_proveedor(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_proveedor', 'POST',true,$ok_data,11,'numero',0);

            //Validacion de datos
            if($ok_data) {
                $id_proveedor = $_POST['id_proveedor'];
                $result = $this->proveedor->eliminar_proveedor($id_proveedor);
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

}
