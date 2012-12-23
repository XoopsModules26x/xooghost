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

function getMetaDescription( $string )
{
    $string = $xoops->module->name() . ' : ' . $string ;
    $string .= '. ' . $xoops->getConfig('meta_description', 3);

    $myts = MyTextSanitizer::getInstance();
    $string = $myts->undoHtmlSpecialChars( $string );
    $string = str_replace('[breakpage]', '', $string);
    // remove html tags
    $string = strip_tags( $string );
    return $string;

function getMetaKeywords( $string, $limit = 5)
{
    $string = strtolower($string) . ', ' . strtolower($xoops->getConfig('meta_keywords', 3));

    $myts = MyTextSanitizer::getInstance();
    $string = $myts->undoHtmlSpecialChars( $string );
    $string = str_replace('[breakpage]', '', $string);
    // remove html tags
    $string = strip_tags( $string );

    $string = html_entity_decode( $string, ENT_QUOTES );
    $search_pattern=array("\t","\r\n","\r","\n",",",".","'",";",":",")","(",'"','?','!','{','}','[',']','<','>','/','+','_','\\','*','pagebreak','page');
    $replace_pattern=array(' ',' ',' ',' ',' ',' ',' ','','','','','','','','','','','','','','','','','','','','');
    $string = str_replace($search_pattern, $replace_pattern, $string);

    $tmpkeywords = explode(' ',$string);

    $tmpkeywords = array_unique($tmpkeywords);
    foreach($tmpkeywords as $keyword) {
        }
    }
    return implode(', ', $keywords);
}
?>