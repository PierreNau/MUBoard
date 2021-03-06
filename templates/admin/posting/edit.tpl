{* purpose of this template: build the Form to edit an instance of posting *}
{include file='admin/header.tpl'}
{pageaddvar name='javascript' value='modules/MUBoard/javascript/MUBoard_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUBoard/javascript/MUBoard_validation.js'}

{if $work eq 'movetoforum'}
<h2>{gt text='Issue to move'}: {$posting.title}</h2>
{/if}
{if $mode eq 'edit'}
    {gt text='Edit posting' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{elseif $mode eq 'create'}
    {gt text='Create posting' assign='templateTitle'}
    {assign var='adminPageIcon' value='new'}
{else}
    {gt text='Edit posting' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{/if}
{if $work eq 'movetoforum'}
    {gt text='Move issue to another forum' assign='templateTitle'}
    {assign var='adminPageIcon' value='edit'}
{/if}
<div class="muboard-posting muboard-edit">
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type=$adminPageIcon size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>
{form enctype='multipart/form-data' cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muboardFormFrame}
    {formsetinitialfocus inputId='parent_id'}

    <fieldset>
        <legend>{gt text='Content'}</legend>
        {if $work ne 'movetoforum'}
        <div class="z-formrow">
            {formlabel for='title' __text='Title'}
            {formtextinput group='posting' id='title' mandatory=false readOnly=false __title='Enter the title of the posting' textMode='singleline' maxLength=255 cssClass=''}
        </div>
        <div class="z-formrow">
            {formlabel for='text' __text='Text' mandatorysym='1'}
            {formtextinput group='posting' id='text' mandatory=true __title='Enter the text of the posting' textMode='multiline' rows='6' cols='50' cssClass='required'}
            {muboardValidationError id='text' class='required'}
        </div>
        <div class="z-formrow">
            {formlabel for='invocations' __text='Invocations'}
            {formintinput group='posting' id='invocations' mandatory=false __title='Enter the invocations of the posting' maxLength=11 cssClass=' validate-digits'}
            {muboardValidationError id='invocations' class='validate-digits'}
        </div>
        <div class="z-formrow">
            {formlabel for='firstImage' __text='First image'}<br />{* break required for Google Chrome *}
            {formuploadinput group='posting' id='firstImage' mandatory=false readOnly=false cssClass=''}

            <div class="z-formnote">{gt text='Allowed file extensions:'} gif, jpeg, jpg, png</div>
            {if $mode ne 'create'}
                {if $posting.firstImage ne ''}
                  <div class="z-formnote">
                      {gt text='Current file'}:
                      <a href="{$posting.firstImageFullPathUrl}" title="{$posting.title|replace:"\"":""}"{if $posting.firstImageMeta.isImage} rel="imageviewer[posting]"{/if}>
                      {if $posting.firstImageMeta.isImage}
                          <img src="{$posting.firstImage|muboardImageThumb:$posting.firstImageFullPath:80:50}" width="80" height="50" alt="{$posting.title|replace:"\"":""}" />
                      {else}
                          {gt text='Download'} ({$posting.firstImageMeta.size|muboardGetFileSize:$posting.firstImageFullPath:false:false})
                      {/if}
                      </a>
                  </div>
                  <div class="z-formnote">
                      {formcheckbox group='posting' id='firstImageDeleteFile' readOnly=false __title='Delete first image ?'}
                      {formlabel for='firstImageDeleteFile' __text='Delete existing file'}
                  </div>
                {/if}
            {/if}
        </div>
        <div class="z-formrow">
            {formlabel for='secondImage' __text='Second image'}<br />{* break required for Google Chrome *}
            {formuploadinput group='posting' id='secondImage' mandatory=false readOnly=false cssClass=''}

            <div class="z-formnote">{gt text='Allowed file extensions:'} gif, jpeg, jpg, png</div>
            {if $mode ne 'create'}
                {if $posting.secondImage ne ''}
                  <div class="z-formnote">
                      {gt text='Current file'}:
                      <a href="{$posting.secondImageFullPathUrl}" title="{$posting.title|replace:"\"":""}"{if $posting.secondImageMeta.isImage} rel="imageviewer[posting]"{/if}>
                      {if $posting.secondImageMeta.isImage}
                          <img src="{$posting.secondImage|muboardImageThumb:$posting.secondImageFullPath:80:50}" width="80" height="50" alt="{$posting.title|replace:"\"":""}" />
                      {else}
                          {gt text='Download'} ({$posting.secondImageMeta.size|muboardGetFileSize:$posting.secondImageFullPath:false:false})
                      {/if}
                      </a>
                  </div>
                  <div class="z-formnote">
                      {formcheckbox group='posting' id='secondImageDeleteFile' readOnly=false __title='Delete second image ?'}
                      {formlabel for='secondImageDeleteFile' __text='Delete existing file'}
                  </div>
                {/if}
            {/if}
        </div>
        <div class="z-formrow">
            {formlabel for='thirdImage' __text='Third image'}<br />{* break required for Google Chrome *}
            {formuploadinput group='posting' id='thirdImage' mandatory=false readOnly=false cssClass=''}

            <div class="z-formnote">{gt text='Allowed file extensions:'} gif, jpeg, jpg, png</div>
            {if $mode ne 'create'}
                {if $posting.thirdImage ne ''}
                  <div class="z-formnote">
                      {gt text='Current file'}:
                      <a href="{$posting.thirdImageFullPathUrl}" title="{$posting.title|replace:"\"":""}"{if $posting.thirdImageMeta.isImage} rel="imageviewer[posting]"{/if}>
                      {if $posting.thirdImageMeta.isImage}
                          <img src="{$posting.thirdImage|muboardImageThumb:$posting.thirdImageFullPath:80:50}" width="80" height="50" alt="{$posting.title|replace:"\"":""}" />
                      {else}
                          {gt text='Download'} ({$posting.thirdImageMeta.size|muboardGetFileSize:$posting.thirdImageFullPath:false:false})
                      {/if}
                      </a>
                  </div>
                  <div class="z-formnote">
                      {formcheckbox group='posting' id='thirdImageDeleteFile' readOnly=false __title='Delete third image ?'}
                      {formlabel for='thirdImageDeleteFile' __text='Delete existing file'}
                  </div>
                {/if}
            {/if}
        </div>
        <div class="z-formrow">
            {formlabel for='firstFile' __text='First file'}<br />{* break required for Google Chrome *}
            {formuploadinput group='posting' id='firstFile' mandatory=false readOnly=false cssClass=''}

            <div class="z-formnote">{gt text='Allowed file extensions:'} pdf</div>
            {if $mode ne 'create'}
                {if $posting.firstFile ne ''}
                  <div class="z-formnote">
                      {gt text='Current file'}:
                      <a href="{$posting.firstFileFullPathUrl}" title="{$posting.title|replace:"\"":""}"{if $posting.firstFileMeta.isImage} rel="imageviewer[posting]"{/if}>
                      {if $posting.firstFileMeta.isImage}
                          <img src="{$posting.firstFile|muboardImageThumb:$posting.firstFileFullPath:80:50}" width="80" height="50" alt="{$posting.title|replace:"\"":""}" />
                      {else}
                          {gt text='Download'} ({$posting.firstFileMeta.size|muboardGetFileSize:$posting.firstFileFullPath:false:false})
                      {/if}
                      </a>
                  </div>
                  <div class="z-formnote">
                      {formcheckbox group='posting' id='firstFileDeleteFile' readOnly=false __title='Delete first file ?'}
                      {formlabel for='firstFileDeleteFile' __text='Delete existing file'}
                  </div>
                {/if}
            {/if}
        </div>
        <div class="z-formrow">
            {formlabel for='secondFile' __text='Second file'}<br />{* break required for Google Chrome *}
            {formuploadinput group='posting' id='secondFile' mandatory=false readOnly=false cssClass=''}

            <div class="z-formnote">{gt text='Allowed file extensions:'} pdf</div>
            {if $mode ne 'create'}
                {if $posting.secondFile ne ''}
                  <div class="z-formnote">
                      {gt text='Current file'}:
                      <a href="{$posting.secondFileFullPathUrl}" title="{$posting.title|replace:"\"":""}"{if $posting.secondFileMeta.isImage} rel="imageviewer[posting]"{/if}>
                      {if $posting.secondFileMeta.isImage}
                          <img src="{$posting.secondFile|muboardImageThumb:$posting.secondFileFullPath:80:50}" width="80" height="50" alt="{$posting.title|replace:"\"":""}" />
                      {else}
                          {gt text='Download'} ({$posting.secondFileMeta.size|muboardGetFileSize:$posting.secondFileFullPath:false:false})
                      {/if}
                      </a>
                  </div>
                  <div class="z-formnote">
                      {formcheckbox group='posting' id='secondFileDeleteFile' readOnly=false __title='Delete second file ?'}
                      {formlabel for='secondFileDeleteFile' __text='Delete existing file'}
                  </div>
                {/if}
            {/if}
        </div>
        <div class="z-formrow">
            {formlabel for='thirdFile' __text='Third file'}<br />{* break required for Google Chrome *}
            {formuploadinput group='posting' id='thirdFile' mandatory=false readOnly=false cssClass=''}

            <div class="z-formnote">{gt text='Allowed file extensions:'} pdf</div>
            {if $mode ne 'create'}
                {if $posting.thirdFile ne ''}
                  <div class="z-formnote">
                      {gt text='Current file'}:
                      <a href="{$posting.thirdFileFullPathUrl}" title="{$posting.title|replace:"\"":""}"{if $posting.thirdFileMeta.isImage} rel="imageviewer[posting]"{/if}>
                      {if $posting.thirdFileMeta.isImage}
                          <img src="{$posting.thirdFile|muboardImageThumb:$posting.thirdFileFullPath:80:50}" width="80" height="50" alt="{$posting.title|replace:"\"":""}" />
                      {else}
                          {gt text='Download'} ({$posting.thirdFileMeta.size|muboardGetFileSize:$posting.thirdFileFullPath:false:false})
                      {/if}
                      </a>
                  </div>
                  <div class="z-formnote">
                      {formcheckbox group='posting' id='thirdFileDeleteFile' readOnly=false __title='Delete third file ?'}
                      {formlabel for='thirdFileDeleteFile' __text='Delete existing file'}
                  </div>
                {/if}
            {/if}
        </div>
    </fieldset>

    {if $mode ne 'create'}
        {include file='admin/include_standardfields_edit.tpl' obj=$posting}
    {/if}
    {/if}
    {if $work ne 'movetoforum'}
    {include file='admin/posting/include_selectOne.tpl' relItem=$posting aliasName='parent' idPrefix='muboardPosting_Parent'}
    {/if}
    {include file='admin/forum/include_selectEditOne.tpl' relItem=$posting aliasName='forum' idPrefix='muboardForum_Forum'}

    {* include display hooks *}
    {if $mode eq 'create'}
        {notifydisplayhooks eventname='muboard.ui_hooks.postings.form_edit' id=null assign='hooks'}
    {else}
        {notifydisplayhooks eventname='muboard.ui_hooks.postings.form_edit' id=$posting.id assign='hooks'}
    {/if}
    {if is_array($hooks) && isset($hooks[0])}
        <fieldset>
            <legend>{gt text='Hooks'}</legend>
            {foreach key='hookName' item='hook' from=$hooks}
            <div class="z-formrow">
                {$hook}
            </div>
            {/foreach}
        </fieldset>
    {/if}

    {* include return control *}
    {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="z-formrow">
                {formlabel for='repeatcreation' __text='Create another item after save'}
                {formcheckbox group='posting' id='repeatcreation' readOnly=false}
            </div>
        </fieldset>
    {/if}

    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {if $mode eq 'edit'}
        {if $work eq 'none'}
        {formbutton id='btnUpdate' commandName='update' __text='Update posting' class='z-bt-save'}
        {else}
        {formbutton id='btnMove' commandName='update' __text='Move issue' class='z-bt-save'}        
        {/if}
      {if !$inlineUsage}
      {if $work eq 'none'}  
        {gt text='Really delete this posting?' assign='deleteConfirmMsg'}
        {formbutton id='btnDelete' commandName='delete' __text='Delete posting' class='z-bt-delete z-btred' confirmMessage=$deleteConfirmMsg}
      {/if} 
      {/if}
    {elseif $mode eq 'create'}
        {formbutton id='btnCreate' commandName='create' __text='Create posting' class='z-bt-ok'}
    {else}
        {formbutton id='btnUpdate' commandName='update' __text='OK' class='z-bt-ok'}
    {/if}        
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}

    </div>
  {/muboardFormFrame}
{/form}

