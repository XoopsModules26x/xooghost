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

$ghost_module = Xooghost::getInstance();
$ghost_module->loadLanguage('common', 'xooghost');
$Xooghost_config = $ghost_module->LoadConfig();
$xooghost_handler = $ghost_module->getHandler('xooghost_page');

XoopsLoad::load('system', 'system');
$system = System::getInstance();

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$page_id = $system->CleanVars($_REQUEST, 'page_id', 0, 'int');
$page = $xooghost_handler->get($page_id);

$output = $system->CleanVars($_REQUEST, 'output', 'print', 'string');

if ( is_object($page) && count($page) != 0 && $page->getVar('xooghost_online') ) {    $tpl = new XoopsTpl();

    $tpl->assign('page', $page->getValues() );

    $tpl->assign('width', $Xooghost_config['xooghost_image_width'] );
    $tpl->assign('height', $Xooghost_config['xooghost_image_height'] );
    $tpl->assign('xooghost_qrcode', $Xooghost_config['xooghost_qrcode'] );

    $tpl->assign('print', true );
    $tpl->assign('output', true );
    $tpl->assign('xoops_sitename', $xoops->getConfig('sitename'));
    $tpl->assign('xoops_pagetitle', $page->getVar('xooghost_title') . ' - ' . $xoops->module->getVar('name') );
    $tpl->assign('xoops_slogan', htmlspecialchars($xoops->getConfig('slogan'), ENT_QUOTES));

    if ($xoops->isActiveModule('pdf') && $output == 'pdf') {/*
        $content = $tpl->fetch('module:xooghost|xooghost_page_pdf.html');
        $pdf = new Pdf('P', 'A4', _LANGCODE, true, _CHARSET, array(10, 10, 10, 10));
        $pdf->setDefaultFont('Helvetica');
        $pdf->writeHtml($content, false);
        $pdf->Output();
*/
    } else {
        $tpl->display('module:xooghost|xooghost_page_print.html');
    }} else {
    $tpl = new XoopsTpl();
    $tpl->assign('xoops_sitename', $xoops->getConfig('sitename'));
    $tpl->assign('xoops_slogan', htmlspecialchars($xoops->getConfig('slogan'), ENT_QUOTES));
    $tpl->assign('not_found', true);
    $tpl->display('module:xooghost|xooghost_page_print.html');
}
?>