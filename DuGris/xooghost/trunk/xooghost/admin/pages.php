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

include dirname(__FILE__) . '/header.php';

switch ($op) {    case 'save':
    if ( !$xoops->security->check() ) {
        $xoops->redirect('pages.php', 5, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    $xooghost_id = $system->CleanVars($_POST, 'xooghost_id', 0, 'int');
    if( isset($xooghost_id) && $xooghost_id > 0 ){
        $page = $xooghost_handler->get($xooghost_id);
    } else {
        $page = $xooghost_handler->create();
    }

    $page->CleanVarsForDB();

    // uploads images
    $myts = MyTextSanitizer::getInstance();
    $upload_images = $xooghost_handler->upload_images( $page->getVar('xooghost_title') );

    if ( is_array( $upload_images ) && count( $upload_images) != 0 ) {        foreach ($upload_images as $k => $reponse ) {            if ( $reponse['error'] == true ) {                $errors[] = $reponse['message'];
            } else {                $page->setVar( $k, $reponse['filename'] );
            }
        }
    } else {        $page->setVar('xooghost_image', $myts->htmlspecialchars( $_POST['image_list'] ) );
    }


    if ( $page_id = $xooghost_handler->insertPage($page)) {        $msg = _AM_XOO_GHOST_SAVED;
        if ( isset($errors) && count($errors) != 0) {            $msg .= '<br />' . implode('<br />', $errors);;        }

        // tags
        if ( $xoops->registry->offsetExists('XOOTAGS') && $xoops->registry->get('XOOTAGS') ) {
            $xootags_handler = $xoops->getModuleHandler('xootags_tags', 'xootags');
            $msg .= '<br />' . $xootags_handler->updateByItem( 'tags', $page_id) ;
        }

        $xoops->redirect('pages.php', 5, $msg);
    }
    break;

    case 'add':
    $page = $xooghost_handler->create();
    $form = $xoops->getModuleForm($page, 'pages', 'xooghost');
    $form->PageForm();
    $form->render();
    break;

    case 'edit':
    $xooghost_id = $system->CleanVars($_REQUEST, 'xooghost_id', 0, 'int');
    $page = $xooghost_handler->get($xooghost_id);
    $form = $xoops->getModuleForm($page, 'pages', 'xooghost');
    $form->PageForm();
    $form->render();
    break;

    case 'del':
    $xooghost_id = $system->CleanVars($_REQUEST, 'xooghost_id', 0, 'int');
    if( isset($xooghost_id) && $xooghost_id > 0 ){
        if ($page = $xooghost_handler->get($xooghost_id) ) {
            $delete = $system->CleanVars( $_POST, 'ok', 0, 'int' );
            if ($delete == 1) {
                if ( !$xoops->security->check() ) {
                    $xoops->redirect('pages.php', 5, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
                }
                $xooghost_handler->delete($page);
                $xoops->redirect('pages.php', 5, _AM_XOO_GHOST_DELETED);
            } else {
                $xoops->confirm(array('ok' => 1, 'xooghost_id' => $xooghost_id, 'op' => 'del'), $_SERVER['REQUEST_URI'], sprintf(_AM_XOO_GHOST_DELETE_CFM . "<br /><b><span style='color : Red'> %s </span></b><br /><br />", $page->getVar('xooghost_title')));
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
    $xooghost_id = $system->CleanVars($_REQUEST, 'xooghost_id', 0, 'int');
    $xooghost_handler->SetOnline($xooghost_id);
    $xoops->redirect('pages.php', 5, _AM_XOO_GHOST_SAVED);
    break;

    default:
    $admin_page->addItemButton(_AM_XOO_GHOST_ADD, 'pages.php?op=add', 'add');
    $admin_page->renderButton();

    $xoops->tpl->assign('pages', $xooghost_handler->renderAdminList() );
    break;
}
include dirname(__FILE__) . '/footer.php';
?>