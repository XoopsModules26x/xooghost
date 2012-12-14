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

$xoops->loadLanguage('preferences', 'xooghost');

switch ($op) {    case 'save':
    if (!$xoops->security()->check()) {
        $xoops->redirect('preferences.php', 3, implode('<br />', $xoops->security()->getErrors()));
    }

    $xooghost_main      = $system->CleanVars($_POST, 'xooghost_main', 0, 'int');
    $xooghost_welcome   = $system->CleanVars($_POST, 'xooghost_welcome', '', 'string');
    $xooghost_main_mode = $system->CleanVars($_POST, 'xooghost_main_mode', 'list', 'string');

    // Write configuration file
    $object = XooGhostPreferences::getInstance();
    $object->writeConfig( $object->Prepare2Save() );
    $xoops->redirect('preferences.php', 3, _XOO_CONFIG_SAVED);
    break;
    default:
    $xoops->theme()->addStylesheet('modules/xooghost/css/preferences.css');
    $form = $xoops->getModuleForm(null, 'preferences', 'xooghost');
    $form->PreferencesForm();
    $form->display();
    break;
}
include dirname(__FILE__) . '/footer.php';
?>