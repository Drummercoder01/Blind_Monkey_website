<?php
	//send to new order via email    
	ini_set("SMTP", "ziehoeveelmensenbetaalden1euro.com ");
	ini_set("sendmail_from", "donmateo@ziehoeveelmensenbetaalden1euro.com");
	$_headers = array(
	  	'MIME-Version' => '1.0',
	  	'Content-type' => 'text/html;charset=UTF-8',
	  	'From' => 'Don-Mateo@info.com',
	  	'Reply-To' => 'donmateo@ziehoeveelmensenbetaalden1euro.com'
	 );
    
	$_onderwerp = "Nueva reserva Don Mateo";
	$_bericht = "Estimado/a $_nombre, su reserva se ha registrado correctamente. <br><br>
	<b>Reserva Nro:</b> $_nro_orden <br>		
    <b>Producto:</b> $_producto <br>
    <b>Invitados:</b> $_personas <br>
    <b>Entradas:</b> $_entradas <br>
    <b>Fecha:</b> $_fecha <br>
    <b>Hora:</b> $_hora <br>
    <b>Nombre:</b> $_nombre <br>
    <b>Telefono:</b> $_telefono <br>
    <b>E-mail:</b> $_mail <br>
    <b>Direccion del Evento:</b> $_direccion <br>
    <b>Nota:</b> $_nota <br><br>
    En la brevedad posible nos pondremos en contacto para confirmar los detalles.<br><br>
    Atentamente<br>
    <b><i>Don Mateo</i></b>  
    ";
    
	// Array of recipients
	$recipients = array('bateroale@gmail.com', 'velazquez.art@gmail.com', $_mail);
    
	// Send email to each recipient
	foreach ($recipients as $_to) {
	  mail($_to, $_onderwerp, $_bericht, $_headers);
	}
      
?>
