<div class="itemStat floatleft">
    <button id="button-stats" title="<{$smarty.const._XOO_GHOST_RATE_VOTES}>"><i class="stats"></i>
        <span class="average"><{$page.xooghost_rates}></span> / <span class="voters"><{$page.xooghost_vote}></span>
        <span class="yourvote <{if !$page.xooghost_yourvote != 0}>hide<{/if}>"><span class="vote">(<{$page.xooghost_yourvote}>)</span></span>
    </button>
</div>

<div id="rating" class="rating">
    <{section name=foo loop=$xooghost_rld.rate_scale}>
        <button id="button-stars<{if $smarty.section.foo.last}>-last<{/if}>" class="option" value="<{$smarty.section.foo.iteration}>" title="<{$smarty.section.foo.iteration}>"><i class="stars"></i>
        </button>
    <{/section}>
    <input type="hidden" id="page_id" value="<{$page.xooghost_id}>">
</div>

<script language="javascript">
    <!--

    $(".option").click(function () {

        var option = $(this).val();
        var item = $("#page_id").val();
        var token = "<{$security}>";

        $.ajax({
            type   : "POST",
            url    : "page_rate.php",
            data   : "option=" + option + "&page_id=" + item + "&XOOPS_TOKEN_REQUEST=" + token,
            success: function (responce) {
                var json = jQuery.parseJSON(responce);
//            alert( json.error );
                if (json.error == "0") {
                    $(".average").tpl(json.average);
                    $(".voters").tpl(json.voters);
                    $(".vote").tpl('(' + json.vote + ')');
                    $(".yourvote").removeClass("hide");
                }
            }
        });
    });
    //-->
</script>
