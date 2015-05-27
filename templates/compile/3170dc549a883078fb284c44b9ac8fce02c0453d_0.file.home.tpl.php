<?php /* Smarty version 3.1.23, created on 2015-05-26 18:11:50
         compiled from "D:/xampp/htdocs/tactun/templates//main/home.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:2669755649b46053149_05848556%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3170dc549a883078fb284c44b9ac8fce02c0453d' => 
    array (
      0 => 'D:/xampp/htdocs/tactun/templates//main/home.tpl',
      1 => 1432656501,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2669755649b46053149_05848556',
  'variables' => 
  array (
    'ns' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_55649b46057836_27184560',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55649b46057836_27184560')) {
function content_55649b46057836_27184560 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '2669755649b46053149_05848556';
?>
<div class="welcome">
  <h1>nested home load</h1>
  <h2>Hello <?php echo $_smarty_tpl->tpl_vars['ns']->value['demoDto']->getName();?>
</h2>
</div>
<?php }
}
?>