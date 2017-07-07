#!/bin/bash

docker stop app
docker stop chen
docker rm app
docker rm chen
docker rmi app


docker build -t app .

docker run -p 7474:7474 -p 7687:7687 -d -v /c/Users/fancy16233/Desktop/Tokyo_Sub/Data:/import --name=chen neo4j

docker run  -i -t -d --name=app -p 8080:80 app