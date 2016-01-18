<style type="text/css">
    [class^="xooghost-image-"], [class*=" xooghost-image-"] {
        background-size: <{$width}>px <{$height}>px;
    }

    <{if count($pages) != 0}>
    <{foreach from=$pages item=csspage name=foo}>
    .xooghost-ico-item <{$csspage.xooghost_id}> {
        background-image: url('<{$csspage.xooghost_image_link}>');
    }

    .xooghost-image-item <{$csspage.xooghost_id}> {
        background-image: url('<{$csspage.xooghost_image_link}>');
    }

    .item <{$csspage.xooghost_id}> {
    <{if $csspage.xooghost_image != "blank.gif"}> padding-left: <{$width}>px;
    <{/if}>
    }

    <{/foreach}>
    <{else}>
    .xooghost-ico-item <{$page.xooghost_id}> {
        background-image: url('<{$page.xooghost_image_link}>');
    }

    .xooghost-image-item <{$page.xooghost_id}> {
        background-image: url('<{$page.xooghost_image_link}>');
    }

    .item <{$page.xooghost_id}> {
    <{if $page.xooghost_image != "blank.gif"}> padding-left: <{$width}>px;
    <{/if}>
    }

    <{/if}>
    .itemImage {
    / / min-height : <{$height}> px;
    }

    .itemImage img {
        max-width: <{$width}>px;
    }
</style>
