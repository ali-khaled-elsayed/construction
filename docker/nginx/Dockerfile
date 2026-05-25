# =============================================================================
# Nginx reverse proxy — extends official Alpine image with project configs
# =============================================================================

FROM nginx:1.27-alpine

RUN apk add --no-cache wget

# Remove default site; use project configs
RUN rm -f /etc/nginx/conf.d/default.conf

COPY docker/nginx/conf.d/ /etc/nginx/conf.d/
COPY docker/nginx/snippets/ /etc/nginx/snippets/

# Laravel public assets (Filament JS/CSS, index.php, etc.)
COPY construction-marketplace-api/public /var/www/html/public

EXPOSE 80 443 8000

CMD ["nginx", "-g", "daemon off;"]
