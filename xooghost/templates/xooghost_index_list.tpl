<ul class="ul-xooghost">
    <{foreach from=$pages item=page name=foo}>
    <li><a href="<{$page.xooghost_link}>" title="<{$page.xooghost_title}>"><i class="xooghost-ico-item<{$page.xooghost_id}>"></i><{$page.xooghost_title}></a></li>
    <{/foreach}>
</ul>
