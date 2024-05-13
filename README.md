# **New branch of settling a development environment for an app built by php, angular and mysql. (nginx as HTTP server)**

## **Follow these steps to run the image in your local machine**
- clone this branch in order to have a copy in your local machine
- In your local machine, open a terminal inside the root derectory and run this command : **docker-compose -f docker-compose-prod.yml up -d** to run all containers
- To see what the app has rendered, type one of these urls:
    - php app: **127.0.0.1:8080/ (example)**
    - angular app: **127.0.0.1:8080/browser/**

## **Where to write each app if you want to use it as a developement environment**
Run first the containers with: **docker-compose -f docker-compose-dev.yml up -d**. You should have the same file structure as this repo before starting it
- for angular app: In the directory **./angular/newApp**
- for php app: In the directory **./laravel_todo**
### **If you want to re-build all container:**
- In order to rebuild the container, run this command : **docker-compose -f docker-compose-prod.yml up -d --build**
