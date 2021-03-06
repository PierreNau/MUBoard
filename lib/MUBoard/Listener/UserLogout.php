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
 * Event handler implementation class for user logout events.
 */
use Doctrine\ORM\UnitOfWork;
class MUBoard_Listener_UserLogout
{
    /**
     * Listener for the `module.users.ui.logout.succeeded` event.
     *
     * Occurs right after a successful logout.
     * All handlers are notified.
     * The event's subject contains the user's user record.
     */
    public static function succeeded(Zikula_Event $event)
    {
		$user = $event->getSubject();
		$uid = $user['uid'];
		 
		MUBoard_Util_View::actualUser($uid , 1);
    	
    }
}
