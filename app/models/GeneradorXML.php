<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 23/04/2021
 * Time: 09:32 a. m.
 */
require 'app/models/cantidad_en_letras.php';

class GeneradorXML
{
    function CrearXMLFactura($nombrexml, $emisor, $cliente, $comprobante, $detalle, $cuotas)
    {

        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';

        $total_letras_ = CantidadEnLetra($comprobante->venta_total);
        //SI EL PRIMERO CARACTER S ESPACIO REEMPLAZAMOS A NADA
        $total_letras = str_replace(' MIL','MIL', $total_letras_);
        $tipo_documento_emisor = "6";
        if($cliente->tipodocumento_codigo==6){
            $razon_social = $cliente->cliente_razonsocial;
        }else{
            $razon_social = $cliente->cliente_nombre;
        }
        $anho = date('Y');
        if($anho == "2021"){
            $icbper = "0.30";
        }elseif($anho == "2022"){
            $icbper = "0.40";
        }else{
            $icbper = "0.50";
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
      <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
         <ext:UBLExtensions>
            <ext:UBLExtension>
               <ext:ExtensionContent />
            </ext:UBLExtension>
         </ext:UBLExtensions>
         <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
         <cbc:CustomizationID>2.0</cbc:CustomizationID>
         <cbc:ID>'.$comprobante->venta_serie.'-'.$comprobante->venta_correlativo.'</cbc:ID>
         <cbc:IssueDate>'.date('Y-m-d', strtotime($comprobante->venta_fecha)).'</cbc:IssueDate>
         <cbc:IssueTime>'.date('H:i:s', strtotime($comprobante->venta_fecha)).'</cbc:IssueTime>
         <cbc:DueDate>'.date('Y-m-d', strtotime($comprobante->venta_fecha)).'</cbc:DueDate>
         <cbc:InvoiceTypeCode listID="0101">'.$comprobante->venta_tipo.'</cbc:InvoiceTypeCode>
         <cbc:Note languageLocaleID="1000"><![CDATA['.$total_letras.']]></cbc:Note>
         <cbc:DocumentCurrencyCode>'.$comprobante->abrstandar.'</cbc:DocumentCurrencyCode>
         <cac:Signature>
            <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
            <cbc:Note><![CDATA['.$emisor->empresa_nombrecomercial.']]></cbc:Note>
            <cac:SignatoryParty>
               <cac:PartyIdentification>
                  <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA['.$emisor->empresa_razon_social.']]></cbc:Name>
               </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
               <cac:ExternalReference>
                  <cbc:URI>#SIGN-EMPRESA</cbc:URI>
               </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
         </cac:Signature>
         <cac:AccountingSupplierParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="'.$tipo_documento_emisor.'">'.$emisor->empresa_ruc.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA['.$emisor->empresa_nombrecomercial.']]></cbc:Name>
               </cac:PartyName>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA['.$emisor->empresa_razon_social.']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cbc:ID>'.$emisor->empresa_ubigeo.'</cbc:ID>
                     <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                     <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                     <cbc:CityName>'.$emisor->empresa_provincia.'</cbc:CityName>
                     <cbc:CountrySubentity>'.$emisor->empresa_departamento.'</cbc:CountrySubentity>
                     <cbc:District>'.$emisor->empresa_distrito.'</cbc:District>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA['.$emisor->empresa_domiciliofiscal.']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>'.$emisor->empresa_pais.'</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingSupplierParty>
         <cac:AccountingCustomerParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="'.$cliente->tipodocumento_codigo.'">'.$cliente->cliente_numero.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA['.$razon_social.']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA['.$cliente->cliente_direccion.']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>PE</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingCustomerParty>';
        //tipo de pago
        /*$xml.='<cac:PaymentTerms>
                    <cbc:ID>FormaPago</cbc:ID>
                    <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                   </cac:PaymentTerms>';*/

        if($comprobante->venta_forma_pago != "CREDITO"){
            $xml.='<cac:PaymentTerms>
                    <cbc:ID>FormaPago</cbc:ID>
                    <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                   </cac:PaymentTerms>';
        }else{
            $xml.='<cac:PaymentTerms>
                        <cbc:ID>FormaPago</cbc:ID>
                        <cbc:PaymentMeansID>Credito</cbc:PaymentMeansID>
                        <cbc:Amount currencyID="PEN">'.$comprobante->venta_total.'</cbc:Amount>
                     </cac:PaymentTerms>';

            foreach ($cuotas as $c){
                $numero = $c->venta_cuota_numero;
                $xml.= '<cac:PaymentTerms>
                        <cbc:ID>FormaPago</cbc:ID>
                        <cbc:PaymentMeansID>Cuota'. str_pad($numero, 3, "00", STR_PAD_LEFT).'</cbc:PaymentMeansID>
                        <cbc:Amount currencyID="PEN">'.$c->venta_cuota_importe.'</cbc:Amount>
                        <cbc:PaymentDueDate>'.date('Y-m-d', strtotime($c->venta_cuota_fecha)).'</cbc:PaymentDueDate>
                    </cac:PaymentTerms>';
            }
        }
        if($comprobante->venta_descuento_global > 0){
            $importe_inicial = $comprobante->venta_total + $comprobante->venta_totaldescuento;
            $xml.= '<cac:AllowanceCharge>
                        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                            <cbc:AllowanceChargeReasonCode>02</cbc:AllowanceChargeReasonCode>
                                <cbc:MultiplierFactorNumeric>'.$comprobante->venta_descuento_global.'</cbc:MultiplierFactorNumeric>
                            <cbc:Amount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totaldescuento.'</cbc:Amount>
                        <cbc:BaseAmount currencyID="'.$comprobante->abrstandar.'">'.number_format($importe_inicial, 2).'</cbc:BaseAmount>
                     </cac:AllowanceCharge>';
        }
        $total_impuesto= $comprobante->venta_totaligv + $comprobante->venta_icbper;

        $xml.='<cac:TaxTotal>
            <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.number_format($total_impuesto, 2).'</cbc:TaxAmount>';

        if($comprobante->venta_totalgravada>0){
            $xml.='<cac:TaxSubtotal>
               <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalgravada.'</cbc:TaxableAmount>
               <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totaligv.'</cbc:TaxAmount>
               <cac:TaxCategory>
                  <cac:TaxScheme>
                     <cbc:ID>1000</cbc:ID>
                     <cbc:Name>IGV</cbc:Name>
                     <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                  </cac:TaxScheme>
               </cac:TaxCategory>
            </cac:TaxSubtotal>';
        }

        if($comprobante->venta_totalexonerada>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalexonerada.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                        <cbc:Name>EXO</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }

        if($comprobante->venta_totalinafecta>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalinafecta.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                        <cbc:Name>INA</cbc:Name>
                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }
        if($comprobante->venta_totalgratuita>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalgratuita.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9996</cbc:ID>
                        <cbc:Name>GRA</cbc:Name>
                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }
        if($comprobante->venta_icbper>0){
            $xml.='<cac:TaxSubtotal>
                      <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_icbper.'</cbc:TaxAmount>
                      <cac:TaxCategory>
                         <cac:TaxScheme>
                            <cbc:ID>7152</cbc:ID>
                            <cbc:Name>ICBPER</cbc:Name>
                            <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                         </cac:TaxScheme>
                      </cac:TaxCategory>
                   </cac:TaxSubtotal>';
        }

        $total_antes_de_impuestos = $comprobante->venta_totalgravada+$comprobante->venta_totalexonerada+$comprobante->venta_totalinafecta;

        $xml.='</cac:TaxTotal>
         <cac:LegalMonetaryTotal>
            <cbc:LineExtensionAmount currencyID="'.$comprobante->abrstandar.'">'.$total_antes_de_impuestos.'</cbc:LineExtensionAmount>
            <cbc:TaxInclusiveAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_total.'</cbc:TaxInclusiveAmount>
            <cbc:PayableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_total.'</cbc:PayableAmount>
         </cac:LegalMonetaryTotal>';
        $item = 1;
        foreach($detalle as $v){

            $xml.='<cac:InvoiceLine>
               <cbc:ID>'.$item.'</cbc:ID>
               <cbc:InvoicedQuantity unitCode="'.$v->medida_codigo_unidad.'">'.$v->venta_detalle_cantidad.'</cbc:InvoicedQuantity>
               <cbc:LineExtensionAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_total.'</cbc:LineExtensionAmount>
               <cac:PricingReference>';
            if($v->venta_detalle_descuento > 0){

                $precio_producto = $v->venta_detalle_importe_total / $v->venta_detalle_cantidad;
            }else{
                $precio_producto = $v->venta_detalle_precio_unitario;
            }
            if($v->codigo == "21"){
                $xml.= '<cac:AlternativeConditionPrice>
                     <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">'.round($precio_producto,2).'</cbc:PriceAmount>
                     <cbc:PriceTypeCode>02</cbc:PriceTypeCode>
                  </cac:AlternativeConditionPrice>';
            }else {
                $xml .= '<cac:AlternativeConditionPrice>
                     <cbc:PriceAmount currencyID="' . $comprobante->abrstandar . '">' . round($precio_producto,2) . '</cbc:PriceAmount>
                     <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
                  </cac:AlternativeConditionPrice>';
            }
            $xml.= '</cac:PricingReference>';
            if($v->venta_detalle_descuento > 0){

                $total_item = $v->venta_detalle_importe_total + $v->venta_detalle_descuento;
                $porcentaje_descuento_item = $v->venta_detalle_descuento / $total_item;
                $xml.= '<cac:AllowanceCharge>
                            <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                            <cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>
                            <cbc:MultiplierFactorNumeric>'.round($porcentaje_descuento_item,2).'</cbc:MultiplierFactorNumeric>
                            <cbc:Amount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_descuento.'</cbc:Amount>
                            <cbc:BaseAmount currencyID="'.$comprobante->abrstandar.'">'.$total_item.'</cbc:BaseAmount>
                         </cac:AllowanceCharge>';
            }

            $xml.= '<cac:TaxTotal>
                      <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_total_igv.'</cbc:TaxAmount>
                      <cac:TaxSubtotal>
                         <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_total.'</cbc:TaxableAmount>
                         <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_total_igv.'</cbc:TaxAmount>
                         <cac:TaxCategory>
                            <cbc:Percent>'.$v->venta_detalle_porcentaje_igv.'</cbc:Percent>
                            <cbc:TaxExemptionReasonCode>'.$v->producto_precio_codigoafectacion.'</cbc:TaxExemptionReasonCode>
                            <cac:TaxScheme>
                               <cbc:ID>'.$v->codigo_afectacion.'</cbc:ID>
                               <cbc:Name>'.$v->nombre_afectacion.'</cbc:Name>
                               <cbc:TaxTypeCode>'.$v->tipo_afectacion.'</cbc:TaxTypeCode>
                            </cac:TaxScheme>
                         </cac:TaxCategory>
                      </cac:TaxSubtotal>';

            if($v->venta_detalle_nombre_producto == "BOLSA PLASTICA"){
                $total_impuesto_bolsa = number_format($v->venta_detalle_cantidad * $icbper, 2);
                $xml.= '<cac:TaxSubtotal>
                            <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$total_impuesto_bolsa.'</cbc:TaxAmount>
                            <cbc:BaseUnitMeasure unitCode="'.$v->medida_codigo_unidad.'">'.$v->venta_detalle_cantidad.'</cbc:BaseUnitMeasure>
                        <cbc:PerUnitAmount currencyID="PEN">'.$icbper.'</cbc:PerUnitAmount>
                        <cac:TaxCategory>
                            <cac:TaxScheme>
                                <cbc:ID>7152</cbc:ID>
                                <cbc:Name>ICBPER</cbc:Name>
                                <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                            </cac:TaxScheme>
                        </cac:TaxCategory>
                        </cac:TaxSubtotal>';
            }

            $xml.=
               '</cac:TaxTotal>';

            $xml.= '<cac:Item>
                      <cbc:Description><![CDATA['.$v->venta_detalle_nombre_producto.']]></cbc:Description>
                      <cac:SellersItemIdentification>
                         <cbc:ID>'.$v->id_producto_precio.'</cbc:ID>
                      </cac:SellersItemIdentification>
                   </cac:Item>';

            if($v->codigo == "21"){
                $xml.= '<cac:Price>
                  <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:PriceAmount>
               </cac:Price>
            </cac:InvoiceLine>';
            }else{
                $xml.= '<cac:Price>
                  <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_unitario.'</cbc:PriceAmount>
               </cac:Price>
            </cac:InvoiceLine>';
            }

            $item++;
        }

        $xml.="</Invoice>";

        $doc->loadXML($xml);
        $doc->save($nombrexml.'.XML');
    }

