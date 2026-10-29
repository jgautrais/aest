# syntax=docker/dockerfile:1

# -------------------------------------------
# Base Node image
# -------------------------------------------
FROM node:24-bookworm-slim AS base

WORKDIR /app

# -------------------------------------------
# Install dependencies
# -------------------------------------------
FROM base AS install

COPY package.json package-lock.json ./
RUN npm ci

# -------------------------------------------
# Local development target
# -------------------------------------------
FROM install AS local

COPY . .

EXPOSE 5173

CMD ["npm", "run", "dev"]

# -------------------------------------------
# Build stage - test, then generate the static site into dist/
# -------------------------------------------
FROM install AS build

COPY . .

RUN npm test && npm run build

# -------------------------------------------
# Production release target with nginx
# -------------------------------------------
FROM nginx:alpine AS release

# Copy built static files
COPY --from=build /app/dist /usr/share/nginx/html

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
