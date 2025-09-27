<?php
/* Smarty version 3.1.31, created on 2025-04-11 18:03:10
  from "D:\Alexis Code Projects\The5AM-Website\1-Full-Site\public_html\smarty\templates\content.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_67f9595e369622_08445869',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5d2d41fafc52cafd7877810f1467ce9a0fc5ed0c' => 
    array (
      0 => 'D:\\Alexis Code Projects\\The5AM-Website\\1-Full-Site\\public_html\\smarty\\templates\\content.tpl',
      1 => 1589655140,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67f9595e369622_08445869 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html lang="nl">

<head>
	<meta charset="UTF-8">
	<link href="../css/content.css" rel="stylesheet" type="text/css">
	<?php echo '<script'; ?>
 src="../js_lib/copyright.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="../ckeditor/ckeditor.js"><?php echo '</script'; ?>
>
	<title>ledenadmin - more info</title>
</head>

<body>
	<div id="wrapper">
		<main>
			<?php echo $_smarty_tpl->tpl_vars['inhoud']->value;?>

			<input type='button' value='Venster sluiten' onclick='window.close()'>
		</main>
		<footer>
			<?php echo '<script'; ?>
>
				document.write(copyRight("webontwikkeling.info"));

			<?php echo '</script'; ?>
>
		</footer>
	</div>
</body>

</html>
<?php }
}
