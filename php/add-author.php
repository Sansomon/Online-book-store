<?php  
session_start();

# Si el administrador está conectado
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

	# Archivo de Conexión a la Base de Datos
	include "../db_conn.php";

    /** Verificar si se envió el nombre del autor**/
	if (isset($_POST['author_name'])) {

		/** Obtener datos de la solicitud POST y almacenarlos en una variable**/
		$name = $_POST['author_name'];

		# Validación simple del formulario
		if (empty($name)) {
			$em = "El nombre del autor es requerido";
			header("Location: ../add-author.php?error=$em");
            exit;
		} else {
			# Insertar en la Base de Datos
			$sql  = "INSERT INTO author (name)
			         VALUES (?)";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name]);

			/** Si no hay errores al  insertar los datos **/
		     if ($res) {
		     	# mensaje de éxito
		     	$sm = "¡Creado exitosamente!";
				header("Location: ../add-author.php?success=$sm");
	            exit;
		     } else {
		     	# mensaje de error
		     	$em = "¡Error desconocido ocurrido!";
				header("Location: ../add-author.php?error=$em");
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
