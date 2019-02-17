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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xooghost
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */
use Xoops\Core\Database\Connection;
use Xoops\Core\Request;

/**
 * Class XooghostPageHandler
 */
class PageHandler extends \XoopsPersistableObjectHandler
{
    private $exclude
        = [
            'backend.php',
            'footer.php',
            'header.php',
            'index.php',
            'page_comment.php',
            'page_like_dislike.php',
            'page_print.php',
            'page_rate.php',
            'qrcode.php',
            'xoops_version.php',
        ];

    /**
     * @param null|\Xoops\Core\Database\Connection $db
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'xooghost', Page::class, 'xooghost_id', 'xooghost_title');

        // Module
        $helper = \XoopsModules\Xooghost\Helper::getInstance();
        $this->config = $helper->loadConfig();
        $this->rldHandler = $helper->rldHandler();
    }

    /**
     * @param $xooghostUrl
     *
     * @return mixed
     */
    public function getByURL($xooghostUrl)
    {
        $criteria = new \Criteria('xooghost_url', $xooghostUrl);
        $page = $this->getObjects($criteria, false, true);

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
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('xooghost_online', 1));
        $criteria->add(new \Criteria('xooghost_published', time(), '<='));
        if ('random' === $sort) {
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
        $ret = [];
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
        if (0 != $Xooghost_id) {
            $page = $this->get($Xooghost_id);
            if (1 == $page->getVar('xooghost_online')) {
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
        if (0 != $page_id) {
            $page = $this->get($page_id);
            if (is_object($page) && 0 != count($page)) {
                $xoops = \Xoops::getInstance();

                if ($ret = $this->rldHandler->setLikeDislike($page_id, $like_dislike)) {
                    if (0 == $like_dislike) {
                        $xooghost_dislike = $page->getVar('xooghost_dislike') + 1;
                        $page->setVar('xooghost_dislike', $xooghost_dislike);
                    } elseif (1 == $like_dislike) {
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
        if (0 != $page_id) {
            $page = $this->get($page_id);
            if (is_object($page) && 0 != count($page)) {
                $xoops = \Xoops::getInstance();

                if ($ret = $this->rldHandler->setRate($page_id, $rate)) {
                    if (is_array($ret) && 3 == count($ret)) {
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
        $form = new \Xoops\Form\Select('', 'xooghost_url');
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
        $criteria = new \CriteriaCompo();
        $criteria->setSort('xooghost_published');
        $criteria->setOrder('DESC');
        if ($online >= 0) {
            $criteria->add(new \Criteria('xooghost_online', $online));
        }
        $criteria->setOrder('asc');

        return $this->getObjects($criteria, true, false);
    }

    /**
     * @param \Xoops\Core\Kernel\XoopsObject $object
     * @param bool        $force
     *
     * @return bool|mixed
     */
    public function insert(\Xoops\Core\Kernel\XoopsObject $object, $force = true)
    {
        $xoops = \Xoops::getInstance();
        if (parent::insert($object, $force)) {
            $object->createPage();
            if ($object->isNew()) {
                return $xoops->db()->getInsertId();
            }

            return $object->getVar('xooghost_id');
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
        $xoops = \Xoops::getInstance();
        $autoload = \XoopsLoad::loadConfig('xooghost');

        $uploader = new \XoopsMediaUploader(
            \XoopsBaseConfig::get('uploads-path') . '/xooghost/images',
            $autoload['mimetypes'],
            $this->config['xooghost_image_size'],
            $this->config['xooghost_image_width'],
            $this->config['xooghost_image_height']
        );

        $ret = [];
        foreach (Request::getArray('xoops_upload_file', [], 'POST') as $k => $input_image) {
            if ('' != Request::getArray($input_image, [], 'FILES')['tmp_name'] || is_readable(Request::getArray($input_image, [], 'FILES')['tmp_name'])) {
                $path_parts = pathinfo(Request::getArray($input_image, [], 'FILES')['name']);
                $uploader->setTargetFileName($this->cleanImage(mb_strtolower($image_name . '.' . $path_parts['extension'])));
                if ($uploader->fetchMedia(Request::getArray('xoops_upload_file', [], 'POST')[$k])) {
                    if ($uploader->upload()) {
                        $ret[$input_image] = ['filename' => $uploader->getSavedFileName(), 'error' => false, 'message' => ''];
                    } else {
                        $ret[$input_image] = ['filename' => Request::getArray($input_image, [], 'FILES')['name'], 'error' => true, 'message' => $uploader->getErrors()];
                    }
                } else {
                    $ret[$input_image] = ['filename' => Request::getArray($input_image, [], 'FILES')['name'], 'error' => true, 'message' => $uploader->getErrors()];
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
        $string = $path_parts['filename'];

        $string = str_replace('_', md5('xooghost'), $string);
        $string = str_replace('-', md5('xooghost'), $string);
        $string = str_replace(' ', md5('xooghost'), $string);

        $string = preg_replace('~\p{P}~', '', $string);
        $string = htmlentities($string, ENT_NOQUOTES, \XoopsLocale::_CHARSET);
        $string = preg_replace("~\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;~", '$1', $string);
        $string = preg_replace("~\&([A-za-z]{2})(?:lig)\;~", '$1', $string); // pour les ligatures e.g. "&oelig;"
        $string = preg_replace("~\&[^;]+\;~", '', $string); // supprime les autres caract�res

        $string = str_replace(md5('xooghost'), '_', $string);

        return $string . '.' . $path_parts['extension'];
    }

    /**
     * @return array
     */
    public function getPhpListAsArray()
    {
        $exclude = $this->exclude;
        $pages = parent::getAll(null, ['xooghost_url'], false, true);
        foreach ($pages as $page) {
            $exclude[] = $page['xooghost_url'];
        }

        $dirname = \XoopsBaseConfig::get('root-path') . '/modules/xooghost';

        $filelist = [];
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if ((preg_match('/(\.php)$/i', $file) && !is_dir($file) && !in_array($file, $exclude, true))) {
                    $file = basename($file);
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
