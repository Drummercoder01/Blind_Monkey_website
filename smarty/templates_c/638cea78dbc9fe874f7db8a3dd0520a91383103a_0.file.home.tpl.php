<?php
/* Smarty version 3.1.31, created on 2023-09-07 19:46:07
  from "/home/a102235/domains/donmateopy.com/public_html/smarty/templates/home.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_64fa0c5f94c9f1_45990233',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '638cea78dbc9fe874f7db8a3dd0520a91383103a' => 
    array (
      0 => '/home/a102235/domains/donmateopy.com/public_html/smarty/templates/home.tpl',
      1 => 1690117430,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_64fa0c5f94c9f1_45990233 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="js/slick-master/slick/slick.css">
    <link rel="stylesheet" href="js/slick-master/slick/slick-theme.css">-->
    <!-- Slick CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"/>
    <!-- Slick Theme CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"/>
    <link rel="stylesheet" href="/scss/style.css">

    <title>Don Mateo</title>
    
    <?php echo '<script'; ?>
 src="/js/jquery-3.6.0.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <!--<?php echo '<script'; ?>
 src="/js/slick-master/slick/slick.min.js"><?php echo '</script'; ?>
>-->
    <?php echo '<script'; ?>
 type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/js/slide.js"><?php echo '</script'; ?>
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
</head>

<body>
    <?php echo $_smarty_tpl->tpl_vars['nav']->value;?>


    <div class='row'>
        <?php echo $_smarty_tpl->tpl_vars['inhoud']->value;?>

    </div>

    <?php echo '<script'; ?>
 src="/js/popper.min.js"><?php echo '</script'; ?>
>

	<?php echo '<script'; ?>
>
       const links = document.querySelectorAll('.nav-link');

        links.forEach((link, index) => {
            link.addEventListener('click', (event) => {                
                const activeIndex = localStorage.getItem('activeLinkIndex');
                const lastIndex = activeIndex ? parseInt(activeIndex) : -1;

                if (lastIndex >= 0 && lastIndex !== index) {
                    links[lastIndex].classList.remove('active');
                }

                link.classList.add('active');
                localStorage.setItem('activeLinkIndex', index);
            });
        });

        window.addEventListener('load', () => {
            const activeIndex = localStorage.getItem('activeLinkIndex');
            if (activeIndex !== null) {
                links[activeIndex].classList.add('active');
            }
        });
    <?php echo '</script'; ?>
>
    
</body>

</html><?php }
}
