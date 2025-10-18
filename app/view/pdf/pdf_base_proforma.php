<?php
require 'app/view/pdf/fpdf/fpdf.php';

class PDF extends FPDF{

    //Cabecera de pagina
    function Header(){
        //Arial bold 15
        $this->Image('media/logo/logo_cralm_chico.png',81,4,45,0);
        $this->Cell(130,10,'',0,8,'C');
        $this->Ln();
        $this->Ln();

        $this->SetFont('Arial','B',16);
        //Mover
        $this->Cell(30);
        //Titulo
        $this->Cell(130,7,'CRALM GROUP E.I.R.L.',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(190,7,'RUC: 20609569752',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(190,7,'CAL.ESTADO DE ISRAEL NRO. 256 LORETO - MAYNAS - IQUITOS',0,1,'C');
        $this->SetFont('Arial','B',12);
        //$this->Cell(190,7,'Fabricación de Prendas de Vestir, Ventas al por menor de articulos de Ferreteria',0,1,'C');
        //$this->SetFont('Arial','B',12);
        //$this->Cell(190,7,'Telf: 938211659 || Email: yolitexeirl.iq@gmail.com',0,1,'C');
        $this->SetFont('Arial','B',16);
        $this->Cell(190,7,'PROFORMA DE VENTA',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(190,7,'---------------------------------------------------------------------------------------------------------------------------',0,0,'C');

        //Salto de linea
        $this->Ln();
    }

    //Pie de pagina
    function Footer(){
        //$this->Image('media/fire/firma2.png',50,235,110);
        //Posicion: a 1.5 cm del final
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Numero de Ipagina
        $fecha = date('d-m-Y h:i:s');
        $this->Cell(0,10,'Pagina ' . $this->PageNo().'/{nb}'." "."|| Hora y fecha de descarga"." ".$fecha,0,0,'C');
    }
}
?>