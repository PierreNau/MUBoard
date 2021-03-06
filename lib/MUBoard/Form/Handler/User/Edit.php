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
 * This handler class handles the page events of the Form called by the MUBoard_user_edit() function.
 * It collects common functionality required by different object types.
 */
class MUBoard_Form_Handler_User_Edit extends MUBoard_Form_Handler_User_Base_Edit
{
    /**
     * Initialize form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @return boolean False in case of initialization errors, otherwise true.
     */
    public function initialize(Zikula_Form_View $view)
    {
        $this->inlineUsage = ((UserUtil::getTheme() == 'Printer') ? true : false);
        $this->idPrefix = $this->request->getGet()->filter('idp', '', FILTER_SANITIZE_STRING);

        // initialise redirect goal
        $this->returnTo = $this->request->getGet()->filter('returnTo', null, FILTER_SANITIZE_STRING);
        // store current uri for repeated creations
        $this->repeatReturnUrl = System::getCurrentURI();

        $this->permissionComponent = $this->name . ':' . $this->objectTypeCapital . ':';

        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $objectTemp = new $entityClass();
        $this->idFields = $objectTemp->get_idFields();

        // retrieve identifier of the object we wish to view
        $this->idValues = MUBoard_Util_Controller::retrieveIdentifier($this->request, array(), $this->objectType, $this->idFields);
        $hasIdentifier = MUBoard_Util_Controller::isValidIdentifier($this->idValues);

        $entity = null;
        $this->mode = ($hasIdentifier) ? 'edit' : 'create';

        if ($this->mode == 'edit') {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_EDIT)) {
                // set an error message and return false
                return LogUtil::registerPermissionError();
            }

            $entity = $this->initEntityForEdit();

