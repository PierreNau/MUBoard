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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Feb 02 13:43:18 CET 2012.
 */

/**
 * The muboardGetLastPosting modifier return the state of a ticket with an image.
 *
 * @param  int       $id      forum id
 *
 * @return string the output of last posting
 */
function smarty_modifier_muboardGetLastPosting($id)
{
	$out = MUBoard_Util_View::getLastPostingOfForum($id);



	return $out;
}
