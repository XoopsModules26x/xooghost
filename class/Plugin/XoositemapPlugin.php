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
 * Class XooghostXoositemapPlugin
 */
class XoositemapPlugin extends \Xoops\Module\Plugin\PluginAbstract implements \XoositemapPluginInterface
{
    /**
     * @param $subcategories
     *
     * @return array
     */
    public function Xoositemap($subcategories)
    {
        $helper = \XoopsModules\Xooghost\Helper::getInstance();
        $pageHandler = $helper->getHandler('Page');

        $pages = $pageHandler->getPublished('published', 'desc');

        $sitemap = [];
        foreach ($pages as $k => $page) {
            $sitemap[$k]['id'] = $k;
            $sitemap[$k]['title'] = $page['xooghost_title'];
            $sitemap[$k]['url'] = $page['xooghost_link'];
            $sitemap[$k]['uid'] = $page['xooghost_uid'];
            $sitemap[$k]['uname'] = $page['xooghost_uid_name'];
            $sitemap[$k]['image'] = $page['xooghost_image_link'];
            $sitemap[$k]['time'] = $page['xooghost_time'];
        }

        return $sitemap;
    }

    /**
     * @param $subcategories
     *
     * @return array
     */
    public function Xoositemap_xml($subcategories)
    {
        $helper = \XoopsModules\Xooghost\Helper::getInstance();
        $pageHandler = $helper->getHandler('Page');

        $sitemap = [];
        $time = 0;

        $pages = $pageHandler->getPublished('published', 'desc');
        foreach ($pages as $k => $page) {
            $sitemap[$k]['url'] = $page['xooghost_link'];
            $sitemap[$k]['time'] = $page['xooghost_time'];
            if ($time < $page['xooghost_time']) {
                $time = $page['xooghost_time'];
            }
        }

        return ['dirname' => \XoopsModules\Xooghost\Helper::getInstance()->getModule()->getVar('dirname'), 'time' => $time, 'items' => $sitemap];
    }
}
