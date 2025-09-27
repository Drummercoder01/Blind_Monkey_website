<?php
/* Smarty version 3.1.31, created on 2020-09-29 11:20:40
  from "C:\wamp\www\Bakkerij_Bestelling_Project\APP_13_final\smarty\templates\content.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5f731888c76210_60723303',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e33f1c1e180b15872676aa0f80b27f23fde8e90c' => 
    array (
      0 => 'C:\\wamp\\www\\Bakkerij_Bestelling_Project\\APP_13_final\\smarty\\templates\\content.tpl',
      1 => 1589655138,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f731888c76210_60723303 (Smarty_Internal_Template $_smarty_tpl) {
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
