<?php
/**
 * MUBoard.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUBoard
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Fri Jun 15 09:09:36 CEST 2012.
 */

/**
 * The muboardGetFileSize modifier displays the size of a given file in a readable way.
 *
 * @param string  $size     File size in bytes.
 * @param string  $filepath The input file path including file name (if file size is not known).
 * @param boolean $nodesc   If set to true the description will not be appended.
 * @param boolean $onlydesc If set to true only the description will be returned.
 *
 * @return string File size in a readable form.
 */
function smarty_modifier_muboardGetFileSize($size = 0, $filepath = '', $nodesc = false, $onlydesc = false)
{
    if (!is_numeric($size)) {
        $size = (int) $size;
    }
    if (!$size) {
        if (empty($filepath) || !file_exists($filepath)) {
            return '';
        }
        $size = filesize($filepath);
    }
    if (!$size) {
        return '';
    }

    $result = MUBoard_Util_View::getReadableFileSize($size, $nodesc, $onlydesc);

    return $result;
}
