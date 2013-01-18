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
 * Utility implementation class for view helper methods.
 */
class MUBoard_Util_View extends MUBoard_Util_Base_View
{

	/**
	 *
	 * This method gets the number of issues in an category
	 */
	public static function getIssuesOfCategory($catid)
	{
		// get repositoy for Categories
		$repository = MUBoard_Util_Model::getCategoryRepository();
		// get category by id
		$category = $repository->selectById($catid);
		// get forums of this category
		$forums = $category->getForum();
			
		// get repository for Forums
		$repository2 = MUBoard_Util_Model::getForumRepository();
			
		$count = 0;
			
		// walk through the forums and take their postings
		foreach ($forums as $forum) {
			// get forum by id
			$forum = $repository2->selectById($forum['id']);
			// get postings of this forum
			$postings = $forum->getPosting();
			// we look if postings are parent postings -> issue
			foreach ($postings as $posting) {
				if ($posting['parent_id'] === NULL) {
					$count = $count + 1;
				}
			}
		}
			
		return $count;
			
	}

	/**
	 *
	 * This method gets the number of postings in an category
	 */
	public static function getPostingsOfCategory($catid)
	{
		// get repositoy for Categories
		$repository = MUBoard_Util_Model::getCategoryRepository();
		// get category by id
		$category = $repository->selectById($catid);
		// get forums of this category
		$forums = $category->getForum();
			
		// get repository for Forums
		$repository2 = MUBoard_Util_Model::getForumRepository();
			
		$count = 0;
			
		// walk through the forums and take their postings
		foreach ($forums as $forum) {
			// get forum by id
			$forum = $repository2->selectById($forum['id']);
			// get postings of this forum
			$postings = $forum->getPosting();
			$number = count($postings);

			$count = $count + $number;

		}
			
		return $count;
			
	}

	/**
	 *
	 * This method gets the number of issues in a forum
	 */
	public static function getIssuesOfForum($forumid)
	{

		$count = 0;

		// get repositoy for Forum
		$repository = MUBoard_Util_Model::getForumRepository();
		// get forum by id
		$forum = $repository->selectById($forumid);
		// get forums of this category
		$postings = $forum->getPosting();
		// we look if postings are parent postings -> issue
		foreach ($postings as $posting) {
			if ($posting['parent_id'] === NULL) {
				$count = $count + 1;
			}
		}
			
		return $count;
			
	}

	/**
	 *
	 * This method gets the number of postings in a forum
	 */
	public static function getPostingsOfForum($forumid)
	{
		// get repositoy for Forum
		$repository = MUBoard_Util_Model::getForumRepository();
		// get forum by id
		$forum = $repository->selectById($forumid);
		// get forums of this category
		$postings = $forum->getPosting();
			
		$count = count($postings);
			
		return $count;
			
	}

	/**
	 *
	 * This method gets the number of answers for a posting
	 */
	public static function getAnswersOfPosting($postingid)
	{

		$count = 0;

		// get repositoy for Posting
		$repository = MUBoard_Util_Model::getPostingRepository();
		// get posting by id
		$posting = $repository->selectById($postingid);
		// get answers of this posting
		$children = $posting->getChildren();

		$count = count($children);
			
		return $count;
			
	}
	
	/**
	 *
	 * This method gets the last answer to an issue
	 */
	public static function getLastAnswer($id)
	{
		// get repositoy for posting
		$repository = MUBoard_Util_Model::getPostingRepository();
		// get posting by id
		$posting = $repository->selectById($id);
		// getchildren of this posting
		$children = $posting->getChildren();
		// we look for the last posting
		$childid = 0;
	
		foreach ($children as $child) {
			if ($child['id'] > $childid) {
				$childid = $child['id'];
			}
		}

		// if childid > 0, we have child for the issue
		if ($childid > 0) {
			// we get the last posting
			$lastposting = $repository->selectById($childid);

			$url = ModUtil::url('MUBoard', 'user', 'display', array('ot' => 'posting', 'id' => $id));
	
			$createdDate = $lastposting->getCreatedDate();
			$date = DateUtil::formatDatetime($createdDate, 'datetimelong');
			$uname = UserUtil::getVar('uname', $lastposting['createdUserId']);
			$out .= __('Last posting by ');
			$out .= $uname;
			$out .= "<br />";
			$out .= __('on ');
			$out .= $date;

		}
		else {
			$out = __('No postings available!');
		}
			
		return $out;
			
	}

