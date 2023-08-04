<?php  
session_start();

# Si el administrador ha iniciado sesión
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Archivo de conexión de base de datos
	include "../db_conn.php";

        /**
        comprobar si el autor
        se envía el nombre
        **/
	if (isset($_POST['author_name']) &&
        isset($_POST['author_id'])) {
		/**
        Obtener datos de la solicitud POST
        y almacenarlos en var
        **/
		$name = $_POST['author_name'];
		$id = $_POST['author_id'];

		#Validación de formulario simple
		if (empty($name)) {
			$em = "El nombre del autor es obligatorio";
			header("Location: ../edit-author.php?error=$em&id=$id");
            exit;
		}else {
			# ACTUALIZAR la base de datos
			$sql  = "UPDATE authors
			         SET name=?
			         WHERE id=?";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name, $id]);

			/**
            Si no hay ningún error 
            insertando los datos
            **/
		     if ($res) {
		     	# mensaje de exito
		     	$sm = "Actualizado exitosamente!";
				header("Location: ../edit-author.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# Error Mensaje
		     	$em = "Ocurrió un error ";
				header("Location: ../edit-author.php?error=$em&id=$id");
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