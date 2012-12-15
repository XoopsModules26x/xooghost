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

function XooSitemap_xooghost( $subcategories = true)
{    $xoops = Xoops::getInstance();
    $xooghost_handler = $xoops->getModuleHandler('xooghost', 'xooghost');
    $pages = $xooghost_handler->getPublished('published', 'desc');

    $sitemap = array();
    foreach ($pages as $k => $page) {        $sitemap[$k]['id']       = $k;        $sitemap[$k]['title']    = $page['xooghost_title'];        $sitemap[$k]['url']      = XOOPS_URL . '/modules/xooghost/' . $page['xooghost_url'];
        $sitemap[$k]['uid']      = $page['xooghost_uid'];
        $sitemap[$k]['uname']    = $page['xooghost_uid_name'];
        $sitemap[$k]['image']    = $page['xooghost_image_link'];
        $sitemap[$k]['date']     = $page['xooghost_published'];
    }
    return $sitemap;
}
?>