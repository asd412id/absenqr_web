FROM bitnami/php-fpm:latest-prod

RUN apt-get update && apt-get install -y autoconf wget build-essential

# install php-redis
RUN pecl install redis
	
RUN pecl install mongodb

ENV BITNAMI_APP_NAME="php-fpm" \
    BITNAMI_IMAGE_VERSION="7.1.32-debian-9-r16" \
    PATH="/opt/bitnami/php/bin:/opt/bitnami/php/sbin:$PATH"

EXPOSE 9000

WORKDIR /app
CMD [ "php-fpm", "-F", "--pid", "/opt/bitnami/php/tmp/php-fpm.pid", "-y", "/opt/bitnami/php/etc/php-fpm.conf" ]