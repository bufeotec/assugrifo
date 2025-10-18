<?php

require 'app/models/Egresos.php';
class EgresosController
{
    private $encriptar;
    private $menu;
    private $log;
    private $nav;
    private $validar;
    private $egreso;

    public function __construct()
    {
        $this->encriptar = new Encriptar();
        //$this->menu = new Menu();
        $this->log = new Log();
        $this->validar = new Validar();
        $this->egreso = new Egresos();
    }

    public function egresos(){
    try{
        $this->nav = new Navbar();
        $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
        require _VIEW_PATH_ . 'header.php';
        require _VIEW_PATH_ . 'navbar.php';
        require _VIEW_PATH_ . 'egresos/egresos.php';
        require _VIEW_PATH_ . 'footer.php';

    }catch (Throwable $e){
        //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
        $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
        echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
        echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
    }
}

    public function agregar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'egresos/agregar.php';
            require _VIEW_PATH_ . 'footer.php';

        }catch (Throwable $e){
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
            $egresos = $this->egreso->listar_egresos();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'egresos/listar.php';
            require _VIEW_PATH_ . 'footer.php';

        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function listar_comprobantes(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $comprobantes = $this->egreso->listar_comprobantes();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'egresos/listar_comprobantes.php';
            require _VIEW_PATH_ . 'footer.php';

        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function agregar_facturas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $tipo_pago = $this->egreso->listar_tipo_pago();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'egresos/agregar_facturas.php';
            require _VIEW_PATH_ . 'footer.php';

        }catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    //FUNCIONES
    public function agregar_egreso(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion

            $ok_data = $this->validar->validar_parametro('egreso_descripcion', 'POST',true,$ok_data,100,'texto',0);
            $ok_data = $this->validar->validar_parametro('egreso_monto', 'POST',true,$ok_data,100,'numero',0);
            $ok_data = $this->validar->validar_parametro('movimiento_tipo', 'POST',true,$ok_data,100,'numero',0);

            //Validacion de datos
            if($ok_data) {
                //Creamos el modelo y ingresamos los datos a guardar
                $model = new Egresos();
                $date = date('Y:m:d H:i:s');
                $model->id_caja_numero = 1;
                $model->egreso_descripcion = $_POST['egreso_descripcion'];
                $model->egreso_monto = $_POST['egreso_monto'];
                $model->movimiento_tipo = $_POST['movimiento_tipo'];
                $model->egreso_estado = 1;
                $model->id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);;
                $model->egreso_fecha_registro = $date;

                $result = $this->egreso->guardar_egreso($model);
            }

        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function guardar_comprobantes(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion

            //$ok_data = $this->validar->validar_parametro('egreso_descripcion', 'POST',true,$ok_data,100,'texto',0);
            //$ok_data = $this->validar->validar_parametro('egreso_monto', 'POST',true,$ok_data,100,'numero',0);

            //Validacion de datos
            if($ok_data) {
                //Creamos el modelo y ingresamos los datos a guardar
                $model = new Egresos();
                $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                $fecha = date('Y:m:d H:i:s');
                $serie = $_POST['comprobante_serie'];
                $correlativo  = $_POST['comprobante_correlativo'];
                $model->id_usuario = $id_usuario;
                $model->comprobante_tipo = $_POST['comprobante_tipo'];
                $model->comprobante_serie = $serie;
                $model->comprobante_correlativo = $correlativo;
                $model->comprobante_fecha_emision = $_POST['comprobante_fecha_emision'];
                $model->comprobante_fecha_registro = $fecha;

                if($_FILES['comprobante_archivo']['name'] !=null) {
                    $path = $_FILES['comprobante_archivo']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $file_path_cv = "media/comprobantes/"."ccmprobante_".$serie."-".$correlativo.".".$ext;
                    move_uploaded_file($_FILES['comprobante_archivo']['tmp_name'],$file_path_cv);
                    $model->comprobante_archivo = $file_path_cv;
                }

                $model->comprobante_concepto = $_POST['comprobante_concepto'];
                $model->comprobante_ruc_proveedor = $_POST['comprobante_ruc_proveedor'];
                $model->comprobante_tipo_pago = $_POST['comprobante_tipo_pago'];
                $model->comprobante_estado = 1;

                $result = $this->egreso->guardar_comprobantes($model);
            }

        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function eliminar_egreso(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_egreso', 'POST',true,$ok_data,11,'numero',0);
            //Validacion de datos
            if($ok_data) {
                $id_egreso = $_POST['id_egreso'];
                $result = $this->egreso->eliminar_egreso($id_egreso);
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function eliminar_comprobante(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try {
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion
            $ok_data = $this->validar->validar_parametro('id_comprobante', 'POST',true,$ok_data,11,'numero',0);
            //Validacion de datos
            if($ok_data) {
                $id_comprobante = $_POST['id_comprobante'];
                $result = $this->egreso->eliminar_comprobante($id_comprobante);
            } else {
                //Código 6: Integridad de datos erronea
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

}