	/**
	 *
	 * This method gets the last posting in a forum
	 */
	public static function getLastPostingOfForum($forumid)
	{
		// get repositoy for forum
		$repository = MUBoard_Util_Model::getForumRepository();
		// get forum by id
		$forum = $repository->selectById($forumid);
		// get postings of this forum
		$postings = $forum->getPosting();
		// we look for the last posting
		$postingid = 0;

		foreach ($postings as $posting) {
			if ($posting['id'] > $postingid) {
				$postingid = $posting['id'];
			}
		}

		// we get a repository for posting
		$repository2 = MUBoard_Util_Model::getPostingRepository();
		// if postingid > 0, we have posting in the forum
		if ($postingid > 0) {
			// we get the last posting
			$lastposting = $repository2->selectById($postingid);
			// we check if last posting is parent or answer
			$parent = $lastposting->getParent_id();
			if ($parent != NULL) {
				$issue = $repository2->selectById($parent);
				$issuetitle = $issue->getTitle();
				$id = $issue->getId();
			}
			else {
				$issuetitle = $lastposting->getTitle();
				$id = $lastposting->getId();
			}
			$url = ModUtil::url('MUBoard', 'user', 'display', array('ot' => 'posting', 'id' => $id));

			$out = '';
			$createdDate = $lastposting->getCreatedDate();
			$date = DateUtil::formatDatetime($createdDate, 'datetimelong');
			$uname = UserUtil::getVar('uname', $lastposting['createdUserId']);
			$out .= __('Last posting by ');
			$out .= $uname;
			$out .= "<br />";
			$out .= __('on ');
			$out .= $date . "<br />";
			$out .= __('Issue: ');
			$out .= "<a href='" . $url;
			$out .= "'>" . $issuetitle . "</a>";

		}
		else {
			$out = __('No postings available!');
		}
			
		return $out;
			
	}


	/**
	 * This method gets the number of postings of an user
	 */
	public static function getNumberOfPostingsOfUser($userid) {

		// get a repository for pstings
		$repository = MUBoard_Util_Model::getPostingRepository();
		// count Postings
		$where = 'tbl.createdUserId = \'' . DataUtil::formatForStore($userid) . '\'';
		$count = $repository->selectCount($where);

		return $count;
	}
	
	/**
	 * This method gets if an issue is open or closed
	 */

	public static function getImportantOfPosting($postingid) {

		$count = self::getAnswersOfPosting($postingid);

			if ($count >= 20) {
				$alt = __('Important issue!');
				$out =  "<img alt='";
				$out .= $alt;
				$out .= "' src='/images/icons/extrasmall/important.png' />";
			}

		return $out;
	}

	/**
	 * This method checks if user must edit this posting - creater of the issue
	 */
	public static function getStateOfEditOfIssue($postingid) {

		//get repository for Postings
		$repository = MUBoard_Util_Model::getPostingRepository();
		// get posting
		$posting = $repository->selectById($postingid);
		// check if issue or anser
		$parent = $posting->getParent();
		// get forum of posting
		$forum = $posting->getForum();
		$forumid = $forum->getId();
		// get userid of user created this posting
		$createdUserId = $posting->getCreatedUserId();
		// get created Date
		$createdDate = $posting->getCreatedDate();
		$createdDate = $createdDate->getTimestamp();

		// get the actual time
		$actualTime = DateUtil::getDatetime();
		// get modvar editTime
		$editTime = ModUtil::getVar('MUBoard', 'editTime');

		$diffTime = DateUtil::getDatetimeDiff($createdDate, $actualTime);
		$diffTimeHours = $diffTime['d'] * 24 + $diffTime['h'];

		if (UserUtil::isLoggedIn()== true) {
		$userid = UserUtil::getVar('uid');
		}
		else {
			$out = '';
		}

		if ($createdUserId == $userid && ($diffTimeHours < $editTime || $parent == NULL) ) {
			$serviceManager = ServiceUtil::getManager();
			// generate an auth key to use in urls
			$csrftoken = SecurityUtil::generateCsrfToken($serviceManager, true);
			$url = ModUtil::url('MUBoard', 'user', 'edit', array('ot' => 'posting', 'id' => $postingid, 'forum' => $forumid, 'token' => $csrftoken));
			$title = __('You have permissions to edit this issue!');
			$out = "<a title='{$title}' id='muboard-user-posting-header-infos-edit-creater' href='{$url}'>
            <img src='/images/icons/extrasmall/xedit.png' />
            </a>";
		}
		else {
			$out = '';
		}

		return $out;
	}

