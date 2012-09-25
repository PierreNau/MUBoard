{* purpose of this template: inclusion template for display of related Ranks in user area *}

<h4>
    <a href="{modurl modname='MUBoard' type='user' func='display' ot='rank' id=$item.id}">
        {$item.name}
    </a>
    <a id="rankItem{$item.id}Display" href="{modurl modname='MUBoard' type='user' func='display' ot='rank' id=$item.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" style="display: none">
        {icon type='view' size='extrasmall' __alt='Quick view'}
    </a>
</h4>
    <script type="text/javascript" charset="utf-8">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            muboardInitInlineWindow($('rankItem{{$item.id}}Display'), '{{$item.name|replace:"'":""}}');
        });
    /* ]]> */
    </script>
    <br />
{if $item.uploadImage ne '' && isset($item.uploadImageFullPathURL)}
    <img src="{$item.uploadImage|muboardImageThumb:$item.uploadImageFullPathURL:50:40}" width="50" height="40" alt="{$item.name|replace:"\"":""}" />
{/if}

