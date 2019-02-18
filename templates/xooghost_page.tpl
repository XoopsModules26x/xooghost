<script type="text/javascript" src="<{xoImgUrl 'media/jquery/ui/jquery.ui.js'}>"></script>
<link rel="stylesheet" href="<{xoImgUrl 'media/jquery/ui/base/ui.all.css'}>" type="text/css" media="screen">

<{include file='module:xooghost/xooghost_css.tpl'}>

<{if $moduletitle != '' && !$pages}>
    <fieldset>
        <legend><{$moduletitle}></legend>
    </fieldset>
<{/if}>

<{if !$not_found}>
    <div class="item">
        <div class="itemHead">
            <{if $qrcode}>
                <div class="itemQRcode">
                    <a href="<{$page.xooghost_link}>" title="<{$page.xooghost_title}>"><img src="<{xoAppUrl 'modules/xooghost/qrcode.php'}>?url=<{$page.xooghost_link}>" alt="<{$page.xooghost_link}>"></a>
                </div>
                <div>
                </div>
            <{/if}>
            <{if $page.xooghost_image != "blank.gif"}>
                <div class="itemImage">
                    <img src="<{$page.xooghost_image_link}>">
                </div>
            <{/if}>

            <div>
                <div class="itemTitle">
                    <a href="<{$page.xooghost_link}>" title="<{$page.xooghost_title}>"><{$page.xooghost_title}></a>
                </div>

                <div class="itemInfo">
                    <div class="itemPoster">
                        <{$smarty.const._XOO_GHOST_AUTHOR}>:
                        <a href="<{xoAppUrl 'userinfo.php'}>?uid=<{$page.xooghost_uid}>" title="<{$page.xooghost_uid_name}>"><{$page.xooghost_uid_name}></a>
                    </div>
                    <div class="itemDate">
                        <{$smarty.const._XOO_GHOST_PUBLISHED}>: <span><{$page.xooghost_published}></span>
                    </div>
                    <{if $page.tags}>
                        <{include file="module:xootags/xootags_bar.tpl" tags=$page.tags}>
                    <{/if}>
                </div>
            </div>
        </div>

        <{if $page.xooghost_content}>
            <div class="itemBody">
                <div class="itemText">
                    <{$page.xooghost_content}>
                </div>
            </div>
        <{else}>
            <div class="clear"></div>
        <{/if}>

        <{if $page.readmore}>
            <div class="itemFoot floatright">
                <a href="<{$page.xooghost_link}>" title="<{$smarty.const._XOO_GHOST_READMORE}>"><{$smarty.const._XOO_GHOST_READMORE}></a>
            </div>
            <div class="clear"></div>
        <{/if}>

        <{if !$pages && !$print}>
            <div class="itemFoot">
                <div class="itemStat floatleft">
                    <button id="button-reads" title="<{$smarty.const._XOO_GHOST_READS}>"><i class="reads"></i><{$page.xooghost_hits}></button>
                    <{if $xooghost_com}>
                        <button id="button-comments" title="<{$smarty.const._XOO_GHOST_COMMENTS}>"><i class="comments"></i><{$page.xooghost_comments}></button>
                    <{/if}>
                    <button id="button-print" title="<{$smarty.const._XOO_GHOST_PRINT}>"><i class="print"></i></button>
                    <!--
                    <button id="button-pdf" title="<{$smarty.const._XOO_GHOST_PDF}>"><i class="pdf"></i></button>
-->
                </div>

                <{if $xooghost_rld.rld_mode != ''}>
                    <div class="floatright">
                        <{if $xooghost_rld.rld_mode == 'rate'}>
                            <{include file='module:xooghost/xooghost_page_rate.tpl'}>
                        <{else}>
                            <{include file='module:xooghost/xooghost_page_like_dislike.tpl'}>
                        <{/if}>
                    </div>
                <{/if}>

                <div class="clear"></div>
            </div>
            <{if $xoosocialnetwork}>
                <{include file='module:xoosocialnetwork/xoosocialnetwork.tpl'}>
            <{/if}>
        <{/if}>
    </div>
    <{if !$pages && !$print}>
        <{include file='module:xooghost/xooghost_footer.tpl'}>
    <{/if}>

<{else}>
    <div class="errorMsg">
        <{$smarty.const._XOO_GHOST_NOTFOUND}>
    </div>
<{/if}>
<script language="javascript">
    <!--
    $("#button-print").click(function () {
        window.open('page_print.php?page_id=<{$page.xooghost_id}>', 'New Project', 'left=20, top=20, width=1100, height=700, toolbar=0, resizable=1, scrollbars=1');
    });
    $("#button-pdf").click(function () {
        window.open('page_print.php?page_id=<{$page.xooghost_id}>&output=pdf', 'New Project', 'left=20, top=20, width=1100, height=700, toolbar=0, resizable=1, scrollbars=1');
    });
    //-->
</script>
