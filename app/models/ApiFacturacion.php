<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 23/04/2021
 * Time: 10:10 a. m.
 */
require 'app/models/Signature.php';
require_once ("app/models/Ventas.php");


class ApiFacturacion
{
    private $log;
    public function __construct()
    {
        $this->log = new Log();
    }

    public function EnviarComprobanteElectronico($emisor, $nombre, $rutacertificado, $ruta_archivo_xml, $ruta_archivo_cdr,$id_venta)
    {
        //require 'app/models/Ventas.php';
        $objfirma = new Signature();
        $objventa = new Ventas();
        $flg_firma = 0; //Posicion del XML: 0 para firma
        // $ruta_xml_firmar = $ruta . '.XML'; //es el archivo XML que se va a firmar
        $ruta = $ruta_archivo_xml . $nombre . '.XML';

        //variable para seguir un orden del proceso,
        $result = 2; //result 2 es error y 1 es ok

        //$ruta_firma = $rutacertificado. 'certificado_20609569752.pfx';
        $ruta_firma = $rutacertificado. 'certificado_prueba.pfx'; //ruta del archivo del certicado para firmar
        //$pass_firma = 'CRALM256group';
        $pass_firma = '12345678'; //contraseña del certificado

        $resp = $objfirma->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);
        //print_r($resp);
        if($resp['respuesta'] == 'ok'){
            $ruta_xml = $ruta_archivo_xml.$nombre.'.XML';
            $result = $objventa->guardar_ruta_xml_venta($id_venta,$ruta_xml);
        }
        //echo '</br> XML FIRMADO';

        //FIRMAR XML - FIN
        if($result == 1){
            //CONVERTIR A ZIP - INICIO
            $zip = new ZipArchive();

            $nombrezip = $nombre.".ZIP";
            $rutazip = $ruta_archivo_xml . $nombre.".ZIP";

            if($zip->open($rutazip, ZipArchive::CREATE) === TRUE)
            {
                $zip->addFile($ruta, $nombre . '.XML');
                $zip->close();
                $result = 1;
            }else{
                $result = 2;
            }

            //echo '</br>XML ZIPEADO';
            //CONVERTIR A ZIP - FIN
            if($result == 1){

                //ENVIAR EL ZIP A LOS WS DE SUNAT - INICIO
                $ws = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService'; //ruta del servicio web de pruebad e SUNAT para enviar documentos
                //$ws = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService'; //Modo produccion
                $ruta_archivo = $rutazip;
                $nombre_archivo = $nombrezip;

                $contenido_del_zip = base64_encode(file_get_contents($ruta_archivo)); //codificar y convertir en texto el .zip

                //echo '</br> '. $contenido_del_zip;
                $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                        <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>'.$emisor->empresa_ruc.$emisor->empresa_usuario_sol.'</wsse:Username>
                                <wsse:Password>'.$emisor->empresa_clave_sol.'</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                        </soapenv:Header>
                        <soapenv:Body>
                        <ser:sendBill>
                            <fileName>'.$nombre_archivo.'</fileName>
                            <contentFile>'.$contenido_del_zip.'</contentFile>
                        </ser:sendBill>
                        </soapenv:Body>
                    </soapenv:Envelope>';

                $header = array(
                    "Content-type: text/xml; charset=\"utf-8\"",
                    "Accept: text/xml",
                    "Cache-Control: no-cache",
                    "Pragma: no-cache",
                    "SOAPAction: ",
                    "Content-lenght: ".strlen($xml_envio)
                );

                $ch = curl_init(); //iniciar la llamada
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 1); //
                curl_setopt($ch,CURLOPT_URL, $ws);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch,CURLOPT_TIMEOUT, 50);
                curl_setopt($ch,CURLOPT_POST, true);
                curl_setopt($ch,CURLOPT_POSTFIELDS, $xml_envio);
                curl_setopt($ch,CURLOPT_HTTPHEADER, $header);

                //para ejecutar los procesos de forma local en windows
                //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
                curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem"); //solo en local, si estas en el servidor web con ssl comentar esta línea

                $response = curl_exec($ch); // ejecucion del llamado y respuesta del WS SUNAT.

