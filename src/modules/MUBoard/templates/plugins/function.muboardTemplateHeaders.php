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
 * The muboardTemplateHeaders plugin performs header() operations
 * to change the content type provided to the user agent.
 *
 * Available parameters:
 *   - contentType:  Content type for corresponding http header.
 *   - asAttachment: If set to true the file will be offered for downloading.
 *   - filename:     Name of download file.
 *
 * @param  array       $params All attributes passed to this function from the template.
 * @param  Zikula_View $view   Reference to the view object.
 *
 * @return boolean false.
 */
function smarty_function_muboardTemplateHeaders($params, $view)
{
    if (!isset($params['contentType'])) {
        $render->trigger_error($view->__f('%1$s: missing parameter \'%2$s\'', array('smarty_function_muboardTemplateHeaders', 'contentType')));
    }

    // apply header
    header('Content-Type: ' . $params['contentType']);

    // if desired let the browser offer the given file as a download
    if (isset($params['asAttachment']) && $params['asAttachment'] && isset($params['filename']) && !empty($params['filename'])) {
        header('Content-Disposition: attachment; filename=' . $params['filename']);
    }

    return;
}
