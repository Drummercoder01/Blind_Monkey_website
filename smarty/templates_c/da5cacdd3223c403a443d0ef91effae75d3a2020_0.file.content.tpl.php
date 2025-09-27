<?php
/* Smarty version 3.1.31, created on 2025-04-21 11:26:40
  from "D:\Alexis Code Projects\The5AM-Website\1-Full-Site\smarty\templates\content.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_68062b708a8447_68432292',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'da5cacdd3223c403a443d0ef91effae75d3a2020' => 
    array (
      0 => 'D:\\Alexis Code Projects\\The5AM-Website\\1-Full-Site\\smarty\\templates\\content.tpl',
      1 => 1745234650,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68062b708a8447_68432292 (Smarty_Internal_Template $_smarty_tpl) {
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
	<title>Text-edit</title>
</head>

<body>
	<div id="wrapper">
		<main>
			<?php echo $_smarty_tpl->tpl_vars['inhoud']->value;?>

			<input type='button' value='Close window' onclick='window.close()'>
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
