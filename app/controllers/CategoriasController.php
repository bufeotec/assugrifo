<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 24/10/2018
 * Time: 19:22
 */
require 'app/models/Active.php';
require 'app/models/Correlativo.php';
require 'app/models/Categoria.php';
require 'app/models/UnidadMedida.php';
class CategoriasController
{
    private $encriptar;
    private $menu;
    private $log;
    private $active;
    private $correlativo;
    private $categoria;
    private $nav;
    private $unidad_medida;
    private $validar;
    public function __construct()
    {
        $this->encriptar = new Encriptar();
        //$this->menu = new Menu();
        $this->log = new Log();
        $this->active = new Active();
        $this->correlativo = new Correlativo();
        $this->categoria = new Categoria();
        $this->unidad_medida = new UnidadMedida();
        $this->validar = new Validar();
    }
    //Vistas
    public function gestionar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $familia = $this->categoria->listar_familias();
            //$categorias = $this->categoria->listar_categorias_familia();

            if(isset($_POST['enviar_dato'])){
                $id_familia = $_POST['id_familia'];
                $categorias = $this->categoria->listar_categorias_familia_($id_familia);
                $familia_ = $this->categoria->listar_familias_($id_familia);
            }
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'categorias/gestionar.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            //En caso de errores insertamos el error generado y redireccionamos a la vista de inicio
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    //FUNCIONES
    public function guardar_categoria(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if($ok_data) {
              $model = new Categoria();
              if(isset($_POST['id_categoria']) && $_POST['id_categoria'] != ""){
                  $model->id_categoria = $_POST['id_categoria'];
              }
              $model->id_familia = $_POST['id_familia'];
              $model->categoria_nombre = $_POST['categoria_nombre'];
              $model->categoria_descripcion = "--";
              $model->categoria_fecha_registro = date('Y-m-d H:i:s');
              $model->categoria_estado = 1;

              $result = $this->categoria->guadar_categorias($model);
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

    public function eliminar_categoria(){
        //Código de error general
        $result = 2;
        //Mensaje a devolver en caso de hacer consulta por app
        $message = 'OK';
        try{
            $ok_data = true;
            if($ok_data) {
                $id_categoria = $_POST['id_categoria'];
                $result = $this->categoria->eliminar_categoria($id_categoria);
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