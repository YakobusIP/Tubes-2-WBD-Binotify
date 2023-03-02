FROM node:latest
WORKDIR /react-vite-app
EXPOSE 3000
COPY package.json package-lock.json ./
RUN npm install --silent
COPY . ./
CMD ["npm", "run", "dev"]