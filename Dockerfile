FROM node:17

WORKDIR /

COPY package.json ./

RUN npm install

ENV PORT=4000

EXPOSE 4000

CMD ["npm", "start"]