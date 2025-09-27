<?php
$_srv = $_SERVER['PHP_SELF'];

if (!isset($_POST['submit']))
{

	echo" <div align='center'>
  <br><br><br><br>
  <h1>PHP hashing resultaat</h1>
       <form  method=post action='$_srv'>
          <table width=300 height=150  cellspacing= 5 >
            <tr valign=middle>
              <td width=86 height=30><h3>string</h3></td>
              <td width=185 align=left><input type=text name='string' /></td>
            </tr>

            <tr valign=middle>
              <td height=30><h3>salt</h3></td>
              <td><input type=text name = 'salt'/></td>
            </tr>

            <tr valign=middle>
              <td height=30 colspan = 2 align=center ><input type = submit  name = 'submit'  value=Verzenden /></td>
            </tr>
          </table> 
      </form> </div>";
}
else
{ 
	$_string = $_POST['string'];
	$_salt = $_POST['salt'];
	$_salt = (isset($_POST['salt']))? $_POST['salt'] : ""; 

	require_once("../php_lib/encrypt.inc.php");
	$_encrypted=encrypt($_string,$_salt);

	echo "<div align='center'>";
	echo "<br><br><br>";
	echo "<h1>PHP hashing resultaat</h1>";

	echo "<p>string &rarr; &quot; $_string&quot; </p>";
	echo "<p>salt &rarr; &quot; $_salt&quot; </p>";

	echo "<p>encrypted (salted) string &rarr; <br>$_encrypted</p>";
	echo "<br><br><br>";
	echo "<a href=$_srv>volgende</a>";
	echo "</div>";
}

?>
