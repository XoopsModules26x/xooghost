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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xooghost
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 */

/**
 * @return bool
 */
function xoops_module_install_xooghost()
{
    $xoops = \Xoops::getInstance();
    $folders = [];
    $folders[] = \XoopsBaseConfig::get('uploads-path') . '/xooghost/images';
    $images = ['index.html', 'blank.gif'];

    foreach ($folders as $folder) {
        if (!xooghost_mkdirs($folder)) {
            return false;
        }
        foreach ($images as $image) {
            if (!xooghost_copyfile(\XoopsBaseConfig::get('uploads-path'), $image, $folder)) {
                return false;
            }
        }
    }

    return true;
}

/**
 * @param              $pathname
 * @param mixed|string $pathout
 *
 * @return bool
 */
function xooghost_mkdirs($pathname, $pathout = XOOPS_ROOT_PATH)
{
    $xoops = \Xoops::getInstance();
    $pathname = mb_substr($pathname, mb_strlen(\XoopsBaseConfig::get('root-path')));
    $pathname = str_replace(DIRECTORY_SEPARATOR, '/', $pathname);

    $dest = $pathout;
    $paths = explode('/', $pathname);

    foreach ($paths as $path) {
        if (!empty($path)) {
            $dest = $dest . '/' . $path;
            if (!is_dir($dest)) {
                if (!mkdir($dest, 0755) && !is_dir($dest)) {
                    return false;
                }
                xooghost_copyfile(\XoopsBaseConfig::get('uploads-path'), 'index.html', $dest);
            }
        }
    }

    return true;
}

/**
 * @param $folder_in
 * @param $source_file
 * @param $folder_out
 *
 * @return bool
 */
function xooghost_copyfile($folder_in, $source_file, $folder_out)
{
    if (!is_dir($folder_out)) {
        if (!xooghost_mkdirs($folder_out)) {
            return false;
        }
    }

    // Simple copy for a file
    if (is_file($folder_in . '/' . $source_file)) {
        return copy($folder_in . '/' . $source_file, $folder_out . '/' . basename($source_file));
    }

    return false;
}
