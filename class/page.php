<?php

namespace XoopsModules\Xooghost;

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

use Xoops\Core\Kernel\Handlers\XoopsUser;
use Xoops\Core\Request;

/**
 * Class Page
 */
class Page extends \XoopsObject
{
    private $exclude_page = [
        'index',
        'search',
        'tag',
        'userinfo',
        'page_comment',
        'pages',
    ];
    private $php_self     = '';

    // constructor

    public function __construct()
    {
        $xoops          = \Xoops::getInstance();
        $this->php_self = basename($xoops->getEnv('PHP_SELF'), '.php');

        $this->initVar('xooghost_id', XOBJ_DTYPE_INT, 0, true, 11);
        $this->initVar('xooghost_url', XOBJ_DTYPE_TXTBOX, '', true, 54);
        $this->initVar('xooghost_title', XOBJ_DTYPE_TXTBOX, '', true, 255);
        $this->initVar('xooghost_uid', XOBJ_DTYPE_INT, 0, true, 8);
        $this->initVar('xooghost_content', XOBJ_DTYPE_TXTBOX, '', true);
        $this->initVar('xooghost_description', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('xooghost_keywords', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('xooghost_image', XOBJ_DTYPE_TXTBOX, 'blank.gif', false, 100);
        $this->initVar('xooghost_published', XOBJ_DTYPE_STIME, 0, false, 10);
        $this->initVar('xooghost_online', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('xooghost_hits', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('xooghost_rates', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('xooghost_like', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('xooghost_dislike', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('xooghost_comments', XOBJ_DTYPE_INT, 0, false, 10);

        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);

        // Module
        $helper           = \XoopsModules\Xooghost\Helper::getInstance();
        $this->config     = $helper->loadConfig();
        $this->rldHandler = $helper->getHandler('Rld');
    }

    /**
     * @param bool $addpost
     */
    public function setPost($addpost = true)
    {
        $xoops         = \Xoops::getInstance();
        $memberHandler = $xoops->getHandlerMember();
        $poster        = $memberHandler->getUser($this->getVar('xooghost_uid'));
        if ($poster instanceof XoopsUser) {
            if ($addpost) {
                $memberHandler->updateUserByField($poster, 'posts', $poster->getVar('posts') + 1);
            } else {
                $memberHandler->updateUserByField($poster, 'posts', $poster->getVar('posts') - 1);
            }
        }
    }

    /**
     * @return mixed|string
     */
    public function getMetaDescription()
    {
        $myts = \MyTextSanitizer::getInstance();
        if ('' != $this->getVar('xooghost_description')) {
            $string = $this->getVar('xooghost_description');
        } else {
            $string = $myts->undoHtmlSpecialChars($this->getVar('xooghost_content'));
        }

        $string = str_replace('[breakpage]', '', $string);
        // remove html tags
        $string = strip_tags($string);
        //        return preg_replace(array('/&amp;/i'), array('&'), $string);
        return $string;
    }

    /**
     * @param int $limit
     *
     * @return string
     */
    public function getMetaKeywords($limit = 5)
    {
        if ('' != $this->getVar('xooghost_keywords')) {
            $string = $this->getVar('xooghost_keywords');
        } else {
            $string = $this->getMetaDescription() . ', ' . $this->getVar('xooghost_keywords');
        }
        $string .= $this->getVar('xooghost_title');

        $string          = html_entity_decode($string, ENT_QUOTES);
        $search_pattern  = ["\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '_', '\\', '*', 'pagebreak', 'page'];
        $replace_pattern = [' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $string          = str_replace($search_pattern, $replace_pattern, $string);

        $tmpkeywords = explode(' ', $string);
        $tmpkeywords = array_count_values($tmpkeywords);
        arsort($tmpkeywords);
        $tmpkeywords = array_keys($tmpkeywords);

        $tmpkeywords = array_unique($tmpkeywords);
        foreach ($tmpkeywords as $keyword) {
            if (mb_strlen(trim($keyword)) >= $limit && !is_numeric($keyword)) {
                $keywords[] = htmlentities(trim($keyword));
            }
        }

        return implode(', ', $keywords);
    }

    /**
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     *
     * @return array
     */
    public function getValues($keys = null, $format = null, $maxDepth = null)
    {
        $xoops = \Xoops::getInstance();
        $myts  = \MyTextSanitizer::getInstance();
        $ret   = parent::getValues();

        $dateformat                 = $this->config['xooghost_date_format'];
        $ret['xooghost_date_day']   = date('d', $this->getVar('xooghost_published'));
        $ret['xooghost_date_month'] = date('m', $this->getVar('xooghost_published'));
        $ret['xooghost_date_year']  = date('Y', $this->getVar('xooghost_published'));
        $ret['xooghost_time']       = $this->getVar('xooghost_published');
        $ret['xooghost_published']  = date(constant($dateformat), $this->getVar('xooghost_published'));

        $ret['xooghost_link'] = \XoopsBaseConfig::get('url') . '/modules/xooghost/' . $this->getVar('xooghost_url');

        $ret['xooghost_uid_name'] = \XoopsUser::getUnameFromId($this->getVar('xooghost_uid'), true);

        if ('blank.gif' !== $this->getVar('xooghost_image')) {
            $ret['xooghost_image_link'] = $xoops_upload_url . '/xooghost/images/' . $this->getVar('xooghost_image');
        } else {
            $ret['xooghost_image_link'] = \XoopsBaseConfig::get('url') . '/' . $xoops->theme()->resourcePath('/modules/xooghost/assets/images/pages.png');
        }

        if (in_array($this->php_self, $this->exclude_page, true) && false !== mb_strpos($this->getVar('xooghost_content'), '[breakpage]')) {
            $ret['xooghost_content'] = mb_substr($this->getVar('xooghost_content'), 0, mb_strpos($this->getVar('xooghost_content'), '[breakpage]'));
            $ret['readmore']         = true;
        } else {
            $ret['xooghost_content'] = str_replace('[breakpage]', '', $this->getVar('xooghost_content'));
        }
        $ret['xooghost_content'] = $myts->undoHtmlSpecialChars($ret['xooghost_content']);

        // tags
        static $tags;
        if (!in_array($this->php_self, $this->exclude_page, true) || 'index' === $this->php_self || 'page_print' === $this->php_self) {
            if ($xoops->registry()->offsetExists('XOOTAGS') && $xoops->registry()->get('XOOTAGS')) {
                $id = $this->getVar('xooghost_id');
                if (!isset($tags[$this->getVar('xooghost_id')])) {
                    $xootagsHandler                     = \XoopsModules\Xootags\Helper::getInstance()->getHandler('Tags'); //$xoops->getModuleHandler('tags', 'xootags');
                    $tags[$this->getVar('xooghost_id')] = $xootagsHandler->getbyItem($this->getVar('xooghost_id'));
                }
                $ret['tags'] = $tags[$this->getVar('xooghost_id')];
            }
        }

        return $ret;
    }

    /**
     * @param $ret
     *
     * @return mixed
     */
    public function getRLD($ret)
    {
        if (!in_array($this->php_self, $this->exclude_page, true)) {
            if ('rate' === $this->config['xooghost_rld']['rld_mode']) {
                $ret['xooghost_vote']     = $this->rldHandler->getVotes($this->getVar('xooghost_id'));
                $ret['xooghost_yourvote'] = $this->rldHandler->getbyUser($this->getVar('xooghost_id'));
            }
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function createPage()
    {
        if (!file_exists(\XoopsBaseConfig::get('root-path') . '/modules/xooghost/' . $this->getVar('xooghost_url'))) {
            $xoopstmp = \Xoops::getInstance();
            $content  = $xoopstmp->tpl()->fetch('admin:xooghost/xooghost_model_page.tpl');
            file_put_contents(\XoopsBaseConfig::get('root-path') . '/modules/xooghost/' . $this->getVar('xooghost_url'), $content);
        }

        return true;
    }

    public function cleanVarsForDB()
    {
        /*
            $request = \Xoops_Request::getInstance();
            $url = $request->getUrl();
            print_r( $request->getParam() );
        */
        $system = \System::getInstance();
        foreach (parent::getValues() as $k => $v) {
            if ('dohtml' !== $k) {
                if (XOBJ_DTYPE_STIME == $this->vars[$k]['data_type'] || XOBJ_DTYPE_MTIME == $this->vars[$k]['data_type'] || XOBJ_DTYPE_LTIME == $this->vars[$k]['data_type']) {
                    //                    $value = $system->cleanVars($_POST[$k], 'date', date('Y-m-d'), 'date') + $system->cleanVars($_POST[$k], 'time', date('u'), 'int');
                    //TODO should we use here getString??
                    $value = Request::getArray('date', date('Y-m-d'), 'POST')[$k] + Request::getArray('time', date('u'), 'POST')[$k];
                    $this->setVar($k, isset($_POST[$k]) ? $value : $v);
                } elseif (XOBJ_DTYPE_INT == $this->vars[$k]['data_type']) {
                    $value = Request::getInt($k, $v, 'POST'); //$system->cleanVars($_POST, $k, $v, 'int');
                    $this->setVar($k, $value);
                } elseif (XOBJ_DTYPE_ARRAY == $this->vars[$k]['data_type']) {
                    $value = Request::getArray($k, $v, 'POST'); // $system->cleanVars($_POST, $k, $v, 'array');
                    $this->setVar($k, $value);
                } else {
                    $value = Request::getString($k, $v, 'POST'); //$system->cleanVars($_POST, $k, $v, 'string');
                    $this->setVar($k, stripslashes($value));
                }
            }
            if ('xooghost_url' === $k) {
                $this->setVar($k, $this->cleanURL($this->getVar($k)));
            }
        }
    }

    /**
     * @param $string
     *
     * @return string
     */
    public function cleanURL($string)
    {
        $string = basename($string, '.php');

        $string = str_replace('_', 'xooghost', $string);
        $string = str_replace('-', 'xooghost', $string);
        $string = str_replace(' ', 'xooghost', $string);

        $string = preg_replace('~\p{P}~', '', $string);
        $string = htmlentities($string, ENT_NOQUOTES, \XoopsLocale::CHARSET);
        $string = preg_replace('~\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;~', '$1', $string);
        $string = preg_replace('~\&([A-za-z]{2})(?:lig)\;~', '$1', $string); // pour les ligatures e.g. '&oelig;'
        $string = preg_replace('~\&[^;]+\;~', '', $string); // supprime les autres caract�res

        $string = str_replace('xooghost', '_', $string);

        return $string . '.php';
    }

    public function sendNotifications()
    {
        $xoops = \Xoops::getInstance();
        if ($xoops->isActiveModule('notifications')) {
            $notificationHandler = \Notifications::getInstance()->getHandlerNotification();
            $tags                = [];
            $tags['MODULE_NAME'] = $xoops->module->getVar('name');
            $tags['ITEM_NAME']   = $this->getVar('xooghost_title');
            $tags['ITEM_URL']    = $xoops->url('/modules/xooghost/' . $this->getVar('xooghost_url'));
            $tags['ITEM_BODY']   = $this->getVar('xooghost_content');
            $tags['DATESUB']     = $this->getVar('xooghost_published');
            $notificationHandler->triggerEvent('global', 0, 'newcontent', $tags);
        }
    }
}
