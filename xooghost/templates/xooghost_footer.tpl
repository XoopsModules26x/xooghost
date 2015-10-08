<script type="text/javascript" src="<{xoImgUrl media/jquery/ui/jquery.ui.js}>"></script>
<link rel="stylesheet" href="<{xoImgUrl media/jquery/ui/base/ui.all.css}>" type="text/css" media="screen"/>

<{if $xooghost_com || $xooghost_not}>
    <script type="text/javascript">
        $(function () {
            $('#controls').tabs({
                selected: 0,
                'select': function (event, ui) {
                    var currentTab = $(ui.tab).attr('href');
                }
            });
        });
    </script>
    <div id="controls" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <{if $xooghost_com}>
                <li class="ui-state-default ui-corner-top"><a href="#comments"><{$smarty.const._XOO_GHOST_COMMENTS}></a></li>
            <{/if}>
            <{if $xooghost_not}>
                <li class="ui-state-default ui-corner-top"><a href="#notifications"><{$smarty.const._XOO_GHOST_NOTIFICATIONS}></a></li>
            <{/if}>
        </ul>

        <{if $xooghost_com}>
            <div id="comments">
                <{include file='module:comments|comments.tpl'}>
            </div>
        <{/if}>

        <{if $xooghost_not}>
            <div id="notifications">
                <{include file='module:notifications|select.tpl'}>
            </div>
        <{/if}>
    </div>
<{/if}>
