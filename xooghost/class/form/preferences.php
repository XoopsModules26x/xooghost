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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XooghostPreferencesForm extends XoopsThemeForm
{
    private $_colors = array(
        'Aqua'    => '#00FFFF',
        'Black'   => '#000000',
        'Blue'    => '#0000FF',
        'Fuchsia' => '#FF00FF',
        'Gray'    => '#808080',
        'Green'   => '#008000',
        'Lime'    => '#00FF00',
        'Maroon'  => '#800000',
        'Navy'    => '#000080',
        'Olive'   => '#808000',
        'Purple'  => '#800080',
        'Red'     => '#FF0000',
        'Silver'  => '#C0C0C0',
        'Teal'    => '#008080',
        'White'   => '#FFFFFF',
        'Yellow'  => '#FFFF00',
    );

    private $_config = array();
    /**
     * @param null $obj
     */
    public function __construct()
    {        $this->_config = XooGhostPreferences::getInstance()->loadConfig();
    }

    /**
     * Maintenance Form
     * @return void
     */
    public function PreferencesForm()
    {        extract( $this->_config );        parent::__construct('', 'form_preferences', 'preferences.php', 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        $tabtray = new XoopsFormTabTray('', 'uniqueid');

        /**
         * Main page
         */
        $tab1 = new XoopsFormTab(_XOO_CONFIG_MAINPAGE, 'tabid-1');
        //xooghost_main
        $tab1->addElement( new XoopsFormRadioYN(_XOO_CONFIG_MAIN, 'xooghost_main', $xooghost_main) );

        //xooghost_welcome
        $tab1->addElement( new XoopsFormTextArea(_XOO_CONFIG_WELCOME, 'xooghost_welcome', $xooghost_welcome, 12, 12) );

        //xooghost_main_mode
        $main_mode = new XoopsFormSelect(_XOO_CONFIG_MAIN_MODE, 'xooghost_main_mode', $xooghost_main_mode, $size = 1);
        $main_mode->addOption('blog',   _XOO_CONFIG_MAIN_MODE_BLOG);
        $main_mode->addOption('list',   _XOO_CONFIG_MAIN_MODE_LIST);
        $main_mode->addOption('select', _XOO_CONFIG_MAIN_MODE_SELECT);
        $main_mode->addOption('table',  _XOO_CONFIG_MAIN_MODE_TABLE);
        $tab1->addElement( $main_mode );

        // limit per page
        $tab1->addElement( new XoopsFormText(_XOO_CONFIG_LIMIT_MAIN, 'xooghost_limit_main', 1, 10, $xooghost_limit_main) );

        // date format
        $main_mode = new XoopsFormSelect(_XOO_CONFIG_DATE_FORMAT, 'xooghost_date_format', $xooghost_date_format, $size = 1);
        $main_mode->addOption('_DATESTRING',       _DATESTRING);
        $main_mode->addOption('_MEDIUMDATESTRING', _MEDIUMDATESTRING);
        $main_mode->addOption('_SHORTDATESTRING',  _SHORTDATESTRING);
        $tab1->addElement( $main_mode );

        /**
         * Images
         */
        $tab2 = new XoopsFormTab(_XOO_CONFIG_IMAGE, 'tabid-2');
        // xooghost_image_size
        $tab2->addElement( new XoopsFormText(_XOO_GHOST_CONFIG_IMAGE_SIZE, 'xooghost_image_size', 1, 10, $xooghost_image_size) );

        // xooghost_image_width
        $tab2->addElement( new XoopsFormText(_XOO_GHOST_CONFIG_IMAGE_WIDTH, 'xooghost_image_width', 1, 10, $xooghost_image_width) );

        // xooghost_image_height
        $tab2->addElement( new XoopsFormText(_XOO_GHOST_CONFIG_IMAGE_HEIGHT, 'xooghost_image_height', 1, 10, $xooghost_image_height) );

        $tabtray->addElement($tab1);
        $tabtray->addElement($tab2);
        $tabtray->addElement( $this->rldForm() );
        $tabtray->addElement( $this->QRcodeForm());

        $this->addElement($tabtray);

        // button
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'save'));

        $button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
        $button->setClass('btn btn-success');
        $button_tray->addElement($button);

        $button_2 = new XoopsFormButton('', 'reset', _RESET, 'reset');
        $button_2->setClass('btn btn-warning');
        $button_tray->addElement($button_2);

        $button_3 = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
        $button_3->setExtra("onclick='javascript:history.go(-1);'");
        $button_3->setClass('btn btn-danger');
        $button_tray->addElement($button_3);

        $this->addElement($button_tray);

    }

    /**
     * Rate / Like - Dislike
     */
    private function rldForm()
    {
        $tab3 = new XoopsFormTab(_XOO_CONFIG_RLD, 'tabid-3');
        extract( $this->_config );

        // Rate / Like / Dislike Mode
        $rld_mode = new XoopsFormSelect(_XOO_CONFIG_RLD_MODE, 'xooghost_rld[rld_mode]', $xooghost_rld['rld_mode']);
        $rld_mode->addOption('none',        _XOO_CONFIG_RLD_NONE);
        $rld_mode->addOption('rate',        _XOO_CONFIG_RLD_RATE);
        $rld_mode->addOption('likedislike', _XOO_CONFIG_RLD_LIKEDISLIKE);
        $tab3->addElement( $rld_mode );

        $rate_scale = new XoopsFormSelect(_XOO_CONFIG_RATE_SCALE, 'xooghost_rld[rate_scale]', $xooghost_rld['rate_scale']);
        for ($i=4; $i <= 10; $i++) {
            $rate_scale->addOption($i, $i);
        }
        $tab3->addElement( $rate_scale );
        return $tab3;
    }

    /**
     * QR Code
     */
    private function QRcodeForm()
    {
        $tab4 = new XoopsFormTab(_XOO_CONFIG_QRCODE, 'tabid-4');
        $xoops = Xoops::getinstance();
        if ( $xoops->isActiveModule('qrcode') ) {
            $xoops->theme()->addScript('modules/xooghost/include/qrcode.js');
            extract( $this->_config );

            // use QR code
            $tab4->addElement( new XoopsFormRadioYN(_XOO_CONFIG_QRCODE_USE, 'xooghost_qrcode[use_qrcode]', $xooghost_qrcode['use_qrcode']) );

            // Error Correction Level
            $ecl_mode = new XoopsFormSelect(_XOO_CONFIG_QRCODE_ECL, 'xooghost_qrcode[CorrectionLevel]', $xooghost_qrcode['CorrectionLevel']);
            $ecl_mode->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xooghost' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );
            $ecl_mode->addOption(0,   _XOO_CONFIG_QRCODE_ECL_L);
            $ecl_mode->addOption(1,   _XOO_CONFIG_QRCODE_ECL_M);
            $ecl_mode->addOption(2,   _XOO_CONFIG_QRCODE_ECL_Q);
            $ecl_mode->addOption(3,   _XOO_CONFIG_QRCODE_ECL_H);
            $tab4->addElement( $ecl_mode );

            // Matrix Point Size
            $matrix_mode = new XoopsFormSelect(_XOO_CONFIG_QRCODE_MATRIX, 'xooghost_qrcode[matrixPointSize]', $xooghost_qrcode['matrixPointSize']);
            $matrix_mode->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xooghost' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );
            for ($i = 1; $i <= 5; $i++) {
                $matrix_mode->addOption($i, $i);
            }
            $tab4->addElement( $matrix_mode );

            // Margin
            $margin_mode = new XoopsFormSelect(_XOO_CONFIG_QRCODE_MARGIN, 'xooghost_qrcode[whiteMargin]', $xooghost_qrcode['whiteMargin']);
            $margin_mode->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xooghost' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );
            for ($i = 0; $i <= 20; $i++) {
                $margin_mode->addOption($i,   $i);
            }
            $tab4->addElement( $margin_mode );

            // Background & Foreground Color
            $colors_tray = new XoopsFormElementTray(_XOO_CONFIG_QRCODE_COLORS, '' );

            $colors_bg = new XoopsFormSelect(_XOO_CONFIG_QRCODE_COLORS_BG . ': ', 'xooghost_qrcode[backgroundColor]', $xooghost_qrcode['backgroundColor'], 1);
            $colors_bg->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xooghost' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );

            $colors_fg = new XoopsFormSelect(_XOO_CONFIG_QRCODE_COLORS_FG . ': ', 'xooghost_qrcode[foregroundColor]', $xooghost_qrcode['foregroundColor'], 1);
            $colors_fg->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xooghost' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );

            foreach ( $this->_colors as $k => $color ) {
                $colors_bg->addOption( $k );
                $colors_fg->addOption( $k );
            }
            $colors_tray->addElement( new XoopsFormLabel( '', "<div class='floatright'><img src='" . $xoops->url('/modules/xooghost/') . "qrcode.php?url=http://dugris.xoofoo.org' name='image_qrcode' id='image_qrcode' alt='" . _XOO_CONFIG_QRCODE . "' /></div>" ) );
            $colors_tray->addElement( $colors_bg );
            $colors_tray->addElement( new XoopsFormLabel( '', '<br />') );
            $colors_tray->addElement( $colors_fg );

            $tab4->addElement( $colors_tray );
        } else {
            $tab4->addElement( new XoopsFormHidden('xooghost_qrcode[use_qrcode]', 0) );
        }

        return $tab4;
    }
}
?>