#!/usr/bin/env bash

docker build -t excusebot/app .
docker tag excusebot/app gcr.io/learning-229721/excusebot/app:1.1
docker push gcr.io/learning-229721/excusebot/app:1.1

kubectl apply -f .deploy/config.yaml
kubectl apply -f .deploy/app.yaml
kubectl apply -f .deploy/nginx.yaml
