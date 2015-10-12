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

use Xoops\Core\Request;

include __DIR__ . '/header.php';

switch ($op) {
    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect('pages.php', 5, implode(',', $xoops->security()->getErrors()));
        }

        $xooghost_id = Request::getInt('xooghost_id', 0); //$system->cleanVars($_POST, 'xooghost_id', 0, 'int');

        if (isset($xooghost_id) && $xooghost_id > 0) {
            $page  = $ghostHandler->get($xooghost_id);
            $isnew = false;
        } else {
            $page  = $ghostHandler->create();
            $isnew = true;
        }

        $page->cleanVarsForDB();

        // uploads images
        $myts          = MyTextSanitizer::getInstance();
        $upload_images = $ghostHandler->uploadImages($page->getVar('xooghost_title'));

        if (is_array($upload_images) && count($upload_images) != 0) {
            foreach ($upload_images as $k => $reponse) {
                if ($reponse['error'] == true) {
                    $errors[] = $reponse['message'];
                } else {
                    $page->setVar($k, $reponse['filename']);
                }
            }
        } else {
            $page->setVar('xooghost_image', $myts->htmlSpecialChars(Request::getString('image_list', '', 'POST')));
        }

        if ($xooghost_id = $ghostHandler->insert($page)) {
            $msg = _AM_XOO_GHOST_SAVED;
            if (isset($errors) && count($errors) != 0) {
                $msg .= '<br />' . implode('<br />', $errors);;
            }

            // tags
            if ($xoops->registry()->offsetExists('XOOTAGS') && $xoops->registry()->get('XOOTAGS')) {
                $xootagsHandler = $xoops->getModuleHandler('tags', 'xootags');
                $msg .= '<br />' . $xootagsHandler->updateByItem('tags', $xooghost_id);
            }

            if ($isnew) {
                $page->setPost(true);

                //notifications
                $page->sendNotifications();
            }
            $xoops->redirect('pages.php', 5, $msg);
        }
        break;

    case 'add':
        $page = $ghostHandler->create();
        $form = $ghostModule->getForm($page, 'pages');
        $form->display();
        break;

    case 'edit':
        $xooghost_id = Request::getInt('xooghost_id', 0); //$system->cleanVars($_REQUEST, 'xooghost_id', 0, 'int');
        $page        = $ghostHandler->get($xooghost_id);
        $form        = $ghostModule->getForm($page, 'pages');
        $form->display();
        break;

    case 'del':
        $xooghost_id = Request::getInt('xooghost_id', 0); //$system->cleanVars($_REQUEST, 'xooghost_id', 0, 'int');
        if (isset($xooghost_id) && $xooghost_id > 0) {
            if ($page = $ghostHandler->get($xooghost_id)) {
                $delete = Request::getInt('ok', 0); //$system->cleanVars($_POST, 'ok', 0, 'int');
                if ($delete == 1) {
                    if (!$xoops->security()->check()) {
                        $xoops->redirect('pages.php', 5, implode(',', $xoops->security()->getErrors()));
                    }
                    // tags
                    if ($xoops->registry()->offsetExists('XOOTAGS') && $xoops->registry()->get('XOOTAGS')) {
                        $xootagsHandler = $xoops->getModuleHandler('tags', 'xootags');
                        $xootagsHandler->deleteByItem($page->getVar('xooghost_id'));
                    }
                    $page->setPost(false);
                    $ghostHandler->delete($page);
                    $xoops->redirect('pages.php', 5, _AM_XOO_GHOST_DELETED);
                } else {
                    $xoops->confirm(array('ok' => 1, 'xooghost_id' => $xooghost_id, 'op' => 'del'), Request::getString('REQUEST_URI', '', 'SERVER'), sprintf(_AM_XOO_GHOST_DELETE_CFM . "<br /><b><span style='color : Red'> %s </span></b><br /><br />", $page->getVar('xooghost_title')));
                }
            } else {
                $xoops->redirect('pages.php', 5);
            }
        } else {
            $xoops->redirect('pages.php', 5);
        }
        break;

    case 'view':
    case 'hide':
        $xooghost_id = Request::getInt('xooghost_id', 0); //$system->cleanVars($_REQUEST, 'xooghost_id', 0, 'int');
        $ghostHandler->setOnline($xooghost_id);
        $xoops->redirect('pages.php', 5, _AM_XOO_GHOST_SAVED);
        break;

    default:
        $online = Request::getInt('online', -1); //$system->cleanVars($_REQUEST, 'online', -1, 'int');
        $admin_page->addItemButton(_AM_XOO_GHOST_ADD, 'pages.php?op=add', 'add');
        $admin_page->renderButton();

        $xoops->tpl()->assign('pages', $ghostHandler->renderAdminList($online));
        break;
}
include __DIR__ . '/footer.php';
