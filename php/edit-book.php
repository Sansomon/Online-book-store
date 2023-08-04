<?php  
session_start();

# Si el administrador ha iniciado sesión
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Archivo de conexión de base de datos
	include "../db_conn.php";

    # Función auxiliar de validación
    include "func-validation.php";

    # Función auxiliar de carga de archivos
    include "func-file-upload.php";


    /** 
	  Si todo el campo de entrada
        están llenos
	**/
	if (isset($_POST['book_id'])          &&
        isset($_POST['book_title'])       &&
        isset($_POST['book_description']) &&
        isset($_POST['book_author'])      &&
        isset($_POST['book_category'])    &&
        isset($_FILES['book_cover'])      &&
        isset($_FILES['file'])            &&
        isset($_POST['current_cover'])    &&
        isset($_POST['current_file'])) {

		/** 
		Obtener datos de la solicitud POST
            y almacenarlos en var
		**/
		$id          = $_POST['book_id'];
		$title       = $_POST['book_title'];
		$description = $_POST['book_description'];
		$author      = $_POST['book_author'];
		$category    = $_POST['book_category'];
        
                    /**
            Obtener la portada actual y el archivo actual
            de la solicitud POST y almacenarlos en var
            **/

        $current_cover = $_POST['current_cover'];
        $current_file  = $_POST['current_file'];

        #simple form Validation
        $text = "Book title";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($title, $text, $location, $ms, "");

		$text = "Book description";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($description, $text, $location, $ms, "");

		$text = "Book author";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($author, $text, $location, $ms, "");

		$text = "Book category";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($category, $text, $location, $ms, "");

            /**
                 si el administrador intenta
                actualizar la portada del libro
            **/
          if (!empty($_FILES['book_cover']['name'])) {
          	 /**
                si el administrador intenta
                    actualizar ambos
                **/
		      if (!empty($_FILES['file']['name'])) {
		      	# actualice ambos aquí

		      	# Portada del libro Subiendo
		        $allowed_image_exs = array("jpg", "jpeg", "png");
		        $path = "cover";
		        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);

		        # Portada del libro Subiendo
		        $allowed_file_exs = array("pdf", "docx", "pptx");
		        $path = "files";
		        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);
                
               /**
            Si ocurrió un error mientras
                cargando
             **/
		        if ($book_cover['status'] == "error" || 
		            $file['status'] == "error") {

			    	$em = $book_cover['data'];

			    	/**
                    Redirigir a '../editar-libro.php'
                    y pasando el mensaje de error y la identificación
                    **/
			    	header("Location: ../edit-book.php?error=$em&id=$id");
			    	exit;
			    }else {
                  # ruta actual de la portada del libro
			      $c_p_book_cover = "../uploads/cover/$current_cover";

			      # ruta de archivo actual
			      $c_p_file = "../uploads/files/$current_file";

			      # Eliminar del servidor
			      unlink($c_p_book_cover);
			      unlink($c_p_file);

			      /**
		              Obtener el nuevo nombre de archivo
                    y el nuevo nombre de la portada del libro 
		          **/
		           $file_URL = $file['data'];
		           $book_cover_URL = $book_cover['data'];

		            #actualizar solo los datos
		          	$sql = "UPDATE books
		          	        SET title=?,
		          	            author_id=?,
		          	            description=?,
		          	            category_id=?,
		          	            cover=?,
		          	            file=?
		          	        WHERE id=?";
		          	$stmt = $conn->prepare($sql);
					$res  = $stmt->execute([$title, $author, $description, $category,$book_cover_URL, $file_URL, $id]);

				    /**
                    Si no hay ningún error mientras
                    actualizando los datos
                    **/
				     if ($res) {
				     	# mensaje de exito
				     	$sm = "Actualizado exitosamente!";
						header("Location: ../edit-book.php?success=$sm&id=$id");
			            exit;
				     }else{
				     	# Error message
				     	$em = "Ocurrió un error!";
						header("Location: ../edit-book.php?error=$em&id=$id");
			            exit;
				     }


			    }
		      }else {
		      	# actualizar solo la portada del libro

		      	# Portada del libro Subiendo
		        $allowed_image_exs = array("jpg", "jpeg", "png");
		        $path = "cover";
		        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);
                
               /**
                Si ocurrió un error mientras
                cargando
                **/
		        if ($book_cover['status'] == "error") {

			    	$em = $book_cover['data'];

			    	    /**
                        Redirigir a '../editar-libro.php'
                        y pasando el mensaje de error y la identificación
                        **/
			    	header("Location: ../edit-book.php?error=$em&id=$id");
			    	exit;
			    }else {
                  # ruta actual de la portada del libro
			      $c_p_book_cover = "../uploads/cover/$current_cover";

			      # Eliminar del servidor
			      unlink($c_p_book_cover);

			        /**
                    Obtener el nuevo nombre de archivo
                    y el nuevo nombre de la portada del libro
                    **/
		           $book_cover_URL = $book_cover['data'];

		            # actualizar solo los datos
		          	$sql = "UPDATE books
		          	        SET title=?,
		          	            author_id=?,
		          	            description=?,
		          	            category_id=?,
		          	            cover=?
		          	        WHERE id=?";
		          	$stmt = $conn->prepare($sql);
					$res  = $stmt->execute([$title, $author, $description, $category,$book_cover_URL, $id]);

				    /**
                    Si no hay ningún error mientras
                    actualizando los datos
                    **/
				     if ($res) {
				     	# mensaje de exito
				     	$sm = "Successfully updated!";
						header("Location: ../edit-book.php?success=$sm&id=$id");
			            exit;
				     }else{
				     	#Mensaje de error
				     	$em = "Ocurrió un error desconocido!";
						header("Location: ../edit-book.php?error=$em&id=$id");
			            exit;
				     }


			    }
		      }
          }
           /**
           si el administrador intenta
           actualizar solo el archivo

           **/
          else if(!empty($_FILES['file']['name'])){
          	# update just the file
            
            # book cover Uploading
	        $allowed_file_exs = array("pdf", "docx", "pptx");
	        $path = "files";
	        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);
            
            /**
			    If error occurred while 
			    uploading
			**/
	        if ($file['status'] == "error") {

		    	$em = $file['data'];

		    	/**
                Redirigir a '../editar-libro.php'
                y pasando el mensaje de error y la identificación
                **/
		    	header("Location: ../edit-book.php?error=$em&id=$id");
		    	exit;
		    }else {
              # ruta actual de la portada del libro
		      $c_p_file = "../uploads/files/$current_file";

		      # Eliminar del servidor
		      unlink($c_p_file);

		      /**
                Obtener el nuevo nombre de archivo
                y el nuevo nombre del archivo
                **/
	           $file_URL = $file['data'];

	            #actualizar solo los datos
	          	$sql = "UPDATE books
	          	        SET title=?,
	          	            author_id=?,
	          	            description=?,
	          	            category_id=?,
	          	            file=?
	          	        WHERE id=?";
	          	$stmt = $conn->prepare($sql);
				$res  = $stmt->execute([$title, $author, $description, $category, $file_URL, $id]);

			    /**
                Si no hay ningún error mientras
                actualizando los datos
                **/
			     if ($res) {
			     	# mensaje de exito
			     	$sm = "Actualizado exitosamente!";
					header("Location: ../edit-book.php?success=$sm&id=$id");
		            exit;
			     }else{
			     	# Error mensaje
			     	$em = "Ocurrió un error!";
					header("Location: ../edit-book.php?error=$em&id=$id");
		            exit;
			     }


		    }
	      
          }else {
          	# actualizar solo los datos
          	$sql = "UPDATE books
          	        SET title=?,
          	            author_id=?,
          	            description=?,
          	            category_id=?
          	        WHERE id=?";
          	$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$title, $author, $description, $category, $id]);

		        /**
                Si no hay ningún error mientras
                actualizando los datos
                **/
		     if ($res) {
		     	# mensaje de exito
		     	$sm = "Actualizado exitosamente!";
				header("Location: ../edit-book.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# Error Mensaje
		     	$em = "Ocurrió un error !";
				header("Location: ../edit-book.php?error=$em&id=$id");
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