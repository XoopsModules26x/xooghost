<div class="like-dislike floatright">
    <button id="button-container-like" class="option" value="1" title="<{$smarty.const._XOO_GHOST_LIKE}>"><i class="thumbs-up"></i>

        <div class="like"><{$page.xooghost_like}></div>
    </button>
    <button id="button-container-unlike" class="option" value="0" title="<{$smarty.const._XOO_GHOST_DISLIKE}>"><i class="thumbs-down"></i>

        <div class="dislike"><{$page.xooghost_dislike}></div>
    </button>
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
            url    : "page_like_dislike.php",
            data   : "option=" + option + "&page_id=" + item + "&XOOPS_TOKEN_REQUEST=" + token,
            success: function (responce) {
                var json = jQuery.parseJSON(responce);
                //alert( json.error );
                if (json.error == "0") {
                    if (option == "1") {
                        $(".like").tpl(json.xooghost_like);
                    } else {
                        $(".dislike").tpl(json.xooghost_dislike);
                    }
                }
            }
        });
    });
    //-->
</script>
