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

class Xooghost_page extends XoopsObject
{
    private $exclude_page = array('index','search','tag','userinfo');
    private $php_self = '';

    // constructor
    public function __construct()
    {
        $xoops = Xoops::getinstance();
        $this->php_self = basename($xoops->getenv('PHP_SELF'), '.php');

        $this->initVar('xooghost_id',            XOBJ_DTYPE_INT,               0, true,      11);
        $this->initVar('xooghost_url',           XOBJ_DTYPE_TXTBOX,           '', true,      54);
        $this->initVar('xooghost_title',         XOBJ_DTYPE_TXTBOX,           '', true,     255);
        $this->initVar('xooghost_uid',           XOBJ_DTYPE_INT,               0, true,       8);
        $this->initVar('xooghost_content',       XOBJ_DTYPE_TXTBOX,           '', true);
        $this->initVar('xooghost_description',   XOBJ_DTYPE_TXTAREA,          '', false);
        $this->initVar('xooghost_keywords',      XOBJ_DTYPE_TXTAREA,          '', false);
        $this->initVar('xooghost_image',         XOBJ_DTYPE_TXTBOX,  'blank.gif', false,    100);
        $this->initVar('xooghost_published',     XOBJ_DTYPE_STIME,             0, false,     10);
        $this->initVar('xooghost_online',        XOBJ_DTYPE_INT,               1, false,      1);
        $this->initVar('xooghost_hits',          XOBJ_DTYPE_INT,               0, false,     10);
        $this->initVar('xooghost_rates',         XOBJ_DTYPE_INT,               0, false,     10);
        $this->initVar('xooghost_like',          XOBJ_DTYPE_INT,               0, false,     10);
        $this->initVar('xooghost_dislike',       XOBJ_DTYPE_INT,               0, false,     10);

        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
    }

    private function Xooghost_page()
    {
        $this->__construct();
    }

    public function setPost( $addpost = true )
    {
        $xoops = Xoops::getinstance();
        $member_handler = $xoops->getHandlerMember();
        $poster = $member_handler->getUser( $this->getVar('xooghost_uid'));
        if ($poster instanceof XoopsUser) {
            if ($addpost) {
                $member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') + 1);
            } else {
                $member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') - 1);
            }
        }
    }

    public function getMetaDescription()
    {
        $myts = MyTextSanitizer::getInstance();
        if ( $this->getVar('xooghost_description') != '' ) {
            $string = $this->getVar('xooghost_description');
        } else {
            $string = $myts->undoHtmlSpecialChars( $this->getVar('xooghost_content') );
        }

        $string = str_replace('[breakpage]', '', $string);
        // remove html tags
        $string = strip_tags( $string );
//        return preg_replace(array('/&amp;/i'), array('&'), $string);
        return $string;
    }
    public function getMetaKeywords( $limit=5 )
    {
        if ( $this->getVar('xooghost_keywords') != '' ) {
            $string = $this->getVar('xooghost_keywords');
        } else {
            $string = $this->getMetaDescription() . ', ' . $this->getVar('xooghost_keywords');
        }
        $string .= $this->getVar('xooghost_title');

        $string = html_entity_decode( $string, ENT_QUOTES );
        $search_pattern=array("\t","\r\n","\r","\n",",",".","'",";",":",")","(",'"','?','!','{','}','[',']','<','>','/','+','_','\\','*','pagebreak','page');
        $replace_pattern=array(' ',' ',' ',' ',' ',' ',' ','','','','','','','','','','','','','','','','','','','','');
        $string = str_replace($search_pattern, $replace_pattern, $string);

        $tmpkeywords = explode(' ',$string);
        $tmpkeywords = array_count_values($tmpkeywords);
        arsort($tmpkeywords);
        $tmpkeywords = array_keys($tmpkeywords);

        $tmpkeywords = array_unique($tmpkeywords);
        foreach($tmpkeywords as $keyword) {
            if ( strlen(trim($keyword)) >= $limit && !is_numeric($keyword) ) {
                $keywords[] = htmlentities( trim( $keyword ) );
            }
        }
        return implode(', ', $keywords);
    }

