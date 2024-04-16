<?php

require_once "../modelos/PlanPagos.php";
require '../vendor/autoload.php';

				use \Com\Tecnick\Color\Model\Cmyk as ColorCMYK;
				use \Com\Tecnick\Color\Model\Gray as ColorGray;
				use \Com\Tecnick\Color\Model\Hsl as ColorHSL;
				use \Com\Tecnick\Color\Model\Rgb as ColorRGB;


$planpagos=new PlanPagos();

$id=isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
$valor_pagado=isset($_POST["valor_pagado"])? limpiarCadena($_POST["valor_pagado"]):"";
$observacion=isset($_POST["observacion"])? limpiarCadena($_POST["observacion"]):"";
$fecha_pago=isset($_POST["fecha_pago"])? limpiarCadena($_POST["fecha_pago"]):"";
$id_user=isset($_POST["id_user"])? limpiarCadena($_POST["id_user"]):"";
//$cuotas=isset($_POST["cuotas"])? limpiarCadena($_POST["cuotas"]):"";


switch ($_GET["op"]){
	case "acentarPago":
       // echo $id, $valor_pagado, $fecha_pago, $observacion;
       $cuotasActualizadas = json_decode($_POST['cuotas']);
  
        $rspta=$planpagos->acentarPago($id, $valor_pagado, $fecha_pago, $observacion,$id_user);
        if(count($cuotasActualizadas) > 0){
            for ($i=0; $i < count($cuotasActualizadas); $i++) { 
                $rspta=$planpagos->actualizarCuotasSaldos($cuotasActualizadas[$i][0], $cuotasActualizadas[$i][1]);
            }
            
        }

        echo json_encode($rspta);
    break;

    case 'listarpagosdeldia':
        $rspta=$planpagos->listarpagosdeldia();
        //declaracion de array
        $data=Array();
        
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
            "0"=>'<button class="btn btn-warning" onclick="irPagos()"> <i class="fa fa-eye"> </i></button>',
            "1"=>$reg->idprestamo,
            "2"=>$reg->cliente,
            "3"=>$reg->usuario,
            "4"=>$reg->fecha,
            "5"=>'$ '.number_format($reg->cuota, 2, ',', '.'),
            "6"=>($reg->estado)?'<span class="label bg-success">Pagó</span>':'<span class="label bg-danger">Pendiente</span>');
        }
        $results = array(
        "sEcho"=>1, //Informacion para el datatables
            "iTotalRecords"=>count($data), //Enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;


    case 'comprobantePago':
        $id = $_GET["id"];
        $rspta=$planpagos->consultarPagoPlanPagos($id);
        $mpdf = new \Mpdf\Mpdf();
        // HTML que deseas convertir en PDF
        while ($reg=$rspta->fetch_object()){
            $cliente = $reg->cliente;
            $nroCuota = $reg->numero_cuota;
            $fecha = $reg->fecha;
            $html = '<div>
            <div style="text-align: center; display: inline-flex;">
                <h1>SISAP</h1>
            </div>                       
                <div style="text-align: center;"><h3>Comprobante de Pago</h3></div>
        
                    <div class="row">
                        <div style="display: inline-flex; width:100%">   
                            <div style="flex: 0 0 50%; max-width: 50%; margin-top: 5px;">
                                <label style="font-size: 18px;font-weight: bold;"><b>Nombre Cliente: </b>'.$reg->cliente.'</label>
                            </div>
                            <div style="flex: 0 0 50%; max-width: 50%; margin-top: 5px;">
                                <label style="font-size: 18px;font-weight: bold;"><b>Documento: </b>'.$reg->cedula.'</label>
                            </div>
                        </div>
                        <div style="display: inline-flex; width:100%; ">   
                            <div style="flex: 0 0 50%; max-width: 50%; margin-top: 5px;">
                                <label style="font-size: 18px;font-weight: bold;"><b>Número de Obligación: </b>'.$reg->idprestamo.'</label>
                            </div>
                            <div style="flex: 0 0 50%; max-width: 50%; margin-top: 5px;">
                                <label style="font-size: 18px;font-weight: bold;"><b>Fecha: </b>'.$reg->fecha.'</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div> 
                            <div>
                                <div>
                                    <div>
                                        <table width="100%" cellspacing="0" style="text-align: left;" border="1">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: left; width:20%">Número Cuota</th>
                                                    <th style="text-align: left; width:60%">Valor</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th style="text-align: left; width:20%">Número Cuota</th>
                                                    <th style="text-align: left; width:60%">Valor</th>
                                                </tr>
                                            </tfoot>
                                            <tbody id="tabla-body">
        
                                            <tr>
                                                <td>'.$reg->numero_cuota.'</td>
                                                <td> $ '.number_format($reg->cuota, 2, ',', '.').'</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>';
        }
       
        
        // Agregar el HTML al PDF
        $mpdf->WriteHTML($html);
        
        // Generar el PDF
        $mpdf->Output('COMPROBANTEPAGO_'.$nroCuota.'_'.$cliente.'_'.$fecha.'.pdf', 'D');
    break;

	
}
?>