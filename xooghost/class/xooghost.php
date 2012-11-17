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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class Xooghost extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->initVar('xooghost_id',            XOBJ_DTYPE_INT,               0, true,      11);
        $this->initVar('xooghost_url',           XOBJ_DTYPE_TXTBOX,           '', true,      54);
        $this->initVar('xooghost_title',         XOBJ_DTYPE_TXTBOX,           '', true,     255);
        $this->initVar('xooghost_content',       XOBJ_DTYPE_TXTBOX,           '', true);
        $this->initVar('xooghost_description',   XOBJ_DTYPE_TXTAREA,          '', true);
        $this->initVar('xooghost_keywords',      XOBJ_DTYPE_TXTAREA,          '', true);
        $this->initVar('xooghost_image',         XOBJ_DTYPE_TXTBOX,  'blank.gif', false,    100);
        $this->initVar('xooghost_published',     XOBJ_DTYPE_STIME,             0, false,     10);
        $this->initVar('xooghost_display',       XOBJ_DTYPE_INT,               1, false,      1);
        $this->initVar('xooghost_hits',          XOBJ_DTYPE_INT,               0, false,     10);

        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
    }

    private function Xooghost()
    {
        $this->__construct();
    }

    public function setView()
    {
        $this->setVar('xooghost_display', 1);
        return true;
    }

    public function setHide()
    {
        $this->setVar('xooghost_display', 0);
        return true;
    }

    public function toArray()
    {
        $myts = MyTextSanitizer::getInstance();
        $ret = $this->getValues();
        $ret['xooghost_published'] = date(_SHORTDATESTRING, $ret['xooghost_published']);
        $ret['xooghost_link'] = XOOPS_URL . '/modules/xooghost/' . $ret['xooghost_url'];

        if ($ret['xooghost_image'] != 'blank.gif') {
            $ret['xooghost_image_link'] = XOOPS_UPLOAD_URL . '/xooghost/images/' . $ret['xooghost_image'];
        } else {
            $ret['xooghost_image_link'] = XOOPS_URL . '/modules/xooghost/images/default.png';
        }

        $ret['xooghost_content'] = $myts->undoHtmlSpecialChars($ret['xooghost_content']);
        return $ret;
    }

    public function create_page() {
        if ( !file_exists( XOOPS_ROOT_PATH . '/modules/xooghost/' . $this->getVar('xooghost_url') ) ) {
            $xoopstmp = Xoops::getInstance();
            $content = $xoopstmp->tpl->fetch('admin:xooghost|xooghost_model_page.html');
            file_put_contents( XOOPS_ROOT_PATH . '/modules/xooghost/' . $this->getVar('xooghost_url') , $content );
        }
        return true;
    }

    public function CleanVarsForDB()
    {
        $system = System::getInstance();
        foreach ( $this->getValues() as $k => $v ) {
            if ( $k != 'dohtml' ) {
                if ( $this->vars[$k]['data_type'] == XOBJ_DTYPE_STIME || $this->vars[$k]['data_type'] == XOBJ_DTYPE_MTIME || $this->vars[$k]['data_type'] == XOBJ_DTYPE_LTIME) {
                    $value = $system->CleanVars($_POST[$k], 'date', date('Y-m-d'), 'date') + $system->CleanVars($_POST[$k], 'time', date('u'), 'int');
                    $this->setVar( $k,  isset( $_POST[$k] ) ? $value : $v );
                } elseif ( $this->vars[$k]['data_type'] == XOBJ_DTYPE_INT ) {
                    $value = $system->CleanVars($_POST, $k, $v, 'int');
                    $this->setVar( $k,  $value );
                } elseif ( $this->vars[$k]['data_type'] == XOBJ_DTYPE_ARRAY ) {
                    $value = $system->CleanVars($_POST, $k, $v, 'array');
                    $this->setVar( $k,  $value );
                } else {
                    $value = $system->CleanVars($_POST, $k, $v, 'string');
                    $this->setVar( $k,  $value );
                }
            }
            if ( $k == 'xooghost_url' ) {
                $this->setVar( $k, $this->cleanURL($this->getVar($k) ) );
            }
        }
    }

    public function CleanURL( $string )
    {
        $string = basename( $string, '.php' );

        $string = str_replace('_', 'xooghost', $string);
        $string = str_replace('-', 'xooghost', $string);
        $string = str_replace(' ', 'xooghost', $string);

        $string = preg_replace('~\p{P}~','', $string);
        $string = htmlentities($string, ENT_NOQUOTES, _CHARSET);
        $string = preg_replace('~\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;~', '$1', $string);
        $string = preg_replace('~\&([A-za-z]{2})(?:lig)\;~', '$1', $string); // pour les ligatures e.g. '&oelig;'
        $string = preg_replace('~\&[^;]+\;~', '', $string); // supprime les autres caractères

        $string = str_replace('xooghost', '_' , $string);
        return $string . '.php';
    }
}

