<?php
/**
 * Created by PhpStorm
 * User: Franz
 * Date: 14/05/2019
 * Time: 22:50
 */

class Categoria{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    //Listar Toda La Info
    public function listar_categorias(){
        try{
            $sql = 'select * from categorias';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_categorias_familia(){
        try{
            $sql = 'select * from categorias c inner join familias f on c.id_familia = f.id_familia where c.categoria_estado = 1 and f.familia_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_categorias_familia_($id_familia){
        try{
            $sql = 'select * from categorias c inner join familias f on c.id_familia = f.id_familia 
                    where c.id_familia = ? and c.categoria_estado = 1 and f.familia_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_familia]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function jalar_categorias($id_familia){
        try{
            $sql = 'select * from categorias where id_familia = ? and categoria_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_familia]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_familias(){
        try{
            $sql = 'select * from familias where familia_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_familias_($id_familia){
        try{
            $sql = 'select * from familias where id_familia = ?and  familia_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_familia]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    //Listar Una Unica Persona por ID
    public function list($id){
        try{
            $sql = 'select * from categoryp where id_categoryp = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    //Guardar o Editar Informacion de Role
    public function save($model){
        try {
            if(empty($model->id_categoryp)){
                $sql = 'insert into categoryp(
                    categoryp_name, categoryp_description
                    ) values(?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->categoryp_name,
                    $model->categoryp_description
                ]);

            } else {
                $sql = "update categoryp
                set
                categoryp_name = ?,
                categoryp_description = ?
                where id_categoryp = ?";

                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->categoryp_name,
                    $model->categoryp_description,
                    $model->id_categoryp
                ]);
                unset($_SESSION['id_categoryp']);
            }
            $result = 1;
        } catch (Exception $e){
            //throw new Exception($e->getMessage());
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function guadar_categorias($model){
        try{
            if(isset($model->id_categoria)){
                $sql = 'update categorias set id_familia = ?, categoria_nombre = ? where id_categoria = ?';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->id_familia,
                    $model->categoria_nombre,
                    $model->id_categoria
                ]);
            }else {
                $sql = 'insert into categorias (id_familia, categoria_nombre, categoria_descripcion, categoria_fecha_registro, categoria_estado) 
                    values (?,?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->id_familia,
                    $model->categoria_nombre,
                    $model->categoria_descripcion,
                    $model->categoria_fecha_registro,
                    $model->categoria_estado
                ]);
            }
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_categoria($id){
        try {
            $sql = 'update categorias set categoria_estado = 0 where id_categoria = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id
            ]);
            $result = 1;
        } catch (Exception $e){
            //throw new Exception($e->getMessage());
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }


}