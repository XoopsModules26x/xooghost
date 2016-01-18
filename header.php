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
 */

use Xoops\Core\Request;

include dirname(dirname(__DIR__)) . '/mainfile.php';
include __DIR__ . '/class/utilities.php';

XoopsLoad::load('system', 'system');
$system = System::getInstance();

$ghostModule  = Xooghost::getInstance();
$ghostConfig  = $ghostModule->loadConfig();
$ghostHandler = $ghostModule->ghostHandler();

$xooghostUrl = basename(Request::getString('SCRIPT_NAME', '', 'SERVER'));
$exclude     = array(
    'footer.php',
    'header.php',
    'index.php',
    'page_like_dislike.php',
    'page_rate.php',
    'qrcode.php',
    'xoops_version.php',);

if (in_array($xooghostUrl, $exclude)) {
    $xoops->header('xooghost_index.tpl');
} else {
    $xoops->header('xooghost_page.tpl');
    $page = $ghostHandler->getByURL($xooghostUrl);
    if (is_object($page) && count($page) != 0 && $page->getVar('xooghost_online') && $page->getVar('xooghost_online')) {
        $_SESSION['xooghost_stat'] = true;
        $time                      = time();
        $Xooghost_id               = $page->getVar('xooghost_id');
        if (!isset($_SESSION['xooghost_view' . $Xooghost_id]) || $_SESSION['xooghost_view' . $Xooghost_id] < $time) {
            $_SESSION['xooghost_view' . $Xooghost_id] = $time + 3600;
            $ghostHandler->SetRead($page);
        }

        // For comments module
        $_GET['ghost_id'] = $page->getVar('xooghost_id');
        if ($plugin = \Xoops\Module\Plugin::getPlugin('xooghost', 'comments')) {
            $xoops->tpl()->assign('xooghost_com', $xoops->isActiveModule('comments'));
        }
        // For comments module

        $content = $page->getValues();
        $content = $page->getRLD($content);
        $xoops->tpl()->assign('page', $content);
        $xoops->tpl()->assign('security', $xoops->security()->createToken());
        $xoops->tpl()->assign('xoops_pagetitle', $page->getVar('xooghost_title') . ' - ' . $xoops->module->getVar('name'));
        $xoops->theme()->addMeta('meta', 'description', $page->getMetaDescription());
        $xoops->theme()->addMeta('meta', 'keywords', $page->getMetaKeywords());
    } else {
        $xoops->tpl()->assign('not_found', true);
    }
}
$xoops->theme()->addStylesheet('modules/xooghost/assets/css/module.css');

$xoops->tpl()->assign('moduletitle', $xoops->module->name());

$xoops->tpl()->assign('template', $ghostConfig['xooghost_main_mode']);
$xoops->tpl()->assign('welcome', $ghostConfig['xooghost_welcome']);
$xoops->tpl()->assign('width', $ghostConfig['xooghost_image_width']);
$xoops->tpl()->assign('height', $ghostConfig['xooghost_image_height']);
$xoops->tpl()->assign('xooghost_rld', $ghostConfig['xooghost_rld']);

$xoops->tpl()->assign('qrcode', $xoops->isActiveModule('qrcode'));

if ($xoops->isActiveModule('notifications')) {
    if ($plugin = \Xoops\Module\Plugin::getPlugin('xooghost', 'notifications') && $xoops->isUser()) {
        $xoops->tpl()->assign('xooghost_not', true);
    }
}
