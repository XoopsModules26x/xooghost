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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xooghost
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 */

use XoopsModules\Xootags\Form;

/**
 * Class PagesForm
 */
class PagesForm extends \Xoops\Form\ThemeForm
{
    /**
     * @param \XoopsModules\Xooghost\Page|\XoopsObject|null $obj
     */
    public function __construct(\XoopsModules\Xooghost\Page $obj = null)
    {
        $this->xoopsObject = $obj;

        /** @var \XoopsModules\Xooghost\Helper $helper */
        $helper       = \XoopsModules\Xooghost\Helper::getInstance();
        $ghostConfig  = $helper->loadConfig();
        $pageHandler = $helper->getHandler('Page');
        $xoops        = \Xoops::getInstance();

        if ($this->xoopsObject->isNew()) {
            parent::__construct(_AM_XOO_GHOST_ADD, 'form_pages', 'pages.php', 'post', true);
        } else {
            parent::__construct(_AM_XOO_GHOST_EDIT . ' : ' . $this->xoopsObject->getVar('xooghost_title'), 'form_pages', 'pages.php', 'post', true);
        }
        $this->setExtra('enctype="multipart/form-data"');

        $tabTray = new \Xoops\Form\TabTray('', 'uniqueid');

        /**
         * Main
         */
        $tab1 = new \Xoops\Form\Tab(_AM_XOO_TABFORM_MAIN, 'tabid-1');
        // Url
        if ($this->xoopsObject->isNew()) {
            $dirlist = $pageHandler->getPhpListAsArray();

            if (count($dirlist) > 0) {
                $ele = new \Xoops\Form\Select('', 'xooghost_url');
                $ele->addOption(0, _AM_XOO_GHOST_CHOOSE);
                $ele->addOptionArray($dirlist);
                $tab1->addElement($ele);
            } else {
                $tab1->addElement(new \Xoops\Form\Text(_XOO_GHOST_URL, 'xooghost_url', 12, 100, $this->xoopsObject->getVar('xooghost_url')), true);
            }
        } else {
            $tab1->addElement(new \Xoops\Form\Hidden('xooghost_url', $this->xoopsObject->getVar('xooghost_url')));
        }

        // Title
        $tab1->addElement(new \Xoops\Form\Text(_XOO_GHOST_TITLE, 'xooghost_title', 12, 100, $this->xoopsObject->getVar('xooghost_title')), true);

        // submitter
        if ($helper->isUserAdmin()) {
            $xooghost_uid = $this->xoopsObject->isNew() ? $xoops->user->getVar('uid') : $this->xoopsObject->getVar('xooghost_uid');
            $tab1->addElement(new \Xoops\Form\SelectUser(_XOO_GHOST_AUTHOR, 'xooghost_uid', true, $xooghost_uid, 1, false));
        } else {
            $xooghost_uid = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
            $tab1->addElement(new \Xoops\Form\Hidden('xooghost_uid', $xooghost_uid));
        }

        // Content
        $tab1->addElement(new \Xoops\Form\TextArea(_XOO_GHOST_CONTENT, 'xooghost_content', $this->xoopsObject->getVar('xooghost_content'), 7, 12), true);

        // image
        $upload_msg[] = _XOO_GHOST_CONFIG_IMAGE_SIZE . ' : ' . $ghostConfig['xooghost_image_size'];
        $upload_msg[] = _XOO_GHOST_CONFIG_IMAGE_WIDTH . ' : ' . $ghostConfig['xooghost_image_width'];
        $upload_msg[] = _XOO_GHOST_CONFIG_IMAGE_HEIGHT . ' : ' . $ghostConfig['xooghost_image_height'];

        $warning_tray = new \Xoops\Form\ElementTray($this->message($upload_msg, ''));
        $image_tray   = new \Xoops\Form\ElementTray(_XOO_GHOST_IMAGE, '');

        $image_box = new \Xoops\Form\File('', 'xooghost_image', 5000000);
        $image_box->setExtra("size ='70%'");
        $image_tray->addElement($image_box);
        $image_tray->addElement($warning_tray);

        $image_array  = \XoopsLists:: getImgListAsArray(\XoopsBaseConfig::get('uploads-path') . '/xooghost/images');
        $image_select = new \Xoops\Form\Select('<br>', 'image_list', $this->xoopsObject->getVar('xooghost_image'));
        $image_select->addOptionArray($image_array);
        $image_select->setExtra("onchange='showImgSelected(\"select_image\", \"image_list\", \"" . '/xooghost/images/' . '", "", "' . \XoopsBaseConfig::get('uploads-url') . "\")'");
        $image_tray->addElement($image_select);
        $image_tray->addElement(new \Xoops\Form\Label('', "<br><img src='" . \XoopsBaseConfig::get('uploads-url') . '/xooghost/images/' . $this->xoopsObject->getVar('xooghost_image') . "' name='select_image' id='select_image' alt=''>"));
        $tab1->addElement($image_tray);

        $tabTray->addElement($tab1);

        /**
         * Metas
         */
        $tab2 = new \Xoops\Form\Tab(_AM_XOO_TABFORM_METAS, 'tabid-2');
        // Meta description
        $tab2->addElement(new \Xoops\Form\TextArea(_XOO_GHOST_DESCRIPTION, 'xooghost_description', $this->xoopsObject->getVar('xooghost_description'), 7, 12));

        // Meta Keywords
        $tab2->addElement(new \Xoops\Form\TextArea(_XOO_GHOST_KEYWORDS, 'xooghost_keywords', $this->xoopsObject->getVar('xooghost_keywords'), 7, 12, _XOO_GHOST_KEYWORDS_DESC));
        $tabTray->addElement($tab2);

        /**
         * Options
         */
        // Published date
        $tab3      = new \Xoops\Form\Tab(_AM_XOO_TABFORM_OPTIONS, 'tabid-3');
        $published = (0 == $this->xoopsObject->getVar('xooghost_published')) ? time() : $this->xoopsObject->getVar('xooghost_published');
        $tab3->addElement(new \Xoops\Form\DateTimeSelect(_XOO_GHOST_PUBLISHED, 'xooghost_published', 15, $published, false));

        // display
        $tab3->addElement(new \Xoops\Form\RadioYesNo(_XOO_GHOST_DISPLAY, 'xooghost_online', $this->xoopsObject->getVar('xooghost_online')));
        $tabTray->addElement($tab3);

        /**
         * Tags
         */
        if ($xoops->registry()->offsetExists('XOOTAGS') && $xoops->registry()->get('XOOTAGS')) {
            $tagsTray = new \Xoops\Form\Tab(_AM_XOO_TABFORM_TAGS, 'tabid-tags');
            //            $tagsFormHandler = $xoops->getModuleForm(0, 'tags', 'xootags');
            //            $tagform = $tagsFormHandler->tagForm('tags', $this->xoopsObject->getVar('xooghost_id'));

            $tagsForm = new \XoopsModules\Xootags\Form\TagsForm();
            $tagform  = $tagsForm->tagForm('tags', $this->xoopsObject->getVar('xooghost_id'));

            $tagsTray->addElement($tagform);
            $tabTray->addElement($tagsTray);
        }

        // hidden
        $this->addElement(new \Xoops\Form\Hidden('xooghost_id', $this->xoopsObject->getVar('xooghost_id')));
        $this->addElement(new \Xoops\Form\Hidden('xooghost_hits', $this->xoopsObject->getVar('xooghost_hits')));
        $this->addElement(new \Xoops\Form\Hidden('xooghost_rates', $this->xoopsObject->getVar('xooghost_rates')));
        $this->addElement(new \Xoops\Form\Hidden('xooghost_like', $this->xoopsObject->getVar('xooghost_like')));
        $this->addElement(new \Xoops\Form\Hidden('xooghost_dislike', $this->xoopsObject->getVar('xooghost_dislike')));
        $this->addElement(new \Xoops\Form\Hidden('xooghost_comments', $this->xoopsObject->getVar('xooghost_comments')));

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

    /**
     * @param        $msg
     * @param string $title
     * @param string $class
     *
     * @return string
     */
    public function message($msg, $title = '', $class = 'errorMsg')
    {
        $ret = "<div class='" . $class . "'>";
        if ('' != $title) {
            $ret .= '<strong>' . $title . '</strong>';
        }
        if (is_array($msg) || is_object($msg)) {
            $ret .= implode('<br>', $msg);
        } else {
            $ret .= $msg;
        }
        $ret .= '</div>';

        return $ret;
    }
}
