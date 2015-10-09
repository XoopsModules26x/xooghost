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

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class XooghostSearchPlugin
 */
class XooghostSearchPlugin extends Xoops\Module\Plugin\PluginAbstract implements SearchPluginInterface
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
        $ret          = array();

        $criteria = new CriteriaCompo();

        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $criteria->setSort('xooghost_published');
        $criteria->setOrder('DESC');

        $criteria->add(new Criteria('xooghost_online', 1));
        $criteria->add(new Criteria('xooghost_published', 0, '>'));
        $criteria->add(new Criteria('xooghost_published', time(), '<='));

        if (is_array($queries) && $count = count($queries)) {
            foreach ($queries as $k => $v) {
                $criteria_content = new CriteriaCompo();
                $criteria_content->add(new Criteria('xooghost_title', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria_content->add(new Criteria('xooghost_content', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria_content->add(new Criteria('xooghost_description', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria_content->add(new Criteria('xooghost_keywords', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria->add($criteria_content, $andor);
            }
        }

        if ($uid != 0) {
            $criteria->add(new Criteria('xooghost_uid', $uid));
        }

        $ghost_module  = Xooghost::getInstance();
        $ghost_handler = $ghost_module->ghostHandler();

        $pages = $ghost_handler->getObjects($criteria, true, false);

        $k = 0;
        foreach ($pages as $page) {
            $ret[$k]['image']   = 'assets/icons/logo_small.png';
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
