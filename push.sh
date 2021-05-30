#!/bin/bash

echo Build images..
docker build -t 192479299400.dkr.ecr.ap-southeast-1.amazonaws.com/sit-careers-api:api-dev -f ./docker/api/Dockerfile.dev .
docker build -t 192479299400.dkr.ecr.ap-southeast-1.amazonaws.com/sit-careers-api:nginx-dev -f ./docker/nginx/Dockerfile .

echo Push images..
docker push 192479299400.dkr.ecr.ap-southeast-1.amazonaws.com/sit-careers-api:api-dev
docker push 192479299400.dkr.ecr.ap-southeast-1.amazonaws.com/sit-careers-api:nginx-dev
