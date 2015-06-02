<?php /* Smarty version 3.1.23, created on 2015-06-02 10:02:14
         compiled from "D:/xampp/htdocs/tactun/templates//main/home.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:24114556d630639a060_96313076%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3170dc549a883078fb284c44b9ac8fce02c0453d' => 
    array (
      0 => 'D:/xampp/htdocs/tactun/templates//main/home.tpl',
      1 => 1433232132,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '24114556d630639a060_96313076',
  'variables' => 
  array (
    'ns' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.23',
  'unifunc' => 'content_556d63063cacf2_73670007',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_556d63063cacf2_73670007')) {
function content_556d63063cacf2_73670007 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '24114556d630639a060_96313076';
?>
<div class="welcome">
  <h1>Demo Nested View which can be updated separately by Ajax</h1>  
  <h3>Inserting row...(MySql)</h3>
  <h3>Updating row...(MySql)</h3>
  <h2>Hello "<?php echo $_smarty_tpl->tpl_vars['ns']->value['demoDto']->getName();?>
"</h2>
</div>
<?php }
}
?>