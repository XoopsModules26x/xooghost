<?php
/**
 * Xooghost module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xooghost
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';

$op = '';
if ( isset( $_POST ) ){
    foreach ( $_POST as $k => $v )  {
        ${$k} = $v;
    }
}
if ( isset( $_GET ) ){
    foreach ( $_GET as $k => $v )  {
        ${$k} = $v;
    }
}

$script_name = basename($_SERVER['SCRIPT_NAME'], '.php');

XoopsLoad::load('system', 'system');
$system = System::getInstance();

$xoops = Xoops::getInstance();
if ($script_name != 'about') {    $xoops->header('xooghost_' . $script_name . '.html');} else {    $xoops->header();}
$xoops->theme()->addStylesheet('modules/xooghost/css/moduladmin.css');

$admin_page = new XoopsModuleAdmin();
if ($script_name != 'about' && $script_name != 'index') {
    $admin_page->renderNavigation( basename($_SERVER['SCRIPT_NAME']) );
} elseif ($script_name != 'index') {
    $admin_page->displayNavigation( basename($_SERVER['SCRIPT_NAME']) );
}

$ghost_module = Xooghost::getInstance();
$ghost_config = $ghost_module->LoadConfig();
$ghost_handler = $ghost_module->GhostHandler();
?>