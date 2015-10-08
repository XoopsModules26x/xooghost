<{assign var=pages value=$block.pages}>

<{include file='module:xooghost/xooghost_css.tpl'}>

<{if $block.template == 'list'}>
    <{include file="module:xooghost/xooghost_index_list.tpl"}>
<{elseif $block.template == 'table'}>
    <{include file="module:xooghost/xooghost_index_table.tpl"}>
<{elseif $block.template == 'select'}>
    <{include file="module:xooghost/xooghost_index_select.tpl"}>
<{/if}>
