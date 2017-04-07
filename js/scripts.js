/**
 * Created by TDr on 4/1/2017.
 */
// Side Navigation
$("#sidenav li").click(function(){
    var fn = $(this).attr('id');
    $.post("ajax.php", {fn: fn})
        .done(function (data) {
            $('#response').html(data);
     });
});

// Top Navigation
$(".nav li").click(function(){
    var fn = $(this).attr('id');
    $.post("ajax.php", {fn: fn})
        .done(function (data) {
            $('#response').html(data);
        });
});

// Validate new domain
$(document).on('keyup','#localdomain',function() {
    var host = $(this).val();
    if(host.length > 5){
        $.post("ajax.php", {fn: 'chkdomain', host: host})
            .done(function (data) {
                if(data != ''){
                    $('#localdomain').closest( ".form-group" ).addClass('has-error');
                    $('#hints').html(data);
                }else {
                    $('#localdomain').closest( ".form-group" ).addClass('has-success');
                }
        });
    }else {
        $('#localdomain').closest( ".form-group" ).removeClass('has-error');
        $('#localdomain').closest( ".form-group" ).removeClass('has-success');
    }
});

//Save Config File
$(document).on('click','#saveconfig',function() {
    var host = $('#hostsfile').val();
    var apache_vhost = $('#apache_vhosts').val();
    var nginx_vhost = $('#nginx_vhosts').val();
    var webroot = $('#webroot').val();
    $.post("ajax.php", {fn: 'saveconf', host: host, apache_vhost: apache_vhost, nginx_vhost: nginx_vhost, webroot: webroot})
        .done(function (data) {
            $('#info').html(data);
        });
});

// Add new domain
$(document).on('click','#adddomain',function() {
    var host = $("#localdomain").val();
    if ($("#createdb").is(':checked')) {
        var db = $("#dbname").val();
    }else {
        var db = '';
    }
    if(host.length > 5){
        $.post("ajax.php", {fn: 'savehost', host: host, db: db})
            .done(function (data) {
                if(data != ''){
                    $('#hints').html(data);
                }
            });
    }
});

//Add DB Container
$(document).on('click','#createdb',function() {
    if ($("#createdb").is(':checked')) {
        $('.dbcon').fadeIn();
    }else {
        $('.dbcon').fadeOut();
    }
});