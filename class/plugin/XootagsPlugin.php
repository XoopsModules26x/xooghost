<?php

namespace XoopsModules\Xooghost\Plugin;

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

/**
 * Class XooghostXootagsPlugin
 */
class XootagsPlugin extends \Xoops\Module\Plugin\PluginAbstract implements \XootagsPluginInterface
{
    /**
     * @param $items
     *
     * @return array
     */
    public function xootags($items)
    {
        $helper       = \XoopsModules\Xooghost\Helper::getInstance();
        $pageHandler = $helper->getHandler('Page');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('xooghost_published');
        $criteria->setOrder('DESC');

        $criteria->add(new \Criteria('xooghost_online', 1));
        $criteria->add(new \Criteria('xooghost_published', 0, '>'));
        $criteria->add(new \Criteria('xooghost_published', time(), '<='));
        $criteria->add(new \Criteria('xooghost_id', '(' . implode(', ', $items) . ')', 'IN'));

        $pages = $pageHandler->getObjects($criteria, false, false);

        $ret = [];
        $k   = 0;
        foreach ($pages as $page) {
            $ret[$k]['itemid']  = $page['xooghost_id'];
            $ret[$k]['link']    = $page['xooghost_url'];
            $ret[$k]['title']   = $page['xooghost_title'];
            $ret[$k]['time']    = $page['xooghost_time'];
            $ret[$k]['uid']     = $page['xooghost_uid'];
            $ret[$k]['content'] = $page['xooghost_content'];
            ++$k;
        }

        return $ret;
    }
}
