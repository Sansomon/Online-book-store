<?php  
session_start();

# Si el administrador está conectado
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

	# Archivo de Conexión a la Base de Datos
	include "../db_conn.php";

    # Función de validación
    include "func-validation.php";

    # Función de carga de archivos
    include "func-file-upload.php";


    /** 
	  Si todos los campos del formulario
	  están completados
	**/
	if (isset($_POST['book_title'])       &&
        isset($_POST['book_description']) &&
        isset($_POST['book_author'])      &&
        isset($_POST['book_category'])    &&
        isset($_FILES['book_cover'])      &&
        isset($_FILES['file'])) {
		/** 
		Obtener datos de la solicitud POST 
		y almacenarlos en variables
		**/
		$title       = $_POST['book_title'];
		$description = $_POST['book_description'];
		$author      = $_POST['book_author'];
		$category    = $_POST['book_category'];

		# Formato de datos para URL
		$user_input = 'title='.$title.'&category_id='.$category.'&desc='.$description.'&author_id='.$author;

		# Validación simple del formulario

        $text = "Título del libro";
        $location = "../add-book.php";
        $ms = "error";
		is_empty($title, $text, $location, $ms, $user_input);

		$text = "Descripción del libro";
        $location = "../add-book.php";
        $ms = "error";
		is_empty($description, $text, $location, $ms, $user_input);

		$text = "Autor del libro";
        $location = "../add-book.php";
        $ms = "error";
		is_empty($author, $text, $location, $ms, $user_input);

		$text = "Categoría del libro";
        $location = "../add-book.php";
        $ms = "error";
		is_empty($category, $text, $location, $ms, $user_input);
        
        # Subida de la portada del libro
        $allowed_image_exs = array("jpg", "jpeg", "png");
        $path = "cover";
        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);

        /**
	    Si ocurre un error durante 
	    la subida de la portada del libro
	    **/
	    if ($book_cover['status'] == "error") {
	    	$em = $book_cover['data'];

	    	/**
	    	  Redirigir a '../add-book.php' 
	    	  y pasar el mensaje de error y la entrada del usuario
	    	**/
	    	header("Location: ../add-book.php?error=$em&$user_input");
	    	exit;
	    } else {
	    	# Subida del archivo del libro
            $allowed_file_exs = array("pdf", "docx", "pptx");
            $path = "files";
            $file = upload_file($_FILES['file'], $allowed_file_exs, $path);

            /**
		    Si ocurre un error durante 
		    la subida del archivo del libro
		    **/
		    if ($file['status'] == "error") {
		    	$em = $file['data'];

		    	/**
		    	  Redirigir a '../add-book.php' 
		    	  y pasar el mensaje de error y la entrada del usuario
		    	**/
		    	header("Location: ../add-book.php?error=$em&$user_input");
		    	exit;
		    } else {
		    	/**
		          Obtener el nombre del nuevo archivo 
		          y el nombre de la portada del libro 
		        **/
		        $file_URL = $file['data'];
		        $book_cover_URL = $book_cover['data'];
                
                # Insertar los datos en la base de datos
                $sql  = "INSERT INTO books (title,
                                            author_id,
                                            description,
                                            category_id,
                                            cover,
                                            file)
                         VALUES (?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
			    $res  = $stmt->execute([$title, $author, $description, $category, $book_cover_URL, $file_URL]);

			/**
		      Si no hay errores al 
		      insertar los datos
		    **/
		     if ($res) {
		     	# Mensaje de éxito
		     	$sm = "¡El libro se ha creado exitosamente!";
				header("Location: ../add-book.php?success=$sm");
	            exit;
		     } else {
		     	# Mensaje de error
		     	$em = "¡Error desconocido ocurrido!";
				header("Location: ../add-book.php?error=$em");
	            exit;
		     }

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