</div>
{include file='admin/footer.tpl'}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='deleteImageArray'}

<script type="text/javascript" charset="utf-8">
/* <![CDATA[ */
    var editImage = '<img src="{{$editImageArray.src}}" width="16" height="16" alt="" />';
    var removeImage = '<img src="{{$deleteImageArray.src}}" width="16" height="16" alt="" />';
    var relationHandler = new Array();
    {{if $work ne 'movetoforum'}}
    var newItem = new Object();
    newItem['ot'] = 'posting';
    newItem['alias'] = 'Parent';
    newItem['prefix'] = 'muboardPosting_ParentSelectorDoNew';
    newItem['acInstance'] = null;
    newItem['windowInstance'] = null;
    relationHandler.push(newItem);
    {{/if}}    
    var newItem = new Object();
    newItem['ot'] = 'forum';
    newItem['alias'] = 'Forum';
    newItem['prefix'] = 'muboardForum_ForumSelectorDoNew';
    newItem['acInstance'] = null;
    newItem['windowInstance'] = null;
    relationHandler.push(newItem);

    document.observe('dom:loaded', function() {
        {{if $work ne 'movetoforum'}}
        muboardInitRelationItemsForm('posting', 'muboardPosting_Parent', false);
        {{/if}}
        muboardInitRelationItemsForm('forum', 'muboardForum_Forum', true);

        muboardAddCommonValidationRules('posting', '{{if $mode eq 'create'}}{{else}}{{$posting.id}}{{/if}}');

        // observe button events instead of form submit
        var valid = new Validation('{{$__formid}}', {onSubmit: false, immediate: true, focusOnError: false});
        {{if $mode ne 'create'}}
            var result = valid.validate();
        {{/if}}

        $('{{if $mode eq 'create'}}btnCreate{{else}}btnUpdate{{/if}}').observe('click', function(event) {
            var result = valid.validate();
            if (!result) {
                // validation error, abort form submit
                Event.stop(event);
            } else {
                // hide form buttons to prevent double submits by accident
                $$('div.z-formbuttons input').each(function(btn) {
                    btn.hide();
                });
            }
            return result;
        });

        Zikula.UI.Tooltips($$('.muboardFormTooltips'));
    });

/* ]]> */
</script>
