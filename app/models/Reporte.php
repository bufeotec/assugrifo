<?php


class Reporte
{
    private $pdo;
    private $log;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }


    public function listar_dia(){
        try{
            $sql = 'select * from talla t inner join producto p on t.id_producto = p.id_producto inner join producto_precio pp on t.id_talla = pp.id_talla
                    where talla_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function listar_dia_(){
        try{
            $sql = 'select * from ventas v inner join ventas_detalle vd on v.id_venta = vd.id_venta inner join producto_precio pp on vd.id_producto_precio = pp.id_producto_precio
                    inner join talla t on pp.id_talla = t.id_talla inner join producto p on t.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria
                    where producto_estado = 1 and talla_estado = 1 and producto_precio_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    //Funcion Especial para Listar Los Productos
    public function listar_dia_stock($fecha){
        try{
            $sql = 'select * from producto p inner join producto_precio pp inner join talla t on pp.id_talla = t.id_talla
                    inner join stocklog s on p.id_producto = s.id_producto
                    inner join proveedores p2 on pp.id_proveedor = p2.id_proveedor where date(s.stocklog_date) = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function total_productos_now($id_talla){
        try{
            $sql = 'select talla_stock from talla where id_talla = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_talla
            ]);
            $r = $stm->fetch();
            $result = $r->talla_stock;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function sacar_descuento($id){
        try{
            $sql = 'select * from ventas v inner join ventas_detalle vd on v.id_venta = vd.id_venta inner join producto_precio pp on vd.id_producto_precio = pp.id_producto_precio
                    inner join producto p on pp.id_producto = p.id_producto where p.id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function consulta_general($fecha_filtro, $fecha_filtro_fin){
        try{
            $sql = 'select * from producto p inner join startproduct s on p.id_producto = s.id_producto inner join stockout s2 on p.id_producto = s2.id_producto
                    inner join stocklog s3 on p.id_producto = s3.id_producto inner join ventas v on p.id_usuario = v.id_usuario';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro, $fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function products_selled($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = "select sum(pvd.venta_detalle_cantidad) total from ventas pv inner join ventas_detalle pvd on pv.id_venta = pvd.id_venta 
                    inner join producto_precio pp on pvd.id_producto_precio = pp.id_producto_precio 
                    inner join talla t on pp.id_talla = t.id_talla inner join producto p on t.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(pv.venta_fecha) between ? and ? and t.id_talla = ? and pv.venta_total <> 0
                    and pv.venta_cancelar <> 'false' and pv.venta_estado=1 and f.id_familia = 2";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function products_selled_o($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = "select sum(pvd.venta_detalle_cantidad) total from ventas pv inner join ventas_detalle pvd on pv.id_venta = pvd.id_venta 
                    inner join producto_precio pp on pvd.id_producto_precio = pp.id_producto_precio 
                    inner join talla t on pp.id_talla = t.id_talla inner join producto p on t.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(pv.venta_fecha) between ? and ? and t.id_talla = ? and pv.venta_total <> 0
                    and pv.venta_cancelar <> 'false' and pv.venta_estado=1 and f.id_familia = 3";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function products_selled_t($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = "select sum(pvd.venta_detalle_cantidad) total from ventas pv inner join ventas_detalle pvd on pv.id_venta = pvd.id_venta 
                    inner join producto_precio pp on pvd.id_producto_precio = pp.id_producto_precio 
                    inner join talla t on pp.id_talla = t.id_talla inner join producto p on t.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(pv.venta_fecha) 
                    between ? and ? and t.id_talla = ? and pv.venta_total <> 0
                    and pv.venta_cancelar <> 'false' and pv.venta_estado=1 and f.id_familia = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function total_por_producto($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = "select sum(pvd.venta_detalle_valor_total) total from ventas pv inner join ventas_detalle pvd on pv.id_venta = pvd.id_venta inner join
                    producto_precio pp on pvd.id_producto_precio = pp.id_producto_precio where date(pv.venta_fecha) between ? and ? and pp.id_producto = ?
                    and pv.venta_total <> 0 and pv.venta_cancelar <> 0";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->total;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }
    public function total_por_producto_toda_fila($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = "select * from ventas pv inner join ventas_detalle pvd on pv.id_venta = pvd.id_venta inner join
                    producto_precio pp on pvd.id_producto_precio = pp.id_producto_precio inner join talla t on pp.id_talla = t.id_talla
                    inner join producto p on t.id_producto = p.id_producto inner join categorias c on p.id_categoria = c.id_categoria
                    inner join familias f on c.id_familia = f.id_familia
                    where date(pv.venta_fecha) between ? and ? and t.id_talla = ?
                    and pv.venta_total <> 0 and pv.venta_cancelar <> 0 and pv.venta_estado=1 and f.id_familia = 2";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetchAll();
            $result = $r;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function total_por_producto_toda_fila_t($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = "select * from ventas pv inner join ventas_detalle pvd on pv.id_venta = pvd.id_venta inner join
                    producto_precio pp on pvd.id_producto_precio = pp.id_producto_precio inner join talla t on pp.id_talla = t.id_talla
                    inner join producto p on t.id_producto = p.id_producto inner join categorias c on p.id_categoria = c.id_categoria
                    inner join familias f on c.id_familia = f.id_familia
                    where date(pv.venta_fecha) between ? and ? and t.id_talla = ?
                    and pv.venta_total <> 0 and pv.venta_cancelar <> 0 and pv.venta_estado=1 and f.id_familia = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetchAll();
            $result = $r;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function total_por_producto_toda_fila_o($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = "select * from ventas pv inner join ventas_detalle pvd on pv.id_venta = pvd.id_venta inner join
                    producto_precio pp on pvd.id_producto_precio = pp.id_producto_precio inner join talla t on pp.id_talla = t.id_talla
                    inner join producto p on t.id_producto = p.id_producto inner join categorias c on p.id_categoria = c.id_categoria
                    inner join familias f on c.id_familia = f.id_familia
                    where date(pv.venta_fecha) between ? and ? and t.id_talla = ?
                    and pv.venta_total <> 0 and pv.venta_cancelar <> 0 and pv.venta_estado=1 and f.id_familia = 3";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetchAll();
            $result = $r;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function jalar_fecha_fin($id){
        try{
            $sql = 'select date(fecha_registro) fecha from startproduct where id_talla = ? order by fecha_registro asc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function inicial($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = 'select startproduct_stock from startproduct where date(fecha_registro) between ? and ? and id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,
                $fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->startproduct_stock;

        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function jalar_nuevo_valor_inicial($nueva_fecha_inicio,$nueva_fecha_fin,$id_producto){
        try{
            $sql = 'select startproduct_stock from startproduct st inner join producto p on st.id_producto = p.id_producto 
                    where date(fecha_registro) between ? and ? and st.id_producto = ?
                    order by id_startproduct asc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $nueva_fecha_inicio,
                $nueva_fecha_fin,
                $id_producto]);
            $r = $stm->fetch();
            $result = $r->startproduct_stock;
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }


    public function stockadded($fecha_filtro,$fecha_filtro_fin, $id_talla){
        try{
            $sql = 'select sum(s.stocklog_added) entrada from stocklog s inner join producto p on s.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(s.stocklog_date) between ? and ? and s.id_talla = ? and f.id_familia = 2
                    order by s.id_stocklog asc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,
                $fecha_filtro_fin,
                $id_talla
            ]);
            $r = $stm->fetch();
            $result = $r->entrada;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function stockadded_o($fecha_filtro,$fecha_filtro_fin, $id_talla){
        try{
            $sql = 'select sum(s.stocklog_added) entrada from stocklog s inner join producto p on s.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(s.stocklog_date) between ? and ? and s.id_talla = ? and f.id_familia = 3
                    order by s.id_stocklog asc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,
                $fecha_filtro_fin,
                $id_talla
            ]);
            $r = $stm->fetch();
            $result = $r->entrada;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function stockout($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = 'select sum(stockout_out) salida from stockout s inner join producto p on s.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(stockout_date) between ? and ? and id_talla = ? and f.id_familia = 2
                    order by id_stockout asc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->salida;

        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function stockout_o($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = 'select sum(stockout_out) salida from stockout s inner join producto p on s.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(stockout_date) between ? and ? and id_talla = ? and f.id_familia = 3
                    order by id_stockout asc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->salida;

        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function stockadded_t($fecha_filtro,$fecha_filtro_fin, $id_talla){
        try{
            $sql = 'select sum(stocklog_added) entrada from stocklog s inner join producto p on s.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(stocklog_date) between ? and ? and id_talla = ? and f.id_familia = 1
                    order by id_stocklog asc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,
                $fecha_filtro_fin,
                $id_talla
            ]);
            $r = $stm->fetch();
            $result = $r->entrada;
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function stockout_t($fecha_filtro,$fecha_filtro_fin, $id_producto){
        try{
            $sql = 'select sum(stockout_out) salida from stockout st inner join producto p on st.id_producto = p.id_producto
                    inner join categorias c on p.id_categoria = c.id_categoria inner join familias f on c.id_familia = f.id_familia
                    where date(stockout_date) between ? and ? and id_talla = ? and f.id_familia = 1
                    order by id_stockout asc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha_filtro,$fecha_filtro_fin,
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->salida;

        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }


    public function listar_egresos_dia($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from egresos where date(egreso_fecha_registro) between ? and ? and egreso_estado = 1 and movimiento_tipo = 2';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
            return $result;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_ingresos_dia($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from egresos where date(egreso_fecha_registro) between ? and ? and egreso_estado = 1 and movimiento_tipo = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
            return $result;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_egresos_nuevo($fecha_nueva){
        try{
            $sql = 'select * from egresos where date(egreso_fecha_registro) = ? and egreso_estado = 1 and movimiento_tipo = 2';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_nueva]);
            $result = $stm->fetchAll();
            return $result;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_ingresos_nuevo($fecha_nueva){
        try{
            $sql = 'select sum(egreso_monto) as total from egresos where date(egreso_fecha_registro) = ? and egreso_estado = 1 and movimiento_tipo = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_nueva]);
            $result = $stm->fetch();
            return $result;
        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_productos(){
        try{
            $sql = 'select * from producto p inner join producto_precio pp on p.id_producto = pp.id_producto order by p.producto_nombre';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }


    public function inventario_inicial($id_producto){
        try{
            $sql = 'select startproduct_stock from startproduct where id_turn = ? and id_product = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $id_producto
            ]);
            $r = $stm->fetch();
            $result = $r->startproduct_stock;

        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }


    public function listar_ventas($fecha){
        try{
            $sql = 'select * from producto_venta sp inner join clientes c on sp.id_cliente = c.id_cliente where date(producto_venta_fecha) = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $fecha
            ]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function listar_monto_apertura_reporte_dia($fecha_hoy){
        try{
            $sql = 'select * from caja where date(caja_apertura_fecha) = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_hoy]);
            $result = $stm->fetch();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_monto_apertura_reporte_dia_filtro($fecha_filtro, $fecha_filtro_hoy){
        try{
            $sql = 'select sum(caja_apertura) total_apertura from caja where date(caja_apertura_fecha) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro, $fecha_filtro_hoy]);
            $result = $stm->fetch();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function filtro_busqueda($fecha_filtro, $fecha_filtro_hoy){
        try{
            $sql = 'select * from caja where date(caja_cierre_fecha) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_hoy]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function listar_egresos($fecha_hoy, $fecha_filtro_fin){
        try{
            $sql = 'select * from egresos where date(egreso_fecha_registro) between ? and ? and egreso_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_hoy,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_monto_apertura($fecha_hoy,$fecha_filtro_fin){
        try{
            $sql = 'select SUM(caja_apertura) total from caja where date(caja_apertura_fecha) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_hoy,$fecha_filtro_fin]);
            $result = $stm->fetch();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_ventas_filtro($fecha_hoy,$fecha_filtro_fin){
        try{
            $sql = 'select * from ventas pv inner join clientes c on pv.id_cliente = c.id_cliente 
                    where date(pv.venta_fecha) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_hoy,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_ventas_nuevo_filtro($fecha){
        try{
            $sql = 'select * from ventas pv inner join clientes c on pv.id_cliente = c.id_cliente 
                    where date(pv.venta_fecha) = ? and pv.venta_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha]);
            $result = $stm->fetchAll();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    //FUNCION PARA CONSULTAR PRODUCTOS AGREGADOS
    public function consultar_agregados($id_producto, $fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from stocklog st inner join producto p on st.id_producto = p.id_producto
                    where st.id_producto = ? and date(stocklog_date) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto, $fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function consultar_agregados_($id_producto, $fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from stocklog st inner join producto p on st.id_producto = p.id_producto
                    where st.id_producto = ? and date(stocklog_date) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto, $fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function consultar_ventas($id_producto, $fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from ventas pv inner join ventas_detalle vd on 
                    pv.id_venta = vd.id_venta inner join producto_precio pp on vd.id_producto_precio = pp.id_producto_precio inner join talla t on pp.id_talla = t.id_talla 
                    inner join producto p on t.id_producto = p.id_producto
                    inner join clientes c on pv.id_cliente = c.id_cliente inner join usuarios u on p.id_usuario = u.id_usuario 
                    inner join personas p2 on u.id_persona = p2.id_persona where t.id_producto = ? and date(pv.venta_fecha) between ? and ? 
                    and pv.venta_total <> 0 and pv.venta_cancelar <> 0';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto, $fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function salidas_stock($id_producto, $fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from stockout st inner join producto p on st.id_producto = p.id_producto where st.id_producto = ? and 
                    date(stockout_date) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto, $fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }
    public function salidas_stock_($id_producto, $fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select * from stockout st inner join producto p on st.id_producto = p.id_producto where st.id_producto = ? and 
                    date(stockout_date) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto, $fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetch();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function jalar_descuento_x_prod($id_producto){
        try{
            $sql = 'select * from ventas_detalle vd inner join ventas v on vd.id_venta = v.id_venta inner join producto_precio pp on vd.id_producto_precio = pp.id_producto_precio
                    where pp.id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_producto]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function listar_cajas(){
        try{
            $sql = 'select * from caja_numero where caja_numero_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function reporte_productos($fecha_filtro,$fecha_filtro_fin,$id_caja){
        try{
            $sql = 'select sum(vd.venta_detalle_cantidad) total, vd.venta_detalle_nombre_producto as nombre from ventas v inner join ventas_detalle vd on v.id_venta = vd.id_venta
                    where date(v.venta_fecha) between ? and ? and v.id_caja = ? group by vd.venta_detalle_nombre_producto';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin,$id_caja]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;
    }

    public function reporte_productos_($fecha_filtro,$fecha_filtro_fin){
        try{
            $sql = 'select sum(vd.venta_detalle_cantidad) total, p.producto_nombre from ventas v inner join ventas_detalle vd on v.id_venta = vd.id_venta 
                    inner join comanda_detalle cd on vd.id_comanda_detalle = cd.id_comanda_detalle inner join 
                    productos p on cd.id_producto = p.id_producto where v.id_caja_numero = 1 and v.venta_fecha between ? and ? group by p.id_producto ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_filtro,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = 0;
        }
        return $result;
    }

    public function ventas_efectivo($id_caja, $fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select SUM(vdp.venta_detalle_valor_total) total from ventas v inner join ventas_detalle vdp on v.id_venta = vdp.id_venta
                    where v.id_caja = ? and date(v.venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and v.id_tipo_pago = 3 and v.venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja, $fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function ventas_tarjeta($id_caja, $fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select SUM(vdp.venta_detalle_valor_total) total from ventas v inner join ventas_detalle vdp on v.id_venta = vdp.id_venta
                    where v.id_caja = ? and date(v.venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and v.id_tipo_pago = 1 and v.venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja, $fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function ventas_trans_plin($id_caja, $fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select SUM(vdp.venta_detalle_valor_total) total from ventas v inner join ventas_detalle vdp on v.id_venta = vdp.id_venta
                    where v.id_caja = ? and date(v.venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and v.id_tipo_pago = 5 and v.venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja, $fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function ventas_trans_yape($id_caja, $fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select SUM(vdp.venta_detalle_valor_total) total from ventas v inner join ventas_detalle vdp on v.id_venta = vdp.id_venta
                    where v.id_caja = ? and date(v.venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and v.id_tipo_pago = 4 and v.venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja, $fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function ventas_trans_otros($id_caja, $fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select SUM(vdp.venta_detalle_valor_total) total from ventas v inner join ventas_detalle vdp on v.id_venta = vdp.id_venta
                    where v.id_caja = ? and date(v.venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and v.id_tipo_pago = 6 and v.venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja, $fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function impuestos($id_caja, $fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select sum(venta_icbper) total from ventas 
                    where id_caja = ? and date(venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and id_tipo_pago = 3 and venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja, $fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function impuestos_($fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select sum(venta_icbper) total from ventas 
                    where id_caja = 1 and date(venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and id_tipo_pago = 3 and venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function impuestos_tt($id_caja, $fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select sum(venta_icbper) total from ventas 
                    where id_caja = ? and date(venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1 and id_tipo_pago <> 3 and venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja, $fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function ingreso_caja_chica_x_caja($id_caja,$fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select sum(egreso_monto) total from egresos
                    where id_caja_numero = ? and date(egreso_fecha_registro) between ? and ? and movimiento_tipo = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function salida_caja_chica_x_caja($id_caja, $fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select sum(egreso_monto) total from egresos
                    where id_caja_numero = ? and date(egreso_fecha_registro) between ? and ? and movimiento_tipo = 2';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja, $fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function reporte_caja_x_caja($id_caja,$fecha_filtro, $fecha_filtro_hoy){
        try{
            $sql = 'select sum(caja_apertura) total_apertura from caja where id_caja_numero = ? and date(caja_apertura_fecha) between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$fecha_filtro, $fecha_filtro_hoy]);
            $result = $stm->fetch();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function n_ventas_salon($id_caja,$fecha_ini_caja, $fecha_fin_caja){
        try{
            $sql = 'select count(id_venta) total from ventas where id_caja = ? and date(venta_fecha) between ? and ? and venta_tipo <> 07 
                    and anulado_sunat = 0 and venta_cancelar = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$fecha_ini_caja, $fecha_fin_caja]);
            $return = $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $return = 0;
        }
        return $return;
    }

    public function datos_caja_($id_caja_numero){
        try{
            $sql = 'select * from caja_numero where id_caja_numero = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja_numero]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


    public function listar_egresos_reporte($id_caja,$fecha_hoy, $fecha_filtro_fin){
        try{
            $sql = 'select * from egresos where id_caja_numero = ? and date(egreso_fecha_registro) between ? and ? and egreso_estado = 1 and movimiento_tipo = 2';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja,$fecha_hoy,$fecha_filtro_fin]);
            $result = $stm->fetchAll();
            return $result;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


}