            if ($this->hasPageLockSupport === true && ModUtil::available('PageLock')) {
                // try to guarantee that only one person at a time can be editing this entity
                /*  ModUtil::apiFunc('PageLock', 'user', 'pageLock',
                        array('lockName'  => $this->name . $this->objectTypeCapital . $this->createCompositeIdentifier(),
                                'returnUrl' => $this->getRedirectUrl(null, $entity))); */
            }
        } else {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_ADD)) {
                return LogUtil::registerPermissionError();
            }

            $entity = $this->initEntityForCreation($entityClass);
        }

        $this->view->assign('mode', $this->mode)
        ->assign('inlineUsage', $this->inlineUsage);

        // We set text field to empty if entity class is posting
        if ($this->request->query->filter('ot', 'category', FILTER_SANITIZE_STRING) == 'posting' && $this->request->query->filter('func', 'main', FILTER_SANITIZE_STRING) == 'display') {
            $entity['text'] = '';
        }

        $entityData = $entity->toArray();

        // assign data to template as array (makes translatable support easier)
        $this->view->assign($this->objectTypeLower, $entityData);

        // save entity reference for later reuse
        $this->entityRef = $entity;

        $this->initializeAdditions();

        // everything okay, no initialization errors occured
        return true;
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user. Commands are typically something
     * that originates from a {@link Zikula_Form_Plugin_Button} plugin. The passed args contains different properties
     * depending on the command source, but you should at least find a <var>$args['commandName']</var>
     * value indicating the name of the command. The command name is normally specified by the plugin
     * that initiated the command.
     * @see Zikula_Form_Plugin_Button
     * @see Zikula_Form_Plugin_ImageButton
     */
    public function HandleCommand(Zikula_Form_View $view, &$args)
    {
        $dom = ZLanguage::getModuleDomain('MUBoard');
        $id = 0;
        $id = $this->request->query->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);
        $forumid = $this->request->query->filter('forum', 0);
        $forumid2 = $this->request->getPost()->filter('muboardForum_ForumItemList', 0, FILTER_SANITIZE_NUMBER_INT);
        $parentid = 0;
        $parentid = $this->request->getPost()->filter('muboardPosting_ParentItemList', 0);
        
        if ($args['commandName'] == 'toforum') {
            if ($forumid > 0) {
                $url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'forum', 'id' => $forumid));
                return System::redirect($url);
            }
        }

        if ($args['commandName'] == 'delete') {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_DELETE)) {
                return LogUtil::registerPermissionError();
            }
        }

        if (!in_array($args['commandName'], array('delete', 'cancel', 'toforum'))) {
            // do forms validation including checking all validators on the page to validate their input
            if (!$this->view->isValid()) {

                if ($id > 0) {
                    if ($parentid == 0) {
                        $idurl = ModUtil::url($this->name, 'user', 'edit', array('ot' => 'posting', 'id' => $id));
                        LogUtil::registerError(__('Sorry! You have to enter a title and a text!', $dom));
                        return System::redirect($idurl);
                    } else {
                        $parentnotnullurl = ModUtil::url($this->name, 'user', 'edit', array('ot' => 'posting', 'id' => $id));
                        LogUtil::registerError(__('Sorry! You have to enter a text!', $dom));
                        return System::redirect($parentnotnullurl);
                    }
                } else {
                    if ($parentid > 0) {
                        $parentnullurl = ModUtil::url($this->name, 'user', 'display' , array('ot' => 'posting', 'id' => $parentid));
                        return LogUtil::registerError(__('Sorry! You have to enter a text!', $dom), null, $parentnullurl);
                    }

                    if ($parentid == 0) {
                        $parentdigiturl = ModUtil::url($this->name, 'user', 'display' , array('ot' => 'posting', 'id' => $parentid));
                    }
                }
                return false;
            }
        }

        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $repository = $this->entityManager->getRepository($entityClass);
        if ($this->hasTranslatableFields === true) {
            $transRepository = $this->entityManager->getRepository($entityClass . 'Translation');
        }

        $result = $this->fetchInputData($view, $args);
        if ($result === false) {
            return $result;
        }

        $hookAreaPrefix = 'muboard.ui_hooks.' . $this->objectTypeLowerMultiple;

        // get treated entity reference from persisted member var
        $entity = $this->entityRef;

        if (in_array($args['commandName'], array('create', 'update'))) {
            // event handling if user clicks on create or update

            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.validate_edit', new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {

                if ($args['commandName'] == 'create' && $forumid > 0 && $forumid2 > 0) {
                    LogUtil::registerError(__('The validation of the hooked security module was incorrect. Please try again.', $dom));
                    $url = ModUtil::url($this->name, 'user', 'edit', array('ot' => 'posting', 'forum' => $forumid));
                    System::redirect($url);
                }
                if ($forumid == 0) {
                    LogUtil::registerError(__('The validation of the hooked security module was incorrect. Please try again.', $dom));
                    $url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'posting', 'id' => $parentid));
                    System::redirect($url);
                }

                if ($args['commandName'] == 'update' && $id > 0) {
                    LogUtil::registerError(__('The validation of the hooked security module was incorrect. Please try again.', $dom));

                }
                return false;
            }

            $this->performUpdate($args);

            $success = true;
            if ($args['commandName'] == 'create') {
                // store new identifier
                foreach ($this->idFields as $idField) {
                    $this->idValues[$idField] = $entity[$idField];
                    // check if the insert has worked, might become obsolete due to exception usage
                    if (!$this->idValues[$idField]) {
                        $success = false;
                        break;
                    }
                }
            } else if ($args['commandName'] == 'update') {
            }
            $this->addDefaultMessage($args, $success);

            // Let any hooks know that we have created or updated an item
            $urlArgs = array('ot' => $this->objectType);
            $urlArgs = $this->addIdentifiersToUrlArgs($urlArgs);
            $url = new Zikula_ModUrl($this->name, 'user', 'display', ZLanguage::getLanguageCode(), $urlArgs);
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.process_edit', $this->createCompositeIdentifier(), $url);
            $this->notifyHooks($hook);
        } else if ($args['commandName'] == 'delete') {
            // event handling if user clicks on delete

            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.validate_delete', new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {
                return false;
            }

            // delete entity
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            $this->addDefaultMessage($args, true);

            // Let any hooks know that we have deleted an item
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.process_delete', $this->createCompositeIdentifier());
            $this->notifyHooks($hook);
        } else if ($args['commandName'] == 'cancel') {
            // event handling if user clicks on cancel
        }

        if ($args['commandName'] != 'cancel') {
            // clear view cache to reflect our changes
            $this->view->clear_cache();
        }

        if ($this->hasPageLockSupport === true && $this->mode == 'edit') {
            ModUtil::apiFunc('PageLock', 'user', 'releaseLock',
            array('lockName' => $this->name . $this->objectTypeCapital . $this->createCompositeIdentifier()));
        }

        return $this->view->redirect($this->getRedirectUrl($args, $entity, $repeatCreateAction));
    }

    /**
     * Get success or error message for default operations.
     *
     * @param Array   $args    arguments from handleCommand method.
     * @param Boolean $success true if this is a success, false for default error.
     * @return String desired status or error message.
     */
    protected function getDefaultMessage($args, $success = false)
    {
        $message = '';
        switch ($args['commandName']) {
            case 'create':
                if ($success === true) {
                    $message = $this->__('Done! Item created.');
                } else {
                    $message = $this->__('Error! Creation attempt failed.');
                }
                break;
            case 'update':
                if ($success === true) {
                    $message = $this->__('Done! Item updated.');
                } else {
                    $message = $this->__('Error! Update attempt failed.');
                }
                break;
            case 'delete':
                if ($success === true) {
                    $message = $this->__('Done! Item deleted.');
                } else {
                    $message = $this->__('Error! Deletion attempt failed.');
                }
                break;
        }
        return $message;
    }
}
