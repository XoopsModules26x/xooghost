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

/**
 * Class XooghostMenusPlugin
 */
class XooghostMenusPlugin extends Xoops\Module\Plugin\PluginAbstract implements MenusPluginInterface
{
    /**
     * expects an array of array containing:
     * name,      Name of the submenu
     * url,       Url of the submenu relative to the module
     * ex: return array(0 => array(
     *      'name' => _MI_PUBLISHER_SUB_SMNAME3;
     *      'url' => "search.php";
     *    ));
     *
     * @return array
     */
    public function subMenus()
    {
        $ret = array();
        if (Xoops::getInstance()->isModule() && Xoops::getInstance()->module->getVar('dirname') == 'xooghost') {
            $ghostModule  = Xooghost::getInstance();
            $ghostConfig  = $ghostModule->loadConfig();
            $ghostHandler = $ghostModule->ghostHandler();

            $i = 0;
            if ($ghostConfig['xooghost_main']) {
                $pages = $ghostHandler->getPublished();
                foreach ($pages as $page) {
                    $ret[$i]['name'] = $page['xooghost_title'];
                    $ret[$i]['url']  = $page['xooghost_url'];
                    ++$i;
                }
            }
        }

        return $ret;
    }
}
