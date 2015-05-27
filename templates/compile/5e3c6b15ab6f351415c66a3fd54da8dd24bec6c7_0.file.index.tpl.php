<?php /* Smarty version 3.1.23, created on 2015-05-27 18:20:16
         compiled from "D:/xampp/htdocs/tactun/templates//main/index.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:177385565eec0c49869_58910056%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5e3c6b15ab6f351415c66a3fd54da8dd24bec6c7' => 
    array (
      0 => 'D:/xampp/htdocs/tactun/templates//main/index.tpl',
      1 => 1432743611,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '177385565eec0c49869_58910056',
  'variables' => 
  array (
    'STATIC_PATH' => 0,
    'VERSION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_5565eec0c774f5_13755479',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5565eec0c774f5_13755479')) {
function content_5565eec0c774f5_13755479 ($_smarty_tpl) {
if (!is_callable('smarty_function_nest')) require_once 'D:/xampp/htdocs/tactun/classes/lib/smarty/plugins/function.nest.php';
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '177385565eec0c49869_58910056';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=1.0,width=device-width">
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['STATIC_PATH']->value;?>
/css/out/styles.css?<?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
" />
		<title>Tactun.com</title>
	</head>
	<body>

		<section id="main" class="content">
			<header>
				<div class="welcome">
					<h1>Welcome to Tactun</h1>
				</div>
			</header>
		</section>
		<section class="content">
      <?php echo smarty_function_nest(array('ns'=>'nested_home'),$_smarty_tpl);?>

    </section>
	</body>
</html>
<?php }
}
?>