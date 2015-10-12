<?php
/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2015-10-11
 * Time: 00:08
 */

//namespace Xoopsmodules\xoocontact;

class XooGhostUtilities
{
    /**
     * @param $string
     *
     * @return mixed|string
     */
    public function getMetaDescription($string)
    {
        $xoops = Xoops::getInstance();
        $myts  = MyTextSanitizer::getInstance();

        if (is_array($string)) {
            $string = implode(', ', $string);
        }
        $string = $xoops->module->name() . ' : ' . $string;
        $string .= '. ' . $xoops->getConfig('meta_description', 3);

        $string = $myts->undoHtmlSpecialChars($string);
        $string = str_replace('[breakpage]', '', $string);
        // remove html tags
        $string = strip_tags($string);

        return $string;
    }

    /**
     * @param     $string
     * @param int $limit
     *
     * @return string
     */
    public function getMetaKeywords($string, $limit = 5)
    {
        $xoops = Xoops::getInstance();
        $myts  = MyTextSanitizer::getInstance();
        $keywords = array();
        if (is_array($string)) {
            $string = implode(', ', $string);
        }
        $string = strtolower($string) . ', ' . strtolower($xoops->getConfig('meta_keywords', 3));
        $string = $myts->undoHtmlSpecialChars($string);
        $string = str_replace('[breakpage]', '', $string);
        $string = strip_tags($string);
        $string = html_entity_decode($string, ENT_QUOTES);

        $search_pattern  = array("\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '_', '\\', '*', 'pagebreak', 'page');
        $replace_pattern = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $string          = str_replace($search_pattern, $replace_pattern, $string);

        $tmpkeywords = explode(' ', $string);

        $tmpkeywords = array_unique($tmpkeywords);
        foreach ($tmpkeywords as $keyword) {
            if (strlen(trim($keyword)) >= $limit && !is_numeric($keyword)) {
                $keywords[] = htmlentities(trim($keyword));
            }
        }

        return implode(', ', $keywords);
    }
}
