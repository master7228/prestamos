<?php
session_start(); 
require_once "../modelos/Prestamos.php";
require_once "../modelos/PlanPagos.php";

$prestamo=new Prestamo();
$plapagos=new PlanPagos();

$idprestamo=isset($_POST["idprestamo"])? limpiarCadena($_POST["idprestamo"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$fprestamo=isset($_POST["fprestamo"])? limpiarCadena($_POST["fprestamo"]):"";
$monto=isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
$interes=isset($_POST["interes"])? limpiarCadena($_POST["interes"]):"";
$formapago=isset($_POST["formapago"])? limpiarCadena($_POST["formapago"]):"";
$plazo=isset($_POST["plazo"])? limpiarCadena($_POST["plazo"]):"";
$fechaInicioPago=isset($_POST["finicio"])? limpiarCadena($_POST["finicio"]):"";
$interesCuotaVencida=isset($_POST["interescuotavencidad"])? limpiarCadena($_POST["interescuotavencidad"]):"";



switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idprestamo)){

			$planPagos = json_decode($_POST['planPagos']);
			$rspta=$prestamo->insertar($idcliente,$idusuario,$fprestamo,$monto,$interes,$formapago,$plazo,$fechaInicioPago,$interesCuotaVencida);
			if($rspta > 0 ){
				for ($i=0; $i < count($planPagos) ; $i++) { 
					$rsptaplapagos = $plapagos->insertar($rspta,$planPagos[$i][0],$planPagos[$i][1],$planPagos[$i][2]);
				}	
			}
			echo $rspta ? "Prestamo registrado" : "Prestamo no se pudo registrar";
		}else{
			$planPagos = json_decode($_POST['planPagos']);
			$rspta=$prestamo->editar($idprestamo,$idcliente,$idusuario,$fprestamo,$monto,$interes,$formapago,$plazo,$fechaInicioPago,$interesCuotaVencida);
			//echo $rspta ? "Prestamo actualizada" : "Prestamo no se pudo actualizar";
			if($rspta > 0 ){
				$rsptaDel = $plapagos->delete($idprestamo);
				
				if($rsptaDel > 0 ){
					for ($i=0; $i < count($planPagos) ; $i++) { 
						$rsptaplapagos = $plapagos->insertar($idprestamo,$planPagos[$i][0],$planPagos[$i][1],$planPagos[$i][2]);
					}
				}
					
			}
			echo $rspta ? "Prestamo actualizada" : "Prestamo no se pudo actualizar";
			
		}
	break;
        
    case 'eliminar':
		$rspta=$prestamo->eliminar($idprestamo);
 		echo $rspta ? "Prestamo eliminado" : "Prestamo no se puede eliminar";
	break;

	case 'cancelado':
		$rspta=$prestamo->cancelado($idprestamo);
 		echo $rspta ? "Prestamo Cancelado" : "Prestamo no se puede Cancelar";
	break;

	/*case 'activar':
		$rspta=$prestamo->activar($idprestamo);
 		echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
	break;
*/
	case 'mostrar':
		$rspta=$prestamo->mostrar($idprestamo);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'mostrarbycliente':
		$rspta=$prestamo->mostrarbycliente($idcliente);
 		//Codificar el resultado utilizando json
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
			$data[]=array("idprestamo"=>$reg->idprestamo,"cliente"=>$reg->cliente,"idcliente"=>$reg->idcliente,"fecha"=>$reg->fecha,"monto"=>$reg->monto,"estado"=>$reg->estado,"interesCuotaVencida"=>$reg->interesCuotaVencida);


		}
 		echo json_encode($data);
	break;

	/*case 'mostrarPlanPagos':
		$rspta=$prestamo->mostrarPlanPagos($idprestamo);
 		//Codificar el resultado utilizando json
		 $data= Array();

 		while ($reg=$rspta->fetch_object()){
			$data[]=array("0"=>$reg->numero_cuota,"1"=>$reg->fecha_proximo_pago,"2"=>$reg->valor_cuota);
		}
 		echo json_encode($data);
	break;*/

	case 'mostrarPlanPagosParaPago':
		$rspta=$prestamo->mostrarPlanPagos($idprestamo);
 		//Codificar el resultado utilizando json
		 $data= Array();

 		while ($reg=$rspta->fetch_object()){
			$data[]=array(	"id"=>$reg->id,
							"numero_cuota"=>$reg->numero_cuota,
							"fecha_proximo_pago"=>$reg->fecha_proximo_pago,
							"valor_cuota"=>$reg->valor_cuota,
							"fecha_pago"=>$reg->fecha_pago,
							"estado"=>$reg->estado,
							"descripcion"=>$reg->descripcion,
							"valor_pagado"=>$reg->valor_pagado);
		}
 		echo json_encode($data);
	break;

	case 'listar':
	
		$rspta=$prestamo->listar();
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
			$botonera = '';
			
			if ($_SESSION['admin'] == 1) {
				$botonera = '<button class="btn btn-warning" title="Editar" onclick="mostrar('.$reg->idprestamo.')"> <i class="fa fa-pencil"> </i></button> '.' <button class="btn btn-warning" title="Ver" onclick="solover('.$reg->idprestamo.')"> <i class="fa fa-eye"> </i></button>';
				$data[]=array(
					"0"=>$botonera ,
				  "1"=>$reg->cliente,
				  "2"=>$reg->usuario,
				  "3"=>$reg->fecha,
				  "4"=>$reg->monto,
				 "5"=>$reg->interes,
				  "6"=>$reg->formapago,
				 "7"=>$reg->plazo,
				  "8"=>($reg->estado)?'<span class="label bg-success">Activo</span>':
				  '<span class="label bg-danger">Cancelado</span>'
				  );
			}else{
				if($_SESSION['idusuario'] == $reg->idusuario ){
					$data[]=array(
						"0"=>$botonera ,
					  "1"=>$reg->cliente,
					  "2"=>$reg->usuario,
					  "3"=>$reg->fecha,
					  "4"=>$reg->monto,
					 "5"=>$reg->interes,
					  "6"=>$reg->formapago,
					 "7"=>$reg->plazo,
					  "8"=>($reg->estado)?'<span class="label bg-success">Activo</span>':
					  '<span class="label bg-danger">Cancelado</span>'
					  );
					  $botonera = '<button class="btn btn-warning" title="Ver" onclick="solover('.$reg->idprestamo.')"> <i class="fa fa-eye"> </i></button>';
				}
			}
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;

	case 'listarprestamosdeldia':
	
		$rspta=$prestamo->listarprestamosdeldia();
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
			$botonera = '';
			
			if ($_SESSION['admin'] == 1) {
				$botonera = '<button class="btn btn-warning" title="Editar" onclick="mostrar('.$reg->idprestamo.')"> <i class="fa fa-pencil"> </i></button> '.' <button class="btn btn-warning" title="Ver" onclick="solover('.$reg->idprestamo.')"> <i class="fa fa-eye"> </i></button>';
				$data[]=array(
					"0"=>$botonera,
					"1"=>$reg->idprestamo,
				  	"2"=>$reg->cliente,
				  	"3"=>$reg->usuario,
				  	"4"=>$reg->fecha,
				  	"5"=>'$ '.number_format($reg->monto, 2, ',', '.'),
				 	"6"=>$reg->interes,
				  	"7"=>$reg->formapago,
				 	"8"=>$reg->plazo
				);
			}else{
				if($_SESSION['idusuario'] == $reg->idusuario ){
					$data[]=array(
						"0"=>$botonera,
						"1"=>$reg->idprestamo,
				  		"2"=>$reg->cliente,
				  		"3"=>$reg->usuario,
				  		"4"=>$reg->fecha,
				  		"5"=>'$ '.number_format($reg->monto, 2, ',', '.'),
				 		"6"=>$reg->interes,
				  		"7"=>$reg->formapago,
				 		"8"=>$reg->plazo
					);
					  $botonera = '<button class="btn btn-warning" title="Ver" onclick="solover('.$reg->idprestamo.')"> <i class="fa fa-eye"> </i></button>';
				}
			}
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;
        
    case 'selectCliente':
        require_once "../modelos/Clientes.php";
		$cliente = new Clientes();
		$rspta = $cliente->select();
		echo '<option></option>';
		while ($reg = $rspta->fetch_object())
        {
            echo '<option value=' . $reg->idcliente . '>' . $reg->nombre . '</option>';
        }
	break;
        
    case "selectUsuario":
        require_once "../modelos/Usuarios.php";
        $usuario = new Usuarios();
        
        $rspta = $usuario->select();
        
        while($reg = $rspta->fetch_object())
        {
            echo '<option value='.$reg->idusuario .'>'.$reg->nombre .'</option>';
        }
    break;

	
}
?>