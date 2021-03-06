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
 * Class XooghostSearchPlugin
 */
class SearchPlugin extends \Xoops\Module\Plugin\PluginAbstract implements \SearchPluginInterface
{
    /**
     * @param $queries
     * @param $andor
     * @param $limit
     * @param $start
     * @param $uid
     *
     * @return array
     */
    public function search($queries, $andor, $limit, $start, $uid)
    {
        $searchstring = '';
        $ret = [];

        $criteria = new \CriteriaCompo();

        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $criteria->setSort('xooghost_published');
        $criteria->setOrder('DESC');

        $criteria->add(new \Criteria('xooghost_online', 1));
        $criteria->add(new \Criteria('xooghost_published', 0, '>'));
        $criteria->add(new \Criteria('xooghost_published', time(), '<='));

        if (is_array($queries) && $count = count($queries)) {
            foreach ($queries as $k => $v) {
                $criteria_content = new \CriteriaCompo();
                $criteria_content->add(new \Criteria('xooghost_title', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria_content->add(new \Criteria('xooghost_content', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria_content->add(new \Criteria('xooghost_description', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria_content->add(new \Criteria('xooghost_keywords', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria->add($criteria_content, $andor);
            }
        }

        if (0 != $uid) {
            $criteria->add(new \Criteria('xooghost_uid', $uid));
        }

        $helper = \XoopsModules\Xooghost\Helper::getInstance();
        $pageHandler = $helper->getHandler('Page');

        $pages = $pageHandler->getObjects($criteria, true, false);

        $k = 0;
        foreach ($pages as $page) {
            $ret[$k]['image'] = 'assets/icons/logo_small.png';
            $ret[$k]['link'] = $page['xooghost_url'];
            $ret[$k]['title'] = $page['xooghost_title'];
            $ret[$k]['time'] = $page['xooghost_time'];
            $ret[$k]['uid'] = $page['xooghost_uid'];
            $ret[$k]['content'] = $page['xooghost_content'];
            ++$k;
        }

        return $ret;
    }
}
