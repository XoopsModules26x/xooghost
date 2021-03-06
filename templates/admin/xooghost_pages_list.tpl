<{if $pages}>
    <table class="outer">
        <thead>
        <tr>
            <th class="txtcenter width10"><{$smarty.const._XOO_GHOST_PUBLISHED}></th>
            <th class="txtcenter width60"><{$smarty.const._XOO_GHOST_TITLE}></th>
            <th class="txtcenter"><{$smarty.const._XOO_GHOST_HITS}></th>
            <th class="txtcenter"><{$smarty.const._XOO_GHOST_DISPLAY}></th>
            <th class="txtcenter"><{$smarty.const._AM_XOO_GHOST_ACTION}></th>
        </tr>
        </thead>

        <{foreach from=$pages item=page}>
        <tr class="<{cycle values="even,odd"}>">
            <td class="txtcenter"><{$page.xooghost_published}></td>

            <td>
                <a href="<{$page.xooghost_link}>" title="<{$page.xooghost_title}>"><{$page.xooghost_title}></a>
            </td>

            <td class="txtcenter"><{$page.xooghost_hits}></td>

            <td class="txtcenter">
                <{if ( $page.xooghost_online )}>
                    <a href="pages.php?op=hide&amp;xooghost_id=<{$page.xooghost_id}>" title="<{$smarty.const._XOO_GHOST_SHOW_HIDE}>"><img src="<{xoImgUrl 'media/xoops/images/icons/16/on.png'}>" alt="<{$smarty.const._AM_XOO_GHOST_HIDE}>"></a>
                <{else}>
                    <a href="pages.php?op=view&amp;xooghost_id=<{$page.xooghost_id}>" title="<{$smarty.const._XOO_GHOST_SHOW_HIDE}>"><img src="<{xoImgUrl 'media/xoops/images/icons/16/off.png'}>" alt="<{$smarty.const._AM_XOO_GHOST_SHOW}>"></a>
                <{/if}>
            </td>

            <td class="txtcenter">
                <a href="pages.php?op=edit&amp;xooghost_id=<{$page.xooghost_id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoImgUrl 'media/xoops/images/icons/16/edit.png'}>" alt="{$smarty.const._EDIT}>"></a>
                <a href="pages.php?op=del&amp;xooghost_id=<{$page.xooghost_id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoImgUrl 'media/xoops/images/icons/16/delete.png'}>" alt="<{$smarty.const._DELETE}>"></a>
            </td>
        </tr>
        <{/foreach}>
    </table>
<{/if}>
