<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 12/10/2020
 * Time: 17:28
 */

require 'app/models/Admin.php';
require 'app/models/Active.php';
require 'app/models/Report.php';
require 'app/models/Turno.php';
require 'app/models/Caja.php';

class AdminController{
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;

    private $admin;
    private $active;
    private $report;
    private $turno;
    private $caja;
    public function __construct()
    {
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();

        $this->admin = new Admin();
        $this->active = new Active();
        $this->report = new Report();
        $this->turno = new Turno();
        $this->caja = new Caja();
    }
    //Vistas/Opciones
    //Vista de acceso al panel de inicio
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $turno = $this->turno->listarturnos();
            foreach($turno as $t){
                $desde = $t->turno_apertura;
                $hasta = $t->turno_cierre;
                $hora = date('H:i');
                $validar_fecha = $this->validar->hourIsBetween($desde,$hasta,$hora);
                if($validar_fecha){
                    $mostrar = $t->turno_nombre;
                }
            }
            $mostrar_ = $mostrar;
            $fecha_hoy = date('Y-m-d');
            $fecha_open = $this->admin->listar_ultima_fecha($fecha_hoy);
            $caja = $this->caja->listar_cajas();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'admin/index.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function finalizar_sesion(){
        $this->sesion->finalizar_sesion();
    }

    //FUNCION PARA AGREGAR APERTURA DE CAJA
    public function agregar_apertura(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $id_usuario_apertura = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
            $ok_data = true;
            //Validamos que todos los parametros a recibir sean correctos. De ocurrir un error de validación,
            //$ok_true se cambiará a false y finalizara la ejecucion de la funcion

            $ok_data = $this->validar->validar_parametro('caja_apertura', 'POST',true,$ok_data,200,'numero',0);

            if($ok_data) {
                //Creamos el modelo y ingresamos los datos a guardar
                $model = new Admin();
                $model->caja_apertura = $_POST['caja_apertura'];
                $model->id_caja_numero = $_POST['caja_numero'];
                $model->id_usuario_apertura= $id_usuario_apertura;
                $model->caja_estado= 1;

                //Guardamos el menú y recibimos el resultado
                $result = $this->admin->guardar_apertura_caja($model);
            }

        } catch (Throwable $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
}

