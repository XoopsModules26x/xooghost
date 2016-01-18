<table class="outer">
    <{foreach from=$pages item=page name=foo}>
    <tr class="<{cycle values="even,odd"}>">
        <td><a href="<{$page.xooghost_link}>" title="<{$page.xooghost_title}>"><i class="xooghost-ico-item<{$page.xooghost_id}>"></i><{$page.xooghost_title}></a></td>
    </tr>
    <{/foreach}>
</table>
