<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 29/10/2018
 * Time: 9:59
 */

class Proveedor
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }


    public function guardar($model){
        try{
            if(isset($model->id_proveedor)){
                $sql = 'update proveedores set
                        proveedor_nombre = ?,
                        proveedor_documento_identidad = ?,
                        proveedor_telefono = ?,
                        proveedor_direccion = ?,
                        proveedor_correo = ?
                        where id_proveedor = ?';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->proveedor_nombre,
                    $model->proveedor_documento_identidad,
                    $model->proveedor_telefono,
                    $model->proveedor_direccion,
                    $model->proveedor_correo,
                    $model->id_proveedor
                ]);
            }else {
                $sql = 'insert into proveedores (proveedor_nombre, proveedor_documento_identidad, proveedor_telefono, proveedor_direccion, proveedor_correo, proveedor_fecha_registro, proveedor_estado)  values (?,?,?,?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->proveedor_nombre,
                    $model->proveedor_documento_identidad,
                    $model->proveedor_telefono,
                    $model->proveedor_direccion,
                    $model->proveedor_correo,
                    $model->proveedor_fecha_registro,
                    $model->proveedor_estado
                ]);
            }
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function listar(){
        try{
            $sql = 'select * from proveedores';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


    public function listar_proveedor($id){
        try{
            $sql = 'select * from proveedores where id_proveedor = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function eliminar_proveedor($id_proveedor){
        try{
            $sql = 'delete from proveedores where id_proveedor = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_proveedor]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

}