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

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

$xoops->disableErrorReporting();

$ret['error'] = 1;

if ( $xoops->security()->check() ) {
    $option = $system->CleanVars($_REQUEST, 'option', 2, 'int');

    $time = time();
    if ( !isset($_SESSION['xooghost_rates' . $page_id]) || $_SESSION['xooghost_rates' . $page_id] < $time ) {
        $_SESSION['xooghost_rates' . $page_id] = $time + 3600;

        $ghost_module = Xooghost::getInstance();
        $ghost_handler = $ghost_module->GhostHandler();

        $ret = $ghost_handler->SetRate( $page_id, $option );
        if ( is_array($ret) && count($ret) > 1) {
        } else {
    }
}
echo json_encode($ret)
?>