    function CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $comprobante, $detalle, $descripcion_nota)
    {
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';

        $total_letras = CantidadEnLetra($comprobante->venta_total);
        if($cliente->tipodocumento_codigo==6){
            $razon_social = $cliente->cliente_razonsocial;
        }else{
            $razon_social = $cliente->cliente_nombre;
        }
        $anho = date('Y');
        if($anho == "2021"){
            $icbper = "0.30";
        }elseif($anho == "2022"){
            $icbper = "0.40";
        }else{
            $icbper = "0.50";
        }


        $xml = '<?xml version="1.0" encoding="UTF-8"?>
      <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
         <ext:UBLExtensions>
            <ext:UBLExtension>
               <ext:ExtensionContent />
            </ext:UBLExtension>
         </ext:UBLExtensions>
         <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
         <cbc:CustomizationID>2.0</cbc:CustomizationID>
         <cbc:ID>'.$comprobante->venta_serie.'-'.$comprobante->venta_correlativo.'</cbc:ID>
         <cbc:IssueDate>'.date('Y-m-d', strtotime($comprobante->venta_fecha)).'</cbc:IssueDate>
         <cbc:IssueTime>'.date('H:i:s', strtotime($comprobante->venta_fecha)).'</cbc:IssueTime>
         <cbc:Note languageLocaleID="1000"><![CDATA['.$total_letras.']]></cbc:Note>
         <cbc:DocumentCurrencyCode>'.$comprobante->abrstandar.'</cbc:DocumentCurrencyCode>
         <cac:DiscrepancyResponse>
            <cbc:ReferenceID>'.$comprobante->serie_modificar.'-'.$comprobante->correlativo_modificar.'</cbc:ReferenceID>
            <cbc:ResponseCode>'.$comprobante->venta_codigo_motivo_nota.'</cbc:ResponseCode>
            <cbc:Description>'.$descripcion_nota->tipo_nota_descripcion.'</cbc:Description>
         </cac:DiscrepancyResponse>
         <cac:BillingReference>
            <cac:InvoiceDocumentReference>
               <cbc:ID>'.$comprobante->serie_modificar.'-'.$comprobante->correlativo_modificar.'</cbc:ID>
               <cbc:DocumentTypeCode>'.$comprobante->tipo_documento_modificar.'</cbc:DocumentTypeCode>
            </cac:InvoiceDocumentReference>
         </cac:BillingReference>
         <cac:Signature>
            <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
            <cbc:Note><![CDATA['.$emisor->empresa_nombrecomercial.']]></cbc:Note>
            <cac:SignatoryParty>
               <cac:PartyIdentification>
                  <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA['.$emisor->empresa_razon_social.']]></cbc:Name>
               </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
               <cac:ExternalReference>
                  <cbc:URI>#SIGN-EMPRESA</cbc:URI>
               </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
         </cac:Signature>
         <cac:AccountingSupplierParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="6">'.$emisor->empresa_ruc.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA['.$emisor->empresa_nombrecomercial.']]></cbc:Name>
               </cac:PartyName>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA['.$emisor->empresa_razon_social.']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cbc:ID>'.$emisor->empresa_ubigeo.'</cbc:ID>
                     <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                     <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                     <cbc:CityName>'.$emisor->empresa_provincia.'</cbc:CityName>
                     <cbc:CountrySubentity>'.$emisor->empresa_departamento.'</cbc:CountrySubentity>
                     <cbc:District>'.$emisor->empresa_distrito.'</cbc:District>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA['.$emisor->empresa_domiciliofiscal.']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>'.$emisor->empresa_pais.'</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingSupplierParty>
         <cac:AccountingCustomerParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="'.$cliente->tipodocumento_codigo.'">'.$cliente->cliente_numero.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA['.$razon_social.']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA['.$cliente->cliente_direccion.']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>PE</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingCustomerParty>';

        /*$xml.='<cac:PaymentTerms>
                    <cbc:ID>FormaPago</cbc:ID>
                    <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                   </cac:PaymentTerms>';*/

        /*if($comprobante->id_tipo_pago != "5"){
            $xml.='<cac:PaymentTerms>
                    <cbc:ID>FormaPago</cbc:ID>
                    <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                   </cac:PaymentTerms>';
        }else{
            $xml.='<cac:PaymentTerms>
                        <cbc:ID>FormaPago</cbc:ID>
                        <cbc:PaymentMeansID>Credito</cbc:PaymentMeansID>
                        <cbc:Amount currencyID="PEN">'.$comprobante->venta_total.'</cbc:Amount>
                     </cac:PaymentTerms>
                            
                     <cac:PaymentTerms>
                        <cbc:ID>FormaPago</cbc:ID>
                        <cbc:PaymentMeansID>Cuota001</cbc:PaymentMeansID>
                        <cbc:Amount currencyID="PEN">'.$comprobante->venta_total.'</cbc:Amount>
                        <cbc:PaymentDueDate>'.date('Y-m-d').'</cbc:PaymentDueDate>
                    </cac:PaymentTerms>';
        }*/

        $xml.='<cac:TaxTotal>
            <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totaligv.'</cbc:TaxAmount>
            <cac:TaxSubtotal>
               <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalgravada.'</cbc:TaxableAmount>
               <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totaligv.'</cbc:TaxAmount>
               <cac:TaxCategory>
                  <cac:TaxScheme>
                     <cbc:ID>1000</cbc:ID>
                     <cbc:Name>IGV</cbc:Name>
                     <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                  </cac:TaxScheme>
               </cac:TaxCategory>
            </cac:TaxSubtotal>';

        if($comprobante->venta_totalexonerada>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalexonerada.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                        <cbc:Name>EXO</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }
        if($comprobante->venta_totalinafecta>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalinafecta.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                        <cbc:Name>INA</cbc:Name>
                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }
        if($comprobante->venta_totalgratuita>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalgratuita.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9996</cbc:ID>
                        <cbc:Name>GRA</cbc:Name>
                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }
        if($comprobante->venta_icbper>0){
            $xml.='<cac:TaxSubtotal>
                      <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_icbper.'</cbc:TaxAmount>
                      <cac:TaxCategory>
                         <cac:TaxScheme>
                            <cbc:ID>7152</cbc:ID>
                            <cbc:Name>ICBPER</cbc:Name>
                            <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                         </cac:TaxScheme>
                      </cac:TaxCategory>
                   </cac:TaxSubtotal>';
        }

        $total_antes_de_impuestos = $comprobante->venta_totalgravada+$comprobante->venta_totalexonerada+$comprobante->venta_totalinafecta;

        $xml.='</cac:TaxTotal>
         <cac:LegalMonetaryTotal>
            <cbc:LineExtensionAmount currencyID="'.$comprobante->abrstandar.'">'.$total_antes_de_impuestos.'</cbc:LineExtensionAmount>
            <cbc:TaxInclusiveAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_total.'</cbc:TaxInclusiveAmount>
            <cbc:PayableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_total.'</cbc:PayableAmount>
         </cac:LegalMonetaryTotal>';
        $item = 1;

        foreach($detalle as $v){

            $xml.='<cac:CreditNoteLine>
               <cbc:ID>'.$item.'</cbc:ID>
               <cbc:CreditedQuantity unitCode="'.$v->medida_codigo_unidad.'">'.$v->venta_detalle_cantidad.'</cbc:CreditedQuantity>
               <cbc:LineExtensionAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_total.'</cbc:LineExtensionAmount>
               <cac:PricingReference>';
            if($v->codigo == "21"){
                $xml.= '<cac:AlternativeConditionPrice>
                     <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_precio_unitario.'</cbc:PriceAmount>
                     <cbc:PriceTypeCode>02</cbc:PriceTypeCode>
                  </cac:AlternativeConditionPrice>';
            }else {
                $xml .= '<cac:AlternativeConditionPrice>
                     <cbc:PriceAmount currencyID="' . $comprobante->abrstandar . '">' . $v->venta_detalle_precio_unitario . '</cbc:PriceAmount>
                     <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
                  </cac:AlternativeConditionPrice>';
            }
            $xml.= '</cac:PricingReference>
               <cac:TaxTotal>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_total_igv.'</cbc:TaxAmount>
                  <cac:TaxSubtotal>
                     <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_total.'</cbc:TaxableAmount>
                     <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_total_igv.'</cbc:TaxAmount>
                     <cac:TaxCategory>
                        <cbc:Percent>'.$v->venta_detalle_porcentaje_igv.'</cbc:Percent>
                        <cbc:TaxExemptionReasonCode>'.$v->producto_precio_codigoafectacion.'</cbc:TaxExemptionReasonCode>
                        <cac:TaxScheme>
                           <cbc:ID>'.$v->codigo_afectacion.'</cbc:ID>
                           <cbc:Name>'.$v->nombre_afectacion.'</cbc:Name>
                           <cbc:TaxTypeCode>'.$v->tipo_afectacion.'</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                     </cac:TaxCategory>
                  </cac:TaxSubtotal>';

            if($v->id_receta == "131"){
                $total_impuesto_bolsa = number_format($v->venta_detalle_cantidad * $icbper, 2);
                $xml.= '<cac:TaxSubtotal>
                            <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$total_impuesto_bolsa.'</cbc:TaxAmount>
                            <cbc:BaseUnitMeasure unitCode="'.$v->medida_codigo_unidad.'">'.$v->venta_detalle_cantidad.'</cbc:BaseUnitMeasure>
                        <cbc:PerUnitAmount currencyID="PEN">'.$icbper.'</cbc:PerUnitAmount>
                        <cac:TaxCategory>
                            <cac:TaxScheme>
                                <cbc:ID>7152</cbc:ID>
                                <cbc:Name>ICBPER</cbc:Name>
                                <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                            </cac:TaxScheme>
                        </cac:TaxCategory>
                        </cac:TaxSubtotal>';
            }

            $xml.=
               '</cac:TaxTotal>';

            $xml.= '<cac:Item>
                      <cbc:Description><![CDATA['.$v->venta_detalle_nombre_producto.']]></cbc:Description>
                      <cac:SellersItemIdentification>
                         <cbc:ID>'.$v->id_producto_precio.'</cbc:ID>
                      </cac:SellersItemIdentification>
                   </cac:Item>';

            if($v->codigo == "21"){
                $xml.= '<cac:Price>
                  <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:PriceAmount>
               </cac:Price>
            </cac:CreditNoteLine>';
            }else{
                $xml.= '<cac:Price>
                  <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_unitario.'</cbc:PriceAmount>
               </cac:Price>
            </cac:CreditNoteLine>';
            }

            $item++;
        }
        $xml.='</CreditNote>';

        $doc->loadXML($xml);
        $doc->save($nombrexml.'.XML');
    }

    function CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $comprobante, $detalle, $descripcion_nota)
    {

        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';

        $total_letras = CantidadEnLetra($comprobante->venta_total);
        if($cliente->tipodocumento_codigo==6){
            $razon_social = $cliente->cliente_razonsocial;
        }else{
            $razon_social = $cliente->cliente_nombre;
        }
        $anho = date('Y');
        if($anho == "2021"){
            $icbper = "0.30";
        }elseif($anho == "2022"){
            $icbper = "0.40";
        }else{
            $icbper = "0.50";
        }


        $xml = '<?xml version="1.0" encoding="UTF-8"?>
      <DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
         <ext:UBLExtensions>
            <ext:UBLExtension>
               <ext:ExtensionContent />
            </ext:UBLExtension>
         </ext:UBLExtensions>
         <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
         <cbc:CustomizationID>2.0</cbc:CustomizationID>
         <cbc:ID>'.$comprobante->venta_serie.'-'.$comprobante->venta_correlativo.'</cbc:ID>
         <cbc:IssueDate>'.date('Y-m-d', strtotime($comprobante->venta_fecha)).'</cbc:IssueDate>
         <cbc:IssueTime>'.date('H:i:s', strtotime($comprobante->venta_fecha)).'</cbc:IssueTime>
         <cbc:Note languageLocaleID="1000"><![CDATA['.$total_letras.']]></cbc:Note>
         <cbc:DocumentCurrencyCode>'.$comprobante->abrstandar.'</cbc:DocumentCurrencyCode>
         <cac:DiscrepancyResponse>
            <cbc:ReferenceID>'.$comprobante->serie_modificar.'-'.$comprobante->correlativo_modificar.'</cbc:ReferenceID>
            <cbc:ResponseCode>'.$comprobante->venta_codigo_motivo_nota.'</cbc:ResponseCode>
            <cbc:Description>'.$descripcion_nota->tipo_nota_descripcion.'</cbc:Description>
         </cac:DiscrepancyResponse>
         <cac:BillingReference>
            <cac:InvoiceDocumentReference>
               <cbc:ID>'.$comprobante->serie_modificar.'-'.$comprobante->correlativo_modificar.'</cbc:ID>
               <cbc:DocumentTypeCode>'.$comprobante->tipo_documento_modificar.'</cbc:DocumentTypeCode>
            </cac:InvoiceDocumentReference>
         </cac:BillingReference>
         <cac:Signature>
            <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
            <cbc:Note><![CDATA['.$emisor->empresa_nombrecomercial.']]></cbc:Note>
            <cac:SignatoryParty>
               <cac:PartyIdentification>
                  <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA['.$emisor->empresa_razon_social.']]></cbc:Name>
               </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
               <cac:ExternalReference>
                  <cbc:URI>#SIGN-EMPRESA</cbc:URI>
               </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
         </cac:Signature>
         <cac:AccountingSupplierParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="6">'.$emisor->empresa_ruc.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA['.$emisor->empresa_nombrecomercial.']]></cbc:Name>
               </cac:PartyName>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA['.$emisor->empresa_razon_social.']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cbc:ID>'.$emisor->empresa_ubigeo.'</cbc:ID>
                     <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                     <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                     <cbc:CityName>'.$emisor->empresa_provincia.'</cbc:CityName>
                     <cbc:CountrySubentity>'.$emisor->empresa_departamento.'</cbc:CountrySubentity>
                     <cbc:District>'.$emisor->empresa_distrito.'</cbc:District>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA['.$emisor->empresa_domiciliofiscal.']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>'.$emisor->empresa_pais.'</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingSupplierParty>
         <cac:AccountingCustomerParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="'.$cliente->tipodocumento_codigo.'">'.$cliente->cliente_numero.'</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA['.$razon_social.']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA['.$cliente->cliente_direccion.']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>PE</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingCustomerParty>';

        /*$xml.='<cac:PaymentTerms>
                    <cbc:ID>FormaPago</cbc:ID>
                    <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                   </cac:PaymentTerms>';*/

        /*if($comprobante->id_tipo_pago != "5"){
            $xml.='<cac:PaymentTerms>
                    <cbc:ID>FormaPago</cbc:ID>
                    <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                   </cac:PaymentTerms>';
        }else{
            $xml.='<cac:PaymentTerms>
                        <cbc:ID>FormaPago</cbc:ID>
                        <cbc:PaymentMeansID>Credito</cbc:PaymentMeansID>
                        <cbc:Amount currencyID="PEN">'.$comprobante->venta_total.'</cbc:Amount>
                     </cac:PaymentTerms>
                            
                     <cac:PaymentTerms>
                        <cbc:ID>FormaPago</cbc:ID>
                        <cbc:PaymentMeansID>Cuota001</cbc:PaymentMeansID>
                        <cbc:Amount currencyID="PEN">'.$comprobante->venta_total.'</cbc:Amount>
                        <cbc:PaymentDueDate>'.date('Y-m-d').'</cbc:PaymentDueDate>
                    </cac:PaymentTerms>';
        }*/

        $xml.='<cac:TaxTotal>
                <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totaligv.'</cbc:TaxAmount>';
        $xml .= '<cac:TaxSubtotal>
                   <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalgravada.'</cbc:TaxableAmount>
                   <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totaligv.'</cbc:TaxAmount>
                   <cac:TaxCategory>
                      <cac:TaxScheme>
                         <cbc:ID>1000</cbc:ID>
                         <cbc:Name>IGV</cbc:Name>
                         <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                      </cac:TaxScheme>
                   </cac:TaxCategory>
                </cac:TaxSubtotal>';
        /*if($comprobante->venta_totalgravada>0){
            $xml .= '<cac:TaxSubtotal>
                   <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalgravada.'</cbc:TaxableAmount>
                   <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totaligv.'</cbc:TaxAmount>
                   <cac:TaxCategory>
                      <cac:TaxScheme>
                         <cbc:ID>1000</cbc:ID>
                         <cbc:Name>IGV</cbc:Name>
                         <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                      </cac:TaxScheme>
                   </cac:TaxCategory>
                </cac:TaxSubtotal>';
        }*/

        if($comprobante->venta_totalexonerada>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalexonerada.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                        <cbc:Name>EXO</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }
        if($comprobante->venta_totalinafecta>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalinafecta.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                        <cbc:Name>INA</cbc:Name>
                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }
        if($comprobante->venta_totalgratuita>0){
            $xml.='<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_totalgratuita.'</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9996</cbc:ID>
                        <cbc:Name>GRA</cbc:Name>
                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
        }
        if($comprobante->venta_icbper>0){
            $xml.='<cac:TaxSubtotal>
                      <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_icbper.'</cbc:TaxAmount>
                      <cac:TaxCategory>
                         <cac:TaxScheme>
                            <cbc:ID>7152</cbc:ID>
                            <cbc:Name>ICBPER</cbc:Name>
                            <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                         </cac:TaxScheme>
                      </cac:TaxCategory>
                   </cac:TaxSubtotal>';
        }

        $total_antes_de_impuestos = $comprobante->venta_totalgravada+$comprobante->venta_totalexonerada+$comprobante->venta_totalinafecta;

        $xml.='</cac:TaxTotal>
         <cac:RequestedMonetaryTotal>
            
            <cbc:PayableAmount currencyID="'.$comprobante->abrstandar.'">'.$comprobante->venta_total.'</cbc:PayableAmount>
         </cac:RequestedMonetaryTotal>';
        $item = 1;

        foreach($detalle as $v){

            $xml.='<cac:DebitNoteLine>
               <cbc:ID>'.$item.'</cbc:ID>
               <cbc:DebitedQuantity unitCode="'.$v->medida_codigo_unidad.'">'.$v->venta_detalle_cantidad.'</cbc:DebitedQuantity>
               <cbc:LineExtensionAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_total.'</cbc:LineExtensionAmount>
               <cac:PricingReference>';
            if($v->codigo == "21"){
                $xml.= '<cac:AlternativeConditionPrice>
                     <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_precio_unitario.'</cbc:PriceAmount>
                     <cbc:PriceTypeCode>02</cbc:PriceTypeCode>
                  </cac:AlternativeConditionPrice>';
            }else {
                $xml .= '<cac:AlternativeConditionPrice>
                     <cbc:PriceAmount currencyID="' . $comprobante->abrstandar . '">' . $v->venta_detalle_precio_unitario . '</cbc:PriceAmount>
                     <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
                  </cac:AlternativeConditionPrice>';
            }
            $xml.= '</cac:PricingReference>
               <cac:TaxTotal>
                  <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_total_igv.'</cbc:TaxAmount>
                  <cac:TaxSubtotal>
                     <cbc:TaxableAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_total.'</cbc:TaxableAmount>
                     <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_total_igv.'</cbc:TaxAmount>
                     <cac:TaxCategory>';
            if($v->producto_precio_codigoafectacion == "10"){
                $xml.= '<cbc:Percent>'.$v->venta_detalle_porcentaje_igv.'</cbc:Percent>';
            }else{
                $xml.= '<cbc:Percent>0.00</cbc:Percent>';
            }
            $xml.= '<cbc:TaxExemptionReasonCode>'.$v->producto_precio_codigoafectacion.'</cbc:TaxExemptionReasonCode>
                        <cac:TaxScheme>
                           <cbc:ID>'.$v->codigo_afectacion.'</cbc:ID>
                           <cbc:Name>'.$v->nombre_afectacion.'</cbc:Name>
                           <cbc:TaxTypeCode>'.$v->tipo_afectacion.'</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                     </cac:TaxCategory>
                  </cac:TaxSubtotal>';

            if($v->id_receta == "131"){
                $total_impuesto_bolsa = number_format($v->venta_detalle_cantidad * $icbper, 2);
                $xml.= '<cac:TaxSubtotal>
                            <cbc:TaxAmount currencyID="'.$comprobante->abrstandar.'">'.$total_impuesto_bolsa.'</cbc:TaxAmount>
                            <cbc:BaseUnitMeasure unitCode="'.$v->medida_codigo_unidad.'">'.$v->venta_detalle_cantidad.'</cbc:BaseUnitMeasure>
                        <cbc:PerUnitAmount currencyID="PEN">'.$icbper.'</cbc:PerUnitAmount>
                        <cac:TaxCategory>
                            <cac:TaxScheme>
                                <cbc:ID>7152</cbc:ID>
                                <cbc:Name>ICBPER</cbc:Name>
                                <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                            </cac:TaxScheme>
                        </cac:TaxCategory>
                        </cac:TaxSubtotal>';
            }

            $xml.=
                '</cac:TaxTotal>';

            $xml.= '<cac:Item>
                      <cbc:Description><![CDATA['.$v->venta_detalle_nombre_producto.']]></cbc:Description>
                      <cac:SellersItemIdentification>
                         <cbc:ID>'.$v->id_producto_precio.'</cbc:ID>
                      </cac:SellersItemIdentification>
                   </cac:Item>';

            if($v->codigo == "21"){
                $xml.= '<cac:Price>
                  <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">0.00</cbc:PriceAmount>
               </cac:Price>
            </cac:CreditNoteLine>';
            }else{
                $xml.= '<cac:Price>
                  <cbc:PriceAmount currencyID="'.$comprobante->abrstandar.'">'.$v->venta_detalle_valor_unitario.'</cbc:PriceAmount>
               </cac:Price>
            </cac:DebitNoteLine>';
            }

            $item++;
        }
        $xml.='</DebitNote>';

        $doc->loadXML($xml);
        $doc->save($nombrexml.'.XML');
    }

    function CrearXMLResumenDocumentos($emisor, $cabecera, $detalle, $nombrexml, $fecha_emision)
    {
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
           <SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2">
          <ext:UBLExtensions>
              <ext:UBLExtension>
                  <ext:ExtensionContent />
              </ext:UBLExtension>
          </ext:UBLExtensions>
          <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
          <cbc:CustomizationID>1.1</cbc:CustomizationID>
          <cbc:ID>'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'].'</cbc:ID>
          <cbc:ReferenceDate>'.$fecha_emision.'</cbc:ReferenceDate>
          <cbc:IssueDate>'.date('Y-m-d').'</cbc:IssueDate>
          <cac:Signature>
              <cbc:ID>'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'].'</cbc:ID>
              <cac:SignatoryParty>
                  <cac:PartyIdentification>
                      <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyName>
                      <cbc:Name><![CDATA['.$emisor->empresa_razon_social.']]></cbc:Name>
                  </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                  <cac:ExternalReference>
                      <cbc:URI>'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'].'</cbc:URI>
                  </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
          </cac:Signature>
          <cac:AccountingSupplierParty>
              <cbc:CustomerAssignedAccountID>'.$emisor->empresa_ruc.'</cbc:CustomerAssignedAccountID>
              <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
              <cac:Party>
                  <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA['.$emisor->empresa_razon_social.']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
              </cac:Party>
          </cac:AccountingSupplierParty>';
        $item = 1;
        foreach ($detalle as $v) {

            if($v->venta_totalgravada != 0){
                $tipo_total = "01"; //gravada
            }elseif($v->venta_totalexonerada != 0){
                $tipo_total = "02"; //exonerada
            }
            else{
                $tipo_total = "03"; //inafecta
            }
            $xml.='<sac:SummaryDocumentsLine>
                 <cbc:LineID>'.$item.'</cbc:LineID>
                 <cbc:DocumentTypeCode>'.$v->venta_tipo.'</cbc:DocumentTypeCode>
                 <cbc:ID>'.$v->venta_serie.'-'.$v->venta_correlativo.'</cbc:ID>';
            if($v->cliente_numero != '11111111'){
                $xml .= '<cac:AccountingCustomerParty>
                             <cbc:CustomerAssignedAccountID>'.$v->cliente_numero.'</cbc:CustomerAssignedAccountID>
                             <cbc:AdditionalAccountID>'.$v->tipodocumento_codigo.'</cbc:AdditionalAccountID>
                         </cac:AccountingCustomerParty>';
            }
            /*if($v->venta_total > 700){
                $xml .= '<cac:AccountingCustomerParty>
                             <cbc:CustomerAssignedAccountID>'.$v->cliente_numero.'</cbc:CustomerAssignedAccountID>
                             <cbc:AdditionalAccountID>'.$v->tipodocumento_codigo.'</cbc:AdditionalAccountID>
                         </cac:AccountingCustomerParty>';
            }*/

            if($v->venta_tipo == "07" || $v->venta_tipo == "08"){
                $xml .= '<cac:BillingReference>
                         <cac:InvoiceDocumentReference>
                            <cbc:ID>'.$v->serie_modificar.'-'.$v->correlativo_modificar.'</cbc:ID>
                            <cbc:DocumentTypeCode>'.$v->tipo_documento_modificar.'</cbc:DocumentTypeCode>
                         </cac:InvoiceDocumentReference>
                     </cac:BillingReference>';
            }

            $xml.= '<cac:Status>
                    <cbc:ConditionCode>'.$v->venta_condicion_resumen.'</cbc:ConditionCode>
                 </cac:Status>                
                 <sac:TotalAmount currencyID="'.$v->abrstandar.'">'.$v->venta_total.'</sac:TotalAmount>';

            $xml.='<sac:BillingPayment>
                           <cbc:PaidAmount currencyID="'.$v->abrstandar.'">'.$v->venta_totalgravada.'</cbc:PaidAmount>
                           <cbc:InstructionID>01</cbc:InstructionID>
                       </sac:BillingPayment>';

            if($v->venta_totalexonerada != 0){
                $xml.=
                    '<sac:BillingPayment>
                           <cbc:PaidAmount currencyID="'.$v->abrstandar.'">'.$v->venta_totalexonerada.'</cbc:PaidAmount>
                           <cbc:InstructionID>02</cbc:InstructionID>
                       </sac:BillingPayment>';
            }
            if($v->venta_totalinafecta != 0){
                $xml.=
                    '<sac:BillingPayment>
                           <cbc:PaidAmount currencyID="'.$v->abrstandar.'">'.$v->venta_totalinafecta.'</cbc:PaidAmount>
                           <cbc:InstructionID>03</cbc:InstructionID>
                       </sac:BillingPayment>';
            }
            if($v->venta_totalgratuita != 0){
                $xml.=
                    '<sac:BillingPayment>
                           <cbc:PaidAmount currencyID="'.$v->abrstandar.'">'.$v->venta_totalgratuita.'</cbc:PaidAmount>
                           <cbc:InstructionID>05</cbc:InstructionID>
                       </sac:BillingPayment>';
            }

            $xml.='<cac:TaxTotal>
                     <cbc:TaxAmount currencyID="'.$v->abrstandar.'">'.$v->venta_totaligv.'</cbc:TaxAmount>';


            /*if($v->venta_totalexonerada != 0){
                $codigo_afectacion = "9997";
                $nombre_afectacion = "EXO";
                $tipo_afectacion = "VAT";
                $xml.='<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="'.$v->abrstandar.'">'.$v->venta_totalexonerada.'</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>'.$codigo_afectacion.'</cbc:ID>
                                 <cbc:Name>'.$nombre_afectacion.'</cbc:Name>
                                 <cbc:TaxTypeCode>'.$tipo_afectacion.'</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';
            }
            if($v->venta_totalinafecta != 0){
                $codigo_afectacion = "9998";
                $nombre_afectacion = "INA";
                $tipo_afectacion = "FRE";
                $xml.='<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="'.$v->abrstandar.'">'.$v->venta_totalinafecta.'</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>'.$codigo_afectacion.'</cbc:ID>
                                 <cbc:Name>'.$nombre_afectacion.'</cbc:Name>
                                 <cbc:TaxTypeCode>'.$tipo_afectacion.'</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';
            }*/

            $xml.='<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="'.$v->abrstandar.'">'.$v->venta_totaligv.'</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>1000</cbc:ID>
                                 <cbc:Name>IGV</cbc:Name>
                                 <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';

            $xml.='</cac:TaxTotal>
             </sac:SummaryDocumentsLine>';
            $item++;
        }

        $xml.='</SummaryDocuments>';

        $doc->loadXML($xml);
        $doc->save($nombrexml.'.XML');
    }

    function CrearXmlBajaDocumentos($emisor, $cabecera, $detalle, $nombrexml)
    {
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
          <ext:UBLExtensions>
              <ext:UBLExtension>
                  <ext:ExtensionContent />
              </ext:UBLExtension>
          </ext:UBLExtensions>
          <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
          <cbc:CustomizationID>1.0</cbc:CustomizationID>
          <cbc:ID>'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'].'</cbc:ID>
          <cbc:ReferenceDate>'.date('Y-m-d', strtotime($detalle->venta_fecha)).'</cbc:ReferenceDate>
          <cbc:IssueDate>'.date('Y-m-d').'</cbc:IssueDate>
          <cac:Signature>
              <cbc:ID>'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'].'</cbc:ID>
              <cac:SignatoryParty>
                  <cac:PartyIdentification>
                      <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyName>
                      <cbc:Name><![CDATA['.$emisor->empresa_razon_social.']]></cbc:Name>
                  </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                  <cac:ExternalReference>
                      <cbc:URI>'.$cabecera['tipocomp'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'].'</cbc:URI>
                  </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
          </cac:Signature>
          <cac:AccountingSupplierParty>
              <cbc:CustomerAssignedAccountID>'.$emisor->empresa_ruc.'</cbc:CustomerAssignedAccountID>
              <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
              <cac:Party>
                  <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA['.$emisor->empresa_razon_social.']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
              </cac:Party>
          </cac:AccountingSupplierParty>';


            $xml.='<sac:VoidedDocumentsLine>
                 <cbc:LineID>1</cbc:LineID>
                 <cbc:DocumentTypeCode>'.$detalle->venta_tipo.'</cbc:DocumentTypeCode>
                 <sac:DocumentSerialID>'.$detalle->venta_serie.'</sac:DocumentSerialID>
                 <sac:DocumentNumberID>'.$detalle->venta_correlativo.'</sac:DocumentNumberID>
                 <sac:VoidReasonDescription><![CDATA[Error en Documento]]></sac:VoidReasonDescription>
             </sac:VoidedDocumentsLine>';


        $xml.='</VoidedDocuments>';

        $doc->loadXML($xml);
        $doc->save($nombrexml.'.XML');
    }

    //PARA LA GUIA DE REMISION
    function CrearXmlGuiaRemision($nombrexml, $emisor, $guia, $detalle_guia, $venta)
    {
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';
        $exis_relacion = true;
        if(!empty($guia->serie_relacion)){
            $venta_s_c = $guia->serie_relacion.'-'.$guia->correlativo_relacion;
            $tipo = '';
            $venta_tipo = '';
            if($guia->tipo_comprobante_relacion == '01'){
                $tipo = 'FACTURA';
                $venta_tipo = '01';
            }else if($guia->tipo_comprobante_relacion == '03'){
                $tipo = 'BOLETA';
                $venta_tipo = '03';
            }
        }else if(!empty($guia->id_venta)){
            $venta_s_c = $venta->venta_serie.'-'.$venta->venta_correlativo;
            $tipo = '';
            $venta_tipo = '';
            if($venta->venta_tipo == '01'){
                $tipo = 'FACTURA';
                $venta_tipo = '01';
            }else if($venta->venta_tipo == '03'){
                $tipo = 'BOLETA';
                $venta_tipo = '03';
            }
        }else{
            $venta_s_c = '';
            $tipo = '';
            $venta_tipo = '';
            $exis_relacion = false;
        }
        $observacion = '-';
        if(!empty($guia->guia_observacion)){
            $observacion = $guia->guia_observacion;
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" 
                xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
                xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
                 <ext:UBLExtensions>
                    <ext:UBLExtension>
                       <ext:ExtensionContent />
                    </ext:UBLExtension>
                 </ext:UBLExtensions>
                 <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                 <cbc:CustomizationID>2.0</cbc:CustomizationID>
                 <cbc:ID>'.$guia->guia_serie.'-'.$guia->guia_correlativo.'</cbc:ID> 
                 <cbc:IssueDate>'.date('Y-m-d', strtotime($guia->fecha_creacion)).'</cbc:IssueDate>
                 <cbc:IssueTime>'.date('H:i:s', strtotime($guia->fecha_creacion)).'</cbc:IssueTime>
                 <cbc:DespatchAdviceTypeCode>'.$guia->remision_tipo_comprobante.'</cbc:DespatchAdviceTypeCode>
                 <cbc:Note>'.$observacion.'</cbc:Note>';
        //guía de remisión dada de baja
        $valor = false;
        if($valor){
            $xml .='<cac:OrderReference>
                 <cbc:ID>EG01-1</cbc:ID>
                 <cbc:OrderTypeCode name=”Guía de Remisión”>09</cbc:OrderTypeCode>
                </cac:OrderReference>';
        }

        //Documento Adicional Relacionado
        if($exis_relacion){
            $xml .='<cac:AdditionalDocumentReference>
                     <cbc:ID>'.$venta_s_c.'</cbc:ID>
                     <cbc:DocumentTypeCode>'.$venta_tipo.'</cbc:DocumentTypeCode>
                     <cbc:DocumentType>'.$tipo.'</cbc:DocumentType>
                     <cac:IssuerParty>
                        <cac:PartyIdentification>
                        <cbc:ID schemeID="6" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$emisor->empresa_ruc.'</cbc:ID>
                        </cac:PartyIdentification>
                      </cac:IssuerParty>
                </cac:AdditionalDocumentReference>';
        }

        $xml .='<cac:Signature>
                    <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
                    <cac:SignatoryParty>
                       <cac:PartyIdentification>
                          <cbc:ID>'.$emisor->empresa_ruc.'</cbc:ID>
                       </cac:PartyIdentification>
                       <cac:PartyName>
                          <cbc:Name>'.$emisor->empresa_nombrecomercial.'</cbc:Name>
                       </cac:PartyName>
                    </cac:SignatoryParty>
                    <cac:DigitalSignatureAttachment>
                       <cac:ExternalReference>
                          <cbc:URI>'.$emisor->empresa_ruc.'</cbc:URI>
                       </cac:ExternalReference>
                    </cac:DigitalSignatureAttachment>
                 </cac:Signature>';
        //Datos del Remitente
        $xml .= '<cac:DespatchSupplierParty>
                        <cbc:CustomerAssignedAccountID schemeID="6">'.$emisor->empresa_ruc.'</cbc:CustomerAssignedAccountID>
                     <cac:Party>
                     <cac:PartyIdentification>
                        <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$emisor->empresa_ruc.'</cbc:ID>
                      </cac:PartyIdentification>
                      <cac:PartyLegalEntity>
                         <cbc:RegistrationName>'.$emisor->empresa_nombrecomercial.'</cbc:RegistrationName>
                      </cac:PartyLegalEntity>
                     </cac:Party>
                 </cac:DespatchSupplierParty>';

        //Datos del destinatario
        $xml .= '<cac:DeliveryCustomerParty>
                     <cac:Party>
                     <cac:PartyIdentification>
                        <cbc:ID schemeID="'.$guia->guia_destinatario_tipo_doc.'" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$guia->guia_destinatario_numero.'</cbc:ID>
                      </cac:PartyIdentification>
                      <cac:PartyLegalEntity>
                       <cbc:RegistrationName><![CDATA['.$guia->guia_destinatario_nombre.']]></cbc:RegistrationName>
                      </cac:PartyLegalEntity>
                     </cac:Party>
                  </cac:DeliveryCustomerParty>';
        //Datos del proveedor
        if(!empty($guia->guia_proveedor_nombre)){
            $xml .= '<cac:SellerSupplierParty>
                         <cac:Party>
                          <cac:PartyIdentification>
                           <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$guia->guia_proveedor_ruc.'</cbc:ID>
                          </cac:PartyIdentification>
                          <cac:PartyLegalEntity>
                           <cbc:RegistrationName>'.$guia->guia_proveedor_nombre.'</cbc:RegistrationName>
                          </cac:PartyLegalEntity>
                         </cac:Party>
                      </cac:SellerSupplierParty>';
        }
        //Datos del envío
        switch ($guia->guia_motivo){
            case '01' : $motivo = 'VENTA';  break;
            case '02' : $motivo = 'COMPRA'; break;
            case '03' : $motivo = 'VENTA CON ENTREGA A TERCEROS'; break;
            case '04' : $motivo = 'TRASLADO ENTRE ESTABLECIMIENTOS'; break;
            case '05' : $motivo = 'CONSIGNACIÓN'; break;
            case '06' : $motivo = 'DEVOLUCIÓN'; break;
            case '07' : $motivo = 'RECOJO DE BIENES TRANSFORMADOS'; break;
            case '08' : $motivo = 'IMPORTACION'; break;
            case '09' : $motivo = 'EXPORTACION'; break;
            case '13' : $motivo = 'OTROS'; break;
            case '14' : $motivo = 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR'; break;
            case '17' : $motivo = 'TRASLADO DE BIENES PARA TRANSFORMACIÓN'; break;
            case '18' : $motivo = 'TRASLADO EMISOR ITINERANTE CP'; break;
            default : $motivo = '-';
        }
        // AGREGAR SI ES NECESARIO MAS ADELANTE <cbc:SplitConsignmentIndicator>01</cbc:SplitConsignmentIndicator> DESPUES DE MOTIVO
        if(empty($guia->guia_peso_bruto)){
            $p_bulto = 10.000;
        }else{
            $p_bulto = $guia->guia_peso_bruto;
        }
        if(empty($guia->guia_unidad_medida)){
            $guia_unidad_medida = 'KGM';
        }else{
            $guia_unidad_medida = $guia->guia_unidad_medida;
        }
        if(empty($guia->guia_n_bulto)){
            $n_bulto = 1;
        }else{
            $n_bulto = $guia->guia_n_bulto;
        }
        $xml .= '<cac:Shipment>
                     <cbc:ID>SUNAT_Envio</cbc:ID>
                     <cbc:HandlingCode>'.$guia->guia_motivo.'</cbc:HandlingCode>
                     <cbc:HandlingInstructions><![CDATA['.$motivo.']]></cbc:HandlingInstructions>
                     <cbc:GrossWeightMeasure unitCode="'.$guia_unidad_medida.'">'.$p_bulto.'</cbc:GrossWeightMeasure>
                     <cbc:TotalTransportHandlingUnitQuantity>'.$n_bulto.'</cbc:TotalTransportHandlingUnitQuantity>';


        $xml .=   '<cac:ShipmentStage>
                        <cbc:ID>1</cbc:ID>
                        <cbc:TransportModeCode>'.$guia->guia_tipo_trans.'</cbc:TransportModeCode>
                        <cac:TransitPeriod>
                            <cbc:StartDate>'.date('Y-m-d', strtotime($guia->guia_fecha_traslado)).'</cbc:StartDate>
                        </cac:TransitPeriod>';

        if($guia->guia_tipo_trans == '01'){
            //PUBLICO
            $xml .= '<cac:CarrierParty>
                         <cac:PartyIdentification>
                            <cbc:ID schemeID="'.$guia->guia_tipo_doc.'">'.$guia->guia_num_doc.'</cbc:ID>
                         </cac:PartyIdentification>
                         <cac:PartyLegalEntity>
                            <cbc:RegistrationName>'.$guia->guia_denominacion.'</cbc:RegistrationName>
                          </cac:PartyLegalEntity>
                    </cac:CarrierParty>';
        }
        if($guia->guia_tipo_trans == '02'){
            //PRIVADO
            $xml .= '<cac:TransportMeans>
                        <cac:RoadTransport>
                            <cbc:LicensePlateID>'.$guia->guia_placa.'</cbc:LicensePlateID>
                        </cac:RoadTransport>
                    </cac:TransportMeans>';
            //datos del conductor
            $denominacion_cond = explode(' // ', $guia->guia_nombre_cond);
            $nombre_con = $denominacion_cond[0];
            $apellido_con = $denominacion_cond[1];
            $xml .= '
                        <cac:DriverPerson>
                            <cbc:ID schemeID="'.$guia->guia_doc_con.'">'.$guia->guia_doc_cond.'</cbc:ID>
                            <cbc:FirstName>'.$nombre_con.'</cbc:FirstName>
                            <cbc:FamilyName>'.$apellido_con.'</cbc:FamilyName>
                            <cbc:JobTitle>Principal</cbc:JobTitle>
                            <cac:IdentityDocumentReference>
                            <!-- LICENCIA DE CONDUCIR -->
                            <cbc:ID>'.$guia->guia_licencia_cond.'</cbc:ID>
                            </cac:IdentityDocumentReference>
                        </cac:DriverPerson>';
        }
        $establecimiento_partida = "";
        $establecimiento_llegada = "";
        if($guia->guia_motivo != 2){
            $establecimiento_llegada = '<cbc:AddressTypeCode listID="'.$guia->guia_destinatario_numero.'">'.$guia->guia_cod_establec_llega.'</cbc:AddressTypeCode>';
            $establecimiento_partida = '<cbc:AddressTypeCode listID="'.$emisor->empresa_ruc.'">'.$guia->guia_cod_establec_par.'</cbc:AddressTypeCode>';
        }
        $xml .= '
                    </cac:ShipmentStage>
                    <cac:Delivery>
                        <cac:DeliveryAddress>
                            <cbc:ID>'.$guia->guia_ubigeo_llega.'</cbc:ID>
                            '.$establecimiento_llegada.'
                            <cac:AddressLine>
                                <cbc:Line><![CDATA['.$guia->guia_direccion_llega.']]></cbc:Line>
                            </cac:AddressLine>
                        </cac:DeliveryAddress>
                        <cac:Despatch>
                            <cac:DespatchAddress>
                                <cbc:ID>'.$guia->guia_ubigeo_par.'</cbc:ID>
                                '.$establecimiento_partida.'
                                <cac:AddressLine>
                                    <cbc:Line><![CDATA['.$guia->guia_direccion_part.']]></cbc:Line>
                                </cac:AddressLine>
                            </cac:DespatchAddress>
                        </cac:Despatch>
                    </cac:Delivery>';
        if($guia->guia_tipo_trans == '02'){
            $xml .=     '<cac:TransportHandlingUnit>
                        <cac:TransportEquipment>
                            <cbc:ID>'.$guia->guia_placa.'</cbc:ID>
                        </cac:TransportEquipment>
                    </cac:TransportHandlingUnit>';
        }
        if($valor){
            $xml .= '
                    <cac:FirstArrivalPortLocation>
                        <cbc:ID>PAI</cbc:ID>
                    </cac:FirstArrivalPortLocation>';
        }
        $xml .= '</cac:Shipment>';

        //Bienes a trasportar
        $item = 1;
        foreach ($detalle_guia as $de){
            $xml .= '<cac:DespatchLine>
                    <cbc:ID>'.$item.'</cbc:ID> 
                    <cbc:DeliveredQuantity unitCode="'.$de->unidad_codigo.'">'.$de->guia_remision_detalle_cantidad.'</cbc:DeliveredQuantity>
                    <cac:OrderLineReference>
                         <cbc:LineID>'.$item.'</cbc:LineID>
                    </cac:OrderLineReference> 
                    <cac:Item>
                      <cbc:Description>'.$de->guia_remision_detalle_descripcion.'</cbc:Description>
                      <cac:SellersItemIdentification>
                       <cbc:ID>'.$de->guia_remision_detalle_cod.'</cbc:ID>
                      </cac:SellersItemIdentification>
                     </cac:Item>
                </cac:DespatchLine>';
            $item++;
        }

        $xml.='</DespatchAdvice>';

        $doc->loadXML($xml);
        $doc->save($nombrexml.'.XML');
    }
}