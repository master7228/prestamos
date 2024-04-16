<?php 
session_start();
require_once "../modelos/Clientes.php";


$cliente=new Clientes();

$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$cedula=isset($_POST["cedula"])? limpiarCadena($_POST["cedula"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$ingresos=isset($_POST["ingresos"])? limpiarCadena($_POST["ingresos"]):"";
$egresos=isset($_POST["egresos"])? limpiarCadena($_POST["egresos"]):"";
$idzona=isset($_POST["zona"])? limpiarCadena($_POST["zona"]):"";


switch ($_GET["op"]){

	case 'test':
		echo "Prestamo test";
	break;
        
	case 'guardaryeditar':
		if (empty($idcliente)){
			if(isset($_FILES['archivos'])){
				$directorio_destino = "../files/clientes/".$cedula."/";
				if (!file_exists($directorio_destino)) {
					mkdir($directorio_destino, 0777, true);
				}
				$contador = 1;
				foreach($_FILES['archivos']['tmp_name'] as $key => $tmp_name){
					$nombre_archivo = $_FILES['archivos']['name'][$key];
					while(file_exists($directorio_destino . $nombre_archivo)){
						$nombre_archivo = $contador . '_' . $_FILES['archivos']['name'][$key];
						$contador++;
					}
					$ruta_destino = $directorio_destino . $nombre_archivo;
					move_uploaded_file($tmp_name, $ruta_destino);
				}
			}
			$rspta=$cliente->insertar($cedula,$nombre,$direccion,$telefono, $ingresos, $egresos, $idzona);
			echo $rspta ? "Cliente registrado" : "Cliente no se pudo registrar";
		}
		else {
			if(isset($_FILES['archivos'])){
				$directorio_destino = "../files/clientes/".$cedula."/";
				if (!file_exists($directorio_destino)) {
					mkdir($directorio_destino, 0777, true);
				}
				$contador = 1;
				foreach($_FILES['archivos']['tmp_name'] as $key => $tmp_name){
					$nombre_archivo = $_FILES['archivos']['name'][$key];
					while(file_exists($directorio_destino . $nombre_archivo)){
						$nombre_archivo = $contador . '_' . $_FILES['archivos']['name'][$key];
						$contador++;
					}
					$ruta_destino = $directorio_destino . $nombre_archivo;
					move_uploaded_file($tmp_name, $ruta_destino);
				}
			}
			$rspta=$cliente->editar($idcliente,$cedula,$nombre,$direccion,$telefono, $ingresos, $egresos, $idzona);
			echo $rspta ? "Cliente actualizado" : "Cliente no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$cliente->desactivar($idcliente);
 		echo $rspta ? "Cliente Desactivado" : "Cliente no se puede desactivar";
	break;

	case 'activar':
		$rspta=$cliente->activar($idcliente);
 		echo $rspta ? "Cliente activado" : "Cliente no se puede activar";
	break;
        
	case 'mostrar':
		$data = array();
		$files = array();
		$rspta=$cliente->mostrar($idcliente);
 		//Codificar el resultado utilizando json
		$directorio = "../files/clientes/".$rspta['cedula']."/";
		// Obtener la lista de archivos en el directorio
		$archivos = scandir($directorio);

		// Iterar sobre los archivos
		foreach ($archivos as $archivo) {
			// Excluir los directorios "." y ".."
			if ($archivo != "." && $archivo != "..") {
				$ruta = $directorio . $archivo;
				if (exif_imagetype($ruta)) {
					array_push($files,"<img src='$ruta' alt='$archivo' style='max-width: 300px; max-height: 300px; margin: 10px;'>");
				} else {
					array_push($files,"<p><a href='$ruta' target='_blank'>$archivo</a></p>");
				}
			}
		}
		array_push($data, $rspta,$files);
 		echo json_encode($data);
	break;

	case 'listar':
		$rspta=$cliente->listar();
 		//Vamos a declarar un array
 		$data= Array();
		
		 if($_SESSION['admin'] == 1){
			while ($reg=$rspta->fetch_object()){
			
				$data[]=array(
					"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcliente.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger" onclick="desactivar('.$reg->idcliente.')"><i class="fa fa-close"></i></button>':
						'<button class="btn btn-warning" onclick="mostrar('.$reg->idcliente.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-primary" onclick="activar('.$reg->idcliente.')"><i class="fa fa-check"></i></button>',
					"1"=>$reg->cedula,
					"2"=>$reg->nombre,
					"3"=>$reg->direccion,
					"4"=>$reg->telefono,
				   "5"=>$reg->ingresos,
					"6"=>$reg->egresos,
				   "7"=>$reg->nombrezona,
				   "8"=>($reg->estado)?'<span class="label bg-success">Activo</span>':'<span class="label bg-danger">Inactivo</span>');
				   
			}
			$results = array(
				"sEcho"=>1, //Información para el datatables
				"iTotalRecords"=>count($data), //enviamos el total registros al datatable
				"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
				"aaData"=>$data);
		 }else{

			while ($reg=$rspta->fetch_object()){
				if($reg->idzona == $_SESSION['zona']){
					$data[]=array(
						"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idcliente.')"><i class="fa fa-pencil"></i></button>',
						"1"=>$reg->cedula,
						"2"=>$reg->nombre,
						"3"=>$reg->direccion,
						"4"=>$reg->telefono,
					   	"5"=>$reg->ingresos,
						"6"=>$reg->egresos,
					   	"7"=>$reg->nombrezona,
					   	"8"=>($reg->estado)?'<span class="label bg-success">Activo</span>':'<span class="label bg-danger">Inactivo</span>');
				}
				
				   
			}
			$results = array(
				"sEcho"=>1, //Información para el datatables
				"iTotalRecords"=>count($data), //enviamos el total registros al datatable
				"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
				"aaData"=>$data);
		 }
 		
			 
 		echo json_encode($results);
	break;

	case 'zonas':
		$rspta=$cliente->zonas();
		$data = array();
		while ($reg=$rspta->fetch_object()){
			array_push($data,$reg);
		}
		//Codificar el resultado utilizando json
		echo json_encode($data);
	break;
}
?>