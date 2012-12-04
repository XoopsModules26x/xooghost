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
$url = $system->CleanVars($_REQUEST, 'url', '', 'string');
extract($Xooghost_config['xooghost_qrcode']);

if ( count($_GET) > 1 ) {    if ( isset($_GET['bgcolor']) ) {
        $xoops->registry->set('XGHOST_BGCOLOR', $_GET['bgcolor']);
    }
    $backgroundColor = ($xoops->registry->offsetExists('XGHOSTBGCOLOR')) ? $xoops->registry->get('XGHOSTBGCOLOR') : $backgroundColor;

    if ( isset($_GET['fgcolor']) ) {
        $xoops->registry->set('XGHOSTFGCOLOR', $_GET['fgcolor']);
    }
    $foregroundColor = ($xoops->registry->offsetExists('XGHOSTFGCOLOR')) ? $xoops->registry->get('XGHOSTFGCOLOR') : $foregroundColor;

    if ( isset($_GET['margin']) ) {
        $xoops->registry->set('XGHOSTMARGIN', $_GET['margin']);
    }
    $whiteMargin = ($xoops->registry->offsetExists('XGHOSTMARGIN')) ? $xoops->registry->get('XGHOSTMARGIN') : $whiteMargin;

    if ( isset($_GET['correction']) ) {
        $xoops->registry->set('XGHOSTCORRECTION', $_GET['correction']);
    }
    $CorrectionLevel = ($xoops->registry->offsetExists('XGHOSTCORRECTION')) ? $xoops->registry->get('XGHOSTCORRECTION') : $CorrectionLevel;

    if ( isset($_GET['size']) ) {
        $xoops->registry->set('XGHOSTSIZE', $_GET['size']);
    }
    $matrixPointSize = ($xoops->registry->offsetExists('XGHOSTSIZE')) ? $xoops->registry->get('XGHOSTSIZE') :$matrixPointSize;
}
if ( $url != '' ) {
    $qrcode = new Xoops_QRcode();
    $qrcode->setLevel( intval($CorrectionLevel) );
    $qrcode->setSize( intval($matrixPointSize) );
    $qrcode->setMargin( intval($whiteMargin) );
    $qrcode->setBackground( constant(strtoupper('_' . $backgroundColor)) );
    $qrcode->setForeground( constant(strtoupper('_' . $foregroundColor)) );
    $qrcode->render( $url );
//    include XOOPS_PATH . '/phpqrcode/qrlib.php';
//    QRcode::png($url, false, $CorrectionLevel, $matrixPointSize, $whiteMargin );
}
?>