<?php

   //header('Location:scripts/A_home.php')

   // Obtener la URL solicitada
   $request = trim($_GET['url'] ?? 'home', '/');

   // Definir rutas
   $routes = [
       '' => 'scripts/A_home.php',           // Página principal
       'home' => 'scripts/A_home.php',       // Home
       //'about' => 'scripts/A_about.php',     // Acerca de
       //'contact' => 'scripts/A_contact.php', // Contacto
       //'products' => 'scripts/A_products.php' // Productos
   ];

   // Buscar coincidencia exacta primero
   if (isset($routes[$request])) {
       include $routes[$request];
       exit;
   }

   // Si no hay coincidencia exacta, mostrar 404
   http_response_code(404);
   echo "Página no encontrada - 404";


?>
