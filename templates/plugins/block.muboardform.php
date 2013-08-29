<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula_Form
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Smarty function to wrap MUBoard_Form_View generated form controls with suitable form tags.
 *
 * @param array            $params  Parameters passed in the block tag.
 * @param string           $content Content of the block.
 * @param Zikula_Form_View $view    Reference to Zikula_Form_View object.
 *
 * @return string The rendered output.
 */
function smarty_block_muboardform($params, $content, $view)
{
    if ($content) {
        PageUtil::addVar('stylesheet', 'system/Theme/style/form/style.css');
        $encodingHtml = (array_key_exists('enctype', $params) ? " enctype=\"$params[enctype]\"" : '');
        $action = htmlspecialchars(System::getCurrentUri());
        $classString = '';
        if (isset($params['cssClass'])) {
            $classString = "class=\"$params[cssClass]\" ";
        }

        $request = new Zikula_Request_Http();

        $id = $request->getGet()->filter('id',0, FILTER_SANITIZE_NUMBER_INT);
        $forumid = $request->getGet()->filter('forum',0, FILTER_SANITIZE_NUMBER_INT);

        // we check if the entrypoint is part of the url
        $stripentrypoint = ModUtil::getVar('ZConfig', 'shorturlsstripentrypoint');
        
        // get url name
        $tables = DBUtil::getTables();
        $modcolumn = $tables['modules_column'];
        $module = 'MUBoard';
        $where = "$modcolumn[name] = '" . DataUtil::formatForStore($module) . "'";
        $module = DBUtil::selectObject('modules', $where);
        $urlname = $module['url'];

        if (ModUtil::getVar('ZConfig', 'shorturls') == 0) {
        if (strpos($action, "func=display")!== false ) {
            $action = 'index.php?module=' . $urlname . '&amp;type=user&amp;func=edit&amp;ot=posting&amp;answer=1';
        }
        if (strpos($action, "func=edit&ot=posting")!== false && $forumid > 0) {
            $action = 'index.php?module=' . $urlname . '&amp;type=user&amp;func=edit&amp;ot=posting&amp;forum' . $forumid;
        }

        } else {

            if (strpos($action, $urlname . "/posting/id.") !== false) {

                if ($stripentrypoint == 1) {
                    $action = $urlname . '/edit/ot/posting/answer/1';
                }
                elseif ($stripentrypoint == 0) {
                    $action = 'index.php/' . $urlname  . '/edit/ot/posting/answer/1';
                }
            }
            if (strpos($action, "edit/ot/posting/forum/") !== false && $forumid > 0) {
                if ($stripentrypoint == 1) {
                    $action = $urlname . '/edit/ot/posting/forum/' . $forumid;
                }
                elseif ($stripentrypoint == 0) {
                    $action = 'index.php/' . $urlname . '/edit/ot/posting/forum/' . $forumid;
                }
            }
        }

        $view->postRender();

        $formId = $view->getFormId();
        $out = "
        <form id=\"{$formId}\" {$classString}action=\"$action\" method=\"post\"{$encodingHtml}>
        $content
        <div>
        {$view->getStateHTML()}
        {$view->getStateDataHTML()}
        {$view->getIncludesHTML()}
        {$view->getCsrfTokenHtml()}
        <input type=\"hidden\" name=\"__formid\" id=\"form__id\" value=\"{$formId}\" />
        <input type=\"hidden\" name=\"FormEventTarget\" id=\"FormEventTarget\" value=\"\" />
        <input type=\"hidden\" name=\"FormEventArgument\" id=\"FormEventArgument\" value=\"\" />
        <script type=\"text/javascript\">
        <!--
        function FormDoPostBack(eventTarget, eventArgument)
        {
        var f = document.getElementById('{$formId}');
        if (!f.onsubmit || f.onsubmit())
        {
        f.FormEventTarget.value = eventTarget;
        f.FormEventArgument.value = eventArgument;
        f.submit();
    }
    }
    // -->
    </script>
    </div>
    </form>
    ";
        return $out;
    }
}