	/**
	 *
	 * This method gets the state of the category abo
	 */
	public static function getStateOfCategoryAbo($categroyid)
	{
		// get repositoy for Categories
		$repository = MUBoard_Util_Model::getAboRepository();
		// get actual userid
		$userid = UserUtil::getVar('uid');
		// look for abo
		$where = 'tbl.catid = \'' . DataUtil::formatForStore($categoryid) . '\'';
		$where .= ' AND ';
		$where .= 'tbl.userid = \'' . DataUtil::formatForStore($userid) . '\'';
		$abo = $repository->selectWhere($where);

		if (!$abo) {
			$url = ModUtil::url('MUBoard', 'admin', 'take', array('ot' => 'abo', 'category' => $categroyid));
			$out =  "<a id='muboard-user-posting-header-infos-abo' href='{$url}'>
            <img src='/images/icons/extrasmall/mail_post_to.png' />
            </a>";
		}

		if ($abo) {
			$url = ModUtil::url('MUBoard', 'admin', 'quit', array('ot' => 'abo', 'category' => $categroyid));
			$out = "<a id='muboard-user-posting-header-infos-abo' href='{$url}'>
            <img src='/images/icons/extrasmall/mail_get.png' />
            </a>";
		}
			
		return $out;
	}

	/**
	 *
	 * This method gets the state of the forum abo
	 */
	public static function getStateOfForumAbo($forumid, $func)
	{

		$request = new Zikula_Request_Http();
		$cat = $request->getGet()->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);

		// get repositoy for Categories
		$repository = MUBoard_Util_Model::getAboRepository();
		if (UserUtil::isLoggedIn() == true) {

		// get actual userid
		$userid = UserUtil::getVar('uid');
		// look for abo
		$where = 'tbl.forumid = \'' . DataUtil::formatForStore($forumid) . '\'';
		$where .= ' AND ';
		$where .= 'tbl.userid = \'' . DataUtil::formatForStore($userid) . '\'';
		$abo = $repository->selectWhere($where);

		if (!$abo) {
			$url = ModUtil::url('MUBoard', 'admin', 'take', array('ot' => 'abo', 'forum' => $forumid, 'view' => $func, 'cat' => $cat));
			$out =  "<a id='muboard-user-posting-header-infos-abo' href='{$url}'>
            <img src='/images/icons/extrasmall/mail_post_to.png' />
            </a>";
		}

		if ($abo) {
			$url = ModUtil::url('MUBoard', 'admin', 'quit', array('ot' => 'abo', 'forum' => $forumid, 'view' => $func, 'cat' => $cat));
			$out = "<a id='muboard-user-posting-header-infos-abo' href='{$url}'>
            <img src='/images/icons/extrasmall/mail_get.png' />
            </a>";
		}
		}
		else {
			$out = '';
		}
			
