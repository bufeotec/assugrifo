<?php

class Proforma
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    //Listar Toda La Info Sobre Personas
    public function listar_clientes(){
        try{
            $sql = 'select * from clientes where cliente_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    //consulta para listar los tipos de igv
    public function listAllIgv(){
        try{
            $sql = 'select * from igv where igv_estado = 1 order by igv_codigoafectacion asc ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function ultimo_correlativo(){
        try{
            $sql = 'select * from proformas order by proforma_correlativo desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function guardar_proforma($model){
        try{
            $sql = 'insert into proformas (id_cliente, id_usuario, id_moneda, proforma_correlativo,proforma_nota, proforma_total,proforma_fecha_vigencia, proforma_fecha_generada, 
                    proforma_estado) values (?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_cliente,
                $model->id_usuario,
                $model->id_moneda,
                $model->proforma_correlativo,
                $model->proforma_nota,
                $model->proforma_total,
                $model->proforma_fecha_vigencia,
                $model->proforma_fecha_generada,
                $model->proforma_estado
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function jalar_id_proforma($proforma_fecha_generada ,$id_cliente){
        try{
            $sql = 'select id_proforma from proformas where proforma_fecha_generada = ? and id_cliente = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$proforma_fecha_generada ,$id_cliente]);
            $result = $stm->fetch();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


    public function guardar_detalle_proforma($model){
        try{
            $sql = 'insert into proforma_detalle (id_proforma, id_producto_precio, id_medida, proforma_detalle_precio, proforma_detalle_producto_cantidad, 
                    proforma_detalle_nombre_producto,producto_proforma_total_selled, proforma_detalle_mm, proforma_detalle_fecha_registro, 
                    proforma_detalle_estado) values (?,?,?,?,?,?,?,?,?,?)';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $model->id_proforma,
                $model->id_producto_precio,
                $model->id_medida,
                $model->proforma_detalle_precio,
                $model->proforma_detalle_producto_cantidad,
                $model->proforma_detalle_nombre_producto,
                $model->producto_proforma_total_selled,
                $model->proforma_detalle_mm,
                $model->proforma_detalle_fecha_registro,
                $model->proforma_detalle_estado
            ]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    public function listar_id_producto_productoprecio($id_producto_precio){
        try{
            $sql = "Select * from producto p inner join producto_precio p2 on p.id_producto = p2.id_producto where p2.id_producto_precio = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto_precio]);
            $result = $stm->fetch();
            $result = $result->id_producto;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }


    public function guardar_stock_nuevo($reducir, $id_producto){
        try{
            $sql = 'update producto set producto_stock = producto_stock - ? where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $reducir, $id_producto
            ]);

            $result = 1;
        }catch (Exception $e){
            //throw new Exception($e->getMessage());
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }


    public function listar_proforma($fecha_i,$fecha_f){
        try {
            $sql = 'select * from proformas p inner join clientes c on p.id_cliente = c.id_cliente where date(p.proforma_fecha_generada) between ? and ? 
                    and proforma_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_i,$fecha_f]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function datos_proforma($id){
        try {
            $sql = 'select * from proforma_detalle pd inner join proformas p on pd.id_proforma = p.id_proforma inner join 
                    clientes c on p.id_cliente = c.id_cliente
                    where p.id_proforma = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_datos_pdf($id){
        try {
            $sql = 'select * from proformas p inner join proforma_detalle pd on p.id_proforma = pd.id_proforma inner join producto_precio pp on pd.id_producto_precio = pp.id_producto_precio
                    inner join talla t on pp.id_talla = t.id_talla inner join producto p2 on t.id_producto = p2.id_producto
                    where p.id_proforma = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_detalle_proforma($id){
        try {
            $sql = 'select * from producto_proforma_detalle where id_producto_proforma = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listAllCredito(){
        try{
            $sql = 'select * from tipo_ncreditos';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listAllDebito(){
        try{
            $sql = 'select * from tipo_ndebitos';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function search_by_barcode_proforma($product_barcode){
        try {
            $sql = 'select * from producto_precio pp inner join talla t on pp.id_talla = t.id_talla inner join producto p on t.id_producto = p.id_producto
                    inner join medida m on pp.id_medida = m.id_medida
                    where t.talla_codigo_barra = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$product_barcode]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 2;
        }
        return $result;
    }

    public function eliminar_detalle_proforma($id_proforma){
        try{
            $sql = 'delete from proforma_detalle where id_proforma = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_proforma]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function eliminar_proforma($id_proforma){
        try{
            $sql = 'delete from proformas where id_proforma = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_proforma]);
            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }


    public function jalar_valor($id_talla){
        try{
            $sql = 'select * from producto_precio where id_talla = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_talla]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }


}