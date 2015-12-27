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

/**
 * Class XooghostXoositemapPlugin
 */
class XooghostXoositemapPlugin extends Xoops\Module\Plugin\PluginAbstract implements XoositemapPluginInterface
{
    /**
     * @param $subcategories
     *
     * @return array
     */
    public function Xoositemap($subcategories)
    {
        $ghostModule  = Xooghost::getInstance();
        $ghostHandler = $ghostModule->ghostHandler();

        $pages = $ghostHandler->getPublished('published', 'desc');

        $sitemap = array();
        foreach ($pages as $k => $page) {
            $sitemap[$k]['id']    = $k;
            $sitemap[$k]['title'] = $page['xooghost_title'];
            $sitemap[$k]['url']   = $page['xooghost_link'];
            $sitemap[$k]['uid']   = $page['xooghost_uid'];
            $sitemap[$k]['uname'] = $page['xooghost_uid_name'];
            $sitemap[$k]['image'] = $page['xooghost_image_link'];
            $sitemap[$k]['time']  = $page['xooghost_time'];
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
        $ghostModule  = Xooghost::getInstance();
        $ghostHandler = $ghostModule->ghostHandler();

        $sitemap = array();
        $time    = 0;

        $pages = $ghostHandler->getPublished('published', 'desc');
        foreach ($pages as $k => $page) {
            $sitemap[$k]['url']  = $page['xooghost_link'];
            $sitemap[$k]['time'] = $page['xooghost_time'];
            if ($time < $page['xooghost_time']) {
                $time = $page['xooghost_time'];
            }
        }

        return array('dirname' => Xooghost::getInstance()->getModule()->getVar('dirname'), 'time' => $time, 'items' => $sitemap);
    }
}