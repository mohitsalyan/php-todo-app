# php-todo-app
## Output
![image](https://github.com/user-attachments/assets/6c5b5464-d7b8-446e-afcb-f25804bcff69)



# Mysql Pod
- db-cm
```
kubectl create cm db-cm --from-literal=MYSQL_DATABASE=mydb --dry-run=client -o yaml > db-cm.yaml
```
- db-secret

```
kubectl create secret generic db-secret --from-literal=MYSQL_ROOT_PASSWORD=rootpassword --dry-run=client -o yaml > db-secret.yaml
```
- mysql-pod
```
kubectl run mysql-pod --image=mysql --dry-run=client -o yaml > mysql-pod.yaml
```

- expose mysql pod service
```
kubectl expose pod mysql-pod --port=3306 --target-port=3306 --name=mysql-svc --dry-run=client -o yaml > mysql-svc.yaml
```

# Phpmyadmin Pod
- phpadmin-cm
```
kubectl create cm phpadmin-cm --from-literal=PMA_HOST=4.5.5.5 --from-literal=PMA_PORT=3306 --dry-run=client -o yaml > phpadmin-cm.yaml
```
- phpadmin-secret

```
kubectl create secret generic phpadmin-secret --from-literal=PMA_USER=root --from-literal=PMA_PASSWORD=rootpassword --dry-run=client -o yaml > phpadmin-secret.yaml
```
- phpadmin-pod
```
kubectl run phpadmin-pod --image=phpmyadmin --dry-run=client -o yaml > phpadmin-pod.yaml
```

- expose phpadmin pod service
```
kubectl expose pod phpadmin-pod --type=NodePort --port=8090 --target-port=80 --name=phpadmin-svc --dry-run=client -o yaml > phpadmin-svc.yaml
```
# Applocation Pod
- phpapp-pod
```
kubectl run phpapp-pod --image=fir3eye/php-app:v1 --dry-run=client -o yaml > phpapp-pod.yaml
```
- Expose pod service
```
kubectl expose pod phpapp-pod --type=NodePort --port=8020 --target-port=80 --name=phpapp-svc --dry-run=client -o yaml > phpapp-svc.yaml
```
