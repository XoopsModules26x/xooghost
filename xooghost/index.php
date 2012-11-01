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

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

$pages = $xooghost_handler->getPublished();

$xoops->tpl->assign('moduletitle', $xoops->module->name() );
$xoops->tpl->assign('form', $xooghost_handler->SelectPage() );
$xoops->tpl->assign('template', $xooGhost_config['xooghost_main_mode'] );
$xoops->tpl->assign('welcome', $xooGhost_config['xooghost_welcome'] );
$xoops->tpl->assign('pages', $pages);

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>