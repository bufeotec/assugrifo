<?php
$mes = date('m');
$nombre_mes='';
switch ($mes){
    case 1: $nombre_mes='ENERO'; break;
    case 2: $nombre_mes='FEBRERO'; break;
    case 3: $nombre_mes='MARZO'; break;
    case 4: $nombre_mes='ABRIL'; break;
    case 5: $nombre_mes='MAYO'; break;
    case 6: $nombre_mes='JUNIO'; break;
    case 7: $nombre_mes='JULIO'; break;
    case 8: $nombre_mes='AGOSTO'; break;
    case 9: $nombre_mes='SETIEMBRE';break;
    case 10:$nombre_mes='OCTUBRE'; break;
    case 11:$nombre_mes='NOVIEMBRE'; break;
    case 12:$nombre_mes='DICIEMBRE'; break;
}
?>
<div class="social">
    <ul>
        <li>
            <div class="container_">
                <button class="cta">
                    <i class="fa fa-user"></i>
                    <span class="button-text"><?php echo $this->encriptar->desencriptar($_SESSION['rn'],_FULL_KEY_);?></span>
                </button>
            </div>
        </li>
        <li>
            <div class="container_">
                <button class="cta">
                    <i class="fa fa-credit-card"></i>
                    <a href="<?= _SERVER_. 'ventas/venta_rapida'?>" style="text-decoration: none" class="text-white"><span  class="button-text">Realizar Venta</span></a>

                </button>
            </div>
        </li>
        <li>
            <div class="container_">
                <button class="cta">
                    <i class="fa fa-industry"></i>
                    <a href="<?= _SERVER_. 'inventario/agregar_producto'?>" style="text-decoration: none" class="text-white"><span  class="button-text">Inventario</span></a>
                </button>
            </div>
        </li>
        <li>
            <div class="container_">
                <button class="cta">
                    <i class="fa fa-car"></i>
                    <a href="<?= _SERVER_. 'proveedor/agregar'?>" style="text-decoration: none" class="text-white"><span  class="button-text ml-4">Proveedores</span></a>
                </button>
            </div>
        </li>
    </ul>
</div>
<style>
    .container_ {
        position: initial;
        float: none;
        top: 50%;
        right: 50%;
        text-align: center;
    }
    .cta {
        width: 60px;
        height: 60px;
        border-radius: 50px;
        border: 2px solid white;
        background: rgb(24,35,51);
        color: #fff;
        transition: width 0.7s;
    }
    .cta:hover {
        width: 180px;
        transition: width 0.7s;
    }

    .cta i {
        opacity: 1;
        transition: opacity 0.5s
    }

    .cta:hover i {
        opacity: 0;
        transition: opacity 0.5s
    }

    .cta .button-text {
        opacity: 0;
        transition: opacity .5s;
        position: absolute;
        width: 100%;
        right: 0;
    }

    .cta:hover .button-text {
        opacity: 1;
        transition: opacity 0.5s
    }
