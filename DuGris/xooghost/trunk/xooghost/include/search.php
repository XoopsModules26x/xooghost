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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

function xooghost_search($queryarray, $andor, $limit, $offset, $userid)
{
    $xoops = Xoops::getInstance();
    $searchstring = '';
    $ret = array();

    $criteria = new CriteriaCompo();

    $criteria->setLimit($limit);
    $criteria->setStart($offset);
    $criteria->setSort('xooghost_published');
    $criteria->setOrder('DESC');

    $criteria->add( new Criteria('xooghost_online', 1) ) ;
    $criteria->add( new Criteria('xooghost_published', 0, '>') ) ;
    $criteria->add( new Criteria('xooghost_published', time(), '<=') ) ;

    if ( is_array($queryarray) && $count = count($queryarray) ) {
        foreach ($queryarray as $k => $v) {
            $criteria_content = new CriteriaCompo();
            $criteria_content->add( new Criteria('xooghost_title', '%' . $v . '%', 'LIKE'), 'OR' ) ;
            $criteria_content->add( new Criteria('xooghost_content', '%' . $v . '%', 'LIKE'), 'OR' ) ;
            $criteria_content->add( new Criteria('xooghost_description', '%' . $v . '%', 'LIKE'), 'OR' ) ;
            $criteria_content->add( new Criteria('xooghost_keywords', '%' . $v . '%', 'LIKE'), 'OR' ) ;
            $criteria->add( $criteria_content, $andor);
        }
    }

    if ( $userid != 0 ) {
        $criteria->add( new Criteria('xooghost_uid', $userid) ) ;
    }

    $xooghost_handler = $xoops->getModuleHandler('xooghost', 'xooghost');
    $pages = $xooghost_handler->getObjects($criteria, false, false);

    foreach ( $pages as $k => $page ) {
        $ret[$k]['image']    = 'icons/logo_small.png';
        $ret[$k]['link']     = $page['xooghost_url'] . '?' . $searchstring;
        $ret[$k]['title']    = $page['xooghost_title'];
        $ret[$k]['time']     = $page['xooghost_published'];
        $ret[$k]['uid']      = $page['xooghost_uid'];
        $ret[$k]['content']  = $page['xooghost_description'];
    }
    return $ret;
}
?>