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
extract($ghost_config['xooghost_qrcode']);

if ( count($_GET) > 1 ) {
        $xoops->registry()->set('GHOST_BGCOLOR', $_GET['bgcolor']);
    }
    $backgroundColor = ($xoops->registry()->offsetExists('GHOST_BGCOLOR')) ? $xoops->registry()->get('GHOST_BGCOLOR') : $backgroundColor;

    if ( isset($_GET['fgcolor']) ) {
        $xoops->registry()->set('GHOST_FGCOLOR', $_GET['fgcolor']);
    }
    $foregroundColor = ($xoops->registry()->offsetExists('GHOST_FGCOLOR')) ? $xoops->registry()->get('GHOST_FGCOLOR') : $foregroundColor;

    if ( isset($_GET['margin']) ) {
        $xoops->registry()->set('GHOST_MARGIN', $_GET['margin']);
    }
    $whiteMargin = ($xoops->registry()->offsetExists('GHOST_MARGIN')) ? $xoops->registry()->get('GHOST_MARGIN') : $whiteMargin;

    if ( isset($_GET['correction']) ) {
        $xoops->registry()->set('GHOST_CORRECTION', $_GET['correction']);
    }
    $CorrectionLevel = ($xoops->registry()->offsetExists('GHOST_CORRECTION')) ? $xoops->registry()->get('GHOST_CORRECTION') : $CorrectionLevel;

    if ( isset($_GET['size']) ) {
        $xoops->registry()->set('GHOST_SIZE', $_GET['size']);
    }
    $matrixPointSize = ($xoops->registry()->offsetExists('GHOST_SIZE')) ? $xoops->registry()->get('GHOST_SIZE') :$matrixPointSize;
}
if ( $url != '' ) {
    $qrcode = new Xoops_qrcode();
    $qrcode->setLevel( intval($CorrectionLevel) );
    $qrcode->setSize( intval($matrixPointSize) );
    $qrcode->setMargin( intval($whiteMargin) );
    $qrcode->setBackground( constant(strtoupper('_' . $backgroundColor)) );
    $qrcode->setForeground( constant(strtoupper('_' . $foregroundColor)) );
    $qrcode->render( $url );
} else {
    $contents = '';
    $size = getimagesize($xoops->url('/images/blank.gif'));
    $handle = fopen($xoops->url('/images/blank.gif'), 'rb');
    while (!feof($handle)) {
        $contents .= fread($handle, 1024);
    }
    fclose($handle);
    echo $contents;
}
?>