<?php  
session_start();

# Si el administrador está conectado
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

	# Archivo de Conexión a la Base de Datos
	include "../db_conn.php";

    /** 
	  Verificar si se estableció el 
	  ID del libro
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
             # OBTENER libro desde la Base de Datos
			 $sql2  = "SELECT * FROM books
			          WHERE id=?";
			 $stmt2 = $conn->prepare($sql2);
			 $stmt2->execute([$id]);
			 $the_book = $stmt2->fetch();

			 if($stmt2->rowCount() > 0){
                # ELIMINAR el libro de la Base de Datos
				$sql  = "DELETE FROM books
				         WHERE id=?";
				$stmt = $conn->prepare($sql);
				$res  = $stmt->execute([$id]);

				/**
			      Si no hay errores al 
			      eliminar los datos
			    **/
			     if ($res) {
			     	# eliminar la portada actual del libro y el archivo
                    $cover = $the_book['cover'];
                    $file  = $the_book['file'];
                    $c_b_c = "../uploads/cover/$cover";
                    $c_f = "../uploads/files/$cover";
                    
                    unlink($c_b_c);
                    unlink($c_f);


			     	# mensaje de éxito
			     	$sm = "¡Eliminado exitosamente!";
					header("Location: ../admin.php?success=$sm");
		            exit;
			     } else {
			     	# mensaje de error
			     	$em = "¡Error desconocido ocurrido!";
					header("Location: ../admin.php?error=$em");
		            exit;
			     }
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
