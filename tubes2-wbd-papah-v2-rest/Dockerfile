FROM node:latest
WORKDIR /usr/app
COPY package*.json ./
RUN npm install
COPY . .
EXPOSE 4000
RUN npm run
CMD ["node", "src/app.js"]