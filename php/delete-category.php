<?php  
session_start();

# Si el administrador está conectado
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

	# Archivo de Conexión a la Base de Datos
	include "../db_conn.php";

    /** 
	  Verificar si se estableció el 
	  ID de la categoría
	**/
	if (isset($_GET['id'])) {
		/** 
		Obtener datos de la solicitud GET 
		y almacenarlos en una variable
		**/
		$id = $_GET['id'];

		# Validación simple del formulario
		if (empty($id)) {
			$em = "¡Error ocurrido!";
			header("Location: ../admin.php?error=$em");
            exit;
		} else {
            # ELIMINAR la categoría de la base de datos
			$sql  = "DELETE FROM categories
			         WHERE id=?";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$id]);

			/**
		      Si no hay errores al 
		      eliminar los datos
		    **/
		     if ($res) {
		     	# mensaje de éxito
		     	$sm = "¡Eliminado exitosamente!";
				header("Location: ../admin.php?success=$sm");
	            exit;
			 } else {
			 	$em = "¡Error ocurrido!";
			    header("Location: ../admin.php?error=$em");
                exit;
			 }
             
		}
	} else {
      header("Location: ../admin.php");
      exit;
	}

} else {
  header("Location: ../login.php");
  exit;
}