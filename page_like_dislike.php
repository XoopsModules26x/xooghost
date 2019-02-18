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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xooghost
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 */
use Xoops\Core\Request;

include __DIR__ . '/header.php';

$xoops->disableErrorReporting();

$ret['error'] = 1;

if ($xoops->security()->check()) {
    $page_id = Request::getInt('page_id', 0); //$system->cleanVars($_REQUEST, 'page_id', 0, 'int');
    $option = Request::getInt('option', 0); //$system->cleanVars($_REQUEST, 'option', 2, 'int');

    $time = time();
    if (!isset($_SESSION['xooghost_like' . $page_id]) || $_SESSION['xooghost_like' . $page_id] < $time) {
        $_SESSION['xooghost_like' . $page_id] = $time + 3600;

        $helper = \XoopsModules\Xooghost\Helper::getInstance();
        $pageHandler = $helper->getHandler('Page');

        $ret = $pageHandler->setLikeDislike($page_id, $option);
        if (is_array($ret) && count($ret) > 1) {
            $ret['error'] = 0;
        } else {
            $ret['error'] = 1;
        }
    }
}
echo json_encode($ret);
