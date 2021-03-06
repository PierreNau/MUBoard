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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Sun Oct 14 15:42:51 CEST 2012.
 */

/**
 * This is the Search controller class providing navigation and interaction functionality.
 */
class MUBoard_Controller_Search extends MUBoard_Controller_Base_Search
{

	/**
	 *
	 */
	public function moduleSearch($args)
	{
		$dom = ZLanguage::getModuleDomain('MUBoard');

		$searchsubmit = $this->request->getPost()->filter('searchsubmit', 'none' , FILTER_SANITIZE_STRING);
		$searchoptions = $this->request->getPost()->filter('searchoptions', 'all' , FILTER_SANITIZE_STRING);
		$searchplace = $this->request->getPost()->filter('searchplace', 'title' , FILTER_SANITIZE_STRING);
		$resultorder = $this->request->getPost()->filter('resultorder', 'none', FILTER_SANITIZE_STRING);

		$kind = $this->request->query->filter('kind', 'none', FILTER_SANITIZE_STRING);

		// user has not entered a string and there is 'none' as kind of search
		if ($searchsubmit == 'none' && $kind == 'none') {
			// return search form template
			return $this->searchRedirect();
		}
		// user hat entered a string
		else {
			if ($searchsubmit != 'none' && $kind == 'none') {
				$searchstring = $this->request->getPost()->filter('searchstring', '', FILTER_SANITIZE_STRING);
				if ($searchstring == '') {
					$url = ModUtil::url($this->name, 'search', 'modulesearch');
					return LogUtil::registerError(__('You have to enter a string!', $dom), null, $url);

				}
				else {
					$args['searchstring'] = $searchstring;
					$args['searchoptions'] = $searchoptions;
					$args['searchplace'] = $searchplace;
					$args['resultorder'] = $resultorder;
					$args['kind'] = $kind;
				}
			}
			if ($searchsubmit == 'none' && $kind != 'none') {
				$args['kind'] = $kind;
			}		
		}
		return ModUtil::apiFunc($this->name, 'search', 'moduleSearch', $args);
	}

	private function searchRedirect()
	{
		return $this->view->fetch('search/moduleSearch.tpl');
	}
}
