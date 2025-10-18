<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 19/11/2018
 * Time: 10:10
 */

class Turn{
    private $log;
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function listall(){
        try{
            $sql = 'select * from turn';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $return = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 2;
        }
        return $return;
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


    public function change($id){
        try{
            $sql = 'update turn set turn_active = 0 where id_turn = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id
            ]);
            $return = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 2;
        }
        return $return;
    }

    public function openBox($id,$cash){
        try{
            $sql = 'update turn set turn_open = 1, turn_inicialcash = ? where id_turn = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $cash,
                $id
            ]);
            $return = 1;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 2;
        }
        return $return;
    }

    public function cashopenBox($id){
        try{
            $sql = 'select turn_inicialcash from turn where id_turn = ? limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id
            ]);
            $ret = $stm->fetch();
            $return = $ret->turn_inicialcash;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
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

    //Funcion Especial para Listar Los Productos
    public function listP(){
        try{
            $sql = 'select * from product p inner join productforsale pdf on p.id_product = pdf.id_product order by p.product_name';
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
    //Verificar si se ingreso caja inicial
    public function getOpen($turn){
        try{
            $sql = 'select * from turn where turn_open = 1 and id_turn = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$turn->id_turn]);
            $ret = $stm->fetchAll();
            if(count($ret) == 1){
                $result = true;
            } else {
                $result = false;
            }

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = false;
        }
        return $result;
    }
}