
FROM node:10

# Setting working directory. All the path will be relative to WORKDIR
WORKDIR /

# Installing dependencies
COPY package*.json ./
RUN npm install

# Make port 80 available to the world outside this container
EXPOSE 80

# Copying source files
COPY . .

# Building app
RUN npm run build

# Running the app
CMD [ "npm", "start" ]