class XooghostXooghostHandler extends XoopsPersistableObjectHandler
{
    private $exclude = array(
                 'comment_delete.php', 'comment_edit.php', 'comment_new.php',
                 'comment_post.php', 'comment_reply.php',
                 'footer.php', 'header.php', 'index.php', 'list.tag.php', 'rss.php', 'rate.php',
                 'view.tag.php', 'xoops_version.php'
    );

    private $_published = null;

    public function __construct(&$db)
    {
        parent::__construct($db, 'xooghost', 'xooghost', 'xooghost_id', 'xooghost_title');
    }

    public function getByURL( $xooghost_url )
    {
        $criteria = new Criteria('xooghost_url', $xooghost_url);
        $page = $this->getObjects($criteria, null, false);
        return $page[0];
    }

    public function getPublished()
    {
        if ( !isset($this->_published) ) {
            $criteria = new CriteriaCompo();
            $criteria->add( new Criteria('xooghost_display', 1) ) ;
            $criteria->add( new Criteria('xooghost_published', time(), '<=') ) ;
            $criteria->setSort( 'xooghost_published' );
            $criteria->setOrder( 'asc' );
            $this->_published = $this->getObjects($criteria, null, false);
        }
        return $this->_published;
    }

    public function SelectPage()
    {
        $pages = $this->getPublished();
        $form = new XoopsFormSelect('', 'xooghost_url');
        $form->setExtra("onChange='javascript:window.location.href=this.value'");
        $form->addOption('index.php', _XOO_GHOST_CHOOSE);
        foreach ($pages as $page) {
            $form->addOption( $page['xooghost_link'], $page['xooghost_title'] );
        }
        return $form->render();
    }

    public function renderAdminList()
    {
        $criteria = new CriteriaCompo();
        $criteria->setSort( 'xooghost_published' );
        $criteria->setOrder( 'asc' );

        return $this->getObjects($criteria, null, false);
    }

    public function insert($object, $force = true)
    {
        if ( parent::insert($object, $force) ) {
            $object->create_page();
            return true;
        }
        return false;
    }

    public function upload_images()
    {
        $xoops = Xoops::getInstance();
        $autoload = XoopsLoad::loadConfig( 'xooghost' );

        $xooGhost_config = XooGhostPreferences::getInstance()->getConfig();

        $uploader = new XoopsMediaUploader( $xoops->path('uploads') . '/xooghost/images', $autoload['mimetypes'], $xooGhost_config['xooghost_image_size'], $xooGhost_config['xooghost_image_width'], $xooGhost_config['xooghost_image_height']);

        $ret = array();
        foreach ( $_POST['xoops_upload_file'] as $k => $input_image ) {
            if ( $_FILES[$input_image]['tmp_name'] != '' || is_readable( $_FILES[$input_image]['tmp_name'] ) ) {
                $uploader->setTargetFileName( $this->CleanImage($_FILES[$input_image]['name']) );
                if ( $uploader->fetchMedia( $_POST['xoops_upload_file'][$k] ) ) {
                    if ( $uploader->upload() ) {
                        $ret[$input_image] = array( 'filename' => $uploader->getSavedFileName(), 'error' => false, 'message' => '');
                    } else {
                        $ret[$input_image] = array( 'filename' => $_FILES[$input_image]['name'], 'error' => true , 'message' => $uploader->getErrors() );
                    }
                } else {
                    $ret[$input_image] = array( 'filename' => $_FILES[$input_image]['name'], 'error' => true , 'message' => $uploader->getErrors() );
                }
            }
        }
        return $ret;
    }

    public function CleanImage( $filename )
    {
        $path_parts = pathinfo( $filename );
        $string = $path_parts['filename'];

        $string = str_replace('_', md5('xooghost'), $string);
        $string = str_replace('-', md5('xooghost'), $string);
        $string = str_replace(' ', md5('xooghost'), $string);

        $string = preg_replace('~\p{P}~','', $string);
        $string = htmlentities($string, ENT_NOQUOTES, _CHARSET);
        $string = preg_replace("~\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;~", "$1", $string);
        $string = preg_replace("~\&([A-za-z]{2})(?:lig)\;~", "$1", $string); // pour les ligatures e.g. "&oelig;"
        $string = preg_replace("~\&[^;]+\;~", "", $string); // supprime les autres caractères

        $string = str_replace(md5('xooghost'), '_' , $string);
        return $string . '.' . $path_parts['extension'];
    }

    public function getPhpListAsArray()
    {
        $exclude = $this->exclude;
        $pages = parent::getAll(null, array('xooghost_url'), false, true);
        foreach ( $pages as $page ) {
            $exclude[] = $page['xooghost_url'];
        }

        $dirname = XOOPS_ROOT_PATH . '/modules/xooghost';

        $filelist = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if ( ( preg_match( '/(\.php)$/i', $file ) && !is_dir( $file ) && !in_array($file, $exclude) ) ) {
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
?>