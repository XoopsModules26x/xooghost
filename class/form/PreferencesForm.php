<?php

namespace XoopsModules\Xooghost\Form;

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
 * Class PreferencesForm
 */
class PreferencesForm extends \Xoops\Form\ThemeForm
{
    private $colors
        = [
            'Aqua' => '#00FFFF',
            'Black' => '#000000',
            'Blue' => '#0000FF',
            'Fuchsia' => '#FF00FF',
            'Gray' => '#808080',
            'Green' => '#008000',
            'Lime' => '#00FF00',
            'Maroon' => '#800000',
            'Navy' => '#000080',
            'Olive' => '#808000',
            'Purple' => '#800080',
            'Red' => '#FF0000',
            'Silver' => '#C0C0C0',
            'Teal' => '#008080',
            'White' => '#FFFFFF',
            'Yellow' => '#FFFF00',
        ];

    private $config = [];

    /**
     * @internal param null $obj
     */
    public function __construct()
    {
        $this->config = \XoopsModules\Xooghost\Preferences::getInstance()->loadConfig();

        extract($this->config);
        parent::__construct('', 'form_preferences', 'preferences.php', 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        $tabTray = new \Xoops\Form\TabTray('', 'uniqueid');

        /**
         * Main page
         */
        $tab1 = new \Xoops\Form\Tab(_XOO_CONFIG_MAINPAGE, 'tabid-1');
        //xooghost_main
        $tab1->addElement(new \Xoops\Form\RadioYesNo(_XOO_CONFIG_MAIN, 'xooghost_main', $xooghost_main));

        //xooghost_welcome
        $tab1->addElement(new \Xoops\Form\TextArea(_XOO_CONFIG_WELCOME, 'xooghost_welcome', $xooghost_welcome, 12, 12));

        //xooghost_main_mode
        $main_mode = new \Xoops\Form\Select(_XOO_CONFIG_MAIN_MODE, 'xooghost_main_mode', $xooghost_main_mode, $size = 1);
        $main_mode->addOption('blog', _XOO_CONFIG_MAIN_MODE_BLOG);
        $main_mode->addOption('list', _XOO_CONFIG_MAIN_MODE_LIST);
        $main_mode->addOption('select', _XOO_CONFIG_MAIN_MODE_SELECT);
        $main_mode->addOption('table', _XOO_CONFIG_MAIN_MODE_TABLE);
        $tab1->addElement($main_mode);

        // limit per page
        $tab1->addElement(new \Xoops\Form\Text(_XOO_CONFIG_LIMIT_MAIN, 'xooghost_limit_main', 1, 10, $xooghost_limit_main));

        // date format
        $main_mode = new \Xoops\Form\Select(_XOO_CONFIG_DATE_FORMAT, 'xooghost_date_format', $xooghost_date_format, $size = 1);
        try {
            $main_mode->addOption('_DATESTRING', \Xoops\Core\Locale\Time::formatDateTime(new \DateTime('now'), 'long'));
        }
        catch (\Exception $e) {
        }
        try {
            $main_mode->addOption('_MEDIUMDATESTRING', \Xoops\Core\Locale\Time::formatDateTime(new \DateTime('now'), 'medium'));
        }
        catch (\Exception $e) {
        }
        try {
            $main_mode->addOption('_SHORTDATESTRING', \Xoops\Core\Locale\Time::formatDateTime(new \DateTime('now'), 'short'));
        }
        catch (\Exception $e) {
        }

        $tab1->addElement($main_mode);

        $tabTray->addElement($tab1);

        /**
         * Images
         */
        $tab2 = new \Xoops\Form\Tab(_XOO_CONFIG_IMAGE, 'tabid-2');
        // xooghost_image_size
        $tab2->addElement(new \Xoops\Form\Text(_XOO_GHOST_CONFIG_IMAGE_SIZE, 'xooghost_image_size', 1, 10, $xooghost_image_size));

        // xooghost_image_width
        $tab2->addElement(new \Xoops\Form\Text(_XOO_GHOST_CONFIG_IMAGE_WIDTH, 'xooghost_image_width', 1, 10, $xooghost_image_width));

        // xooghost_image_height
        $tab2->addElement(new \Xoops\Form\Text(_XOO_GHOST_CONFIG_IMAGE_HEIGHT, 'xooghost_image_height', 1, 10, $xooghost_image_height));

        $tabTray->addElement($tab2);

        /**
         * Rate / Like - Dislike
         */
        $rld = new \Xoops\Form\Tab(_XOO_CONFIG_RLD, 'tabid-rld');

        // Rate / Like / Dislike Mode
        $rld_mode = new \Xoops\Form\Select(_XOO_CONFIG_RLD_MODE, 'xooghost_rld[rld_mode]', $xooghost_rld['rld_mode']);
        $rld_mode->addOption('none', _XOO_CONFIG_RLD_NONE);
        $rld_mode->addOption('rate', _XOO_CONFIG_RLD_RATE);
        $rld_mode->addOption('likedislike', _XOO_CONFIG_RLD_LIKEDISLIKE);
        $rld->addElement($rld_mode);

        $rate_scale = new \Xoops\Form\Select(_XOO_CONFIG_RATE_SCALE, 'xooghost_rld[rate_scale]', $xooghost_rld['rate_scale']);
        for ($i = 4; $i <= 10; ++$i) {
            $rate_scale->addOption($i, $i);
        }
        $rld->addElement($rate_scale);

        $tabTray->addElement($rld);

        $this->addElement($tabTray);

        /**
         * Buttons
         */
        $buttonTray = new \Xoops\Form\ElementTray('', '');
        $buttonTray->addElement(new \Xoops\Form\Hidden('op', 'save'));

        $buttonSubmit = new \Xoops\Form\Button('', 'submit', \XoopsLocale::A_SUBMIT, 'submit');
        $buttonSubmit->setClass('btn btn-success');
        $buttonTray->addElement($buttonSubmit);

        $buttonReset = new \Xoops\Form\Button('', 'reset', \XoopsLocale::A_RESET, 'reset');
        $buttonReset->setClass('btn btn-warning');
        $buttonTray->addElement($buttonReset);

        $buttonCancel = new \Xoops\Form\Button('', 'cancel', \XoopsLocale::A_CANCEL, 'button');
        $buttonCancel->setExtra("onclick='javascript:history.go(-1);'");
        $buttonCancel->setClass('btn btn-danger');
        $buttonTray->addElement($buttonCancel);

        $this->addElement($buttonTray);
    }
}
