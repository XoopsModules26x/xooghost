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
    private $_config = array();
    /**
     * @param null $obj
     */
    public function __construct()
    {        include_once dirname( dirname ( __FILE__ ) ) . '/xoopreferences.php';
        $object = new XooPreferences();
        $this->_config = $object->config;
    }

    /**
     * Maintenance Form
     * @return void
     */
    public function PreferencesForm()
    {        extract( $this->_config );        parent::__construct('', "form_preferences", "preferences.php", 'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        $this->insertBreak(_MI_XOO_GHOST_CONFIG_MAINPAGE,'preferenceTitle');

        //xooghost_main
        $this->addElement( new XoopsFormRadioYN(_MI_XOO_GHOST_CONFIG_MAIN, 'xooghost_main', $xooghost_main) );

        //xooghost_welcome
        $this->addElement( new XoopsFormTextArea(_MI_XOO_GHOST_CONFIG_WELCOME, 'xooghost_welcome', $xooghost_welcome, 12, 12) );

        //xooghost_main_mode
        $main_mode = new XoopsFormSelect(_MI_XOO_GHOST_CONFIG_MAIN_MODE, 'xooghost_main_mode', $xooghost_main_mode, $size = 1);
        $main_mode->addOption('list',   _MI_XOO_GHOST_CONFIG_MAIN_MODE_LIST);
        $main_mode->addOption('table',  _MI_XOO_GHOST_CONFIG_MAIN_MODE_TABLE);
        $main_mode->addOption('select', _MI_XOO_GHOST_CONFIG_MAIN_MODE_SELECT);
        $main_mode->addOption('news',   _MI_XOO_GHOST_CONFIG_MAIN_MODE_NEWS);
        $this->addElement( $main_mode );

        $this->insertBreak(_MI_XOO_GHOST_CONFIG_IMAGE,'preferenceTitle');
        // xooghost_image_size
        $this->addElement( new XoopsFormText(_XOO_GHOST_CONFIG_IMAGE_SIZE, 'xooghost_image_size', 1, 10, $xooghost_image_size) );

        // xooghost_image_width
        $this->addElement( new XoopsFormText(_XOO_GHOST_CONFIG_IMAGE_WIDTH, 'xooghost_image_width', 1, 10, $xooghost_image_width) );

        // xooghost_image_height
        $this->addElement( new XoopsFormText(_XOO_GHOST_CONFIG_IMAGE_HEIGHT, 'xooghost_image_height', 1, 10, $xooghost_image_height) );

        // button
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'save'));
        $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
        $cancel_send = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
        $cancel_send->setExtra("onclick='javascript:history.go(-1);'");
        $button_tray->addElement($cancel_send);
        $this->addElement($button_tray);
    }
}
?>