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

class XooghostSystemPlugin extends Xoops_Module_Plugin_Abstract implements SystemPluginInterface
{
    public function userPosts($uid)
    {        $ghost_module = Xooghost::getInstance();
        $ghost_handler = $ghost_module->GhostHandler();

        $criteria = new CriteriaCompo();
        $criteria->add( new Criteria('xooghost_online', 1) ) ;
        $criteria->add( new Criteria('xooghost_published', time(), '<=') ) ;
        $criteria->add( new Criteria('xooghost_uid', $uid) );

        return $ghost_handler->getCount($criteria);
    }

    public function waiting()
    {        $ghost_module = Xooghost::getInstance();
        $ghost_handler = $ghost_module->GhostHandler();
        $criteria = new CriteriaCompo(new Criteria('xooghost_online', 0));
        if ($count = $ghost_handler->getCount($criteria)) {            $ret['count'] = $count;
            $ret['name'] = Xoops::getInstance()->getHandlerModule()->getBydirname('xooghost')->getVar('name');
            $ret['link'] = Xoops::getInstance()->url('modules/xooghost/admin/pages.php?online=0');
            return $ret;
        }
        return false;
    }

    public function backend($limit=10)
    {        $xoops = Xoops::getInstance();
        $ghost_module = Xooghost::getInstance();
        $ghost_handler = $ghost_module->GhostHandler();

        $ret = array();
        $messages = $ghost_handler->getPublished('published', 'desc', 0, $limit);
        foreach ($messages as $k => $message) {
            $ret[$k]['title']   = $message['xooghost_title'];
            $ret[$k]['link']    = $xoops->url('modules/xooghost/' . $message['xooghost_url']);
            $ret[$k]['content'] = $message['xooghost_content'];
            $ret[$k]['date']    = $message['xooghost_time'];
        }
        return $ret;
    }

    public function userMenus()
    {
        return array();
    }
}