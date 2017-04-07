<?php
require_once ('config.php');

function createVHost($host = '')
{
	$doc_root = DOC_ROOT;
    $vhostScriptApache = "

#VirtualHost $host      -------------------------------
<VirtualHost *:80>
    ServerAdmin admin@$host
    DocumentRoot \"$doc_root\\$host\"
    ServerName www.$host
    ServerAlias $host
    ErrorLog \"logs/$host-error.log\"
    CustomLog \"logs/$host-access.log\" common
    <Directory  \"$doc_root\\$host\">
        Options +Indexes +FollowSymLinks +MultiViews
        AllowOverride All
        Require local
        Require all granted
    </Directory>
</VirtualHost>
#-------------------------------------------------------";

$vhostScriptNginx = "#VirtualHost $host      -------------------------------
server {
    listen       80;
    server_name  $host  www.$host;

    location / {
        root   $doc_root\\$host;
        index  index.php index.html index.htm;".
        'try_files $uri $uri/ /index.php?$args;
    }
    
    location ~ \\.php$ {'.
        "root   D:/root/www/$host;
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;".
        'fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;'."
        include        fastcgi_params;
    }
}
#-------------------------------------------------------";
    if(substr($_SERVER["SERVER_SOFTWARE"],0,5) == 'Apache'){
        $vhostScript =  $vhostScriptApache;
    }elseif(substr($_SERVER["SERVER_SOFTWARE"],0,5) == 'nginx') {
        $vhostScript =  $vhostScriptNginx;
    }
    return $vhostScript;
}

function createHost($host)
{
    $hostEntry = "
127.0.0.1     $host
127.0.0.1     www.$host";
    return $hostEntry;
}

function createDirectory ($host) {
    if(!is_dir(DOC_ROOT.'\/'.$host)){
      mkdir(DOC_ROOT.'\/'.$host, 0777, true);
      return file_put_contents(DOC_ROOT.'\/'.$host."/index.php","<?php phpinfo(); ?>");

    }else{
        return FALSE;
    }
}

function createEntry($host)
{
    if (!empty($host)) {
        $vHostEntry = createVHost($host);
        $hostEntry = createHost($host);
        $hostCheck = checkHost($host);
        $vhostCheck = checkVHost($host);
        $createDIR = createDirectory ($host);

        // Add Windows Host File Entry
        if ($hostCheck == '') {
            if(file_put_contents(HOST_FILE, $hostEntry, FILE_APPEND | LOCK_EX)){
                echo "<div class='alert alert-success' role='alert'>Successfully Updated Host File.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Host file entry [$hostCheck] exists!</div>";
        }

        if(substr($_SERVER["SERVER_SOFTWARE"],0,5) == 'Apache'){

            //Apache Host File Entry
            if ($vhostCheck == '') {
                if(file_put_contents(APACHE_VHOST, $vHostEntry, FILE_APPEND | LOCK_EX)){
                    echo "<div class='alert alert-success' role='alert'>Successfully Updated Apache Virtual Host File.</div>";
                }
            } else {
                echo "<div class='alert alert-danger' role='alert'>Virtual Host file entry exists!</div> <pre>$vhostCheck</pre>";
            }

        }elseif(substr($_SERVER["SERVER_SOFTWARE"],0,5) == 'nginx') {

            //NGINX host file creation
            if ($vhostCheck == '') {
                if(is_dir(NGINX_VHOST)) {
                    if (file_put_contents(NGINX_VHOST."/$host.conf", $vHostEntry)) {
                        echo "<div class='alert alert-success' role='alert'>Successfully Created NGINX Host File.</div>";
                    }
                }else {
                    echo "<div class='alert alert-danger' role='alert'>Virtual Host file entry exists!</div> <pre>$vhostCheck</pre>";
                }
            }
        }

        // Create directory in web root
        if ($createDIR == TRUE) {
            echo "<div class='alert alert-success' role='alert'>Successfully Created Directory.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Directory exists!</div>";
        }
    }
}

function checkVHost($host)
{
    $match = '';
    if(substr($_SERVER["SERVER_SOFTWARE"],0,5) == 'Apache'){
        if ($file = fopen(APACHE_VHOST, "r")) {

            while (!feof($file)) {
                $line = ltrim(fgets($file));
                if (preg_match("/\b$host?\b/", $line)) {
                    $match .= $line;
                }
            }
            fclose($file);
        }
    }elseif(substr($_SERVER["SERVER_SOFTWARE"],0,5) == 'nginx') {
        if(is_file(NGINX_VHOST."/$host.conf")) {$match = $host;}
    }

    return $match;
}

function checkHost($host)
{
    if ($file = fopen(HOST_FILE, "r")) {
        $match = '';
        while (!feof($file)) {
            $line = ltrim(fgets($file));
            if (substr($line, 0, 9) == '127.0.0.1') {
                if (preg_match("/\b$host?\b/", $line)) {
                    $match = $line;
                    break;
                }
            }
        }
        fclose($file);
    }
    return $match;
}

function viewHosts()
{
    if ($file = fopen(HOST_FILE, "r")) {
        $match = '';
        while (!feof($file)) {
            $line = ltrim(fgets($file));
            if (substr($line, 0, 9) == '127.0.0.1') {
              $match .= $line;
            }
        }
        fclose($file);
    }
    return $match;
}

function config(){
    if(is_file('config.php')){
        $conf = array();
        if ($file = fopen('config.php', "r")) {
            while (!feof($file)) {
                $line = ltrim(fgets($file));
                if (preg_match("/\bDEFINE?\b/", $line)) {
                    $line = trim($line);
                    $line = substr($line,8);
                    $line = substr($line,0,-3);
                    $line = str_replace("'",'',$line);
                    $line = explode(",",$line);
                    $conf[$line[0]] = $line[1];
                }
            }
            fclose($file);
        }
    }
    return $conf;
}

function createDB($db) {
    //TODO Add MySQL Details.
}