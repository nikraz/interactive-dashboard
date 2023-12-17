#prod minimal setup, not suitable for running tests
#FROM node:latest as build-stage

#WORKDIR /app
#COPY ./package*.json ./
#RUN npm install
#COPY . .
#RUN npm run build
#
## Step 2: Serve the app using a lightweight HTTP server, like nginx
#FROM nginx:stable-alpine as production-stage
#COPY --from=build-stage /app/dist /usr/share/nginx/html
#EXPOSE 80
#CMD ["nginx", "-g", "daemon off;"]


FROM node:latest

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

EXPOSE 8080

ENV NODE_ENV development

CMD ["npm", "run", "serve"]