    public function getValues($keys = null, $format = null, $maxDepth = null)
    {
        $xoops = Xoops::getInstance();
        $myts = MyTextSanitizer::getInstance();
        $ret = parent::getValues();

        $ghost_module = Xooghost::getInstance();
        $ghost_config = $ghost_module->LoadConfig();

        $dateformat = $ghost_config['xooghost_date_format'];
        $ret['xooghost_date_day'] = date('d', $ret['xooghost_published'] );
        $ret['xooghost_date_month'] = date('m', $ret['xooghost_published'] );
        $ret['xooghost_date_year'] = date('Y', $ret['xooghost_published'] );
        $ret['xooghost_time']      = $ret['xooghost_published'];
        $ret['xooghost_published'] = date(constant($dateformat), $ret['xooghost_published']);

        $ret['xooghost_link'] = XOOPS_URL . '/modules/xooghost/' . $ret['xooghost_url'];

        $ret['xooghost_uid_name'] = XoopsUser::getUnameFromId($ret['xooghost_uid'], true);

        if ($ret['xooghost_image'] != 'blank.gif') {
            $ret['xooghost_image_link'] = XOOPS_UPLOAD_URL . '/xooghost/images/' . $ret['xooghost_image'];
        } else {
            $ret['xooghost_image_link'] = XOOPS_URL . '/' . $xoops->theme()->resourcePath('/modules/xooghost/images/pages.png');
        }

        $ret['xooghost_content'] = $myts->undoHtmlSpecialChars($ret['xooghost_content']);

        if ( in_array( $this->php_self, $this->exclude_page) && strpos($ret['xooghost_content'], '[breakpage]') !== false ) {
            $ret['xooghost_content'] = substr( $ret['xooghost_content'], 0, strpos($ret['xooghost_content'], '[breakpage]') );
            $ret['readmore'] = true;
        } else {
            $ret['xooghost_content'] = str_replace('[breakpage]', '', $ret['xooghost_content']);
        }

        if ( !in_array( $this->php_self, $this->exclude_page) ) {
            if ( isset($_SESSION['xooghost_stat'])) {
                $rld_handler = $ghost_module->getHandler('xooghost_rld');
                $ret['xooghost_vote'] = $rld_handler->getVotes($ret['xooghost_id']);
                $ret['xooghost_yourvote'] = $rld_handler->getbyUser($ret['xooghost_id']);
            }
        }

            // tags
        if ( !in_array( $this->php_self, $this->exclude_page) || $this->php_self == 'index' || $this->php_self == 'page_print' ) {
            if ( $xoops->registry()->offsetExists('XOOTAGS') && $xoops->registry()->get('XOOTAGS') ) {
                $xootags_handler = $xoops->getModuleHandler('xootags_tags', 'xootags');
                $ret['tags'] = $xootags_handler->getbyItem($ret['xooghost_id']);
            }
        }
        return $ret;
    }

    public function create_page() {
        if ( !file_exists( XOOPS_ROOT_PATH . '/modules/xooghost/' . $this->getVar('xooghost_url') ) ) {
            $xoopstmp = Xoops::getInstance();
            $content = $xoopstmp->tpl()->fetch('admin:xooghost|xooghost_model_page.html');
            file_put_contents( XOOPS_ROOT_PATH . '/modules/xooghost/' . $this->getVar('xooghost_url') , $content );
        }
        return true;
    }

