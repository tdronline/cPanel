<form>
    <div class="form-group">
        <label>HOSTS file Path</label>
        <input type="text" required class="form-control" value="<?php echo @$conf['HOST_FILE']; ?>" id="hostsfile" placeholder="C:\Windows\System32\drivers\etc\hosts">
    </div>
    <div class="form-group">
        <label>Apache VHosts File Path</label>
        <input type="text" required class="form-control" value="<?php echo @$conf['APACHE_VHOST']; ?>" id="apache_vhosts" placeholder="D:\root\apache\conf\extra\httpd-vhosts.conf">
    </div>
    <div class="form-group">
        <label>NGINX sites folder</label>
        <input type="text" required class="form-control" value="<?php echo @$conf['NGINX_VHOST']; ?>" id="nginx_vhosts" placeholder="D:\root\nginx\conf\sites-enabled">
    </div>
    <div class="form-group">
        <label>Apache Web Root</label>
        <input type="text" required class="form-control" value="<?php echo @$conf['DOC_ROOT']; ?>" id="webroot" placeholder="D:\root\www\">
    </div>
    <button type="button" id="saveconfig" class="btn btn-default">Save</button>
</form>
<div id="info"></div>