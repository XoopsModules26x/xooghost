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

$modversion['name']           = _MI_XOO_GHOST_NAME;
$modversion['description']    = _MI_XOO_GHOST_DESC;
$modversion['version']        = 1.00;
$modversion['author']         = 'JEN Laurent';
$modversion['nickname']       = 'DuGris';
$modversion['credits']        = 'The XOOPS Project';
$modversion['license']        = 'GNU GPL 2.0';
$modversion['license_url']    = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official']       = 1;
$modversion['help']           = 'page=help';
$modversion['image']          = 'images/xooghost_logo.png';
$modversion['dirname']        = 'xooghost';

// about
$modversion['release_date']        = '2012/10/01';
$modversion['module_website_url']  = 'dugris.xoofoo.org';
$modversion['module_website_name'] = 'XooFoo.org - Laurent JEN';
$modversion['module_status']       = 'alpha';
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = '2.6.0';
$modversion['min_db']              = array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

// paypal
$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'dugris93@gmail.com';
$modversion['paypal']['item_name']     = _MI_XOO_GHOST_DESC;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'EUR';

// Admin menu
$modversion['system_menu'] = 1;

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Manage extension
$modversion['extension'] = 0;
$modversion['extension_module'][] = '';

// Scripts to run upon installation or update
$modversion['onInstall'] = 'install/install.php';
$modversion['onUpdate']  = 'install/update.php';
$modversion['onUninstall'] = '';

// JQuery
$modversion['jquery'] = 1;

// Mysql file
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][1] = 'xooghost';
$modversion['tables'][2] = 'xooghost_rld';

// blocks
$i = 0;
$modversion['blocks'][$i]['file']           = 'xooghost_blocks.php';
$modversion['blocks'][$i]['name']           = _MI_XOO_GHOST_BLOCK_NAME;
$modversion['blocks'][$i]['description']    = '';
$modversion['blocks'][$i]['show_func']      = 'xooghost_show';
$modversion['blocks'][$i]['edit_func']      = 'xooghost_edit';
$modversion['blocks'][$i]['options']        = 'list|published|asc';
$modversion['blocks'][$i]['template']       = 'xooghost_block.html';

// Users Synchronize post
$modversion['sync']['table_name'] = 'xooghost';
$modversion['sync']['uid_column'] = 'xooghost_uid';
$modversion['sync']['criteria']   = new Criteria('xooghost_online', 1);

// Menu
$modversion['hasMain'] = 1;
$xoops = Xoops::getInstance();

if ( is_object($xoops->module) && $xoops->module->dirname() == 'xooghost' && !$xoops->isAdminSide ) {

    $ghost_module = Xooghost::getInstance();
    $ghost_handler = $ghost_module->getHandler('xooghost_page');
    $ghost_config = $ghost_module->LoadConfig();

    if ( $ghost_config['xooghost_main'] ) {
        $i = 0;
        $pages = $ghost_handler->getPublished();
        foreach ($pages as $page) {
            $modversion['sub'][$i]['name']  = $page['xooghost_title'];
            $modversion['sub'][$i]['url']   = $page['xooghost_url'];
            $i++;
        }
    }
}
?>