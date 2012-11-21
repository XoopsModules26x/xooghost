<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');

return $config = array(
    'xooghost_main'         => 1,
    'xooghost_welcome'      => '',
    'xooghost_main_mode'    => 'list',
/*
    'xooghost_limit'        => 5,
    'xooghost_date_format'  => "_SHORTDATESTRING",
*/
    'xooghost_image_size'   => 100000,
    'xooghost_image_width'  => 100,
    'xooghost_image_height' => 100,

    'xooghost_qrcode'       => array(
        'use_qrcode'        => 0,
        'CorrectionLevel'   => 'L',
        'matrixPointSize'   => 2,
        'whiteMargin'       => 0,
/*
        'backgroundColor'   => 'FFFFFF',
        'foregroundColor'   => '000000',
*/
    ),
);
?>