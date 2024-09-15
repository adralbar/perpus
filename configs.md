server
{
listen 80;
server_name 10.14.130.88;
index index.php index.html index.htm default.php default.htm default.html;
root /www/wwwroot/public_html;

    #SSL-START SSL related configuration, do NOT delete or modify the next line of commented-out 404 rules
    #error_page 404/404.html;
    #SSL-END

    #ERROR-PAGE-START  Error page configuration, allowed to be commented, deleted or modified
    #error_page 404 /404.html;
    #error_page 502 /502.html;
    #ERROR-PAGE-END

    #PHP-INFO-START  PHP reference configuration, allowed to be commented, deleted or modified
    include enable-php-81.conf;
    #PHP-INFO-END

    #REWRITE-START URL rewrite rule reference, any modification will invalidate the rewrite rules set by the panel
    include /www/server/panel/vhost/rewrite/10.14.130.88.conf;
    #REWRITE-END


        #ABSENSI
    location /absensi-api {
    alias /www/wwwroot/public_html/absensi-api/public;
    try_files $uri $uri/ @absensi-api;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @absensi-api {
      rewrite /absensi-api/(.*)$ /absensi-api/index.php?/$1 last;
    }
    #ABSENSI END


      #QCC
    location /qcc {
    alias /www/wwwroot/public_html/qcc/public;
    try_files $uri $uri/ @qcc;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @qcc {
      rewrite /qcc/(.*)$ /qcc/index.php?/$1 last;
    }
    #QCC END


     #HELPDESK
    location /help-desk {
    alias /www/wwwroot/public_html/help-desk/public;
    try_files $uri $uri/ @help-desk;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @help-desk {
      rewrite /help-desk/(.*)$ /help-desk/index.php?/$1 last;
    }
    #HELPDESK END

#PROJECT MANAGEMENT

location /project-management {
alias /www/wwwroot/public_html/project-management/public;
try_files $uri $uri/ @project-management;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      add_header 'Access-Control-Allow-Origin' '*' always;
      include fastcgi_params;
      }
    }

    location @project-management {
      rewrite /project-management/(.*)$ /project-management/index.php?/$1 last;
    }

    #PROJECT MANAGEMENT END


    #AGENDA-API
    location /agenda-api {
    alias /www/wwwroot/public_html/agenda-api/public;
    try_files $uri $uri/ @agenda-api;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @agenda-api {
      rewrite /agenda-api/(.*)$ /agenda-api/index.php?/$1 last;
    }
    #AGENDA-API END


     #BOOKINGROOM
    location /api-bokingroom {
    alias /www/wwwroot/public_html/api-bokingroom/public;
    try_files $uri $uri/ @api-bokingroom;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @api-bokingroom {
      rewrite /bookingroom/(.*)$ /api-bokingroom/index.php?/$1 last;
    }
    #BOOKINGROOM END


    #HENKATEN
    # location /henkaten-app {
    # alias /www/wwwroot/public_html/henkaten-app/public;
    # try_files $uri $uri/ @henkaten-app;

    # location ~ \.php$ {
    #   fastcgi_pass unix:/tmp/php-cgi-81.sock;
    #   fastcgi_param SCRIPT_FILENAME $request_filename;
    #   include fastcgi_params;
    #   }
    # }

    # location @henkaten-app {
    #   rewrite /henkaten-app/(.*)$ /henkaten-app/index.php?/$1 last;
    # }
    #HENKATEN END

    #HENKATEN
    location /henkaten-api {
    alias /www/wwwroot/public_html/henkaten-api/public;
    try_files $uri $uri/ @henkaten-api;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @henkaten-api {
      rewrite /henkaten-api/(.*)$ /henkaten-api/index.php?/$1 last;
    }
    #HENKATEN END

    #INVENTORY_DASHBOARD
    location /inventory-dashboard {
    alias /www/wwwroot/public_html/inventory-dashboard/public;
    try_files $uri $uri/ @inventory-dashboard;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @inventory-dashboard {
      rewrite /inventory-dashboard/(.*)$ /inventory-dashboard/index.php?/$1 last;
    }
    #INVENTORY_DASHBOARD END

    #MARKETING_DASHBOARD
    location /marketing-dashboard {
    alias /www/wwwroot/public_html/marketing-dashboard/public;
    try_files $uri $uri/ @marketing-dashboard;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @marketing-dashboard {
      rewrite /marketing-dashboard/(.*)$ /marketing-dashboard/index.php?/$1 last;
    }
    #MARKETING_DASHBOARD END

    #LANDINGPAGE
    location /app-api {
    alias /www/wwwroot/public_html/app-api/public;
    try_files $uri $uri/ @app-api;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @app-api {
      rewrite /app-api/(.*)$ /app-api/index.php?/$1 last;
    }
    #LANDINGPAGE END

     #MOLD_API_&_DASHBOARD
    location /mold {
    alias /www/wwwroot/public_html/mold/public;
    try_files $uri $uri/ @mold;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @mold {
      rewrite /mold/(.*)$ /mold/index.php?/$1 last;
    }

#MOLD*API*&\_DASHBOARD_END

       #SMARTOFFICEAPI
    location /smartoffice-api {
    alias /www/wwwroot/public_html/smartoffice-api/public;
    try_files $uri $uri/ @smartoffice-api;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @smartoffice-api {
      rewrite /smartoffice-api/(.*)$ /smartoffice-api/index.php?/$1 last;
    }
    #SmartOfficeAPI END



         #bookingroom
    location /bookingroom {
    alias /www/wwwroot/public_html/bookingroom/public;
    try_files $uri $uri/ @bookingroom;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @bookingroom {
      rewrite ^/bookingroom/(.*)$ /bookingroom/index.php?/$1 last;
    }
    #bokingroom END



       #API_mold-main
    location /API_mold-main {
    alias /www/wwwroot/public_html/API_mold-main/public;
    try_files $uri $uri/ @API_mold-main;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @API_mold-main {
      rewrite /API_mold-main/(.*)$ /API_mold-main/index.php?/$1 last;
    }
    #API_mold-main END

           #API_power_monitoring
    location /powmon-api {
    alias /www/wwwroot/public_html/powmon-api/public;
    try_files $uri $uri/ @powmon-api;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @powmon-api {
      rewrite /powmon-api/(.*)$ /powmon-api/index.php?/$1 last;
    }
    #API_Power-Monitoring END

               #API_Empty Box
    location /empty-box {
    alias /www/wwwroot/public_html/empty-box/public;
    try_files $uri $uri/ @empty-box;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @empty-box {
      rewrite /empty-box/(.*)$ /empty-box/index.php?/$1 last;
    }
    #API_Empty Box


             #Boxing Dashboard
    location /boxing-dashboard-api {
    alias /www/wwwroot/public_html/boxing-dashboard-api/public;
    try_files $uri $uri/ @boxing-dashboard-api;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @boxing-dashboard-api {
      rewrite ^/boxing-dashboard-api/(.*)$ /boxing-dashboard-api/index.php?/$1 last;
    }
    #Boxing dashboard END

     #Test Upload Dashboard
    location /Roles-permissions-manager {
    alias /www/wwwroot/public_html/Roles-permissions-manager/public;
    try_files $uri $uri/ @Roles-permissions-manager;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @Roles-permissions-manager {
      rewrite ^/Roles-permissions-manager/(.*)$ /Roles-permissions-manager/index.php?/$1 last;
    }
    #Test Upload Dashboard

     #schedule-maintenance
    location /schedule-maintenance {
    alias /www/wwwroot/public_html/schedule-maintenance/public;
    try_files $uri $uri/ @schedule-maintenance;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @schedule-maintenance {
      rewrite ^/schedule-maintenance/(.*)$ /schedule-maintenance/index.php?/$1 last;
    }
    #schedule-maintenance


     #Test hr_management
    location /hr_management {
    alias /www/wwwroot/public_html/hr_management/public;
    try_files $uri $uri/ @hr_management;

    location ~ \.php$ {
      fastcgi_pass unix:/tmp/php-cgi-81.sock;
      fastcgi_param SCRIPT_FILENAME $request_filename;
      include fastcgi_params;
      }
    }

    location @hr_management {
      rewrite ^/hr_management/(.*)$ /hr_management/index.php?/$1 last;
    }
    #Test hr_management

    # Forbidden files or directories
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.env|\.svn|\.project|LICENSE|README.md)
    {
        return 404;
    }

    # Directory verification related settings for one-click application for SSL certificate
    location ~ \.well-known{
        allow all;
    }

    #Prohibit putting sensitive files in certificate verification directory
    if ( $uri ~ "^/\.well-known/.*\.(php|jsp|py|js|css|lua|ts|go|zip|tar\.gz|rar|7z|sql|bak)$" ) {
        return 403;
    }

    # location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    # {
    #     expires      30d;
    #     error_log /dev/null;
    #     access_log off;
    # }

    # location ~ .*\.(js|css)?$
    # {
    #     expires      12h;
    #     error_log /dev/null;
    #     access_log off;
    # }
    access_log  /www/wwwlogs/10.14.130.88.log;
    error_log  /www/wwwlogs/10.14.130.88.error.log;

}
