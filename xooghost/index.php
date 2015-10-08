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

include __DIR__ .  '/header.php';

$start = $system->cleanVars($_REQUEST, 'start', 0, 'int');

$pages = $ghost_handler->getPublished('published', 'desc', $start, $ghost_config['xooghost_limit_main']);

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('xooghost_online', 1));
$criteria->add(new Criteria('xooghost_published', 0, '>'));
$criteria->add(new Criteria('xooghost_published', time(), '<='));

$pages_count = $ghost_handler->getCount($criteria);

$xoops->tpl()->assign('pages', $pages);

// Page navigation
$paginate = new Xoopaginate($pages_count, $ghost_config['xooghost_limit_main'], $start, 'start', '');

// Metas
foreach ($pages as $k => $page) {
    $description[] = $page['xooghost_title'];
}
$xoops->theme()->addMeta($type = 'meta', 'description', XooGhost_getMetaDescription($description));
$xoops->theme()->addMeta($type = 'meta', 'keywords', XooGhost_getMetaKeywords($description));

include __DIR__ .  '/footer.php';
