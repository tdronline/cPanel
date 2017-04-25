#Local cPanel

Local Virtual Host Manager for Windows Based Apache PHP environments.
You can simply create virtual host entry with one click.

You need to remove read only access for windows host file in order to work with this app. (If you are a real server serving out side never do this) You have no harm doing this in a development environment.

First create the configuration file for your dev environment using the cogwheel icon on top right corner.
Fill the paths and you are ready to go.

For NGINX based servers I included the conf files for each server seperately so If you dont have that please update your nginx.conf with

include D:/root/nginx/sites-enabled/*.conf;

With where you want to include the NGINX conf files.
That's it. If you find any errors creating files please check permissions because Apache or NGINX dont run with Admin privileges. 

Let me know what you think. 

tdr(@)tdronline.info
