<?php
/**
 * Created by PhpStorm
 * User: Franz
 * Date: 29/04/2019
 * Time: 20:15
 */

class Turni{
    private $log;
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function searchByDay($date){
        try{
            $sql = 'select * from turn where turn_datestart = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$date]);
            $return = $stm->fetch();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 2;
        }
        return $return;
    }

    public function offTurns(){
        try{
            $sql = 'update turn set turn_active = 0 where turn_active = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = true;

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = false;
        }
        return $return;
    }

    public function save($model){
        try{
            $sql = 'insert into turn (turn_datestart, turn_inicialcash, turn_active, turn_open) values (?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->turn_datestart,
                $model->turn_inicialcash,
                1,
                0
            ]);
            $return = 1;

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 2;
        }
        return $return;
    }

    public function searchByDayi($date){
        try{
            $sql = 'select * from turn where turn_datestart = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$date]);
            $return = $stm->fetch();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 2;
        }
        return $return;
    }

    public function listP(){
        try{
            $sql = 'select * from product p inner join productforsale pdf on p.id_product = pdf.id_product';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function setStock($productos, $id_turn){
        try {
            $sql = 'insert into startproduct(id_turn, id_product, startproduct_stock) values ';
            $firstvalue = true;
            foreach ($productos as $p){
                if($firstvalue){
                    $sql = $sql . '('.$id_turn.','.$p->id_product.','.$p->product_stock.')';
                    $firstvalue = false;
                } else {
                    $sql = $sql . ',('.$id_turn.','.$p->id_product.','.$p->product_stock.')';
                }

            }
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = 1;

        } catch (Exception $e){
            $error = $e->getMessage();
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;

    }
}