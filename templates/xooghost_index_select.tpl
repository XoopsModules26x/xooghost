<div class="txtcenter">
    <select size="1" onChange='window.location.href=this.value' name="xooghost_url" id="xooghost_url" title="">
        <option value="index.php"><{$smarty.const._XOO_GHOST_CHOOSE}></option>

        <{foreach from=$pages item=page name=foo}>
        <option value="<{$page.xooghost_link}>"><i class="xooghost-ico-item<{$page.xooghost_id}>"></i><{$page.xooghost_title}></option>
        <{/foreach}>
    </select>
</div>
