FROM nginx:stable-alpine

COPY docker/ssl/localhost.crt /etc/nginx/certs/localhost.crt
COPY docker/ssl/localhost.csr /etc/nginx/certs/localhost.csr
COPY docker/ssl/localhost.ext /etc/nginx/certs/localhost.ext
COPY docker/ssl/localhost.key /etc/nginx/certs/localhost.key
COPY docker/ssl/RSAWEB-CA.key /etc/nginx/certs/RSAWEB-CA.key
COPY docker/ssl/RSAWEB-CA.pem /etc/nginx/certs/RSAWEB-CA.pem
COPY docker/ssl/RSAWEB-CA.srl /etc/nginx/certs/RSAWEB-CA.srl

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
