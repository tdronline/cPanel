<?php
require_once ('config.php');

function createVHost($host = '')
{
	$doc_root = DOC_ROOT;
    $vhostScript = "

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
    if(!is_dir(DOC_ROOT.$host)){
      return mkdir(DOC_ROOT.'\/'.$host, 0777, true);
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
        if ($hostCheck == '') {
            if(file_put_contents(HOST_FILE, $hostEntry, FILE_APPEND | LOCK_EX)){
                echo "<div class='alert alert-success' role='alert'>Successfully Updated Host File.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Host file entry [$hostCheck] exists!</div>";
        }
        if ($vhostCheck == '') {
            if(file_put_contents(VHOST, $vHostEntry, FILE_APPEND | LOCK_EX)){
                echo "<div class='alert alert-success' role='alert'>Successfully Updated Virtual Host File.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Virtual Host file entry exists!</div> <pre>$vhostCheck</pre>";
        }
        if ($createDIR == TRUE) {
            echo "<div class='alert alert-success' role='alert'>Successfully Created Directory.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Directory exists!</div>";
        }
    }
}

function checkVHost($host)
{
    if ($file = fopen(VHOST, "r")) {
        $match = '';
        while (!feof($file)) {
            $line = ltrim(fgets($file));
            if (preg_match("/\b$host?\b/", $line)) {
                $match .= $line;
            }
        }
        fclose($file);
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