# ─────────────────────────────────────────────────────────────────────────────
# WeConnect — Application Image
# Base: php:8.2-apache (Debian Bookworm slim)
# ─────────────────────────────────────────────────────────────────────────────

FROM php:8.2-apache

# ── Labels ───────────────────────────────────────────────────────────────────
LABEL maintainer="tejas-kapade"
LABEL org.opencontainers.image.title="WeConnect"
LABEL org.opencontainers.image.description="Live anonymous chat web application"
LABEL org.opencontainers.image.source="https://github.com/tejas-kapade/WECONNECT"

# ── System dependencies & PHP extensions ─────────────────────────────────────
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        curl \
    && docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
        mbstring \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/*

# ── Apache configuration ──────────────────────────────────────────────────────
# Enable mod_rewrite for clean URLs
RUN a2enmod rewrite

RUN a2enmod headers

# Custom Apache virtual host — serves from /var/www/html
COPY docker/apache-weconnect.conf /etc/apache2/sites-available/000-default.conf

# ── PHP configuration ─────────────────────────────────────────────────────────
COPY docker/php.ini /usr/local/etc/php/conf.d/weconnect.ini

# ── Application code ──────────────────────────────────────────────────────────
# Copy application files into the web root
COPY --chown=www-data:www-data app/ /var/www/html/

# ── Permissions ───────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && find /var/www/html -type d -exec chmod 755 {} \;

# ── Health check ──────────────────────────────────────────────────────────────
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# ── Expose & run ─────────────────────────────────────────────────────────────
EXPOSE 80

CMD ["apache2-foreground"]
