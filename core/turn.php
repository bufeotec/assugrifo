<?php
/**
 * Created by PhpStorm
 * User: Franz
 * Date: 29/04/2019
 * Time: 20:17
 */
//Inicio Clase Para Generación Automatica de Turnos
require 'app/models/Turni.php';
$turni = new Turni();

//Obtener Fecha Actual
$date_now = date('Y-m-d');
//Verifica si hay un turno activo con la fecha actual
$active = $turni->searchByDay($date_now);

if(!$active){
    //Si no hay un turno activo con la fecha del dia, entra aquí.
    $turni->offTurns();
    $modelo = new Turni();
    $modelo->turn_datestart = $date_now;
    $modelo->turn_inicialcash = 0;
    $turnif = $turni->save($modelo);

    if($turnif == 1){
        $turn = $turni->searchByDayi($date_now);
        $productos = $turni->listP();
        $result = $turni->setStock($productos, $turn->id_turn);
    }
}