</style>
<style>
    .social {
        position: fixed; /* Hacemos que la posición en pantalla sea fija para que siempre se muestre en pantalla*/
        right: 30px; /* Establecemos la barra en la izquierda */
        top: 200px; /* Bajamos la barra 200px de arriba a abajo */
        z-index: 2000; /* Utilizamos la propiedad z-index para que no se superponga algún otro elemento como sliders, galerías, etc */
    }

    .social ul {
        list-style: none;
    }

    .social ul li .icon-usuario {background:rgb(24,35,51);} /* Establecemos los colores de cada red social, aprovechando su class */
    .social ul li .icon-facebook {background:#3b5998;} /* Establecemos los colores de cada red social, aprovechando su class */
    .social ul li .icon-twitter {background: #00abf0;}
    .social ul li .icon-googleplus {background: #d95232;}
    .social ul li .icon-pinterest {background: #ae181f;}
    .social ul li .icon-mail {background: #666666;}

</style>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <!--<div class="d-sm-flex align-items-center justify-content-between mb-4">-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div style="background-image: url(<?= _SERVER_.'media/logo/1.jpg'?>)">
                    <div class="card-body text-center font-weight-bold text-white" style="background: rgba(242, 144, 57, 0.85)">
                        <h5><b>Bienvenido a <?= _EMPRESA_;?></b></h5>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <hr>
        <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fa fa-download fa-sm text-white-50"></i> Generate Report</a>-->
    <!--</div>-->
    <!-- Content Row -->
    <style>
        .fondo {
            background:rgb(24,35,51) ;
        }
        .texto-f{
            color: rgb(24,35,51);
        }
    </style>
    <div class="row">

        <div class="col-lg-3"></div>
        <div class="col-lg-6 text-center" style="background: transparent">
            <div class="card">
                <?php if(!$fecha_open){ ?>
                    <div class="card-header py-3 fondo">
                        <h3 class="font-weight-bold text-white">APERTURA DE CAJA - DÍA DE HOY</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-group">Turno:</label>
                                <input class="form-control" type="text" id="turno" name="turno" value="<?= $mostrar_;?>" readonly>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-group">Caja:</label>
                                <select class="form-control" id= "caja_numero" name="caja_numero">
                                    <?php
                                    foreach($caja as $l){
                                        ?>
                                        <option value="<?php echo $l->id_caja_numero;?>"><?php echo $l->caja_numero_nombre;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8" style="text-align: center">
                            <label >MONTO APERTURA CAJA - Para HOY: <br> <?php echo date('d -'). $nombre_mes.date('- Y') ;?></label>
                            <input type="text" class="form-control" id="caja_apertura" name="caja_apertura" onkeypress="validar_numeros(this.id)" >
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4" style="margin-top: 20px;text-align: center">
                            <button id="btn-agregar-apertura" class="btn btn-primary mb-3" onclick="apertura()"><i class="fa fa-save fa-sm text-white-50"></i> APERTURAR CAJA</button>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>
                    <?php
                } else {
                    $monto_apertura = $this->admin->mostrar_valor_apertura($fecha_hoy);
                    ?>
                    <br>
                    <div class="row">
                        <div class="card" style="width: 100%">
                            <div class="card-header fondo text-white">
                                <h4>Apertura de Caja</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="lds-dual-ring text-center" style="background: url(<?= _SERVER_._LOGO_GENERAL?>) no-repeat;background-size: cover; display: flex; align-items: center">

                                        </div>
<!--                                    <div class=" circulo_r"  style="background: transparent; width: 120px;border: 3px dotted rgba(24,35,51,0.6); height: 120px; border-radius: 70px; display: flex;align-items: center">
                                        <div class=""  style="margin: 0px auto; background: transparent; width: 110px;border: 1px solid rgba(24,35,51,0.6); height: 110px; border-radius: 60px; display: flex;align-items: center">

                                        </div>

                                    </div>

-->
                                    </div>
                                    <div class="col-lg-8 p-4" style="text-align: center;">
                                        <h3 class="texto-f mb-2" >El Monto de Apertura de Caja para hoy es: S/. <?php echo $monto_apertura;?></h3>
                                    </div>
                                    <div class="col-lg-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>

        </div>
        <div class="col-lg-3"></div>
    </div>
</div>
<style>
    .lds-dual-ring {
        display: inline-block;
        width: 130px;
        height: 130px;
    }
    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 200px;
        height: 120px;
        margin: 8px;
        border-radius: 50%;
        border: 6px solid rgb(24,35,51);
        border-color: rgb(24,35,51) transparent rgb(24,35,51) transparent;
        animation: lds-dual-ring 2.2s linear infinite;
    }
    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<!-- End of Main Content -->

<script type="text/javascript">
    $(document).ready(function(){
        $('#caja_apertura').keypress(function(e){
            if(e.which === 13){
                apertura();
            }
        });
    });

    function apertura() {
        var valor = true;
        //Extraemos las variable según los valores del campo consultado
        var caja_apertura = $('#caja_apertura').val();
        var caja_numero = $('#caja_numero').val();
        var turno = $('#turno').val();

        //Validamos si los campos a usar no se encuentran vacios
        valor = validar_campo_vacio('caja_apertura', caja_apertura, valor);
        valor = validar_campo_vacio('caja_numero', caja_numero, valor);

        if(valor){
            //Definimos el mensaje y boton a afectar
            var boton = "btn-agregar-apertura";
            //Cadena donde enviaremos los parametros por POST
            var cadena = "caja_apertura=" + caja_apertura +
            "&caja_numero=" + caja_numero +
            "&turno=" + turno;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Admin/agregar_apertura",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, "Guardando...", true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i>  Aperturar Caja", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Ingreso de Apertura Exitoso!', 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            break;
                        case 2:
                            respuesta('Error al ingresar la apertura de la caja', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }
</script>