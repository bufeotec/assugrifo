<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 10/10/2020
 * Time: 0:46
 */
class Turno
{
    private $pdo;
    private $log;
    private $encriptar;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
        $this->encriptar = new Encriptar();
    }


    //REGISTRAR TUNROS
    public function agregar_turno($model){
        try{
            if(isset($model->id_turno)){
                $sql = 'update turnos set
                        turno_nombre = ?,
                        turno_apertura = ?,
                        turno_cierre = ?
                        where id_turno = ?';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->turno_nombre,
                    $model->turno_apertura,
                    $model->turno_cierre,
                    $model->id_turno
                ]);
            } else {
                $sql = 'insert into turnos (turno_nombre, turno_apertura, turno_cierre, turno_estado) values (?,?,?,?)';
                $stm = $this->pdo->prepare($sql);
                $stm->execute([
                    $model->turno_nombre,
                    $model->turno_apertura,
                    $model->turno_cierre,
                    $model->turno_estado,
                ]);
            }
            return 1;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    //LISTAR TURNOS
    public function listar_turnos(){
        try {
            $sql = 'SELECT * FROM turnos where turno_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    //LISTAR TURNOS
    public function listar(){
        try {
            $sql = 'select id_turno from turnos where turno_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    //LISTAR TURNOS
    public function listarturnos(){
        try {
            $sql = 'SELECT * FROM turnos where turno_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //Eliminar documento
    public function eliminar_turno($turno){
        try{
            $sql = 'delete from turnos where id_turno = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$turno]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
}