<{if $moduletitle != ''}>
    <fieldset>
        <legend><{$moduletitle}><a href="<{xoAppUrl modules/xooghost/backend.php}>" title="<{$smarty.const._XOO_GHOST_RSS_FEED}>"><img src="<{xoImgUrl modules/xooghost/assets/icons/32/rss.png}>"/></a>
        </legend>
    </fieldset>
<{/if}>

<{if $welcome}>
    <div class="xooghostMsg">
        <{$welcome}>
    </div>
<{/if}>

<{include file='module:xooghost/xooghost_css.tpl'}>

<{$xoopaginate->display()}>
<{if $template == 'blog'}>
    <{include file="module:xooghost/xooghost_index_blog.tpl"}>
<{elseif $template == 'list'}>
    <{include file="module:xooghost/xooghost_index_list.tpl"}>
<{elseif $template == 'table'}>
    <{include file="module:xooghost/xooghost_index_table.tpl"}>
<{elseif $template == 'select'}>
    <{include file="module:xooghost/xooghost_index_select.tpl"}>
<{/if}>
<{$xoopaginate->display()}>

<{include file='module:xooghost/xooghost_footer.tpl'}>
