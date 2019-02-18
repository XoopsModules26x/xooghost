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
class SystemPlugin extends \Xoops\Module\Plugin\PluginAbstract implements \SystemPluginInterface
{
    /**
     * @param int $uid
     *
     * @return mixed
     */
    public function userPosts($uid)
    {
        $helper       = \XoopsModules\Xooghost\Helper::getInstance();
        $pageHandler = $helper->getHandler('Page');

        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('xooghost_online', 1));
        $criteria->add(new \Criteria('xooghost_published', time(), '<='));
        $criteria->add(new \Criteria('xooghost_uid', $uid));

        return $pageHandler->getCount($criteria);
    }

    /**
     * @return bool
     */
    public function waiting()
    {
        $helper       = \XoopsModules\Xooghost\Helper::getInstance();
        $pageHandler = $helper->getHandler('Page');
        $criteria     = new \CriteriaCompo(new \Criteria('xooghost_online', 0));
        if ($count = $pageHandler->getCount($criteria)) {
            $ret['count'] = $count;
            $ret['name']  = \Xoops::getInstance()->getHandlerModule()->getByDirname('xooghost')->getVar('name');
            $ret['link']  = \Xoops::getInstance()->url('modules/xooghost/admin/pages.php?online=0');

            return $ret;
        }

        return false;
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function backend($limit = 10)
    {
        $xoops        = \Xoops::getInstance();
        $helper       = \XoopsModules\Xooghost\Helper::getInstance();
        $pageHandler = $helper->getHandler('Page');

        $ret      = [];
        $messages = $pageHandler->getPublished('published', 'desc', 0, $limit);
        foreach ($messages as $k => $message) {
            $ret[$k]['title']   = $message['xooghost_title'];
            $ret[$k]['link']    = $xoops->url('modules/xooghost/' . $message['xooghost_url']);
            $ret[$k]['content'] = $message['xooghost_content'];
            $ret[$k]['date']    = $message['xooghost_time'];
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function userMenus()
    {
        return [];
    }
}
