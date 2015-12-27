<?php

/**
 * Xoopaginate : Page navigation manager
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

use Xoops\Core\Request;

/**
 * Class XooPaginate
 */
class XooPaginate
{
    private $prev;
    private $next;
    private $first;
    private $last;

    private $total;
    private $perpage;
    private $current;
    private $extra;
    private $url;
    private $offset;

    /**
     * @param        $total_items
     * @param        $items_perpage
     * @param        $current_start
     * @param string $start_name
     * @param string $extra_arg
     * @param int    $offset
     */
    public function __construct($total_items, $items_perpage, $current_start, $start_name = 'start', $extra_arg = '', $offset = 1)
    {
        $this->total   = (int)($total_items);
        $this->perpage = (int)($items_perpage);
        $this->current = (int)($current_start);
        $this->extra   = $extra_arg;
        if ($extra_arg != '' && (substr($extra_arg, -5) !== '&amp;' || substr($extra_arg, -1) !== '&')) {
            $this->extra = '&amp;' . $extra_arg;
        }
        $this->url    = Request::getString('PHP_SELF','' ,'SERVER') . '?' . trim($start_name) . '=';
        $this->offset = (int)($offset);

        $this->render();
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getValue($key)
    {
        return $this->$key;
    }

    public function display()
    {
        echo $this->render();
    }

    /**
     * @return mixed|string
     */
    private function render()
    {
        $xoops = Xoops::getInstance();
        $xoops->tpl()->assign('xoopaginate', $this);

        $total_pages = ceil($this->total / $this->perpage);
        $i           = 0;
        if ($this->total != 0 && $this->perpage != 0) {
            if (($this->current - $this->perpage) >= 0) {
                $this->prev  = $this->url . ($this->current - $this->perpage) . $this->extra;
                $this->first = $this->url . 0 . $this->extra;
            }

            $counter      = 1;
            $current_page = (int)(floor(($this->current + $this->perpage) / $this->perpage));
            while ($counter <= $total_pages) {
                if ($counter == $current_page) {
                    $pages[$i]['text']  = $counter;
                    $pages[$i]['link']  = $this->url . (($counter - 1) * $this->perpage) . $this->extra;
                    $pages[$i]['value'] = (($counter - 1) * $this->perpage);

                    ++$i;
                } elseif (($counter > $current_page - $this->offset && $counter < $current_page + $this->offset) || $counter == 1 || $counter == $total_pages) {
                    if ($counter == $total_pages && $current_page < $total_pages - $this->offset) {
                        $pages[$i]['link']  = false;
                        $pages[$i]['text']  = '...';
                        $pages[$i]['value'] = '.';
                        ++$i;
                    }
                    $pages[$i]['text']  = $counter;
                    $pages[$i]['link']  = $this->url . (($counter - 1) * $this->perpage) . $this->extra;
                    $pages[$i]['value'] = (($counter - 1) * $this->perpage);
                    ++$i;
                    if ($counter == 1 && $current_page > 1 + $this->offset) {
                        $pages[$i]['link']  = false;
                        $pages[$i]['text']  = '...';
                        $pages[$i]['value'] = '.';
                        ++$i;
                    }
                }
                ++$counter;
            }

            if (($this->current + $this->perpage) < $this->total) {
                $this->next = $this->url . ($this->current + $this->perpage) . $this->extra;
                $this->last = $this->url . (($counter - 2) * $this->perpage) . $this->extra;
            }
        }
        if ($this->total >= $this->perpage && ceil($this->total / $this->perpage) > 1) {
            $xoops->tpl()->assign('xoopages', $pages);
        }

        return $xoops->tpl()->fetch('module:xooghost/xoo_paginate.tpl');
    }
}