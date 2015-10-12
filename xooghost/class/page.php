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
use Xoops\Core\Request;

/**
 * Class XooghostPage
 */
class XooghostPage extends XoopsObject
{
    private $exclude_page
        = array(
            'index',
            'search',
            'tag',
            'userinfo',
            'page_comment',
            'pages'
        );
    private $php_self = '';

    // constructor
    /**
     *
     */
    public function __construct()
    {
        $xoops          = Xoops::getInstance();
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
        $ghostModule      = Xooghost::getInstance();
        $this->config      = $ghostModule->loadConfig();
        $this->rldHandler = $ghostModule->rldHandler();
    }

    /**
     * @param bool $addpost
     */
    public function setPost($addpost = true)
    {
        $xoops          = Xoops::getInstance();
        $memberHandler = $xoops->getHandlerMember();
        $poster         = $memberHandler->getUser($this->getVar('xooghost_uid'));
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
        $myts = MyTextSanitizer::getInstance();
        if ($this->getVar('xooghost_description') != '') {
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
        if ($this->getVar('xooghost_keywords') != '') {
            $string = $this->getVar('xooghost_keywords');
        } else {
            $string = $this->getMetaDescription() . ', ' . $this->getVar('xooghost_keywords');
        }
        $string .= $this->getVar('xooghost_title');

        $string          = html_entity_decode($string, ENT_QUOTES);
        $search_pattern  = array("\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '_', '\\', '*', 'pagebreak', 'page');
        $replace_pattern = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $string          = str_replace($search_pattern, $replace_pattern, $string);

        $tmpkeywords = explode(' ', $string);
        $tmpkeywords = array_count_values($tmpkeywords);
        arsort($tmpkeywords);
        $tmpkeywords = array_keys($tmpkeywords);

        $tmpkeywords = array_unique($tmpkeywords);
        foreach ($tmpkeywords as $keyword) {
            if (strlen(trim($keyword)) >= $limit && !is_numeric($keyword)) {
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
        $xoops = Xoops::getInstance();
        $myts  = MyTextSanitizer::getInstance();
        $ret   = parent::getValues();

        $dateformat                 = $this->config['xooghost_date_format'];
        $ret['xooghost_date_day']   = date('d', $this->getVar('xooghost_published'));
        $ret['xooghost_date_month'] = date('m', $this->getVar('xooghost_published'));
        $ret['xooghost_date_year']  = date('Y', $this->getVar('xooghost_published'));
        $ret['xooghost_time']       = $this->getVar('xooghost_published');
        $ret['xooghost_published']  = date(constant($dateformat), $this->getVar('xooghost_published'));

        $ret['xooghost_link'] = \XoopsBaseConfig::get('url') . '/modules/xooghost/' . $this->getVar('xooghost_url');

        $ret['xooghost_uid_name'] = XoopsUser::getUnameFromId($this->getVar('xooghost_uid'), true);

        if ($this->getVar('xooghost_image') !== 'blank.gif') {
            $ret['xooghost_image_link'] = $xoops_upload_url . '/xooghost/images/' . $this->getVar('xooghost_image');
        } else {
            $ret['xooghost_image_link'] = \XoopsBaseConfig::get('url') . '/' . $xoops->theme()->resourcePath('/modules/xooghost/assets/images/pages.png');
        }

        if (in_array($this->php_self, $this->exclude_page) && strpos($this->getVar('xooghost_content'), '[breakpage]') !== false) {
            $ret['xooghost_content'] = substr($this->getVar('xooghost_content'), 0, strpos($this->getVar('xooghost_content'), '[breakpage]'));
            $ret['readmore']         = true;
        } else {
            $ret['xooghost_content'] = str_replace('[breakpage]', '', $this->getVar('xooghost_content'));
        }
        $ret['xooghost_content'] = $myts->undoHtmlSpecialChars($ret['xooghost_content']);

        // tags
        static $tags;
        if (!in_array($this->php_self, $this->exclude_page) || $this->php_self === 'index' || $this->php_self === 'page_print') {
            if ($xoops->registry()->offsetExists('XOOTAGS') && $xoops->registry()->get('XOOTAGS')) {
                $id = $this->getVar('xooghost_id');
                if (!isset($tags[$this->getVar('xooghost_id')])) {
                    $xootagsHandler                    = $xoops->getModuleHandler('tags', 'xootags');
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
        if (!in_array($this->php_self, $this->exclude_page)) {
            if ($this->config['xooghost_rld']['rld_mode'] === 'rate') {
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
        if (!file_exists(\XoopsBaseConfig::get('root-path')  . '/modules/xooghost/' . $this->getVar('xooghost_url'))) {
            $xoopstmp = Xoops::getInstance();
            $content  = $xoopstmp->tpl()->fetch('admin:xooghost/xooghost_model_page.tpl');
            file_put_contents(\XoopsBaseConfig::get('root-path')  . '/modules/xooghost/' . $this->getVar('xooghost_url'), $content);
        }

        return true;
    }

    public function cleanVarsForDB()
    {
        /*
            $request = Xoops_Request::getInstance();
            $url = $request->getUrl();
            print_r( $request->getParam() );
        */
        $system = System::getInstance();
        foreach (parent::getValues() as $k => $v) {
            if ($k !== 'dohtml') {
                if ($this->vars[$k]['data_type'] == XOBJ_DTYPE_STIME || $this->vars[$k]['data_type'] == XOBJ_DTYPE_MTIME || $this->vars[$k]['data_type'] == XOBJ_DTYPE_LTIME) {
//                    $value = $system->cleanVars($_POST[$k], 'date', date('Y-m-d'), 'date') + $system->cleanVars($_POST[$k], 'time', date('u'), 'int');
                    //TODO should we use here getString??
                    $value = Request::getArray('date', date('Y-m-d'), 'POST')[$k] + Request::getArray('time', date('u'), 'POST')[$k];
                    $this->setVar($k, isset($_POST[$k]) ? $value : $v);
                } elseif ($this->vars[$k]['data_type'] == XOBJ_DTYPE_INT) {
                    $value = Request::getInt($k, $v, 'POST'); //$system->cleanVars($_POST, $k, $v, 'int');
                    $this->setVar($k, $value);
                } elseif ($this->vars[$k]['data_type'] == XOBJ_DTYPE_ARRAY) {
                    $value = Request::getArray($k, $v, 'POST'); // $system->cleanVars($_POST, $k, $v, 'array');
                    $this->setVar($k, $value);
                } else {
                    $value = Request::getString($k, $v, 'POST'); //$system->cleanVars($_POST, $k, $v, 'string');
                    $this->setVar($k, stripslashes($value));
                }
            }
            if ($k === 'xooghost_url') {
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
        $string = htmlentities($string, ENT_NOQUOTES, XoopsLocale::_CHARSET);
        $string = preg_replace('~\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;~', '$1', $string);
        $string = preg_replace('~\&([A-za-z]{2})(?:lig)\;~', '$1', $string); // pour les ligatures e.g. '&oelig;'
        $string = preg_replace('~\&[^;]+\;~', '', $string); // supprime les autres caract�res

        $string = str_replace('xooghost', '_', $string);

        return $string . '.php';
    }

    public function sendNotifications()
    {
        $xoops = Xoops::getInstance();
        if ($xoops->isActiveModule('notifications')) {
            $notificationHandler = Notifications::getInstance()->getNotificationHandler();
            $tags                 = array();
            $tags['MODULE_NAME']  = $xoops->module->getVar('name');
            $tags['ITEM_NAME']    = $this->getVar('xooghost_title');
            $tags['ITEM_URL']     = $xoops->url('/modules/xooghost/' . $this->getVar('xooghost_url'));
            $tags['ITEM_BODY']    = $this->getVar('xooghost_content');
            $tags['DATESUB']      = $this->getVar('xooghost_published');
            $notificationHandler->triggerEvent('global', 0, 'newcontent', $tags);
        }
    }
}

/**
 * Class XooghostPageHandler
 */
class XooghostPageHandler extends XoopsPersistableObjectHandler
{
    private $exclude
        = array(
            'backend.php',
            'footer.php',
            'header.php',
            'index.php',
            'page_comment.php',
            'page_like_dislike.php',
            'page_print.php',
            'page_rate.php',
            'qrcode.php',
            'xoops_version.php'
        );

    /**
     * @param null|\Xoops\Core\Database\Connection $db
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'xooghost', 'XooghostPage', 'xooghost_id', 'xooghost_title');

        // Module
        $ghostModule      = Xooghost::getInstance();
        $this->config      = $ghostModule->loadConfig();
        $this->rldHandler = $ghostModule->rldHandler();
    }

    /**
     * @param $xooghostUrl
     *
     * @return mixed
     */
    public function getByURL($xooghostUrl)
    {
        $criteria = new Criteria('xooghost_url', $xooghostUrl);
        $page     = $this->getObjects($criteria, false, true);

        return $page[0];
    }

    /**
     * @param string $sort
     * @param string $order
     * @param int    $start
     * @param int    $limit
     *
     * @return array
     */
    public function getPublished($sort = 'published', $order = 'desc', $start = 0, $limit = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('xooghost_online', 1));
        $criteria->add(new Criteria('xooghost_published', time(), '<='));
        if ($sort === 'random') {
            $criteria->setSort('rand()');
        } else {
            $criteria->setSort('xooghost_' . $sort);
        }
        $criteria->setOrder($order);
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        return $this->getObjects($criteria, true, false);
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        $ret   = array();
        $pages = $this->getPublished();
        foreach ($pages as $page) {
            $ret[] = $page['xooghost_url'];
        }

        return $ret;
    }

    /**
     * @param $Xooghost_id
     *
     * @return bool
     */
    public function setOnline($Xooghost_id)
    {
        if ($Xooghost_id != 0) {
            $page = $this->get($Xooghost_id);
            if ($page->getVar('xooghost_online') == 1) {
                $page->setVar('xooghost_online', 0);
            } else {
                $page->setVar('xooghost_online', 1);
            }
            $this->insert($page);

            return true;
        }

        return false;
    }

    /**
     * @param $pageObj
     *
     * @return bool
     */
    public function setRead($pageObj)
    {
        $read = $pageObj->getVar('xooghost_hits') + 1;
        $pageObj->setVar('xooghost_hits', $read);
        $this->insert($pageObj);

        return true;
    }

    /**
     * @param $page_id
     * @param $like_dislike
     *
     * @return array|bool
     */
    public function setLikeDislike($page_id, $like_dislike)
    {
        if ($page_id != 0) {
            $page = $this->get($page_id);
            if (is_object($page) && count($page) != 0) {
                $xoops = Xoops::getInstance();

                if ($ret = $this->rldHandler->setLikeDislike($page_id, $like_dislike)) {
                    if ($like_dislike == 0) {
                        $xooghost_dislike = $page->getVar('xooghost_dislike') + 1;
                        $page->setVar('xooghost_dislike', $xooghost_dislike);
                    } elseif ($like_dislike == 1) {
                        $xooghost_like = $page->getVar('xooghost_like') + 1;
                        $page->setVar('xooghost_like', $xooghost_like);
                    }
                    $this->insert($page);

                    return $page->getValues();
                }
            }

            return false;
        }

        return false;
    }

    /**
     * @param $page_id
     * @param $rate
     *
     * @return bool
     */
    public function setRate($page_id, $rate)
    {
        if ($page_id != 0) {
            $page = $this->get($page_id);
            if (is_object($page) && count($page) != 0) {
                $xoops = Xoops::getInstance();

                if ($ret = $this->rldHandler->setRate($page_id, $rate)) {
                    if (is_array($ret) && count($ret) == 3) {
                        $page->setVar('xooghost_rates', $ret['average']);
                        $this->insert($page);

                        return $ret;
                    }
                }
            }

            return false;
        }

        return false;
    }

    /**
     * @return string
     */
    public function selectPage()
    {
        $pages = $this->getPublished();
        $form  = new Xoops\Form\Select('', 'xooghost_url');
        $form->setExtra("onChange='javascript:window.location.href=this.value'");
        $form->addOption('index.php', _XOO_GHOST_CHOOSE);
        foreach ($pages as $page) {
            $form->addOption($page['xooghost_link'], $page['xooghost_title']);
        }

        return $form->render();
    }

    /**
     * @param int $online
     *
     * @return array
     */
    public function renderAdminList($online = -1)
    {
        $criteria = new CriteriaCompo();
        $criteria->setSort('xooghost_published');
        $criteria->setOrder('DESC');
        if ($online >= 0) {
            $criteria->add(new Criteria('xooghost_online', $online));
        }
        $criteria->setOrder('asc');

        return $this->getObjects($criteria, true, false);
    }

    /**
     * @param XoopsObject $object
     * @param bool        $force
     *
     * @return bool|mixed
     */
    public function insert(XoopsObject $object, $force = true)
    {
        $xoops = Xoops::getInstance();
        if (parent::insert($object, $force)) {
            $object->createPage();
            if ($object->isNew()) {
                return $xoops->db()->getInsertId();
            } else {
                return $object->getVar('xooghost_id');
            }
        }

        return false;
    }

    /**
     * @param $image_name
     *
     * @return array
     */
    public function uploadImages($image_name)
    {
        $xoops    = Xoops::getInstance();
        $autoload = XoopsLoad::loadConfig('xooghost');

        $uploader = new XoopsMediaUploader(
            \XoopsBaseConfig::get('uploads-path') . '/xooghost/images', $autoload['mimetypes'], $this->config['xooghost_image_size'], $this->config['xooghost_image_width'], $this->config['xooghost_image_height']
        );

        $ret = array();
        foreach (Request::getArray('xoops_upload_file', array(), 'POST') as $k => $input_image) {
            if (Request::getArray($input_image, array(), 'FILES')['tmp_name'] != '' || is_readable(Request::getArray($input_image, array(), 'FILES')['tmp_name'])) {
                $path_parts = pathinfo(Request::getArray($input_image, array(), 'FILES')['name']);
                $uploader->setTargetFileName($this->cleanImage(strtolower($image_name . '.' . $path_parts['extension'])));
                if ($uploader->fetchMedia(Request::getArray('xoops_upload_file', array(), 'POST')[$k])) {
                    if ($uploader->upload()) {
                        $ret[$input_image] = array('filename' => $uploader->getSavedFileName(), 'error' => false, 'message' => '');
                    } else {
                        $ret[$input_image] = array('filename' => Request::getArray($input_image, array(), 'FILES')['name'], 'error' => true, 'message' => $uploader->getErrors());
                    }
                } else {
                    $ret[$input_image] = array('filename' => Request::getArray($input_image, array(), 'FILES')['name'], 'error' => true, 'message' => $uploader->getErrors());
                }
            }
        }

        return $ret;
    }

    /**
     * @param $filename
     *
     * @return string
     */
    public function cleanImage($filename)
    {
        $path_parts = pathinfo($filename);
        $string     = $path_parts['filename'];

        $string = str_replace('_', md5('xooghost'), $string);
        $string = str_replace('-', md5('xooghost'), $string);
        $string = str_replace(' ', md5('xooghost'), $string);

        $string = preg_replace('~\p{P}~', '', $string);
        $string = htmlentities($string, ENT_NOQUOTES, XoopsLocale::_CHARSET);
        $string = preg_replace("~\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;~", "$1", $string);
        $string = preg_replace("~\&([A-za-z]{2})(?:lig)\;~", "$1", $string); // pour les ligatures e.g. "&oelig;"
        $string = preg_replace("~\&[^;]+\;~", "", $string); // supprime les autres caract�res

        $string = str_replace(md5('xooghost'), '_', $string);

        return $string . '.' . $path_parts['extension'];
    }

    /**
     * @return array
     */
    public function getPhpListAsArray()
    {
        $exclude = $this->exclude;
        $pages   = parent::getAll(null, array('xooghost_url'), false, true);
        foreach ($pages as $page) {
            $exclude[] = $page['xooghost_url'];
        }

        $dirname = \XoopsBaseConfig::get('root-path')  . '/modules/xooghost';

        $filelist = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if ((preg_match('/(\.php)$/i', $file) && !is_dir($file) && !in_array($file, $exclude))) {
                    $file            = basename($file);
                    $filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }

        return $filelist;
    }
}
