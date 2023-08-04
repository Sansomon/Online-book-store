<?php  
session_start();

# Si el administrador ha iniciado sesión
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Si el ID del libro no está configurado
	if (!isset($_GET['id'])) {
		#Redirect to admin.php page
        header("Location: admin.php");
        exit;
	}

	$id = $_GET['id'];

	# Archivo de conexión de base de datos
	include "db_conn.php";

	# Función de ayudante de libro
	include "php/func-book.php";
    $book = get_book($conn, $id);
    
    # Si la identificación no es válida
    if ($book == 0) {
    	#Redirigir a la página admin.php
        header("Location: admin.php");
        exit;
    }

    # Función auxiliar de categoría
	include "php/func-category.php";
    $categories = get_all_categories($conn);

    # función auxiliar de autor
	include "php/func-author.php";
    $authors = get_all_author($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Editar Libro</title>

    <!-- bootstrap 5 CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

</head>
<body>
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="admin.php">Administrador</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" 
		         id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link" 
		             aria-current="page" 
		             href="index.php">Tienda</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-book.php">Añadir Libro</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-category.php">Añadir Categoría</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-author.php">Añadir Autor</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="logout.php">Cerrar Sesión</a>
		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>
     <form action="php/edit-book.php"
           method="post"
           enctype="multipart/form-data" 
           class="shadow p-4 rounded mt-5"
           style="width: 90%; max-width: 50rem;">

     	<h1 class="text-center pb-5 display-4 fs-3">
     		Editar Libro
     	</h1>
     	<?php if (isset($_GET['error'])) { ?>
          <div class="alert alert-danger" role="alert">
			  <?=htmlspecialchars($_GET['error']); ?>
		  </div>
		<?php } ?>
		<?php if (isset($_GET['success'])) { ?>
          <div class="alert alert-success" role="alert">
			  <?=htmlspecialchars($_GET['success']); ?>
		  </div>
		<?php } ?>
     	<div class="mb-3">
		    <label class="form-label">
			Título del libro
		           </label>
		    <input type="text" 
		           hidden
		           value="<?=$book['id']?>" 
		           name="book_id">

		    <input type="text" 
		           class="form-control"
		           value="<?=$book['title']?>" 
		           name="book_title">
		</div>

		<div class="mb-3">
		    <label class="form-label">
			Descripción del libro
		           </label>
		    <input type="text" 
		           class="form-control" 
		           value="<?=$book['description']?>"
		           name="book_description">
		</div>

		<div class="mb-3">
		    <label class="form-label">
			Autor del libro
		           </label>
		    <select name="book_author"
		            class="form-control">
		    	    <option value="0">
					Seleccionar autor
		    	    </option>
		    	    <?php 
                    if ($authors == 0) {
                    	# ¡Hacer nada!
                    }else{
		    	    foreach ($authors as $author) { 
		    	    	if ($book['author_id'] == $author['id']) { ?>
		    	    	<option 
		    	    	  selected
		    	    	  value="<?=$author['id']?>">
		    	    	  <?=$author['name']?>
		    	        </option>
		    	        <?php }else{ ?>
						<option 
							value="<?=$author['id']?>">
							<?=$author['name']?>
						</option>
		    	   <?php }} } ?>
		    </select>
		</div>

		<div class="mb-3">
		    <label class="form-label">
			Categoría de libro
		           </label>
		    <select name="book_category"
		            class="form-control">
		    	    <option value="0">
		    	    	Seleccionar categoría
		    	    </option>
		    	    <?php 
                    if ($categories == 0) {
            
                    }else{
		    	    foreach ($categories as $category) { 
		    	    	if ($book['category_id'] == $category['id']) { ?>
		    	    	<option 
		    	    	  selected
		    	    	  value="<?=$category['id']?>">
		    	    	  <?=$category['name']?>
		    	        </option>
		    	        <?php }else{ ?>
						<option 
							value="<?=$category['id']?>">
							<?=$category['name']?>
						</option>
		    	   <?php }} } ?>
		    </select>
		</div>

		<div class="mb-3">
		    <label class="form-label">
			Portada del libro
		           </label>
		    <input type="file" 
		           class="form-control" 
		           name="book_cover">

		     <input type="text" 
		           hidden
		           value="<?=$book['cover']?>" 
		           name="current_cover">

		    <a href="uploads/cover/<?=$book['cover']?>"
		       class="link-dark">Portada actual</a>
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           Archivo
		           </label>
		    <input type="file" 
		           class="form-control" 
		           name="file">

		     <input type="text" 
		           hidden
		           value="<?=$book['file']?>" 
		           name="current_file">

		    <a href="uploads/files/<?=$book['file']?>"
		       class="link-dark">Archivo actual</a>
		</div>

	    <button type="submit" 
	            class="btn btn-primary">
	            Actualizar</button>
     </form>
	</div>
</body>
</html>

<?php }else{
  header("Location: login.php");
  exit;
} ?>