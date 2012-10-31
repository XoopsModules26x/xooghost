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
$xoops = Xoops::getInstance();
$xoops->loadLanguage('common', 'xooghost');

$xooghost_handler = $xoops->getModuleHandler('xooghost', 'xooghost');

if ( $xooghost_url = basename($_SERVER['SCRIPT_NAME']) == 'index.php') {    $xoops->header('xooghost_index.html');
} else {    $xoops->header('xooghost_page.html');
    $page = $xooghost_handler->getByURL($xooghost_url);
    $xoops->tpl->assign('page', $page);
    $xoops->theme->addMeta($type = 'meta', 'description', $page['xooghost_description']);
    $xoops->theme->addMeta($type = 'meta', 'keywords', $page['xooghost_keywords']);
}
$xoops->theme->addStylesheet('modules/xooghost/css/module.css');


include_once dirname ( __FILE__ ) . '/class/xoopreferences.php';
$object = new XooPreferences();
$xooGhost_config = $object->config;
?>