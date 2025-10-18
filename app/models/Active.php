<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 20/11/2018
 * Time: 19:06
 */

class Active{
    private $log;
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function getTurnactive(){
        try{
            $sql = 'select id_turn from turn where turn_active = 1 limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetch();
            $return = $result->id_turn;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }


    public function getTurnactiveall(){
        try{
            $sql = 'select * from turn where turn_active = 1 limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function getTurnactiveall_id($id){
        try{
            $sql = 'select * from turn where turn_datestart = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id
            ]);
            $return = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function getAllTurns(){
        try{
            $sql = 'select * from turn order by id_turn desc ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }
}