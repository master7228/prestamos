<?php 
//Incluimos inicialmente la conexion a la base de datos
require "../config/Conexion.php";
    
Class PlanPagos
{
    //implementamos nuestro constructor
    public function __construct()
    {   

    }
    
    //implementamos un metodo para insertar registros
    public function insertar($idprestamo,$numero_cuota,$fecha_proximo_pago,$valor_cuota)   {
        $sql="INSERT INTO planPagos (idprestamo,numero_cuota,fecha_proximo_pago,valor_cuota,estado) 
        VALUES ('$idprestamo','$numero_cuota','$fecha_proximo_pago','$valor_cuota',0)";
        return ejecutarConsulta($sql);
    }

    public function delete($idprestamo)   {
        $sql= "DELETE FROM planPagos WHERE idprestamo = $idprestamo ";
        return ejecutarConsulta($sql);
    }

    public function acentarPago($id, $valor_pagado, $fecha_pago, $observacion, $id_user){
        $sql= "UPDATE planPagos SET valor_pagado = $valor_pagado, fecha_pago = '$fecha_pago', descripcion = '$observacion', estado = 1, id_user = $id_user   WHERE id = $id ";
        return ejecutarConsulta($sql);
    }

    public function actualizarCuotasSaldos($id_planPagos, $valor){
        $sql= "UPDATE planPagos SET valor_cuota = $valor  WHERE id = $id_planPagos ";
        return ejecutarConsulta($sql);
    }
    
}

?>