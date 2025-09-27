<?php
try {
    require_once("../code/initialisatie.inc.php");
    /*******************************************
    *    (Input en) verwerking
    ********************************************/
    include("../code/header.php");

    $_inhoud.= "
        <div class='row mx-0 px-5'>
            <div class='col-12'>
                <h1 class='titulo' style='margin-top: 130px'>Contacto</h1>
            </div>
            <div class='col-12'>
                <form method='post' enctype='multipart/form-data' id='formulier' action='$_srv'>
                    <div class='row'>                    
                        <div class='form-group col-md-6 mb-3'>
                            <input type='text' class='form-control formContact py-2' name='firstName' id='firstName'
                                placeholder='Nombre' autocomplete='off' required>
                        </div>
                        <div class='form-group col-md-6 mb-3'>
                            <input type='text' class='form-control formContact py-2' name='lastName' id='lastName' placeholder='Apellido'
                                autocomplete='off' required>
                        </div>                    
                        <div class='form-group col-md-6 mb-3'>
                            <input type='email' class='form-control formContact py-2' name='email' id='email' placeholder='E-mail'
                                autocomplete='off' required>
                        </div>
                        <div class='form-group col-md-6 mb-3'>
                            <input type='tel' class='form-control formContact py-2' name='gsm' id='gsm' placeholder='Telefono'
                                autocomplete='off' required>
                        </div>
                        <div class='form-group col-12 mb-3'>
                            <input type='text' class='form-control formContact py-2' name='subject' id='subject' placeholder='Sujeto'
                                autocomplete='off' required>
                        </div>
                        <div class='form-group col-12 mb-3'>
                            <textarea class='form-control formContact py-2' name='message' id='message' rows='3'
                                placeholder='Su mensaje...' required></textarea>
                        </div>
                        <div class='submitHolder col-12 w-100 text-center'>
                            <input class='p-2 px-5 btn fw-bold formSubmit btn1' type='submit' value='ENVIAR' name='submit' id='formSubmit'>
                          
                        </div>
                    </div>
                </form>
            </div>
        </div>";

    if (isset($_POST['submit'])) {
        // Retrieve form data
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $gsm = $_POST['gsm'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
    
        // Get current date and time with UTC-4 offset
        $utc_offset = -4; // UTC-4
        $current_datetime = new DateTime("now", new DateTimeZone("UTC"));
        $current_datetime->modify("$utc_offset hours"); // Add the UTC offset
        $fecha_hora = $current_datetime->format('Y-m-d H:i:s');

        // Perform database insertion
        $_query = "INSERT INTO messages (nombre, apellido, email, telefono, sujeto, mensaje, fecha_hora) VALUES ('$firstName', '$lastName', '$email', '$gsm', '$subject', '$message', '$fecha_hora')";
        $_result = $_PDO->query($_query);

        // Check if the insertion was successful and handle the result accordingly
        if ($_result) {
            // Database insertion successful, send email
            ini_set("SMTP", "ziehoeveelmensenbetaalden1euro.com ");
    		ini_set("sendmail_from", "donmateo@ziehoeveelmensenbetaalden1euro.com");

            $_headers = array(
                'MIME-Version' => '1.0',
                'Content-type' => 'text/html;charset=UTF-8',
                'From' => 'Don-Mateo@info.com',
                'Reply-To' => 'donmateo@ziehoeveelmensenbetaalden1euro.com'
           );

    		$_onderwerp = "Formulario de Contacto Don Mateo";
    		$_bericht = "$firstName $lastName ha enviado un mensaje a través del formulario de contacto de la página web de Don Mateo.<br><br>
            <b>Nombre:</b> $firstName<br>
            <b>Apellido:</b> $lastName<br>
            <b>E-mail:</b> $email<br>
            <b>Telefono:</b> $gsm<br>
            <b>Sujeto:</b> $subject<br>
            <b>Mensaje:</b> $message<br><br>
            <b>Fecha y Hora:</b> $fecha_hora<br><br>
            <b>Atentamente,</b><br>
            <b>Don Mateo</b>";
    
    		// Array of recipients
    		$recipients = array('bateroale@gmail.com','velazquez.art@gmail.com');

    		// Send email to each recipient
    		foreach ($recipients as $_to) {
        	mail($_to, $_onderwerp, $_bericht, $_headers);
   		}

    		$_inhoud = "
                <!-- Bootstrap Modal -->
                <div class='modal fade' id='messageModal' tabindex='-1' role='dialog' aria-labelledby='messageModalLabel' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='messageModalLabel'>Mensaje</h5>
                                <button type='button' class='close' data-bs-dismiss='modal' aria-label='Close' id='modalCloseButton'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <p id='messageText'></p>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal' id='redirectButton'>Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- eind Modal -->
    
                <script>
                    $(document).ready(function() {
                        $('#messageText').text('Su mensaje ha sido enviado con éxito. En breve nos pondremos en contacto con usted.');
                        $('#messageModal').modal('show');
                    });
                </script>

                <script>
                $(document).ready(function() {
                    // Add click event handler to the button
                    $('#redirectButton').on('click', function() {
                        // Redirect to a new URL
                        window.location.href = '../scripts/A_contact.php';
                    });
                });
                </script>
                
                <script>
                    $(document).ready(function() {
                        // Add click event handler to the button
                        $('#modalCloseButton').on('click', function() {
                            // Redirect to a new URL
                            window.location.href = '../scripts/A_contact.php';
                        });
                    });
                </script>";
		} 
        else 
      	{
    		// Database insertion failed
    		$_inhoud = "
            <!-- Bootstrap Modal -->
            <div class='modal fade' id='messageModal' tabindex='-1' role='dialog' aria-labelledby='messageModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='messageModalLabel'>Mensaje</h5>
                            <button type='button' class='close' data-bs-dismiss='modal' aria-label='Close' id='modalCloseButton'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <p id='messageText'></p>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal' id='redirectButton'>Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- eind Modal -->

            <script>
                $(document).ready(function() {
                    $('#messageText').text('Su mensaje ha fallado, por favor inténtelo más tarde.');
                    $('#messageModal').modal('show');
                });
            </script>
            <script>
                $(document).ready(function() {
                    // Add click event handler to the button
                    $('#redirectButton').on('click', function() {
                        // Redirect to a new URL
                        window.location.href = '../scripts/A_contact.php';
                    });
                });
            </script>
            <script>
                $(document).ready(function() {
                    // Add click event handler to the button
                    $('#modalCloseButton').on('click', function() {
                        // Redirect to a new URL
                        window.location.href = '../scripts/A_contact.php';
                    });
                });
                </script>           
            ";      
		}
           
    }    

    // commentaar file definieren  
    /* $_commentaar = "A_home_C.html"; */
    $_menu = 0;
    require_once("../code/output.inc.php");
} catch (Exception $_e) {
    // exception handling funtions 
    include("../php_lib/myExceptionHandling.inc.php"); 
    echo myExceptionHandling($_e, "../logs/error_log.csv");
}
?>