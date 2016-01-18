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
 */

class Xooghost extends Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname(basename(dirname(__DIR__)));
        $this->loadLanguage('common');
        $this->loadLanguage('preferences');

        XoopsLoad::load('xoopaginate', $this->_dirname);
    }

    /**
     * @return Xoops\Module\Helper\HelperAbstract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return mixed
     */
    public function loadConfig()
    {
        XoopsLoad::load('xoopreferences', $this->_dirname);

        return XooGhostPreferences::getInstance()->getConfig();
    }

    /**
     * @return \Xoops\Module\Helper\XoopsObjectHandler
     */
    public function ghostHandler()
    {
        return $this->getHandler('Page');
    }

    /**
     * @return \Xoops\Module\Helper\XoopsObjectHandler
     */
    public function rldHandler()
    {
        return $this->getHandler('Rld');
    }
}
