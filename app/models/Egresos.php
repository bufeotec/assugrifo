<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 29/10/2018
 * Time: 9:59
 */

class Egresos
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function listar_tipo_pago(){
        try{
            $sql = 'select * from tipo_pago where tipo_pago_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function guardar_egreso($model){

        try{
            $sql = 'insert into egresos (id_caja_numero,id_usuario,movimiento_tipo, egreso_descripcion, egreso_monto, egreso_estado, egreso_fecha_registro) 
                    values (?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_caja_numero,
                $model->id_usuario,
                $model->movimiento_tipo,
                $model->egreso_descripcion,
                $model->egreso_monto,
                $model->egreso_estado,
                $model->egreso_fecha_registro
            ]);
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function guardar_comprobantes($model){

        try{
            $sql = 'insert into comprobantes (id_usuario, comprobante_tipo, comprobante_serie, comprobante_correlativo, comprobante_fecha_emision, 
                    comprobante_fecha_registro, comprobante_archivo, comprobante_concepto, comprobante_ruc_proveedor, comprobante_tipo_pago, 
                    comprobante_estado) values (?,?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_usuario,
                $model->comprobante_tipo,
                $model->comprobante_serie,
                $model->comprobante_correlativo,
                $model->comprobante_fecha_emision,
                $model->comprobante_fecha_registro,
                $model->comprobante_archivo,
                $model->comprobante_concepto,
                $model->comprobante_ruc_proveedor,
                $model->comprobante_tipo_pago,
                $model->comprobante_estado
            ]);
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //LISTAR EGRESOS
    public function listar_egresos(){
        try {
            $sql = 'select * from egresos where egreso_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_comprobantes(){
        try {
            $sql = 'select * from comprobantes c inner join tipo_pago tp on c.comprobante_tipo_pago = tp.id_tipo_pago where comprobante_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //cambiar estado del egreso
    public function eliminar_egreso($id_egreso){
        try {
            $sql = "delete from egresos
                where id_egreso = ?";

            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_egreso
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function eliminar_comprobante($id_comprobante){
        try {
            $sql = "update comprobantes set comprobante_estado = 0 where id_comprobante = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_comprobante
            ]);
            $result = 1;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

}