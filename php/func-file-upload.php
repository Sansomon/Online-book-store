<?php 

# Función auxiliar de carga de archivos
function upload_file($files, $allowed_exs, $path){
   #obtener datos y almacenar
   $file_name = $files['name'];
   $tmp_name  = $files['tmp_name'];
   $error     = $files['error'];

   # si no se produjo ningún error durante la carga
   if ($error === 0) {
   	  
   	  ## obtener la extensión del archivo almacenarlo en var
   	  $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);


	  $file_ex_lc = strtolower($file_ex);


		if (in_array($file_ex_lc, $allowed_exs)) {
			/**
            renombrando el archivo
            con cadenas aleatorias
            **/
			$new_file_name = uniqid("",true).'.'.$file_ex_lc;

			# asignación de ruta de carga
			$file_upload_path = '../uploads/'.$path.'/'.$new_file_name;

			move_uploaded_file($tmp_name, $file_upload_path);

            $sm['status'] = 'success';
	        $sm['data']   = $new_file_name;

	        return $sm;
            
		}else{

	      $em['status'] = 'error';
	      $em['data']   = "You can't upload files of this type";

	      # Devuelve la matriz em
	      return $em;
		}
   }else {
      $em['status'] = 'error';
      $em['data']   = 'Error occurred while uploading!';

      #  Devuelve la matriz em
      return $em;
   }
}