#!/bin/bash

source ".env"

NAME=${COMPOSE_PROJECT_NAME}-php-fpm
CONTAINER=$(docker ps --filter name=$NAME -q)

docker exec -ti $CONTAINER bash -c "composer create-project modx/revolution=${MODX_VERSION} ./"
docker exec -ti $CONTAINER bash -c "composer config version ${MODX_VERSION}"
docker exec -ti $CONTAINER bash -c "php setup/cli-install.php --database_server=mariadb \
  --database=$MARIADB_DATABASE --database_user=$MARIADB_USERNAME --database_password=$MARIADB_PASSWORD \
  --table_prefix=modx_ --language=en --cmsadmin=$MODX_USERNAME --cmspassword=$MODX_PASSWORD \
  --cmsadminemail=$MODX_EMAIL --context_mgr_path=/modx/manager/ --context_mgr_url=/manager/ \
  --context_connectors_path=/modx/connectors/ --context_connectors_url=/connectors/ --context_web_path=/modx/ \
  && rm -rf _build .github"

docker exec -ti $CONTAINER bash -c "composer config repositories.mmx-app path /mmx-app"
docker exec -ti $CONTAINER bash -c "composer require mmx/app"
docker exec -ti $CONTAINER bash -c "composer exec mmx-app install"
docker exec -ti $CONTAINER bash -c "rm -rf core/cache"
