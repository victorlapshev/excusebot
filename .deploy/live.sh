#!/usr/bin/env bash

docker build -t excusebot/app ./..
docker tag excusebot:app gcr.io/learning-229721/excusebot/app:1.0
docker push gcr.io/learning-229721/excusebot/app:1.0

kubectl apply -f config.yaml
kubectl apply -f app.yaml
kubectl apply -f nginx.yaml
