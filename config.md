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
