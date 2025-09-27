<?php
/* Smarty version 3.1.31, created on 2022-02-19 18:03:37
  from "C:\wamp\www\Don_Mateo_website_responsive\smarty\templates\bestellen.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_621130f95b4222_76286776',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9a4cc51384d28536a254c93395c3783a00fcc95f' => 
    array (
      0 => 'C:\\wamp\\www\\Don_Mateo_website_responsive\\smarty\\templates\\bestellen.tpl',
      1 => 1602147630,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_621130f95b4222_76286776 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <link href="../css/bestellen.css" rel="stylesheet" type="text/css">

  <?php echo '<script'; ?>
 src="../js_lib/copyright.js"><?php echo '</script'; ?>
>

  <?php
$__section_teller_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_teller']) ? $_smarty_tpl->tpl_vars['__smarty_section_teller'] : false;
$__section_teller_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['jsInclude']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_teller_0_total = $__section_teller_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_teller'] = new Smarty_Variable(array());
if ($__section_teller_0_total != 0) {
for ($__section_teller_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index'] = 0; $__section_teller_0_iteration <= $__section_teller_0_total; $__section_teller_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index']++){
?>
  <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['jsInclude']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index'] : null)];?>
"><?php echo '</script'; ?>
>
  <?php
}
}
if ($__section_teller_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_teller'] = $__section_teller_0_saved;
}
?>

  <title>Bake Of App</title>
</head>

<body>
  <div id="mainbox">
    <header>
      <img src="../images/brood.jpg" height="100%" alt="webontwikkeling" />
      <p>Bake Off</p>
    </header>
    <nav>
      <ul>
        <?php
$__section_teller_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_teller']) ? $_smarty_tpl->tpl_vars['__smarty_section_teller'] : false;
$__section_teller_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['menu']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_teller_1_total = $__section_teller_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_teller'] = new Smarty_Variable(array());
if ($__section_teller_1_total != 0) {
for ($__section_teller_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index'] = 0; $__section_teller_1_iteration <= $__section_teller_1_total; $__section_teller_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index']++){
?>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index'] : null)]['d_link'];?>
"><?php echo $_smarty_tpl->tpl_vars['menu']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index'] : null)]['d_item'];?>

          </a>
        </li>
        <?php
}
}
if ($__section_teller_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_teller'] = $__section_teller_1_saved;
}
?>
      </ul>
    </nav>

    <main>
      <!-- <article id="artleft">
        <?php echo (($tmp = @$_smarty_tpl->tpl_vars['commentaar']->value)===null||$tmp==='' ? "<h1>leden-admin</h1>" : $tmp);?>


      </article>-->
      <article id="artright">
        <?php echo $_smarty_tpl->tpl_vars['inhoud']->value;?>

      </article>
    </main>

    <footer>
      <?php echo '<script'; ?>
 language="javascript">
        document.write(copyRight("webontwikkeling.info"));
      <?php echo '</script'; ?>
>
    </footer>

  </div>

</body></html>
<?php }
}
