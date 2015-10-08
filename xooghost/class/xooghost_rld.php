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

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * Class Xooghost_rld
 */
class Xooghost_rld extends XoopsObject
{
    // constructor
    /**
     *
     */
    public function __construct()
    {
        $this->initVar('xooghost_rld_id', XOBJ_DTYPE_INT, 0, true, 5);
        $this->initVar('xooghost_rld_page', XOBJ_DTYPE_INT, 1, false, 5);
        $this->initVar('xooghost_rld_uid', XOBJ_DTYPE_INT, 0, true, 5);
        $this->initVar('xooghost_rld_time', XOBJ_DTYPE_INT, time(), true, 10);
        $this->initVar('xooghost_rld_ip', XOBJ_DTYPE_TXTBOX, '0.0.0.0', true, 15);
        $this->initVar('xooghost_rld_rates', XOBJ_DTYPE_INT, 0, true, 5);
        $this->initVar('xooghost_rld_like', XOBJ_DTYPE_INT, 0, true, 1);
        $this->initVar('xooghost_rld_dislike', XOBJ_DTYPE_INT, 0, true, 1);
    }

    private function Xooghost_rld()
    {
        $this->__construct();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class    = __CLASS__;
            $instance = new $class();
        }

        return $instance;
    }

    public function CleanVarsForDB()
    {
        $system = System::getInstance();
        foreach (parent::getValues() as $k => $v) {
            if ($k != 'dohtml') {
                if ($this->vars[$k]['data_type'] == XOBJ_DTYPE_STIME || $this->vars[$k]['data_type'] == XOBJ_DTYPE_MTIME || $this->vars[$k]['data_type'] == XOBJ_DTYPE_LTIME) {
                    $value = $system->cleanVars($_POST[$k], 'date', date('Y-m-d'), 'date') + $system->cleanVars($_POST[$k], 'time', date('u'), 'int');
                    $this->setVar($k, isset($_POST[$k]) ? $value : $v);
                } elseif ($this->vars[$k]['data_type'] == XOBJ_DTYPE_INT) {
                    $value = $system->cleanVars($_POST, $k, $v, 'int');
                    $this->setVar($k, $value);
                } elseif ($this->vars[$k]['data_type'] == XOBJ_DTYPE_ARRAY) {
                    $value = $system->cleanVars($_POST, $k, $v, 'array');
                    $this->setVar($k, $value);
                } else {
                    $value = $system->cleanVars($_POST, $k, $v, 'string');
                    $this->setVar($k, $value);
                }
            }
        }
    }
}

/**
 * Class XooghostXooghost_rldHandler
 */
class XooghostXooghost_rldHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|\Xoops\Core\Database\Connection $db
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'xooghost_rld', 'Xooghost_rld', 'xooghost_rld_id', 'xooghost_rld_page');
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class    = __CLASS__;
            $instance = new $class($db);
        }

        return $instance;
    }

    /**
     * @param $page_id
     *
     * @return int
     */
    public function getVotes($page_id)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('xooghost_rld_page', $page_id));
        $criteria->add(new Criteria('xooghost_rld_rates', 0, '!='));

        return parent::getCount($criteria);
    }

    /**
     * @param $page_id
     *
     * @return int
     */
    public function getbyUser($page_id)
    {
        $xoops = Xoops::getInstance();
        $uid   = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
        $ip    = $xoops->getenv('REMOTE_ADDR');

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('xooghost_rld_page', $page_id));

        $criteria2 = new CriteriaCompo();
        $criteria2->add(new Criteria('xooghost_rld_uid', $uid), 'OR');
        $criteria2->add(new Criteria('xooghost_rld_ip', $ip), 'OR');
        $criteria->add($criteria2, 'AND');
        $tmp = $this->getObjects($criteria, false, false);
        if (count($tmp) != 0) {
            return $tmp[0]['xooghost_rld_rates'];
        }

        return 0;
    }

    /**
     * @param $page_id
     * @param $like_dislike
     *
     * @return bool
     */
    public function SetLike_Dislike($page_id, $like_dislike)
    {
        $xoops   = Xoops::getInstance();
        $uid     = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
        $ip      = $xoops->getenv('REMOTE_ADDR');
        $like    = ($like_dislike == 1) ? 1 : 0;
        $dislike = ($like_dislike == 0) ? 1 : 0;

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('xooghost_rld_page', $page_id));

        $criteria2 = new CriteriaCompo();
        $criteria2->add(new Criteria('xooghost_rld_uid', $uid), 'OR');
        $criteria2->add(new Criteria('xooghost_rld_ip', $ip), 'OR');
        $criteria->add($criteria2, 'AND');
        $tmp = $this->getObjects($criteria, false, false);
        if (count($tmp) == 0) {
            $rldObject = $this->create();
            $rldObject->setVar('xooghost_rld_page', $page_id);
            $rldObject->setVar('xooghost_rld_uid', $uid);
            $rldObject->setVar('xooghost_rld_time', time());
            $rldObject->setVar('xooghost_rld_ip', $ip);
            $rldObject->setVar('xooghost_rld_rates', 0);
            $rldObject->setVar('xooghost_rld_like', $like);
            $rldObject->setVar('xooghost_rld_dislike', $dislike);
            if ($this->insert($rldObject)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $page_id
     * @param $vote
     *
     * @return array|bool
     */
    public function SetRate($page_id, $vote)
    {
        $xoops = Xoops::getInstance();
        $uid   = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
        $ip    = $xoops->getenv('REMOTE_ADDR');

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('xooghost_rld_page', $page_id));

        $criteria2 = new CriteriaCompo();
        $criteria2->add(new Criteria('xooghost_rld_uid', $uid), 'OR');
        $criteria2->add(new Criteria('xooghost_rld_ip', $ip), 'OR');
        $criteria->add($criteria2, 'AND');
        $tmp = $this->getObjects($criteria, false, false);
        if (count($tmp) == 0) {
            $rldObject = $this->create();
            $rldObject->setVar('xooghost_rld_page', $page_id);
            $rldObject->setVar('xooghost_rld_uid', $uid);
            $rldObject->setVar('xooghost_rld_time', time());
            $rldObject->setVar('xooghost_rld_ip', $ip);
            $rldObject->setVar('xooghost_rld_rates', $vote);
            $rldObject->setVar('xooghost_rld_like', 0);
            $rldObject->setVar('xooghost_rld_dislike', 0);
            if ($tmp = $this->insert($rldObject)) {
                return $this->getAverage($page_id, $vote);
            }
        }

        return false;
    }

    /**
     * @param $page_id
     * @param $vote
     *
     * @return array
     */
    private function getAverage($page_id, $vote)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('xooghost_rld_page', $page_id));
        $criteria->add(new Criteria('xooghost_rld_rates', 0, '!='));

        $res    = $this->getObjects($criteria, false, false);
        $rates  = 0;
        $voters = 0;
        foreach ($res as $k => $v) {
            $rates = $rates + $v['xooghost_rld_rates'];
            ++$voters;
        }

        return array('voters' => $voters, 'average' => ($rates / $voters), 'vote' => $vote);
    }
}
