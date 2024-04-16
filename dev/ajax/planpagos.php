<?php

require_once "../modelos/PlanPagos.php";


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
        print_r($cuotasActualizadas);
        $rspta=$planpagos->acentarPago($id, $valor_pagado, $fecha_pago, $observacion,$id_user);
        if(count($cuotasActualizadas) > 0){
            for ($i=0; $i < count($cuotasActualizadas); $i++) { 
                $rspta=$planpagos->actualizarCuotasSaldos($cuotasActualizadas[$i][0], $cuotasActualizadas[$i][1]);
            }
            
        }
        echo json_encode($rspta);
    break;

	
}
?>