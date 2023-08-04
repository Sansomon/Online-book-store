<?php 
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {
    
    # Archivo de Conexión a la Base de Datos
	include "../db_conn.php";
    
    # Función de validación
	include "func-validation.php";
	
	/** 
	   Obtener datos de la solicitud POST 
	   y almacenarlos en variables
	**/

	$email = $_POST['email'];
	$password = $_POST['password'];

	# Validación simple del formulario

	$text = "Email";
	$location = "../login.php";
	$ms = "error";
    is_empty($email, $text, $location, $ms, "");

    $text = "Contraseña";
	$location = "../login.php";
	$ms = "error";
    is_empty($password, $text, $location, $ms, "");

    # Buscar el email en la base de datos
    $sql = "SELECT * FROM admin 
            WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    # Si el email existe
    if ($stmt->rowCount() === 1) {
    	$user = $stmt->fetch();

    	$user_id = $user['id'];
    	$user_email = $user['email'];
    	$user_password = $user['password'];
    	if ($email === $user_email) {
    		if (password_verify($password, $user_password)) {
    			$_SESSION['user_id'] = $user_id;
    			$_SESSION['user_email'] = $user_email;
    			header("Location: ../admin.php");
    		} else {
    			# Mensaje de error
    	        $em = "Nombre de usuario o contraseña incorrectos";
    	        header("Location: ../login.php?error=$em");
    		}
    	} else {
    		# Mensaje de error
    	    $em = "Nombre de usuario o contraseña incorrectos";
    	    header("Location: ../login.php?error=$em");
    	}
    } else {
    	# Mensaje de error
    	$em = "Nombre de usuario o contraseña incorrectos";
    	header("Location: ../login.php?error=$em");
    }

} else {
	# Redirigir a "../login.php"
	header("Location: ../login.php");
}
