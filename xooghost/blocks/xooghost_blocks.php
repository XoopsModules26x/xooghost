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
 *
 * @param $options
 *
 * @return
 */

function xooghost_show($options)
{
    $ghost_module  = Xooghost::getInstance();
    $ghost_config  = $ghost_module->LoadConfig();
    $ghost_handler = $ghost_module->GhostHandler();

    $ghost_module->xoops()->theme()->addStylesheet('modules/xooghost/assets/css/module.css');
    $ghost_module->xoops()->theme()->addStylesheet('modules/xooghost/assets/css/block.css');

    $block['template'] = $options[0];
    $block['pages']    = $ghost_handler->getPublished($options[1], $options[2], 0, $options[3]);
    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function xooghost_edit($options)
{
    $ghost_module = Xooghost::getInstance();
    $ghost_config = $ghost_module->LoadConfig();

    $block_form = new XoopsBlockForm();

    $display_mode = new Xoops\Form\Select(_MB_XOO_GHOST_MODE . ' : ', 'options[0]', $options[0]);
    $display_mode->addOption('list', _MB_XOO_GHOST_MODE_LIST);
    $display_mode->addOption('table', _MB_XOO_GHOST_MODE_TABLE);
    $display_mode->addOption('select', _MB_XOO_GHOST_MODE_SELECT);
    $block_form->addElement($display_mode);

    $sort_mode = new Xoops\Form\Select(_MB_XOO_GHOST_SORT . ' : ', 'options[1]', $options[1]);
    $sort_mode->addOption('id', _MB_XOO_GHOST_SORT_ID);
    $sort_mode->addOption('published', _MB_XOO_GHOST_SORT_RECENTS);
    $sort_mode->addOption('hits', _MB_XOO_GHOST_SORT_HITS);

    if ($ghost_config['xooghost_rld']['rld_mode'] != 'none') {
        if ($ghost_config['xooghost_rld']['rld_mode'] == 'rate') {
            $sort_mode->addOption('rates', _MB_XOO_GHOST_SORT_RATES);
        } else {
            $sort_mode->addOption('like', _MB_XOO_GHOST_SORT_LIKE);
            $sort_mode->addOption('dislike', _MB_XOO_GHOST_SORT_DISLIKE);
        }
    }
    $sort_mode->addOption('random', _MB_XOO_GHOST_SORT_RANDOM);
    $block_form->addElement($sort_mode);

    $order_mode = new Xoops\Form\Select(_MB_XOO_GHOST_ORDER . ' : ', 'options[2]', $options[2]);
    $order_mode->addOption('asc', _MB_XOO_GHOST_ORDER_ASC);
    $order_mode->addOption('desc', _MB_XOO_GHOST_ORDER_DESC);
    $block_form->addElement($order_mode);

    $block_form->addElement(new Xoops\Form\Text(_MB_XOO_GHOST_LIMIT, 'options[3]', 1, 2, $options[3]));
    return $block_form->render();
}
