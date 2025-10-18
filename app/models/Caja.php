<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 29/10/2018
 * Time: 9:59
 */

class Caja
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function guardar_caja($model){
        try{
            if(isset($model->id_caja_numero)){
                $sql = 'update caja_numero set
                        caja_numero_nombre = ?,
                        caja_numero_fecha = ?
                        where id_caja_numero = ?';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->caja_numero_nombre,
                    $model->caja_numero_fecha,
                    $model->id_caja_numero
                ]);
            }else {
                $sql = 'insert into caja_numero (caja_numero_nombre, caja_numero_fecha, caja_numero_estado) values (?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->caja_numero_nombre,
                    $model->caja_numero_fecha,
                    $model->caja_numero_estado,
                ]);
            }
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function listar_cajas(){
        try{
            $sql = 'select * from caja_numero where caja_numero_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function eliminar_caja($id_caja_numero){
        try {
            $sql = 'update caja_numero set
                caja_numero_estado = 0
                where id_caja_numero = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_caja_numero
            ]);
            return 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
}