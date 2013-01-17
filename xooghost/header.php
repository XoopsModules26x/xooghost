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

include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';
include dirname(__FILE__) . '/include/functions.php';

XoopsLoad::load('system', 'system');
$system = System::getInstance();

$ghost_module = Xooghost::getInstance();
$ghost_config = $ghost_module->LoadConfig();
$ghost_handler = $ghost_module->GhostHandler();

$Xooghost_url = basename($_SERVER['SCRIPT_NAME']);
$exclude = array(
    'footer.php',
    'header.php',
    'index.php',
    'page_like_dislike.php',
    'page_rate.php',
    'qrcode.php',
    'xoops_version.php',
);

if ( in_array($Xooghost_url, $exclude) ) {    $xoops->header('xooghost_index.html');
} else {    $xoops->header('xooghost_page.html');
    $page = $ghost_handler->getByURL($Xooghost_url);
    if ( is_object($page) && count($page) != 0 && $page->getVar('xooghost_online') && $page->getVar('xooghost_online') ) {        $_SESSION['xooghost_stat'] = true;
        $time = time();
        $Xooghost_id = $page->getVar('xooghost_id');
        if ( !isset($_SESSION['xooghost_view' . $Xooghost_id]) || $_SESSION['xooghost_view' . $Xooghost_id] < $time ) {
            $_SESSION['xooghost_view' . $Xooghost_id] = $time + 3600;
            $ghost_handler->SetRead( $page );
        }

        // For comments module
        $_GET['ghost_id'] = $page->getVar('xooghost_id');
        if ($plugin = Xoops_Module_Plugin::getPlugin('xooghost', 'comments')) {
            $xoops->tpl()->assign('xooghost_com', $xoops->isActiveModule('comments') );
        }
        // For comments module

        $content = $page->getValues();
        $content = $page->getRLD( $content );
        $xoops->tpl()->assign('page', $content );
        $xoops->tpl()->assign('security', $xoops->security()->createToken() );
        $xoops->tpl()->assign('xoops_pagetitle' , $page->getVar('xooghost_title') . ' - ' . $xoops->module->getVar('name') );
        $xoops->theme()->addMeta('meta', 'description', $page->getMetaDescription() );
        $xoops->theme()->addMeta('meta', 'keywords', $page->getMetaKeywords() );
    } else {        $xoops->tpl()->assign('not_found', true);
    }
}
$xoops->theme()->addStylesheet('modules/xooghost/css/module.css');

$xoops->tpl()->assign('moduletitle', $xoops->module->name() );

$xoops->tpl()->assign('template', $ghost_config['xooghost_main_mode'] );
$xoops->tpl()->assign('welcome', $ghost_config['xooghost_welcome'] );
$xoops->tpl()->assign('width', $ghost_config['xooghost_image_width'] );
$xoops->tpl()->assign('height', $ghost_config['xooghost_image_height'] );
$xoops->tpl()->assign('xooghost_rld', $ghost_config['xooghost_rld'] );

$xoops->tpl()->assign('qrcode', $xoops->isActiveModule('qrcode') );

if ($xoops->isActiveModule('notifications')) {    if ($plugin = Xoops_Module_Plugin::getPlugin('xooghost', 'notifications') && $xoops->isUser()) {
        $xoops->tpl()->assign('xooghost_not', true );
    }
}
?>