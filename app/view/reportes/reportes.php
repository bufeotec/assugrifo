<hr><h2 class="concss">
    <a href="http://localhost/fire"><i class="fa fa-fire"></i> INICIO</a> >
    <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
</h2><hr>

<div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-4">
        <a href="<?= _SERVER_?>Reporte/reporte_dia">
            <button class="boton_blanco">
                <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/reporte_dia.png" height="60%">
                <br><br>
                <h5 class="font-weight-bold">REPORTE GENERAL</h5>
            </button>
        </a>
    </div>
    <div class="col-lg-4">
        <a href="<?= _SERVER_?>Reporte/ingresos_y_egresos">
            <button class="boton_blanco">
                <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/reporte_ingresoyegreso.png" height="60%">
                <br><br>
                <h5 class="font-weight-bold">REPORTE POR DÍA</h5>
            </button>
        </a>
    </div>
    <div class="col-lg-2"></div>
    <div class="col-lg-3" style="display: none">
        <a href="<?= _SERVER_?>Reporte/inventario">
        <button class="boton_blanco">
            <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/reporte_inventario.png" height="60%">
            <br><br>
            <h5 class="font-weight-bold">INVENTARIO</h5>
        </button>
        </a>
    </div>
    <div class="col-lg-3" style="display: none">
        <a href="<?= _SERVER_?>Reporte/reporte_compras">
        <button class="boton_blanco">
            <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/reporte_compraproducto.png" height="60%">
            <br><br>
            <h5 class="font-weight-bold">COMPRA DE PRODUCTOS</h5>
        </button>
        </a>
    </div>
</div>
