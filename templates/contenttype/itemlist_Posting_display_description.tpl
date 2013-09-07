{* Purpose of this template: Display postings within an external context *}

{foreach item='item' from=$items}
{checkpermissionblock component='MUBoard:Category:' instance="`$item.forum.category.id`::" level="ACCESS_OVERVIEW"}
{if $item.createdUserId eq 0 || $item.createdUserId eq 1}
{gt text='Guest' assign='itemuser'}
{else}
{usergetvar name='uname' uid=$item.createdUserId assign='itemuser'}
{/if}
{if $item.parent_id eq NULL}
    <h6>{$itemuser} {gt text='opened issue:'} {$item.title} | {$item.createdDate|dateformat:datebrief}</h6>
{else}
    <h6>{$itemuser} {gt text='answered to issue:'} {$item.parent.title} | {$item.createdDate|dateformat:datebrief}</h6>
{/if}
{if $item.text}
    {$item.text|notifyfilters:'muboard.filter_hooks.postings.filter'|safehtml|truncate:200:"..."}
{/if}
    {if $item.parent_id eq NULL}
        <a href="{modurl modname='MUBoard' type='user' func='display' ot=$objectType id=$item.id}">{gt text='Read more'}</a>
    {else}
        <a href="{modurl modname='MUBoard' type='user' func='display' ot=$objectType id=$item.parent_id}/#{$item.id}">{gt text='Read more'}</a>
    {/if}
{/checkpermissionblock}
{foreachelse}
    {gt text='No entries found.'}
{/foreach}

