#!/bin/bash
docker rmi $(docker images -f "dangling=true" -q)

docker build . -t general
docker ps -a | grep generar_excel | awk '{ print $1 }' | xargs docker stop | xargs docker rm
# Port configuration work as follows:
# {host}:{container}
docker run \
    -v $(pwd)/src:/var/www/html \
    -v $(pwd)/uploads:/var/www/uploads \
    --env ENV=test \
    -d -p 1002:80 --name generar_excel general