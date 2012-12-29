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


defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class XooghostCommentsPlugin extends Xoops_Plugin_Abstract implements CommentsPluginInterface
{
    /**
     * @return string
     */
    public function itemName()
    {        return 'ghost_id';
    }

    /**
     * @return string
     */
    public function pageName()
    {        return 'page_comment.php';
    }

    /**
     * @return array
     */
    public function extraParams()
    {
        return array();
    }

    /**
     * This method will be executed upon successful post of an approved comment.
     * This includes comment posts by administrators, and change of comment status from 'pending' to 'active' state.
     * An CommentsComment object that has been approved will be passed as the first and only parameter.
     * This should be useful for example notifying the item submitter of a comment post.
     *
     * @param CommentsComment $comment
     *
     * @return void
     */
    public function approve(CommentsComment $comment)
    {
        //Where are you looking at?
    }

    /**
     * This method will be executed whenever the total number of 'active' comments for an item is changed.
     *
     * @param int $item_id   The unique ID of an item
     * @param int $total_num The total number of active comments
     *
     * @return void
     */
    public function update($item_id, $total_num)
    {
        $db = Xoops::getInstance()->db();
        $sql = 'UPDATE ' . $db->prefix('xooghost') . ' SET xooghost_comments = ' . intval($total_num) . ' WHERE xooghost_id = ' . intval($item_id);
        $db->query($sql);
    }

    /**
     * This method will be executed whenever a new comment form is displayed.
     * You can set a default title for the comment and a header to be displayed on top of the form
     * ex: return array(
     *      'title' => 'My Article Title',
     *      'text' => 'Content of the article');
     *      'timestamp' => time(); //Date of the article in unix format
     *      'uid' => Id of the article author
     *
     * @param int $item_id The unique ID of an item
     *
     * @return array
     */
    public function itemInfo($item_id)
    {        $ret = array();

        $ghost_module = Xooghost::getInstance();
        $ghost_handler = $ghost_module->GhostHandler();
        $page = $page = $ghost_handler->get($item_id);

        $ret['text']      = $page->getVar('xooghost_content');
        $ret['title']     = $page->getVar('xooghost_title');
        $ret['uid']       = $page->getVar('xooghost_uid');
        $ret['timestamp'] = $page->getVar('xooghost_published');
        return $ret;
    }
}

