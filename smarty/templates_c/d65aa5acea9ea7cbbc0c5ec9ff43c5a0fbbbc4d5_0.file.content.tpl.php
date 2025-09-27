<?php
/* Smarty version 3.1.31, created on 2020-05-11 16:30:20
  from "/Applications/MAMP/htdocs/webo/C_Applicaties/APP_13_final/smarty/templates/content.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5eb9617c5c7101_86755849',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd65aa5acea9ea7cbbc0c5ec9ff43c5a0fbbbc4d5' => 
    array (
      0 => '/Applications/MAMP/htdocs/webo/C_Applicaties/APP_13_final/smarty/templates/content.tpl',
      1 => 1522154989,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5eb9617c5c7101_86755849 (Smarty_Internal_Template $_smarty_tpl) {
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
