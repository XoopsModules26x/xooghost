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

include __DIR__ . '/header.php';

$xoops->header();

$admin_page = new \Xoops\Module\Admin();

// extension
$admin_page->addConfigBoxLine(array('comments', 'warning'), 'extension');
$admin_page->addConfigBoxLine(array('pdf', 'warning'), 'extension');
$admin_page->addConfigBoxLine(array('qrcode', 'warning'), 'extension');
$admin_page->addConfigBoxLine(array('xoosocialnetwork', 'warning'), 'extension');
$admin_page->addConfigBoxLine(array('notifications', 'warning'), 'module');

$admin_page->displayIndex();

include __DIR__ . '/footer.php';
