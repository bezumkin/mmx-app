#!/bin/bash

source ".env"

NAME=${COMPOSE_PROJECT_NAME}-node
CONTAINER=$(docker ps --filter name=$NAME -q)

docker exec -ti $CONTAINER bash -c "npm i && npm run build"