    public function CleanVarsForDB()
    {
        $system = System::getInstance();
        foreach ( parent::getValues() as $k => $v ) {
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

class XooghostXooghost_pageHandler extends XoopsPersistableObjectHandler
{
    private $exclude = array(
        'footer.php',
        'header.php',
        'index.php',
        'page_like_dislike.php',
        'page_print.php',
        'page_rate.php',
        'qrcode.php',
        'xoops_version.php',
    );

    public function __construct($db)
    {
        parent::__construct($db, 'xooghost', 'Xooghost_page', 'xooghost_id', 'xooghost_title');
    }

    public function getByURL( $Xooghost_url )
    {
        $criteria = new Criteria('xooghost_url', $Xooghost_url);
        $page = $this->getObjects($criteria, null, true);
        return $page[0];
    }

    public function getPublished( $sort = 'published', $order = 'desc', $start = 0, $limit = 0 )
    {
        $criteria = new CriteriaCompo();
        $criteria->add( new Criteria('xooghost_online', 1) ) ;
        $criteria->add( new Criteria('xooghost_published', time(), '<=') ) ;
        if ( $sort == 'random' ) {
            $criteria->setSort( 'rand()' );
        } else {
            $criteria->setSort( 'xooghost_' . $sort );
        }
        $criteria->setOrder( $order );
        $criteria->setStart( $start );
        $criteria->setLimit( $limit );

        return $this->getObjects($criteria, null, false);
    }

    public function SetOnline( $Xooghost_id )
    {
        if ($Xooghost_id != 0){
            $page = $this->get( $Xooghost_id );
            if ( $page->getVar('xooghost_online') == 1 ) {
                $page->setVar('xooghost_online', 0);
            } else {
                $page->setVar('xooghost_online', 1);
            }
            $this->insert( $page );
            return true;
        }
        return false;
    }

    public function SetRead( $pageObj )
    {
        $read = $pageObj->getVar('xooghost_hits') + 1;
        $pageObj->setVar('xooghost_hits', $read );
        $this->insert( $pageObj );
        return true;
    }

    public function SetLike_Dislike( $page_id, $like_dislike )
    {
        if ($page_id != 0){
            $page = $this->get( $page_id );
            if (is_object($page) && count($page) != 0) {
                $xoops = Xoops::getInstance();

                $ghost_module = Xooghost::getInstance();
                $rld_handler = $ghost_module->getHandler('xooghost_rld');

                if ( $ret = $rld_handler->SetLike_Dislike($page_id, $like_dislike) ) {
                    if ($like_dislike == 0) {
                        $xooghost_dislike = $page->getVar('xooghost_dislike') + 1;
                        $page->setVar('xooghost_dislike', $xooghost_dislike);
                    } elseif ($like_dislike == 1) {
                        $xooghost_like = $page->getVar('xooghost_like') + 1;
                        $page->setVar('xooghost_like', $xooghost_like);
                    }
                    $this->insert( $page );
                    return $page->getValues();
                }
            }
            return false;
        }
        return false;
    }

    public function SetRate( $page_id, $rate )
    {
        if ($page_id != 0){
            $page = $this->get( $page_id );
            if (is_object($page) && count($page) != 0) {
                $xoops = Xoops::getInstance();

                $ghost_module = Xooghost::getInstance();
                $rld_handler = $ghost_module->getHandler('xooghost_rld');

                if ( $ret = $rld_handler->SetRate($page_id, $rate) ) {
                    if ( is_array($ret) && count($ret) == 3 ) {
                        $page->setVar('xooghost_rates', $ret['average']);
                        $this->insert( $page );
                        return $ret;
                    }
                }
            }
            return false;
        }
        return false;
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

    public function insert(XoopsObject $object, $force = true)
    {
        $xoops = Xoops::getinstance();
        if ( parent::insert($object, $force) ) {
            $object->create_page();
            if ($object->isNew()) {
                return $xoops->db()->getInsertId();
            } else {
                return $object->getVar('xooghost_id');
            }
        }
        return false;
    }

    public function upload_images( $image_name )
    {
        $xoops = Xoops::getInstance();
        $autoload = XoopsLoad::loadConfig( 'xooghost' );

        $ghost_config = XooGhostPreferences::getInstance()->getConfig();

        $uploader = new XoopsMediaUploader( $xoops->path('uploads') . '/xooghost/images', $autoload['mimetypes'], $ghost_config['xooghost_image_size'], $ghost_config['xooghost_image_width'], $ghost_config['xooghost_image_height']);

        $ret = array();
        foreach ( $_POST['xoops_upload_file'] as $k => $input_image ) {
            if ( $_FILES[$input_image]['tmp_name'] != '' || is_readable( $_FILES[$input_image]['tmp_name'] ) ) {
                $path_parts = pathinfo( $_FILES[$input_image]['name'] );
                $uploader->setTargetFileName( $this->CleanImage( strtolower($image_name . '.' . $path_parts['extension']) ) );
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