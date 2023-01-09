FROM node:current-alpine

WORKDIR /var/www/

COPY package*.json ./

RUN npm install

EXPOSE 8080

CMD ["npm", "run", "serve"]