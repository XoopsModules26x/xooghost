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

XoopsLoad::load('system', 'system');
$system = System::getInstance();

$xoops = Xoops::getInstance();
$xoops->loadLanguage('common', 'xooghost');

$xooghost_handler = $xoops->getModuleHandler('xooghost', 'xooghost');

XoopsLoad::load('xoopreferences', 'xooghost');
$Xooghost_config = XooGhostPreferences::getInstance()->getConfig();
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
    $page = $xooghost_handler->getByURL($Xooghost_url);
    if ( is_object($page) && count($page) != 0 && $page->getVar('xooghost_online') && $page->getVar('xooghost_online') ) {        $time = time();
        $Xooghost_id = $page->getVar('xooghost_id');
        if ( !isset($_SESSION['xooghost_view' . $Xooghost_id]) || $_SESSION['xooghost_view' . $Xooghost_id] < $time ) {
            $_SESSION['xooghost_view' . $Xooghost_id] = $time + 3600;
            $xooghost_handler->SetRead( $page );
        }
        $xoops->tpl->assign('security', $xoops->security->createToken() );
        $xoops->tpl->assign('page', $page->toArray() );
        $xoops->tpl->assign('xoops_pagetitle' , $page->getVar('xooghost_title') . ' - ' . $xoops->module->getVar('name') );
        $xoops->theme->addMeta($type = 'meta', 'description', $page->getVar('xooghost_description'));
        $xoops->theme->addMeta($type = 'meta', 'keywords', $page->getVar('xooghost_keywords'));
    } else {        $xoops->tpl->assign('not_found', true);
    }
}
$xoops->theme->addStylesheet('modules/xooghost/css/module.css');

$xoops->tpl->assign('moduletitle', $xoops->module->name() );

$xoops->tpl->assign('template', $Xooghost_config['xooghost_main_mode'] );
$xoops->tpl->assign('welcome', $Xooghost_config['xooghost_welcome'] );
$xoops->tpl->assign('width', $Xooghost_config['xooghost_image_width'] );
$xoops->tpl->assign('height', $Xooghost_config['xooghost_image_height'] );
$xoops->tpl->assign('xooghost_qrcode', $Xooghost_config['xooghost_qrcode'] );
$xoops->tpl->assign('xooghost_rld', $Xooghost_config['xooghost_rld'] );

?>