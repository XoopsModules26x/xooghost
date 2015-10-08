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

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class XooghostPreferencesForm
 */
class XooghostPreferencesForm extends Xoops\Form\ThemeForm
{
    private $_colors
        = array(
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
     * @internal param null $obj
     */
    public function __construct()
    {
        $this->_config = XooGhostPreferences::getInstance()->loadConfig();

        extract($this->_config);
        parent::__construct('', 'form_preferences', 'preferences.php', 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        $tabtray = new Xoops\Form\TabTray('', 'uniqueid');

        /**
         * Main page
         */
        $tab1 = new Xoops\Form\Tab(_XOO_CONFIG_MAINPAGE, 'tabid-1');
        //xooghost_main
        $tab1->addElement(new Xoops\Form\RadioYesNo(_XOO_CONFIG_MAIN, 'xooghost_main', $xooghost_main));

        //xooghost_welcome
        $tab1->addElement(new Xoops\Form\TextArea(_XOO_CONFIG_WELCOME, 'xooghost_welcome', $xooghost_welcome, 12, 12));

        //xooghost_main_mode
        $main_mode = new Xoops\Form\Select(_XOO_CONFIG_MAIN_MODE, 'xooghost_main_mode', $xooghost_main_mode, $size = 1);
        $main_mode->addOption('blog', _XOO_CONFIG_MAIN_MODE_BLOG);
        $main_mode->addOption('list', _XOO_CONFIG_MAIN_MODE_LIST);
        $main_mode->addOption('select', _XOO_CONFIG_MAIN_MODE_SELECT);
        $main_mode->addOption('table', _XOO_CONFIG_MAIN_MODE_TABLE);
        $tab1->addElement($main_mode);

        // limit per page
        $tab1->addElement(new Xoops\Form\Text(_XOO_CONFIG_LIMIT_MAIN, 'xooghost_limit_main', 1, 10, $xooghost_limit_main));

        // date format
        $main_mode = new Xoops\Form\Select(_XOO_CONFIG_DATE_FORMAT, 'xooghost_date_format', $xooghost_date_format, $size = 1);
        $main_mode->addOption('_DATESTRING', XoopsLocale::_DATESTRING);
        $main_mode->addOption('_MEDIUMDATESTRING', XoopsLocale::_MEDIUMDATESTRING);
        $main_mode->addOption('_SHORTDATESTRING', XoopsLocale::_SHORTDATESTRING);
        $tab1->addElement($main_mode);

        $tabtray->addElement($tab1);

        /**
         * Images
         */
        $tab2 = new Xoops\Form\Tab(_XOO_CONFIG_IMAGE, 'tabid-2');
        // xooghost_image_size
        $tab2->addElement(new Xoops\Form\Text(_XOO_GHOST_CONFIG_IMAGE_SIZE, 'xooghost_image_size', 1, 10, $xooghost_image_size));

        // xooghost_image_width
        $tab2->addElement(new Xoops\Form\Text(_XOO_GHOST_CONFIG_IMAGE_WIDTH, 'xooghost_image_width', 1, 10, $xooghost_image_width));

        // xooghost_image_height
        $tab2->addElement(new Xoops\Form\Text(_XOO_GHOST_CONFIG_IMAGE_HEIGHT, 'xooghost_image_height', 1, 10, $xooghost_image_height));

        $tabtray->addElement($tab2);

        /**
         * Rate / Like - Dislike
         */
        $rld = new Xoops\Form\Tab(_XOO_CONFIG_RLD, 'tabid-rld');

        // Rate / Like / Dislike Mode
        $rld_mode = new Xoops\Form\Select(_XOO_CONFIG_RLD_MODE, 'xooghost_rld[rld_mode]', $xooghost_rld['rld_mode']);
        $rld_mode->addOption('none', _XOO_CONFIG_RLD_NONE);
        $rld_mode->addOption('rate', _XOO_CONFIG_RLD_RATE);
        $rld_mode->addOption('likedislike', _XOO_CONFIG_RLD_LIKEDISLIKE);
        $rld->addElement($rld_mode);

        $rate_scale = new Xoops\Form\Select(_XOO_CONFIG_RATE_SCALE, 'xooghost_rld[rate_scale]', $xooghost_rld['rate_scale']);
        for ($i = 4; $i <= 10; ++$i) {
            $rate_scale->addOption($i, $i);
        }
        $rld->addElement($rate_scale);

        $tabtray->addElement($rld);

        $this->addElement($tabtray);

        /**
         * Buttons
         */
        $button_tray = new Xoops\Form\ElementTray('', '');
        $button_tray->addElement(new Xoops\Form\Hidden('op', 'save'));

        $button = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
        $button->setClass('btn btn-success');
        $button_tray->addElement($button);

        $button_2 = new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset');
        $button_2->setClass('btn btn-warning');
        $button_tray->addElement($button_2);

        $button_3 = new Xoops\Form\Button('', 'cancel', XoopsLocale::A_CANCEL, 'button');
        $button_3->setExtra("onclick='javascript:history.go(-1);'");
        $button_3->setClass('btn btn-danger');
        $button_tray->addElement($button_3);

        $this->addElement($button_tray);
    }
}
