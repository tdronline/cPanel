<?php
require_once ('functions.php');

$fn = trim($_REQUEST['fn']);

if($fn == 'addhost') {
   echo "<div id='hints'></div>
<div class='form-group'>
        <label class='control-label'>Enter Local Domain Name</label>
        <input type='text' required class='form-control' id='localdomain' placeholder='myhost.local'>
    </div>
    <div class='well well-sm'>
    <div class='checkbox'>
        <label>
            <input type='checkbox' id='createdb'> Create Database
        </label>
    </div>
    <div class='dbcon'>
    <div class='form-group'>
    <label class='control-label'>DB Name</label>
    <input type='text' class='form-control' id='dbname' placeholder='my_database' />
    </div>
    </div>
</div>
   <button type='button' id='adddomain' class='btn btn-default'>Add Host</button> ";
}

if($fn == 'viewdomains') {
    $hosts = viewHosts();
    echo nl2br($hosts);
}

if($fn == 'config') {
    $conf = config();
    include('config-form.php');
    //print_r($conf);
}

if($fn == 'chkdomain'){
    $host = $_REQUEST['host'];
    echo checkHost($host);
}

if($fn == 'saveconf') {
    $host = trim($_REQUEST['host']);
    $apache_vhost = trim($_REQUEST['apache_vhost']);
    $nginx_vhost = trim($_REQUEST['nginx_vhost']);
    $webroot = trim($_REQUEST['webroot']);

    if(!empty($host) && !empty($apache_vhost) && !empty($nginx_vhost) && !empty($webroot)) {
        $configEntry = "<?php
DEFINE('HOST_FILE','$host');
DEFINE('APACHE_VHOST','$apache_vhost');
DEFINE('NGINX_VHOST','$nginx_vhost');
DEFINE('DOC_ROOT','$webroot');
    ";
        echo $configEntry;
        if (is_file('config.php')) {
            if (file_put_contents('config.php', $configEntry)) {
                echo "<div class='alert alert-success' role='alert'>Successfully Updated Config File.</div>";
            }
        } else {
            if (file_put_contents('config.php', $configEntry)) {
                echo "<div class='alert alert-success' role='alert'>Successfully Crated Config File.</div>";
            }
        }
    }
}

if($fn == 'savehost') {
    $host = trim($_REQUEST['host']);
    $db = trim($_REQUEST['db']);
    createEntry($host);
    createDB($db);
}