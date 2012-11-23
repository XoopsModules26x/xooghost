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

class XooghostPagesForm extends XoopsThemeForm
{
    /**
     * @param null $obj
     */
    public function __construct($obj = null)
    {        $this->xoopsObject = $obj;
    }

    /**
     * Maintenance Form
     * @return void
     */
    public function PageForm()
    {        $xoops = Xoops::getInstance();
        global $xooghost_handler;

        $Xooghost_config = XooGhostPreferences::getInstance()->loadConfig();

        if ($this->xoopsObject->isNew() ) {
            parent::__construct(_AM_XOO_GHOST_ADD, 'form_pages', 'pages.php', 'post', true);
        } else {            parent::__construct(_AM_XOO_GHOST_EDIT . ' : ' . $this->xoopsObject->getVar('xooghost_title'), 'form_pages', 'pages.php', 'post', true);
        }
        $this->setExtra('enctype="multipart/form-data"');

        // Url
        if ($this->xoopsObject->isNew() ) {
            $dirlist = $xooghost_handler->getPhpListAsArray();

            if ( count( $dirlist ) > 0 ) {
                $ele = new XoopsFormSelect('', 'xooghost_url');
                $ele->addOption(0, _AM_XOO_GHOST_CHOOSE);
                $ele->addOptionArray( $dirlist );
                $this->addElement( $ele );
            } else {
                $this->addElement( new XoopsFormText(_XOO_GHOST_URL, 'xooghost_url', 50, 50, $this->xoopsObject->getVar('xooghost_url')), true );
            }
        } else {
            $this->addElement( new XoopsFormHidden('xooghost_url', $this->xoopsObject->getVar('xooghost_url')) );
        }

        // Title
        $this->addElement( new XoopsFormText(_XOO_GHOST_TITLE, 'xooghost_title', 100, 255, $this->xoopsObject->getVar('xooghost_title')) , true );

        // Content
        $this->addElement( new XoopsFormTextArea(_XOO_GHOST_CONTENT, 'xooghost_content', $this->xoopsObject->getVar('xooghost_content'), 7, 50), true );

        // image
        $upload_msg[] = _XOO_GHOST_CONFIG_IMAGE_SIZE . ' : ' . $Xooghost_config['xooghost_image_size'];
        $upload_msg[] = _XOO_GHOST_CONFIG_IMAGE_WIDTH . ' : ' . $Xooghost_config['xooghost_image_width'];
        $upload_msg[] = _XOO_GHOST_CONFIG_IMAGE_HEIGHT . ' : ' . $Xooghost_config['xooghost_image_height'];

        $image_tray = new XoopsFormElementTray(_XOO_GHOST_IMAGE, '' );
        $image_tray->setDescription( $this->message($upload_msg) );
        $image_box = new XoopsFormFile('', 'xooghost_image', 5000000);
        $image_box->setExtra( "size ='70%'") ;
        $image_tray->addElement( $image_box );

        $image_array = XoopsLists :: getImgListAsArray( $xoops->path('uploads') . '/xooghost/images' );
        $image_select = new XoopsFormSelect( '<br />', 'image_list', $this->xoopsObject->getVar('xooghost_image') );
        $image_select->addOptionArray( $image_array );
        $image_select->setExtra( "onchange='showImgSelected(\"select_image\", \"image_list\", \"" . '/xooghost/images/' . "\", \"\", \"" . $xoops->url('uploads') . "\")'" );
        $image_tray->addElement( $image_select );
        $image_tray->addElement( new XoopsFormLabel( '', "<br /><img src='" . $xoops->url('uploads') . '/xooghost/images/' . $this->xoopsObject->getVar('xooghost_image') . "' name='select_image' id='select_image' alt='' />" ) );
        $this->addElement( $image_tray );

        // Meta description
        $this->addElement( new XoopsFormTextArea(_XOO_GHOST_DESCRIPTION, 'xooghost_description', $this->xoopsObject->getVar('xooghost_description'), 7, 50), true );

        // Meta Keywords
        $this->addElement( new XoopsFormTextArea(_XOO_GHOST_KEYWORDS, 'xooghost_keywords', $this->xoopsObject->getVar('xooghost_keywords'), 7, 50), true );

        // Published date
        $published = ($this->xoopsObject->getVar('xooghost_published') == 0) ? time() : $this->xoopsObject->getVar('xooghost_published');
        $this->addElement( new XoopsFormDateTime(_XOO_GHOST_PUBLISHED, 'xooghost_published', 15, $published, false) );

        // display
        $this->addElement( new XoopsFormRadioYN(_XOO_GHOST_DISPLAY, 'xooghost_online',  $this->xoopsObject->getVar('xooghost_online')) );

        // hidden
        $this->addElement( new XoopsFormHidden('xooghost_id', $this->xoopsObject->getVar('xooghost_id')) );
        $this->addElement( new XoopsFormHidden('xooghost_hits', $this->xoopsObject->getVar('xooghost_hits')) );
        $this->addElement( new XoopsFormHidden('xooghost_rates', $this->xoopsObject->getVar('xooghost_rates')) );
        $this->addElement( new XoopsFormHidden('xooghost_like', $this->xoopsObject->getVar('xooghost_like')) );
        $this->addElement( new XoopsFormHidden('xooghost_dislike', $this->xoopsObject->getVar('xooghost_dislike')) );

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

    public function message($msg, $title = '', $class='errorMsg' )
    {
        $ret = "<div class='" . $class . "'>";
        if ( $title != '' ) {
            $ret .= "<strong>" . $title . "</strong>";
        }
        if ( is_array( $msg ) || is_object( $msg ) ) {
            $ret .= implode('<br />', $msg);
        } else {
            $ret .= $msg;
        }
        $ret .= "</div>";
        return $ret;
    }
}
?>