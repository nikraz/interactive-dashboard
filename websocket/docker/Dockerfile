FROM node:14

WORKDIR /usr/src/app

COPY package*.json ./

RUN npm install

EXPOSE 6001

COPY . .

CMD ["node", "server.js"]
