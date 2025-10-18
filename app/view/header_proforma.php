<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 30/03/2020
 * Time: 19:30
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>PROFORMA FIRE</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= _SERVER_ . _STYLES_ADMIN_;?>img/logo_intrag.jpg" type="image/x-icon"/>

    <!-- Fonts and icons -->
    <script src="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Open+Sans:300,400,600,700"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['<?= _SERVER_ . _STYLES_ADMIN_;?>assets/css/fonts.css']},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/css/azzara.min.css">
    <link rel="stylesheet" href="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/css/select2.min.css">
    <link rel="stylesheet" href="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/css/egg_styles.css">

    <!-- Custom styles for this page -->
    <link href="<?= _SERVER_ . _STYLES_ADMIN_;?>datatable/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/css/demo.css">

    <!-- Alertify -->
    <script src="<?php echo _SERVER_ . _STYLES_ALL_;?>alertifyjs/alertify.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo _SERVER_ . _STYLES_ALL_;?>alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="<?php echo _SERVER_ . _STYLES_ALL_;?>alertifyjs/css/themes/default.css">
    <!-- tabla responsive -->
    <link rel="stylesheet" type="text/css" href="<?php echo _SERVER_ . _STYLES_ADMIN_;?>assets/js/plugin/responsive.DataTable/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo _SERVER_ . _STYLES_ADMIN_;?>assets/js/plugin/responsive.DataTable/css/jquery.dataTables.min.css">
    <script src="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/js/plugin/responsive.DataTable/jquery3.5.1.js"></script>
    <script src="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/js/plugin/responsive.DataTable/js/jquery.dataTables.min.js"></script>
    <script src="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/js/plugin/responsive.DataTable/js/dataTables.responsive.min.js"></script>

    <style>
        label{font-weight: bold !important;}
        .readonly_select_asistencia{pointer-events: none;background: lightgrey}
        .no-show{display: none;}
        .no-habil{background-color: #eaa5a5 !important;}
    </style>
</head>
