<?php  
session_start();

#Si el administrador ha iniciado sesión
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Archivo de conexión de base de datos
	include "../db_conn.php";


    /** 
	  comprobar si la categoría
        se envía el nombre
	**/
	if (isset($_POST['category_name']) &&
        isset($_POST['category_id'])) {
		/** 
		Obtener datos de la solicitud POST
            y almacenarlos en var
		**/
		$name = $_POST['category_name'];
		$id = $_POST['category_id'];

		#Validación de formulario simple
		if (empty($name)) {
			$em = "The category name is required";
			header("Location: ../edit-category.php?error=$em&id=$id");
            exit;
		}else {
			# ACTUALIZAR la base de datos
			$sql  = "UPDATE categories 
			         SET name=?
			         WHERE id=?";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name, $id]);

			/**
		      Si no hay ningún error mientras
                actualizando los datos
		    **/
		     if ($res) {
		     	# mensaje de exito
		     	$sm = "Successfully updated!";
				header("Location: ../edit-category.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# Mensaje de error
		     	$em = "Unknown Error Occurred!";
				header("Location: ../edit-category.php?error=$em&id=$id");
	            exit;
		     }
		}
	}else {
      header("Location: ../admin.php");
      exit;
	}

}else{
  header("Location: ../login.php");
  exit;
}