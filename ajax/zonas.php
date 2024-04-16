<?php 
require_once "../modelos/Zona.php";

$zona=new Zona();

$idzona=isset($_POST["idzona"])? limpiarCadena($_POST["idzona"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]){
        
	case 'guardaryeditar':
		if (empty($idzona)){
			$rspta=$zona->insertar($nombre, $descripcion);
			echo $rspta ? "Zona registrada" : "Zona no se pudo registrar";
		}
		else {
			$rspta=$zona->editar($idzona,$nombre,$descripcion);
			echo $rspta ? "Zona actualizado" : "Zona no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$zona->desactivar($idzona);
 		echo $rspta ? "Zona Desactivado" : "Zona no se puede desactivar";
	break;

	case 'activar':
		$rspta=$zona->activar($idzona);
 		echo $rspta ? "Zona activado" : "Zona no se puede activar";
	break;
        
	case 'mostrar':
		$rspta=$zona->mostrar($idzona);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$zona->listar($idzona);
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idzona.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idzona.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idzona.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idzona.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
				"2"=>$reg->descripcion,
                "3"=>($reg->estado)?'<span class="label bg-primary">Activado</span>':'<span class="bg-warning">Desactivado</span>');
                
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
}
?>