<?php
/* Smarty version 3.1.31, created on 2022-02-19 18:01:23
  from "C:\wamp\www\Don_Mateo_website_responsive\smarty\templates\logon.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_62113073a11e23_02100605',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ef20f234e40da2588f918f7931cddf7a3b0d15fa' => 
    array (
      0 => 'C:\\wamp\\www\\Don_Mateo_website_responsive\\smarty\\templates\\logon.tpl',
      1 => 1602318468,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62113073a11e23_02100605 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">

  <link rel="stylesheet" href="../css/logon.css">
  <?php echo '<script'; ?>
 src="../js_lib/copyright.js"><?php echo '</script'; ?>
>
  <title>Bake Off Log-in</title>
</head>

<body>
  <div id="mainbox">
    <main>
      <p id=msg><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</p>
      <form method=post action=<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
>
        <label>Logon-id</label>
        <input type=text name=logon>
        <label>Paswoord</label>
        <input type=password name=paswoord>
        <label>Permanent<br>(8 hours)</label>
        <input type=checkbox name=persist>
        <input type=submit name='submit' value=Verzenden class=submit>
        <div class=clearfix></div>
        <div id='vergeten'>
          <a href=../scripts/P_vergeten.php>Paswoord Vergeten?</a>
        </div>
      </form>
    </main>
    <footer>
      <?php echo '<script'; ?>
 language="javascript">
        document.write(copyRight("webontwikkeling.info"));

      <?php echo '</script'; ?>
>
    </footer>
  </div>
</body>

</html>
<?php }
}
