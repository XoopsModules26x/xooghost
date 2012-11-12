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
    if (!$xoops->security->check()) {
        $xoops->redirect("preferences.php", 3, implode('<br />', $xoops->security->getErrors()));
    }

    $xooghost_main      = $system->CleanVars($_POST, 'xooghost_main', 0, 'int');
    $xooghost_welcome   = $system->CleanVars($_POST, 'xooghost_welcome', '', 'string');
    $xooghost_main_mode = $system->CleanVars($_POST, 'xooghost_main_mode', 'list', 'string');

    // Write configuration file
    include_once dirname( dirname ( __FILE__ ) ) . '/class/xoopreferences.php';
    $object = new XooGhostPreferences();
    foreach ( array_keys($_POST) as $k) {        if ( strstr($k, $xoops->module->dirname() . '_') ) {            $config[$k] = $_POST[$k];        }    }
    $object->writeConfig( $config );
    $xoops->redirect("preferences.php", 3, _AM_XOO_GHOST_SAVED);
    break;
    default:
    $form = $xoops->getModuleForm(null, 'preferences', 'xooghost');
    $form->PreferencesForm();
    $form->render();
    break;
}
include dirname(__FILE__) . '/footer.php';
?>