<?php  
session_start();

# Si el administrador está conectado
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

	# Archivo de Conexión a la Base de Datos
	include "../db_conn.php";

    /** 
	  Verificar si se envió el 
	  nombre de la categoría
	**/
	if (isset($_POST['category_name'])) {
		/** 
		Obtener datos de la solicitud POST 
		y almacenarlos en una variable
		**/
		$name = $_POST['category_name'];

		# Validación simple del formulario
		if (empty($name)) {
			$em = "El nombre de la categoría es requerido";
			header("Location: ../add-category.php?error=$em");
            exit;
		} else {
			# Insertar en la Base de Datos
			$sql  = "INSERT INTO categories (name)
			         VALUES (?)";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name]);

			/**
		      Si no hay errores al 
		      insertar los datos
		    **/
		     if ($res) {
		     	# mensaje de éxito
		     	$sm = "¡Creado exitosamente!";
				header("Location: ../add-category.php?success=$sm");
	            exit;
		     } else {
		     	# mensaje de error
		     	$em = "¡Error desconocido ocurrido!";
				header("Location: ../add-category.php?error=$em");
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
