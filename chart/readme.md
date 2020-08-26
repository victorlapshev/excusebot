# Push new image
```shell script
docker build -t bot:latest . --target prod
docker tag bot:latest localhost:5000/bot:latest
docker run --rm -it --network=host alpine ash -c "apk add socat && socat TCP-LISTEN:5000,reuseaddr,fork TCP:$(minikube ip):5000"
docker push localhost:5000/bot:latest  
```