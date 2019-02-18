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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xooghost
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 */
use Xoops\Core\Request;

include dirname(dirname(__DIR__)) . '/mainfile.php';

$helper = \XoopsModules\Xooghost\Helper::getInstance();
$ghostConfig = $helper->loadConfig();
$pageHandler = $helper->getHandler('Page');

\XoopsLoad::load('system', 'system');
$system = \System::getInstance();

$xoops = \Xoops::getInstance();
$xoops->disableErrorReporting();

$page_id = Request::getInt('page_id', 0); //$system->cleanVars($_REQUEST, 'page_id', 0, 'int');
$page = $pageHandler->get($page_id);

$output = Request::getString('output', 'print'); //$system->cleanVars($_REQUEST, 'output', 'print', 'string');

if (is_object($page) && 0 != count($page) && $page->getVar('xooghost_online')) {
    $tpl = new \XoopsTpl();

    $tpl->assign('page', $page->getValues());

    $tpl->assign('width', $ghostConfig['xooghost_image_width']);
    $tpl->assign('height', $ghostConfig['xooghost_image_height']);
    $tpl->assign('xooghost_qrcode', $ghostConfig['xooghost_qrcode']);

    $tpl->assign('print', true);
    $tpl->assign('output', true);
    $tpl->assign('xoops_sitename', $xoops->getConfig('sitename'));
    $tpl->assign('xoops_pagetitle', $page->getVar('xooghost_title') . ' - ' . $xoops->module->getVar('name'));
    $tpl->assign('xoops_slogan', htmlspecialchars($xoops->getConfig('slogan'), ENT_QUOTES));

    if ($xoops->isActiveModule('pdf') && 'pdf' === $output) {
        /*
                $content = $tpl->fetch('module:xooghost/xooghost_page_pdf.tpl');
                $pdf = new Pdf('P', 'A4', _LANGCODE, true, _CHARSET, array(10, 10, 10, 10));
                $pdf->setDefaultFont('Helvetica');
                $pdf->writeHtml($content, false);
                $pdf->Output();
        */
    } else {
        $tpl->display('module:xooghost/xooghost_page_print.tpl');
    }
} else {
    $tpl = new \XoopsTpl();
    $tpl->assign('xoops_sitename', $xoops->getConfig('sitename'));
    $tpl->assign('xoops_slogan', htmlspecialchars($xoops->getConfig('slogan'), ENT_QUOTES));
    $tpl->assign('not_found', true);
    $tpl->display('module:xooghost/xooghost_page_print.tpl');
}
