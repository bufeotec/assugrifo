<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 22/11/2018
 * Time: 18:07
 */

class Report{
    private $log;
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    //Funcion Listar Todos los Productos
    public function listP(){
        try{
            $sql = 'select * from product';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Turn|listP');
            $result = 0;
        }
        return $result;
    }

    //Listar Inventario Inicial
    public function initial_inventory($turn, $id_product){
        try{
            $sql = 'select startproduct_stock from startproduct where id_turn = ? and id_product = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn,
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->startproduct_stock;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|initial_inventory');
            $result = 0;
        }
        return $result;
    }

    public function stockadded($turn, $id_product){
        try{
            $sql = 'select sum(stocklog_added) entrada from stocklog where id_turn = ? and id_product = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn,
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->entrada;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|stockadded');
            $result = 0;
        }
        return $result;
    }
    public function stockadded_dates($fecha_i,$fecha_f, $id_product){
        try{
            $sql = 'select * from stocklog where stocklog_date BETWEEN ? and ? and id_product = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_i,$fecha_f,
                $id_product
            ]);
            $r = $stm->fetchAll();
            $result = $r;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|stockadded');
            $result = 0;
        }
        return $result;
    }

    public function stockout($turn, $id_product){
        try{
            $sql = 'select sum(stockout_out) salida from stockout where id_turn = ? and id_product = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn,
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->salida;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|stockout');
            $result = 0;
        }
        return $result;
    }
    public function stockout_dates($fecha_i,$fecha_f, $id_product){
        try{
            $sql = 'select * from stockout where stockout_date BETWEEN ? and ? and id_product = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_i,$fecha_f,
                $id_product
            ]);
            $r = $stm->fetchAll();
            $result = $r;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|stockout');
            $result = 0;
        }
        return $result;
    }

    public function products_selled($turn, $id_product){
        try{
            $sql = "select sum(s.sale_productstotalselled) total from saleproduct sp inner join saledetail s on sp.id_saleproduct = s.id_saleproduct inner join productforsale p on s.id_productforsale = p.id_productforsale
                    where sp.id_turn = ? and p.id_product = ? and sp.saleproduct_total <> 0 and sp.saleproduct_cancelled <> 'false'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn,
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|products_selled');
            $result = 0;
        }
        return $result;
    }
    public function products_selled_dates($fecha_i,$fecha_f, $id_product){
        try{
            $sql = "select * from saleproduct sp inner join saledetail s on sp.id_saleproduct = s.id_saleproduct inner join productforsale p on s.id_productforsale = p.id_productforsale
                    where sp.saleproduct_date BETWEEN ? and ? and p.id_product = ? and sp.saleproduct_total <> 0 and sp.saleproduct_cancelled <> 'false'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_i,$fecha_f,
                $id_product
            ]);
            $r = $stm->fetchAll();
            $result = $r;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|products_selled');
            $result = 0;
        }
        return $result;
    }

    public function products_revoke($turn, $id_product){
        try{
            $sql = "select sum(s.sale_productstotalselled) total from saleproduct sp inner join saledetail s on sp.id_saleproduct = s.id_saleproduct inner join productforsale p on s.id_productforsale = p.id_productforsale
                    where sp.id_turn = ? and p.id_product = ? and sp.saleproduct_cancelled = 0";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn,
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|products_selled');
            $result = 0;
        }
        return $result;
    }

    public function products_free($turn, $id_product){
        try{
            $sql = "select sum(s.sale_productstotalprice) total from saleproduct sp inner join saledetail s on sp.id_saleproduct = s.id_saleproduct inner join productforsale p on s.id_productforsale = p.id_productforsale
                    where sp.id_turn = ? and p.id_product = ? and sp.saleproduct_total = 0 ";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn,
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|products_free');
            $result = 0;
        }
        return $result;
    }

    public function products_debt($turn, $id_product){
        try{
            $sql = "select sum(s.sale_productstotalprice) total from saleproduct sp inner join saledetail s on sp.id_saleproduct = s.id_saleproduct inner join productforsale p on s.id_productforsale = p.id_productforsale
                    where sp.id_turn = ? and p.id_product = ? and sp.saleproduct_cancelled = 'false'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn,
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|products_debt');
            $result = 0;
        }
        return $result;
    }

    public function total_per_product($turn, $id_product){
        try{
            $sql = "select sum(s.sale_productstotalprice) total from saleproduct sp inner join saledetail s on sp.id_saleproduct = s.id_saleproduct inner join productforsale p on s.id_productforsale = p.id_productforsale
                    where sp.id_turn = ? and p.id_product = ? and sp.saleproduct_total <> 0 and sp.saleproduct_cancelled <> 0";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn,
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|total_per_product');
            $result = 0;
        }
        return $result;
    }

    public function total_products_now($id_product){
        try{
            $sql = 'select product_stock from product where id_product = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_product
            ]);
            $r = $stm->fetch();
            $result = $r->product_stock;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|total_products_now');
            $result = 0;
        }
        return $result;
    }

    //Calcular ganancias Productos
    public function total_products($turn){
        try{
            $sql = "select sum(sp.saleproduct_total) total from saleproduct sp inner join saledetail s on sp.id_saleproduct = s.id_saleproduct inner join productforsale p on s.id_productforsale = p.id_productforsale
                    where sp.id_turn = ?  and sp.saleproduct_total <> 0 and sp.saleproduct_cancelled <> 'false'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|total_per_product');
            $result = 0;
        }
        return $result;
    }

    //Calcular ganancias Alquiler
    public function total_rent($turn){
        try{
            $sql = "select sum(salerent_total) total from salerent where id_turn = ? and salerent_total <> 0 and salerent_cancelled = 'true'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|total_rent');
            $result = 0;
        }
        return $result;
    }

    //Calcular ganancias Deuda
    public function total_debt($turn){
        try{
            $sql = "select sum(debtpay_mont) total from debtpay where id_turn = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|total_rent');
            $result = 0;
        }
        return $result;
    }

    //Calcular ganancias Deuda Alquiler
    public function total_debtrent($turn){
        try{
            $sql = 'select sum(debtrentpay_mont) total from debtrentpay where id_turn = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|total_rent');
            $result = 0;
        }
        return $result;
    }

    //Egresos
    public function all_expense($turn){
        try{
            $sql = 'select * from expense where id_turn = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn
            ]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|total_rent');
            $result = 0;
        }
        return $result;
    }
    public function all_saleproduct($turn){
        try{
            $sql = 'select * from saleproduct sp inner join client c on sp.id_client=c.id_client where id_turn = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn
            ]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|all_saleproduct');
            $result = 0;
        }
        return $result;
    }

    public function all_expense_number($turn){
        try{
            $sql = 'select sum(expense_mont) total from expense where id_turn = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $turn->id_turn
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|total_rent');
            $result = 0;
        }
        return $result;
    }

    //Funcion kardex por producto por fechas ventas
    public function kardex_product_dates_sales($fecha_i,$fecha_f,$producto){
        try{
            $sql = 'SELECT * FROM product p inner JOIN productforsale pfs on p.id_product=pfs.id_product inner join saledetail sd on sd.id_productforsale = pfs.id_productforsale inner join saleproduct sp on sp.id_saleproduct = sd.id_saleproduct where p.id_product = ? AND sp.saleproduct_date BETWEEN ? AND ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$producto,$fecha_i,$fecha_f]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|kardex_product_dates_sales');
            $result = 0;
        }
        return $result;
    }
    //Funcion kardex por producto por fechas stock
    public function kardex_product_dates_stockout($fecha_i,$fecha_f,$producto){
        try{
            $sql = 'SELECT * FROM product p inner JOIN stockout sl on sl.id_product = p.id_product where sl.id_product = ? AND sl.stockout_date BETWEEN ? AND ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$producto,$fecha_i,$fecha_f]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), 'Report|kardex_product_dates_stockout');
            $result = 0;
        }
        return $result;
    }

}