                $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE); // objten el codigo de respuesta de la peticion al WS SUNAT
                $estadofe = "0"; //inicializo estado de operación interno

                if($httpcode == 200)//200: La comunicacion fue satisfactoria
                {
                    $doc = new DOMDocument();//clase que nos permite crear documentos XML
                    $doc->loadXML($response); //cargar y crear el XML por medio de text-xml response

                    if( isset( $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue ) ) // si en la etique de rpta hay valor entra
                    {
                        $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue; //guadarmos la respuesta(text-xml) en la variable
                        $cdr = base64_decode($cdr); //decodificando el xml
                        file_put_contents($ruta_archivo_cdr . 'R-' . $nombrezip, $cdr ); //guardo el CDR zip en la carpeta cdr
                        $zip = new ZipArchive();
                        if($zip->open($ruta_archivo_cdr. 'R-' . $nombrezip ) === true ) //rpta es identica existe el archivo
                        {
                            $zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.XML');
                            $zip->close();
                            $ruta_cdr = $ruta_archivo_cdr.'R-' . $nombre . '.XML';
                            $result = $objventa->guardar_ruta_cdr_venta($id_venta,$ruta_cdr);

                            if($result == 1){
                                //INICIO - VERIFICAR RESPUESTA DEL CDR
                                $xml_cdr = simplexml_load_file($ruta_cdr);
                                $xml_cdr->registerXPathNamespace('c', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

                                $DocumentResponse = array();

                                $ReferenceID    = $xml_cdr->xpath('///c:ReferenceID');
                                $ResponseCode   = $xml_cdr->xpath('///c:ResponseCode');
                                $Description    = $xml_cdr->xpath('///c:Description');
                                $Notes          = $xml_cdr->xpath('///c:Note');

                                $DocumentResponse['RefenceID']      = (string)$ReferenceID[0];
                                $DocumentResponse['ResponseCode']   = (string)$ResponseCode[0];
                                $DocumentResponse['Description']    = (string)$Description[0];

                                if(count($Notes) > 0){
                                    foreach ($Notes as $note){
                                        $DocumentResponse['Notes'][] = (string)$Notes[0];
                                    }
                                }
                                //FIN - VERIFICAR RESPUESTA DEL CDR

                                $estado_sunat = $DocumentResponse['Description'];
                                $objventa->guardar_repuesta_venta($id_venta, $estado_sunat);
                            }
                        } else {
                            $estadofe = '2';
                            $result = 3; //error de envio comprobante
                            $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                            $mensaje = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                            //LOG DE TRAX ERRORES DB
                            $estado_sunat = 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                            $objventa->guardar_repuesta_venta($id_venta, $estado_sunat);
                            //echo 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                        }
                    } else {
                        $estadofe = '2';
                        $result = 3; //error de envio comprobante
                        $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                        $mensaje = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                        //LOG DE TRAX ERRORES DB
                        $estado_sunat = 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                        $objventa->guardar_repuesta_venta($id_venta, $estado_sunat);
                        //echo 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                    }
                } else { //Problemas de comunicacion
                    $doc = new DOMDocument();//clase que nos permite crear documentos XML
                    $doc->loadXML($response); //cargar y crear el XML por medio de text-xml response
                    if(isset($doc->getElementsByTagName('faultcode')->item(0)->nodeValue)){
                        $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                        $mensaje = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                        //LOG DE TRAX ERRORES DB
                        $estado_sunat = 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                        $objventa->guardar_repuesta_venta($id_venta, $estado_sunat);
                    }else{
                        $estadofe = "3";
                        //LOG DE TRAX ERRORES DB
                        echo curl_error($ch);
                        $estado_sunat = 'Hubo o existe un problema de conexión';
                        $objventa->guardar_repuesta_venta($id_venta, $estado_sunat);
                        //echo 'Hubo existe un problema de conexión';
                    }
                    $result = 4; //error de comunicacion(internet o sunat)

                }

                curl_close($ch);

                //ENVIAR EL ZIP A LOS WS DE SUNAT - FIN
            }

        }
        return $result;

    }

    public function EnviarResumenComprobantes($emisor,$nombre, $rutacertificado, $ruta_archivo_xml)
    {
        //firma del documento
        $objSignature = new Signature();
        $result = 2;
        $flg_firma = "0";
        //$ruta_archivo_xml = "xml/";
        $ruta = $ruta_archivo_xml.$nombre.'.XML';

        //$ruta_firma = $rutacertificado. 'certificado_20609569752.pfx';
        $ruta_firma = $rutacertificado. 'certificado_prueba.pfx'; //ruta del archivo del certicado para firmar
        //$pass_firma = 'CRALM256group';
        $pass_firma = '12345678'; //contraseña del certificado

        $resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);
        //print_r($resp); //hash
        if($resp['respuesta'] == 'ok'){
            //Generar el .zip

            $zip = new ZipArchive();

            $nombrezip = $nombre.".ZIP";
            $rutazip = $ruta_archivo_xml.$nombre.".ZIP";

            if($zip->open($rutazip,ZIPARCHIVE::CREATE)===true){
                $zip->addFile($ruta, $nombre.'.XML');
                $zip->close();
                $result = 1;
            }else{
                $result = 2;
            }
        }
        $ticket = "0";
        $mensaje = "";
        if($result == 1){
            //Enviamos el archivo a sunat

            $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            //$ws = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService'; //Modo produccion


            $ruta_archivo = $ruta_archivo_xml.$nombrezip;
            $nombre_archivo = $nombrezip;
            $ruta_archivo_cdr = "libs/ApiFacturacion/cdr/";

            $contenido_del_zip = base64_encode(file_get_contents($ruta_archivo));


            $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				 <soapenv:Header>
				 	<wsse:Security>
				 		<wsse:UsernameToken>
				 			<wsse:Username>'.$emisor->empresa_ruc.$emisor->empresa_usuario_sol.'</wsse:Username>
				 			<wsse:Password>'.$emisor->empresa_clave_sol.'</wsse:Password>
				 		</wsse:UsernameToken>
				 	</wsse:Security>
				 </soapenv:Header>
				 <soapenv:Body>
				 	<ser:sendSummary>
				 		<fileName>'.$nombre_archivo.'</fileName>
				 		<contentFile>'.$contenido_del_zip.'</contentFile>
				 	</ser:sendSummary>
				 </soapenv:Body>
				</soapenv:Envelope>';


            $header = array(
                "Content-type: text/xml; charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: ",
                "Content-lenght: ".strlen($xml_envio)
            );


            $ch = curl_init();
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_URL,$ws);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($ch,CURLOPT_TIMEOUT,50);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            //para ejecutar los procesos de forma local en windows
            //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");


            $response = curl_exec($ch);

            $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            $estadofe = "0";

            $ticket = "0";
            if($httpcode == 200){
                $doc = new DOMDocument();
                $doc->loadXML($response);

                if (isset($doc->getElementsByTagName('ticket')->item(0)->nodeValue)) {
                    $ticket = $doc->getElementsByTagName('ticket')->item(0)->nodeValue;
                    //echo "TODO OK NRO TK: ".$ticket;
                    $result = 1;
                    $mensaje = "TICKET ENVIADO";
                }else{
                    $this->log->insertar($doc->saveXML(), 'XML');
                    $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                    $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                    //echo "error ".$codigo.": ".$mensaje;
                    $mensaje = "error ".$codigo.": ".$mensaje;
                    $result = 4;
                }

            }else{
                $doc = new DOMDocument();
                $doc->loadXML($response);
                $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                //echo "error ".$codigo.": ".$mensaje;
                $mensaje = "error ".$codigo.": ".$mensaje;
                echo curl_error($ch);
                //echo "Problema de conexión";
                //$mensaje = "Problema de conexión";
                $result = 3;
            }

            curl_close($ch);
            //return $ticket;
        }
        $resultado = array(
            "result" => $result,
            "ticket" => $ticket,
            "mensaje" => $mensaje
        );
        return $resultado;

    }


    function ConsultarTicket($emisor, $cabecera, $ticket, $ruta_archivo_cdr, $tipo)
    {
        $objventa = new Ventas();
        $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService"; //modo beta
        //$ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService"; //modo produccion
        $ruta_archivo_xml = "libs/ApiFacturacion/xml/";
        $nombre	= $emisor->empresa_ruc.'-'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];
        $nombre_xml	= $nombre.".XML";

        //===============================================================//
        //FIRMADO DEL cpe CON CERTIFICADO DIGITAL
        $objSignature = new Signature();
        $flg_firma = "0";
        $ruta = $ruta_archivo_xml.$nombre_xml;

        //$ruta_firma = $rutacertificado. 'certificado_202606136618.pfx';
        //$ruta_firma = $rutacertificado. 'certificado_prueba.pfx'; //ruta del archivo del certicado para firmar
        //$pass_firma = 'Firebufeo1';
        $pass_firma = 'Group_2023'; //contraseña del certificado

        //===============================================================//

        //ALMACENAR EL ARCHIVO EN UN ZIP
        $zip = new ZipArchive();

        $nombrezip = $nombre.".ZIP";

        /*if($zip->open($nombrezip,ZIPARCHIVE::CREATE)===true){
            $zip->addFile($ruta, $nombre_xml);
            $zip->close();
        }*/

        //===============================================================//

        //ENVIAR ZIP A SUNAT
        $ruta_archivo = $nombre;
        $nombre_archivo = $nombre;
        //$ruta_archivo_cdr = "cdr/";

        //$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo.'.ZIP'));
        //FIN ZIP

        $xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
                <wsse:Security>
                    <wsse:UsernameToken>
                    <wsse:Username>'.$emisor->empresa_ruc.$emisor->empresa_usuario_sol.'</wsse:Username>
                    <wsse:Password>'.$emisor->empresa_clave_sol.'</wsse:Password>
                    </wsse:UsernameToken>
                </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                <ser:getStatus>
                    <ticket>' . $ticket . '</ticket>
                </ser:getStatus>
            </soapenv:Body>
        </soapenv:Envelope>';


        $header = array(
            "Content-type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: ",
            "Content-lenght: ".strlen($xml_envio)
        );


        $ch = curl_init();
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
        curl_setopt($ch,CURLOPT_URL,$ws);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
        curl_setopt($ch,CURLOPT_TIMEOUT,60);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml_envio);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        //para ejecutar los procesos de forma local en windows
        //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

        //echo "codigo:".$httpcode;

        if($httpcode == 200){
            $doc = new DOMDocument();
            $doc->loadXML($response);

            if(isset($doc->getElementsByTagName('content')->item(0)->nodeValue)){
                $cdr = $doc->getElementsByTagName('content')->item(0)->nodeValue;
                $cdr = base64_decode($cdr);


                file_put_contents($ruta_archivo_cdr."R-".$nombre_archivo.".ZIP", $cdr);

                $zip = new ZipArchive;
                if($zip->open($ruta_archivo_cdr."R-".$nombre_archivo.".ZIP")===true){
                    $zip->extractTo($ruta_archivo_cdr,'R-'.$nombre_archivo.'.XML');
                    $zip->close();
                }
                $mensaje_consulta = "Ha sido aceptado";
                $nombre_ruta_cdr = $ruta_archivo_cdr.'R-'.$nombre_archivo.'.XML';
                if($tipo == 1){
                    $objventa->actualizar_estadoconsulta_x_ticket($ticket,$nombre_ruta_cdr,$mensaje_consulta);
                }else{
                    $objventa->actualizar_estadoconsulta_x_ticket_anulado($ticket,$nombre_ruta_cdr,$mensaje_consulta);
                }
                //echo "TODO OK";
                $result = 1;
            }else{
                $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                //echo "error ".$codigo.": ".$mensaje;
                $mensaje_consulta = "error ".$codigo.": ".$mensaje;
                $nombre_ruta_cdr = '';
                if($tipo == 1){
                    $objventa->actualizar_estadoconsulta_x_ticket($ticket,$nombre_ruta_cdr,$mensaje_consulta);
                }else{
                    $objventa->actualizar_estadoconsulta_x_ticket_anulado($ticket,$nombre_ruta_cdr,$mensaje_consulta);
                }
                $result = 4;
            }

        }else{
            echo curl_error($ch);
            echo "Problema de conexión";
            $result = 3;
        }

        curl_close($ch);
        return $result;
    }

    function consultarComprobante($emisor, $comprobante)
    {
        try{
            $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            $soapUser = "";
            $soapPassword = "";

            $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
				xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
				xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
					<soapenv:Header>
						<wsse:Security>
							<wsse:UsernameToken>
								<wsse:Username>'.$emisor->empresa_ruc.$emisor->empresa_usuario_sol.'</wsse:Username>
								<wsse:Password>'.$emisor->empresa_clave_sol.'</wsse:Password>
							</wsse:UsernameToken>
						</wsse:Security>
					</soapenv:Header>
					<soapenv:Body>
						<ser:getStatus>
							<rucComprobante>'.$emisor->empresa_ruc.'</rucComprobante>
							<tipoComprobante>'.$comprobante['tipo_comprobante'].'</tipoComprobante>
							<serieComprobante>'.$comprobante['serie'].'</serieComprobante>
							<numeroComprobante>'.$comprobante['correlativo'].'</numeroComprobante>
						</ser:getStatus>
					</soapenv:Body>
				</soapenv:Envelope>';

            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: ",
                "Content-length: " . strlen($xml_post_string),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $ws);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            //para ejecutar los procesos de forma local en windows
            //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

        } catch (Exception $e) {
            //echo "SUNAT ESTA FUERA SERVICIO: ".$e->getMessage();
            $result = "SUNAT ESTA FUERA SERVICIO";
        }
        return $result;
    }

    //DATOS NUEVOS PARA GUIA
    public function EnviarGuiaRemision($emisor,$nombre, $rutacertificado, $ruta_archivo_xml, $ruta_archivo_cdr, $id_guia)
    {
        //firma del documento
        $objSignature = new Signature();
        $objventa = new Ventas();
        $result = 2;
        $flg_firma = "0";
        //$ruta_archivo_xml = "xml/";
        $ruta = $ruta_archivo_xml.$nombre.'.XML';

        //$ruta_firma = $rutacertificado. 'certificado_20609569752.pfx';
        $ruta_firma = $rutacertificado. 'certificado_prueba.pfx'; //ruta del archivo del certicado para firmar
        //$pass_firma = 'CRALM256group';
        $pass_firma = '12345678'; //contraseña del certificado

        $resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);//se firma el xml
        //print_r($resp); //hash
        if($resp['respuesta'] == 'ok'){
            $ruta_xml = $ruta_archivo_xml.$nombre.'.XML';
            $result = $objventa->guardar_ruta_xml_guia($id_guia,$ruta_xml);
        }
        //FIRMAR XML - FIN
        if($result == 1){
            //CONVERTIR A ZIP - INICIO
            $zip = new ZipArchive();

            $nombrezip = $nombre.".ZIP";
            $rutazip = $ruta_archivo_xml . $nombre.".ZIP";

            if($zip->open($rutazip, ZipArchive::CREATE) === TRUE)
            {
                $zip->addFile($ruta, $nombre . '.XML');
                $zip->close();
                $result = 1;
            }else{
                $result = 2;
            }

            //echo '</br>XML ZIPEADO';
            //CONVERTIR A ZIP - FIN
            if($result == 1){

                //ENVIAR EL ZIP A LOS WS DE SUNAT - INICIO
                //https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-
                //$ws = 'https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService'; //ruta del servicio web de pruebad e SUNAT para enviar documentos
                $ws = 'https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService'; //Modo produccion
                $ruta_archivo = $rutazip;
                $nombre_archivo = $nombrezip;

                $contenido_del_zip = base64_encode(file_get_contents($ruta_archivo)); //codificar y convertir en texto el .zip

                //echo '</br> '. $contenido_del_zip;
                $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                        <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>'.$emisor->empresa_ruc.$emisor->empresa_usuario_sol.'</wsse:Username>
                                <wsse:Password>'.$emisor->empresa_clave_sol.'</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                        </soapenv:Header>
                        <soapenv:Body>
                        <ser:sendBill>
                            <fileName>'.$nombre_archivo.'</fileName>
                            <contentFile>'.$contenido_del_zip.'</contentFile>
                        </ser:sendBill>
                        </soapenv:Body>
                    </soapenv:Envelope>';

                $header = array(
                    "Content-type: text/xml; charset=\"utf-8\"",
                    "Accept: text/xml",
                    "Cache-Control: no-cache",
                    "Pragma: no-cache",
                    "SOAPAction: ",
                    "Content-lenght: ".strlen($xml_envio)
                );

                $ch = curl_init(); //iniciar la llamada
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 1); //
                curl_setopt($ch,CURLOPT_URL, $ws);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch,CURLOPT_TIMEOUT, 50);
                curl_setopt($ch,CURLOPT_POST, true);
                curl_setopt($ch,CURLOPT_POSTFIELDS, $xml_envio);
                curl_setopt($ch,CURLOPT_HTTPHEADER, $header);

                //para ejecutar los procesos de forma local en windows
                //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
                curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem"); //solo en local, si estas en el servidor web con ssl comentar esta línea

                $response = curl_exec($ch); // ejecucion del llamado y respuesta del WS SUNAT.

                $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE); // objten el codigo de respuesta de la peticion al WS SUNAT
                $estadofe = "0"; //inicializo estado de operación interno

                if($httpcode == 200)//200: La comunicacion fue satisfactoria
                {
                    $doc = new DOMDocument();//clase que nos permite crear documentos XML
                    $doc->loadXML($response); //cargar y crear el XML por medio de text-xml response

                    if( isset( $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue ) ) // si en la etique de rpta hay valor entra
                    {
                        $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue; //guadarmos la respuesta(text-xml) en la variable
                        $cdr = base64_decode($cdr); //decodificando el xml
                        file_put_contents($ruta_archivo_cdr . 'R-' . $nombrezip, $cdr ); //guardo el CDR zip en la carpeta cdr
                        $zip = new ZipArchive();
                        if($zip->open($ruta_archivo_cdr. 'R-' . $nombrezip ) === true ) //rpta es identica existe el archivo
                        {
                            $zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.xml');
                            $zip->close();
                            $ruta_cdr = $ruta_archivo_cdr.'R-' . $nombre . '.XML';
                            $result = $objventa->guardar_ruta_cdr_guia($id_guia,$ruta_cdr);
                            if($result == 1){
                                //INICIO - VERIFICAR RESPUESTA DEL CDR
                                $xml_cdr = simplexml_load_file($ruta_cdr);
                                $xml_cdr->registerXPathNamespace('c', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

                                $DocumentResponse = array();

                                $ReferenceID    = $xml_cdr->xpath('///c:ReferenceID');
                                $ResponseCode   = $xml_cdr->xpath('///c:ResponseCode');
                                $Description    = $xml_cdr->xpath('///c:Description');
                                $Notes          = $xml_cdr->xpath('///c:Note');

                                $DocumentResponse['RefenceID']      = (string)$ReferenceID[0];
                                $DocumentResponse['ResponseCode']   = (string)$ResponseCode[0];
                                $DocumentResponse['Description']    = (string)$Description[0];

                                if(count($Notes) > 0){
                                    foreach ($Notes as $note){
                                        $DocumentResponse['Notes'][] = (string)$Notes[0];
                                    }
                                }
                                //FIN - VERIFICAR RESPUESTA DEL CDR

                                $estado_sunat = $DocumentResponse['Description'];
                                $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                            }
                            //$estadofe = '1';
                            //echo 'Procesado correctamente, OK';
                        }else{
                            $result = 2;
                            $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                            $mensaje = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                            //LOG DE TRAX ERRORES DB
                            $estado_sunat = 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                            $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                            //echo 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                        }
                    }
                    else {
                        $estadofe = '2';
                        $result = 3; //error de envio comprobante
                        $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                        $mensaje = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                        $mensagge = $doc->getElementsByTagName('message')->item(0)->nodeValue;
                        //LOG DE TRAX ERRORES DB
                        $estado_sunat = 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje.$mensagge;
                        $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                        //echo 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                    }
                }
                else { //Problemas de comunicacion
                    $doc = new DOMDocument();//clase que nos permite crear documentos XML
                    $doc->loadXML($response); //cargar y crear el XML por medio de text-xml response
                    if($httpcode == 500){
                        $estadofe = "3";
                        $result = 4; //error de comunicacion(internet o sunat)
                        $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                        $men = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                        $mensaje = $doc->getElementsByTagName('message')->item(0)->nodeValue;
                        //LOG DE TRAX ERRORES DB
                        $estado_sunat = 'Ocurrio un error con código: ' . $codigo . ' :' . $men. ' Msje:' .$mensaje;
                        $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);

                    }else{
                        $estadofe = "3";
                        $result = 4; //error de comunicacion(internet o sunat)
                        //LOG DE TRAX ERRORES DB
                        echo curl_error($ch);
                        $estado_sunat = 'Hubo o existe un problema de conexión';
                        $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                        //echo 'Hubo existe un problema de conexión';
                    }
                }

                curl_close($ch);

                //ENVIAR EL ZIP A LOS WS DE SUNAT - FIN
            }

        }
        return $result;

    }
    public function EnviarGuiaRemision_nuevo($emisor,$nombre, $rutacertificado, $ruta_archivo_xml, $ruta_archivo_cdr, $guia)
    {
        //firma del documento
        $objSignature = new Signature();
        $objventa = new Ventas();
        $result = 2;
        $flg_firma = "0";
        //$ruta_archivo_xml = "xml/";
        $ruta = $ruta_archivo_xml.$nombre.'.XML';
        $id_guia = $guia->id_guia;

        //$ruta_firma = $rutacertificado. 'certificado_20609569752.pfx';
        $ruta_firma = $rutacertificado. 'certificado_prueba.pfx'; //ruta del archivo del certicado para firmar
        //$pass_firma = 'CRALM256group';
        $pass_firma = '12345678'; //contraseña del certificado

        $resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);//se firma el xml
        //print_r($resp); //hash
        if($resp['respuesta'] == 'ok'){
            $ruta_xml = $ruta_archivo_xml.$nombre.'.XML';
            $result = $objventa->guardar_ruta_xml_guia($id_guia,$ruta_xml);
        }
        //FIRMAR XML - FIN
        if($result == 1){
            //CONVERTIR A ZIP - INICIO
            $zip = new ZipArchive();

            $nombrezip = $nombre.".zip";
            $rutazip = $ruta_archivo_xml . $nombre.".zip";

            if($zip->open($rutazip, ZipArchive::CREATE) === TRUE)
            {
                $zip->addFile($ruta, $nombre . '.xml');
                $zip->close();
                $result = 1;
            }else{
                $result = 2;
            }

            //echo '</br>XML ZIPEADO';
            //CONVERTIR A ZIP - FIN
            if($result == 1){

                //INICIO - DATOS PARA DE IDENTIFICACION PARA ENVIAR GRE
                $empresa_id = $emisor->empresa_gre_id;
                $empresa_clave = $emisor->empresa_gre_clave;
                $api_token = "https://api-seguridad.sunat.gob.pe/v1/clientessol/$empresa_id/oauth2/token/";
                //PRUEBA
                //$api_token = "https://gre-test.nubefact.com/v1/clientessol/test-85e5b0ae-255c-4891-a595-0b98c65c9854/oauth2/token";
                /*$body = array(
                    "grant_type"          => "password",
                    "scope"               => "https://api-cpe.sunat.gob.pe",
                    "client_id"           => "$empresa_id",
                    "client_secret"       => "$empresa_clave",
                    "username"            => $emisor->empresa_ruc.$emisor->empresa_usuario_sol,
                    "password"            => "$emisor->empresa_clave_sol"
                );*/
                $body = 'grant_type=password&scope=https%3A%2F%2Fapi-cpe.sunat.gob.pe&client_id='.$empresa_id."&client_secret=".$empresa_clave."&username=".$emisor->empresa_ruc.$emisor->empresa_usuario_sol."&password=".$emisor->empresa_clave_sol;
                //PRUEBA
                //$body = "grant_type=password&scope=https%3A%2F%2Fapi-cpe.sunat.gob.pe&client_id=test-85e5b0ae-255c-4891-a595-0b98c65c9854&client_secret=test-Hty/M6QshYvPgItX2P0+Kw==&username=".$emisor->empresa_ruc."MODDATOS&password=MODDATOS";
                //INICIO - OBTENER EL TOKEN

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_token,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $body,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/x-www-form-urlencoded',
                        'Cookie: TS019e7fc2=014dc399cbec395515f87110c4b4072f91cbfc5383d35b7025aaf4e6d64ebeba3d2162e14870599f2b76d143077f70dbcf62596481'
                    ),
                ));

                $respuesta = curl_exec($curl);

                curl_close($curl);
                $leer_respuesta = json_decode($respuesta, true);
                if(isset($leer_respuesta['cod'])){
                    $codigo_error = $leer_respuesta['cod'];
                    $mensaje_error = $leer_respuesta['msg'];
                    $estado_sunat = $codigo_error.' // '.$mensaje_error;
                    $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                    $result = 6; //erro al obtener el token
                }else{
                    if(isset($leer_respuesta['access_token'])){
                        $access_token = $leer_respuesta['access_token'];
                        $token_type = $leer_respuesta['token_type'];
                        $expires_in = $leer_respuesta['expires_in'];
                        $result = 1; //sigue el curso
                        $autorizacion = "Bearer ".$access_token;
                    }else{
                        $estado_sunat = 'No hubo conexión';
                        $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                        $result = 7; //no obtiene respuesta y termina la operacion
                    }
                }
                //FIN - OBTENER EL TOKEN
                //FIN - DATOS PARA DE IDENTIFICACION PARA ENVIAR GRE
                if($result == 1){

                    $ruta_archivo = $rutazip;
                    $nombre_archivo = $nombrezip;
                    $contenido_del_zip = base64_encode(file_get_contents($ruta_archivo)); //codificar y convertir en texto el .zip
                    $hash = hash('sha256', file_get_contents($ruta_archivo), false);
                    $url_guia = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/".$nombre.'?numRucEmisor='.$emisor->empresa_ruc.'&codCpe='.$guia->guia_remision_tipo.'&numSerie='.$guia->guia_serie.'&numCpe='.$guia->guia_correlativo;
                    //PRUEBA
                    //$url_guia = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/".$nombre.'?numRucEmisor='.$emisor->empresa_ruc.'&codCpe='.$guia->guia_remision_tipo.'&numSerie='.$guia->guia_serie.'&numCpe='.$guia->guia_correlativo;

                    //$body = array('archivo.nomArchivo='.$nombrezip.'&archivo.arcGreZip='.$contenido_del_zip);
                    $body_guia = array(
                        'archivo' => array(
                            'nomArchivo'    => $nombrezip,
                            'arcGreZip'     => $contenido_del_zip,
                            'hashZip'       => $hash
                        )
                    );
                    $json_guia = json_encode($body_guia);

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url_guia,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $json_guia,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: '.$autorizacion,
                            'Content-Type: application/json'
                        ),
                    ));

                    $resp_envio = curl_exec($curl);

                    curl_close($curl);
                    $leer_respuesta = json_decode($resp_envio, true);

                    if(isset($leer_respuesta['cod'])){
                        $codigo_error = $leer_respuesta['cod'];
                        $mensaje_error = $leer_respuesta['msg'];
                        $cod_err = $leer_respuesta['errors']['cod'];
                        $cod_msg = $leer_respuesta['errors']['msg'];
                        $estado_sunat = $codigo_error.' // '.$mensaje_error.'... CodError: '.$cod_err.'. Msg. '.$cod_msg;
                        $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                        $result = 8; //erro al obtener el token
                    }else{
                        if(isset($leer_respuesta['numTicket'])){
                            $num_ticket = $leer_respuesta['numTicket'];
                            $fecRecepcion = $leer_respuesta['fecRecepcion'];
                            $objventa->guardar_repuesta_tiket_recepcion_guia($id_guia, $num_ticket, $fecRecepcion);
                            $result = 1;
                        }else{
                            $estado_sunat = 'No hubo conexión';
                            $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                            $result = 7; //no obtiene respuesta y termina la operacion
                        }
                    }
                    curl_close($curl);
                    if($result == 1){
                        //INICIO - si se obtiene el ticket se tiene que consultar su validez
                        $parametro = '?numTicket='.$num_ticket;
                        $url_consul = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/".$num_ticket.$parametro;
                        //PRUEBA
                        //$url_consul = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/".$num_ticket.$parametro;

                        //$body = array('archivo.nomArchivo='.$nombrezip.'&archivo.arcGreZip='.$contenido_del_zip);
                        $body_consul = array();

                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $url_consul,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_POSTFIELDS => $body_consul,
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: '.$autorizacion
                            ),
                        ));

                        $resp_consul = curl_exec($curl);

                        curl_close($curl);
                        $leer_respuesta = json_decode($resp_consul, true);
                        if(isset($leer_respuesta['cod'])){
                            $codigo_error = $leer_respuesta['cod'];
                            $mensaje_error = $leer_respuesta['msg'];
                            $codigo_error_2 = $leer_respuesta['errors']['cod'];
                            $mensaje_error_2 = $leer_respuesta['errors']['msg'];
                            $estado_sunat = $codigo_error.' // '.$mensaje_error.'... CodError: '.$codigo_error_2.'. Msg. '.$mensaje_error_2;
                            $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                            $result = 9; //Rechazo al enviar a sunat
                        }else{
                            if(isset($leer_respuesta['codRespuesta'])){
                                $cod_respuesta = $leer_respuesta['codRespuesta'];
                                $hay_cdr = $leer_respuesta['indCdrGenerado'];
                                if($hay_cdr == 0){
                                    //NO HAY CDR
                                    if($cod_respuesta == 98){
                                        $estado_sunat = 'Envio en proceso';
                                        $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                        $result = 100;
                                    }else if($cod_respuesta == 99){
                                        //ENVIO CON ERROR
                                        $numError = $leer_respuesta['error']['numError'];
                                        $desError = $leer_respuesta['error']['desError'];
                                        $estado_sunat = $numError.': '.$desError;
                                        $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                        $result = 10;
                                    }
                                }else if($hay_cdr == 1){
                                    //SI HAY CDR

                                    //DECODIFICAR EL ARCHIVO CDR PARA QUE SEA GUARDADO EN ZIP Y LUEGO LEER SU DESCRIPCION SI ESTÁ ACEPTADO
                                    $arcCdr = $leer_respuesta['arcCdr'];
                                    $cdr = base64_decode($arcCdr); //decodificando el xml
                                    file_put_contents($ruta_archivo_cdr . 'R-' . $nombrezip, $cdr ); //guardo el CDR zip en la carpeta cdr
                                    $zip = new ZipArchive();
                                    if($zip->open($ruta_archivo_cdr. 'R-' . $nombrezip ) === true ) //rpta es identica existe el archivo
                                    {
                                        $zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.xml');
                                        $zip->close();
                                        $ruta_cdr = $ruta_archivo_cdr.'R-' . $nombre . '.XML';
                                        $result = $objventa->guardar_ruta_cdr_guia($id_guia,$ruta_cdr);
                                        if($result == 1){
                                            //INICIO - VERIFICAR RESPUESTA DEL CDR
                                            if($cod_respuesta == 0){
                                                //ENVIO OK
                                                $xml_cdr = simplexml_load_file($ruta_cdr);
                                                $xml_cdr->registerXPathNamespace('c', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

                                                $DocumentResponse = array();

                                                $ReferenceID    = $xml_cdr->xpath('///c:ReferenceID');
                                                $ResponseCode   = $xml_cdr->xpath('///c:ResponseCode');
                                                $Description    = $xml_cdr->xpath('///c:Description');
                                                $DocumentDescription    = $xml_cdr->xpath('///c:DocumentDescription');
                                                $Notes          = $xml_cdr->xpath('///c:Note');

                                                $DocumentResponse['RefenceID']      = (string)$ReferenceID[0];
                                                $DocumentResponse['ResponseCode']   = (string)$ResponseCode[0];
                                                $DocumentResponse['Description']    = (string)$Description[0];
                                                $DocumentResponse['DocumentDescription']    = (string)$DocumentDescription[0];

                                                if(count($Notes) > 0){
                                                    foreach ($Notes as $note){
                                                        $DocumentResponse['Notes'][] = (string)$Notes[0];
                                                    }
                                                }
                                                //FIN - VERIFICAR RESPUESTA DEL CDR

                                                $estado_sunat = $DocumentResponse['Description'];
                                                $link_pdf = $DocumentResponse['DocumentDescription'];
                                                $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                                $objventa->guardar_repuesta_guia_pdf($id_guia, $link_pdf);

                                            }else if($cod_respuesta == 99){
                                                //ENVIO CON ERROR
                                                $numError = $leer_respuesta['error']['numError'];
                                                $desError = $leer_respuesta['error']['desError'];
                                                $estado_sunat = $numError.': '.$desError;
                                                $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                                $result = 10;
                                            }
                                        }
                                        //$estadofe = '1';
                                        //echo 'Procesado correctamente, OK';
                                    }
                                }

                            }else{
                                $estado_sunat = 'No hubo conexión';
                                $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                $result = 7; //no obtiene respuesta y termina la operacion
                            }
                        }

                        //FIN - si se obtiene el ticket se tiene que consultar su validez
                    }
                }



            }

        }
        return $result;

    }
    public function Consultar_ticket_GuiaRemision($emisor,$nombre, $rutacertificado, $ruta_archivo_xml, $ruta_archivo_cdr, $guia)
    {
        $objventa = new Ventas();
        $result = 1;
        $flg_firma = "0";
        //$ruta_archivo_xml = "xml/";
        $ruta = $ruta_archivo_xml.$nombre.'.XML';
        $id_guia = $guia->id_guia;
        $num_ticket = $guia->guia_remision_numTicket;

        $nombrezip = $nombre.".ZIP";
        $rutazip = $ruta_archivo_xml . $nombre.".ZIP";

        if($result == 1){

            //INICIO - DATOS PARA DE IDENTIFICACION PARA ENVIAR GRE
            $empresa_id = $emisor->empresa_gre_id;
            $empresa_clave = $emisor->empresa_gre_clave;
            $api_token = "https://api-seguridad.sunat.gob.pe/v1/clientessol/$empresa_id/oauth2/token/";

            $body = 'grant_type=password&scope=https%3A%2F%2Fapi-cpe.sunat.gob.pe&client_id='.$empresa_id."&client_secret=".$empresa_clave."&username=".$emisor->empresa_ruc.$emisor->empresa_usuario_sol."&password=".$emisor->empresa_clave_sol;
            //INICIO - OBTENER EL TOKEN

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $api_token,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Cookie: TS019e7fc2=014dc399cbec395515f87110c4b4072f91cbfc5383d35b7025aaf4e6d64ebeba3d2162e14870599f2b76d143077f70dbcf62596481'
                ),
            ));

            $respuesta = curl_exec($curl);

            curl_close($curl);
            $leer_respuesta = json_decode($respuesta, true);
            if(isset($leer_respuesta['cod'])){
                $codigo_error = $leer_respuesta['cod'];
                $mensaje_error = $leer_respuesta['msg'];
                $estado_sunat = $codigo_error.' // '.$mensaje_error;
                $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                $result = 6; //erro al obtener el token
            }else{
                if(isset($leer_respuesta['access_token'])){
                    $access_token = $leer_respuesta['access_token'];
                    $token_type = $leer_respuesta['token_type'];
                    $expires_in = $leer_respuesta['expires_in'];
                    $result = 1; //sigue el curso
                    $autorizacion = "Bearer ".$access_token;
                }else{
                    $estado_sunat = 'No hubo conexión';
                    $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                    $result = 7; //no obtiene respuesta y termina la operacion
                }
            }
            //FIN - OBTENER EL TOKEN
            //FIN - DATOS PARA DE IDENTIFICACION PARA ENVIAR GRE
            if($result == 1){


                if($result == 1){
                    //INICIO - si se obtiene el ticket se tiene que consultar su validez
                    $parametro = '?numTicket='.$num_ticket;
                    $url_consul = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/".$num_ticket.$parametro;

                    //$body = array('archivo.nomArchivo='.$nombrezip.'&archivo.arcGreZip='.$contenido_del_zip);
                    $body_consul = array();

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url_consul,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_POSTFIELDS => $body_consul,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: '.$autorizacion
                        ),
                    ));

                    $resp_consul = curl_exec($curl);

                    curl_close($curl);
                    $leer_respuesta = json_decode($resp_consul, true);
                    if(isset($leer_respuesta['cod'])){
                        $codigo_error = $leer_respuesta['cod'];
                        $mensaje_error = $leer_respuesta['msg'];
                        $codigo_error_2 = $leer_respuesta['errors']['cod'];
                        $mensaje_error_2 = $leer_respuesta['errors']['msg'];
                        $estado_sunat = $codigo_error.' // '.$mensaje_error.'... CodError: '.$codigo_error_2.'. Msg. '.$mensaje_error_2;
                        $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                        $result = 9; //Rechazo al enviar a sunat
                    }else{
                        if(isset($leer_respuesta['codRespuesta'])){
                            $cod_respuesta = $leer_respuesta['codRespuesta'];
                            $hay_cdr = $leer_respuesta['indCdrGenerado'];
                            if($hay_cdr == 0){
                                //NO HAY CDR
                                if($cod_respuesta == 98){
                                    $estado_sunat = 'Envio en proceso';
                                    $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                    $result = 100;
                                }else if($cod_respuesta == 99){
                                    //ENVIO CON ERROR
                                    $numError = $leer_respuesta['error']['numError'];
                                    $desError = $leer_respuesta['error']['desError'];
                                    $estado_sunat = $numError.': '.$desError;
                                    $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                    $result = 10;
                                }
                            }else if($hay_cdr == 1){
                                //SI HAY CDR

                                //DECODIFICAR EL ARCHIVO CDR PARA QUE SEA GUARDADO EN ZIP Y LUEGO LEER SU DESCRIPCION SI ESTÁ ACEPTADO
                                $arcCdr = $leer_respuesta['arcCdr'];
                                $cdr = base64_decode($arcCdr); //decodificando el xml
                                file_put_contents($ruta_archivo_cdr . 'R-' . $nombrezip, $cdr ); //guardo el CDR zip en la carpeta cdr
                                $zip = new ZipArchive();
                                if($zip->open($ruta_archivo_cdr. 'R-' . $nombrezip ) === true ) //rpta es identica existe el archivo
                                {
                                    $zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.xml');
                                    $zip->close();
                                    $ruta_cdr = $ruta_archivo_cdr.'R-' . $nombre . '.XML';
                                    $result = $objventa->guardar_ruta_cdr_guia($id_guia,$ruta_cdr);
                                    if($result == 1){
                                        //INICIO - VERIFICAR RESPUESTA DEL CDR
                                        if($cod_respuesta == 0){
                                            //ENVIO OK
                                            $xml_cdr = simplexml_load_file($ruta_cdr);
                                            $xml_cdr->registerXPathNamespace('c', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

                                            $DocumentResponse = array();

                                            $ReferenceID    = $xml_cdr->xpath('///c:ReferenceID');
                                            $ResponseCode   = $xml_cdr->xpath('///c:ResponseCode');
                                            $Description    = $xml_cdr->xpath('///c:Description');
                                            $DocumentDescription    = $xml_cdr->xpath('///c:DocumentDescription');
                                            $Notes          = $xml_cdr->xpath('///c:Note');

                                            $DocumentResponse['RefenceID']      = (string)$ReferenceID[0];
                                            $DocumentResponse['ResponseCode']   = (string)$ResponseCode[0];
                                            $DocumentResponse['Description']    = (string)$Description[0];
                                            $DocumentResponse['DocumentDescription']    = (string)$DocumentDescription[0];

                                            if(count($Notes) > 0){
                                                foreach ($Notes as $note){
                                                    $DocumentResponse['Notes'][] = (string)$Notes[0];
                                                }
                                            }
                                            //FIN - VERIFICAR RESPUESTA DEL CDR

                                            $estado_sunat = $DocumentResponse['Description'];
                                            $link_pdf = $DocumentResponse['DocumentDescription'];
                                            $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                            $objventa->guardar_repuesta_guia_pdf($id_guia, $link_pdf);

                                        }else if($cod_respuesta == 99){
                                            //ENVIO CON ERROR
                                            $numError = $leer_respuesta['error']['numError'];
                                            $desError = $leer_respuesta['error']['desError'];
                                            $estado_sunat = $numError.': '.$desError;
                                            $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                                            $result = 10;
                                        }
                                    }
                                    //$estadofe = '1';
                                    //echo 'Procesado correctamente, OK';
                                }
                            }

                        }else{
                            $estado_sunat = 'No hubo conexión';
                            $objventa->guardar_repuesta_guia($id_guia, $estado_sunat);
                            $result = 7; //no obtiene respuesta y termina la operacion
                        }
                    }

                    //FIN - si se obtiene el ticket se tiene que consultar su validez
                }
            }


        }
        return $result;

    }


}