<?php
echo '<strong>$_SERVER[\'PHP_SELF\'] &rarr;</strong><br><br>';
echo $_SERVER['PHP_SELF'];
echo"<br><br><br><br><br>";
echo '<strong>basename($_SERVER[\'PHP_SELF\'],\'.php\') &rarr;</strong><br><br>';
echo basename($_SERVER['PHP_SELF'],".php");
?>
