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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Tue Sep 25 14:18:39 CEST 2012.
 */

/**
 * The muboardImageThumb modifier displays a thumbnail image.
 *
 * @param  string    $fileName   The input file name.
 * @param  string    $filePath   The input file path (including file name).
 * @param  int       $width      Desired width.
 * @param  int       $height     Desired height.
 * @param  array     $thumbArgs  Additional arguments.
 *
 * @return string The thumbnail file path.
 */
function smarty_modifier_muboardImageThumb($fileName = '', $filePath = '', $width = 100, $height = 80, $thumbArgs = array())
{
    /**
     * By overriding this plugin or the util method called below you may add further thumbnail arguments
     * based on custom conditions.
     */
    return MUBoard_Util_Image::getThumb($filePath, $width, $height, $thumbArgs);
}