		return $out;
	}

	/**
	 *
	 * This method gets the state of the posting abo
	 */
	public static function getStateOfPostingAbo($postingid)
	{
		$request = new Zikula_Request_Http();
		// get objecttype
		$ot = $request->getGet()->filter('ot', 'category', FILTER_SANITIZE_STRING);
		$forumid = $request->getGet()->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);
		// get repositoy for Categories
		$repository = MUBoard_Util_Model::getAboRepository();
		if (UserUtil::isLoggedIn() == true) {
		// get actual userid
		$userid = UserUtil::getVar('uid');
		// look for abo
		$where = 'tbl.postingid = \'' . DataUtil::formatForStore($postingid) . '\'';
		$where .= ' AND ';
		$where .= 'tbl.userid = \'' . DataUtil::formatForStore($userid) . '\'';
		$abo = $repository->selectWhere($where);

		if ($ot == 'posting') {
			if (!$abo) {
				$url = ModUtil::url('MUBoard', 'admin', 'take', array('ot' => 'abo', 'posting' => $postingid, 'object' => $ot));
				$out =  "<a id='muboard-user-posting-header-infos-abo' href='{$url}'>
            <img src='/images/icons/extrasmall/mail_post_to.png' />
            </a>";
			}

			if ($abo) {
				$url = ModUtil::url('MUBoard', 'admin', 'quit', array('ot' => 'abo', 'posting' => $postingid, 'object' => $ot));
				$out = "<a id='muboard-user-posting-header-infos-abo' href='{$url}'>
            <img src='/images/icons/extrasmall/mail_get.png' />
            </a>";
			}
		}
		if ($ot == 'forum') {
			if (!$abo) {
				$url = ModUtil::url('MUBoard', 'admin', 'take', array('ot' => 'abo', 'posting' => $postingid, 'object' => $ot, 'forum' => $forumid));
				$out =  "<a id='muboard-user-posting-header-infos-abo' href='{$url}'>
            <img src='/images/icons/extrasmall/mail_post_to.png' />
            </a>";
			}

			if ($abo) {
				$url = ModUtil::url('MUBoard', 'admin', 'quit', array('ot' => 'abo', 'posting' => $postingid, 'object' => $ot,'forum' => $forumid));
				$out = "<a id='muboard-user-posting-header-infos-abo' href='{$url}'>
            <img src='/images/icons/extrasmall/mail_get.png' />
            </a>";
			}
		}
		}
		else {
			$out = '';
		}

		return $out;
	}

	/**
	 * This method gives back if new postings are in the
	 * forum since last login and show the relevant icon
	 * @param itemid          id of the relevant item
	 * @param kind            1 we check for forums, 2 we check for issues
	 */
	public static function PostingsSinceLastLogin($itemid, $kind = 1)
	{
		$dom = ZLanguage::getModuleDomain('MUBoard');

		if ($kind == 1) {
			// get repositoy for Forum
			$repository = MUBoard_Util_Model::getForumRepository();
			// get forum by id
			$forum = $repository->selectById($itemid);
			$forumid = $forum->getId();

			// we get the saved postingids
			$postingids = SessionUtil::getVar('muboardpostingids');

			if (count($postingids) > 0 && is_array($postingids)) {
				$postingids = implode(",", $postingids);

				// get repository for Posting
				$repository2 = MUBoard_Util_Model::getPostingRepository();
				$where = 'tbl.forum = \'' . DataUtil::formatForStore($itemid) . '\'';
				$where .= ' AND ';
				$where .= 'tbl.id IN (' . $postingids . ')';
				$countpostings = $repository2->selectCount($where);
				
				if ($countpostings > 0) {

					$out = self::getOut();
				}
				else {
					$out = self::getOut(2);
				}
			}
			else {
				$out = self::getOut(2);
			}

		}

		if ($kind == 2) {
			// get repository for Posting
			$repository = MUBoard_Util_Model::getPostingRepository();
			// get postingid
			$posting = $repository->selectById($itemid);
			$postingid = $posting->getId();

			$postingids = SessionUtil::getVar('muboardpostingids');

			if ($postingids) {
				if (in_array($postingid, $postingids)) {
					$out = self::getOut();
				}
				else {
					$out = self::getOut(2);
				}
			}
			else {
				$out = self::getOut(2);
			}
		}

		return $out;
			

	}

	/**
	 *
	 *
	 */
	public static function actualUser($userid, $kind = 1) {

		$userrepository = MUBoard_Util_Model::getUserRepository();
		$where = 'tbl.userid = \'' . DataUtil::formatForStore($userid) . '\'';
		$user = $userrepository->selectWhere($where);
		if (count($user) == 1) {
			$user = $user[0];
			$serviceManager = ServiceUtil::getManager();
			$entityManager = $serviceManager->getService('doctrine.entitymanager');

			if ($kind == 1) {
				$user->setLastVisit(DateUtil::getDatetime());
			}
			if ($kind == 2) {
				$user->setNumberPostings($user->getNumberPostings() + 1);
			}
			$entityManager->flush();

			$rank = $user->getRank();
			if (isset($rank)) {
				$rankid = $rank->getId();
			}

			if (isset($rankid)) {
				$rankrepository = MUBoard_Util_Model::getRankRepository();
				$rank = $rankrepository->selectById($rankid);
				$rankspecial = $rank->getSpecial();
				$rankMaxPostings = $rank->getMaxPostings();
				// check if it is a special rank
				if ($rankspecial == 1) {
					// nothing to do
				}
				// is no special rank
				else {
					$numberPostings = $user->getNumberPostings();
					if ($numberPostings > $rankMaxPostings) {
						$where = 'tbl.minPostings <= \'' . DataUtil::formatForStore($numberPostings) . '\'';
						$where .= ' AND ';
						$where = 'tbl.maxPostings >= \'' . DataUtil::formatForStore($numberPostings) . '\'';

						$rank = $rankrepository->selectWhere($where);
						$newRank = $rankrepository->selectById($rank[0]['id']);
						if (count($newRank == 1)) {
							$user->setRank($newRank);
							$entityManager->flush();
						}
					}
				}
			}
			else {
				$rankrepository = MUBoard_Util_Model::getRankRepository();
				$where = 'tbl.minPostings = 0';
				$rank = $rankrepository->selectWhere($where);
				$firstRank = $rankrepository->selectById($rank[0]['id']);
				if (isset($firstRank)) {
					$user->setRank($firstRank);
					$entityManager->flush();
				}
			}
		}
		else {
			$serviceManager = ServiceUtil::getManager();
			$entityManager = $serviceManager->getService('doctrine.entitymanager');
			$user = new MUBoard_Entity_User();
			$user->setLastVisit(DateUtil::getDatetime());
			$user->setUserid($userid);
			$user->setNumberPostings(0);
			$entityManager->persist($user);
			$entityManager->flush();

		}

	}

	/**
	 *
	 */
	public static function getUserRank($id) {

		$dom = ZLanguage::getModuleDomain('MUBoard');

		// we get a repository for users
		$userrepository = MUBoard_Util_Model::getUserRepository();
		// get infos of the relevant user
		$where = 'tbl.userid = \'' . DataUtil::formatForStore($id) . '\'';
		$user = $userrepository->selectWhere($where);
		if (count($user) == 1) {
			$user = $user[0];
		}

		$userregDate = UserUtil::getVar('user_regdate', $id);
		$userregDate = DateUtil::formatDatetime($userregDate, 'datebrief');
		$lastVisit = DateUtil::formatDatetime($user['lastVisit'], 'datebrief');

		$out = __('Registered: ', $dom) . $userregDate . '<br />';
		$out .= __('Last Visit: ', $dom) . $lastVisit . '<br />';
		$out .= __('Postings: ', $dom) . $user['numberPostings'] . '<br />';
		$out .= __('Rank: ') . $user['rank']['name'] . '<br />';
		if ($user['rank']['special'] == 0) {
			$imagepath = ModUtil::getVar('MUBoard', 'standardIcon');
			for ($i = 0; $i < $user['rank']['numberOfIcons']; $i++) {
				$out .= "<img src='" . $imagepath . "' />";
			}
		}
		else {
			$out .= '<img src="$user.rank.uploadImage|muboardImageThumb:$user.rank.uploadImageFullPath:250:150" width="250" height="150" />';
		}
		$out .= "<br />";

		return $out;
	}

	/**
	 *
	 */
	public static function actualPostings($userid) {
		if (UserUtil::isLoggedIn() == true) {
			// we get a repository for users
			$userrepository = MUBoard_Util_Model::getUserRepository();
			$where = 'tbl.userid = \'' . DataUtil::formatForStore($userid) . '\'';
			$user = $userrepository->selectWhere($where);
			// we get the datetime of the last visit of muboard
			$lastVisit = $user[0]['lastVisit'];
			// we get the timestamp
			$lastVisit = $lastVisit->getTimestamp();
			// we format for
			$lastVisit = date( 'Y-m-d H:i:s', $lastVisit);

			$date = DateUtil::getDatetime();

			// we get a repository for postings
			$postingrepository = MUBoard_Util_Model::getPostingRepository();
			$where = 'tbl.createdDate > \'' . $lastVisit . '\'';
			$postings = $postingrepository->selectWhere($where);

			$forumids = array();
			$postingids = array();

			foreach ($postings as $posting) {
				// get forum id
				$forum = $posting->getForum();
				$forumid = $forum->getId();
				if (!in_array($forumid, $forumids)){
					$forumids[] = $forumid;
				}
				// get posting id
				$parent = $posting->getParent();
				if (!is_null($parent)) {
					$parentid = $parent->getId();
					if ($parentid != NULL)
					if (!in_array($parentid, $postingids)) {
						$postingids[] = $parentid;
					}
				}
				else {
					$id = $posting->getId();
					if (!in_array($id, $postingids)) {
						$postingids[] = $id;
					}

				}
			}

			//SessionUtil::setVar('muboardforumids', $forumids);
			SessionUtil::setVar('muboardpostingids', $postingids);
		}
	}

	/**
	 *
	 */
	public static function modifyPostings($userid) {
		$view = new Zikula_Request_Http();
		$postingid = $view->getGet()->filter('id', 0, FILTER_SANITIZE_STRING);
		$postingids = SessionUtil::getVar('muboardpostingids');
		if (count($postingids) > 0 && is_array($postingids)) {
			if(in_array($postingid,$postingids)){
				$pos=array_search($postingid,$postingids);
				unset($postingids[$pos]);
			}
		}
		if (count($postingids) > 0) {
			SessionUtil::setVar('muboardpostingids', $postingids);
		}
		else {
			SessionUtil::delVar('muboardpostingids');
		}
	}

	/**
	 * output for the method actualPostings
	 */
	protected static function getOut($kind = 1) {

		if ($kind == 1) {
			$out = "<img src='/images/icons/small/folder_new.png' />";
		}
		else {
			$out = "<img src='/images/icons/small/folder_blue.png' />";
		}
		return $out;
	}
}
