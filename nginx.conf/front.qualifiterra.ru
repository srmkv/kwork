map $sent_http_content_type $expires {
    "text/html"                 epoch;
    "text/html; charset=utf-8"  epoch;
    default                     off;
}

server {             # the port nginx is listening on
    server_name     front.qualifiterra.ru; # setup your domain here
    gzip            on;
    gzip_types      text/plain application/xml text/css application/javascript;
    gzip_min_length 1000;

    location / {
        expires $expires;

	proxy_buffering			    off;
        proxy_redirect                      off;
        proxy_set_header Host               $host;
        proxy_set_header X-Real-IP          $remote_addr;
        proxy_set_header X-Forwarded-For    $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto  $scheme;
        proxy_read_timeout          1m;
        proxy_connect_timeout       1m;
        proxy_pass                          http://127.0.0.1:8011; # set the address of the Node.js instance here
    }

    listen 443 ssl; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/front.qualifiterra.ru/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/front.qualifiterra.ru/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot



    #listen 443 ssl; # managed by Certbot
    #ssl_certificate /etc/letsencrypt/live/qualifiterra.ru/fullchain.pem; # managed by Certbot
    #ssl_certificate_key /etc/letsencrypt/live/qualifiterra.ru/privkey.pem; # managed by Certbot
    #include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    #ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot

}

