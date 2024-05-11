FROM node:lts-alpine AS builder

WORKDIR /app

COPY ./angular/newApp/package.json ./
RUN npm install

COPY ./angular/newApp .
RUN npm run build

FROM nginx:latest

EXPOSE 80

COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

COPY --from=builder /app/dist/new-app /usr/share/nginx/html

COPY ./laravel_todo /var/www/html