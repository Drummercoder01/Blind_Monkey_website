<?php
try
{
	require("../code/initialisatie.inc.php");
	// Ruta del archivo a editar
	$file_path = "../content/Y_about_text_I.html";

	
	// Guardar si se envió el formulario
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editor'])) {
	    $_inhoud = $_POST['editor'];
	    file_put_contents($file_path, $_inhoud);
	}
	// Leer el contenido del archivo
	if (file_exists($file_path)) {
	    $_inhoud = file_get_contents($file_path);
	}

	// Inicializar variable
	$_inhoud= "
	<div class='bg-dark text-white'>

		<div class='container py-5'>
		    <div class='row justify-content-center'>
		        <div class='col-lg-10'>
				    <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-events'>About</h1>
		            <form method='post'>
		                <textarea name='editor' id='editor'>".htmlspecialchars($_inhoud)."</textarea>
		                <div class='text-center mt-4'>
		                    <button type='submit' class='btn btn-light px-4 py-2 fw-bold'>Save</button>
		                </div>
		            </form>
		        </div>
		    </div>
		</div>

		<script>
		    ClassicEditor
		        .create(document.querySelector('#editor'))
		        .catch(error => {
		            console.error(error);
		        });
		</script>

	</div>";


	require("../code/output_admin.inc.php");
}
catch (Exception $e)
{
	// exception handling funtions 
	include("../php_lib/myExceptionHandling.inc.php"); 
	echo myExceptionHandling($_e,"../logs/error_log.csv");
}
?>



