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
$module = \Xoops::getInstance()->getHandlerModule()->getByDirname('xooghost');

$i                      = 0;
$adminmenu[$i]['title'] = _MI_XOO_GHOST_INDEX;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = 'home.png';

if (\Xoops::getInstance()->isAdmin()) {
    ++$i;
    $adminmenu[$i]['title'] = _MI_XOO_GHOST_PREFERENCES;
    $adminmenu[$i]['link']  = 'admin/preferences.php';
    $adminmenu[$i]['icon']  = 'administration.png';

    ++$i;
    $adminmenu[$i]['title'] = _MI_XOO_GHOST_MODCONFIG;
    $adminmenu[$i]['link']  = '../system/admin.php?fct=preferences&op=showmod&mod=' . $module->getVar('mid');
    $adminmenu[$i]['icon']  = 'configs.png';
}

++$i;
$adminmenu[$i]['title'] = _MI_XOO_GHOST_PAGES;
$adminmenu[$i]['link']  = 'admin/pages.php';
$adminmenu[$i]['icon']  = 'content.png';

if (\Xoops::getInstance()->isActiveModule('comments')) {
    ++$i;
    $adminmenu[$i]['title'] = _MI_COMMENTS_NAME;
    $adminmenu[$i]['link']  = '../comments/admin/main.php?module=' . $module->getVar('mid');
    $adminmenu[$i]['icon']  = 'comments.png';
}

++$i;
$adminmenu[$i]['title'] = _MI_XOO_GHOST_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = 'about.png';
