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

function xooghost_show($options)
{    $xoops = Xoops::getInstance();    $xoops->theme->addStylesheet('modules/xooghost/css/module.css');
    $xooghost_handler = $xoops->getModuleHandler('xooghost', 'xooghost');

    $block['template'] = $options[0];
    $block['pages'] = $xooghost_handler->getPublished();
	return $block;
}

function xooghost_edit($options)
{    $block_form = new XoopsFormElementTray('&nbsp;', '<br />');

    $tmp = new XoopsFormSelect(_MB_XOO_GHOST_MODE . ' : ', 'options[0]', $options[0]);
    $tmp->addOption('list', _MB_XOO_GHOST_MODE_LIST);
    $tmp->addOption('table', _MB_XOO_GHOST_MODE_TABLE);
    $tmp->addOption('select', _MB_XOO_GHOST_MODE_SELECT);
    $block_form->addElement($tmp);
	return $block_form->render();
}
?>