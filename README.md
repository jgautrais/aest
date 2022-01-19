# Aest

A respository for an area estimation tool.

## Context

Visual assessment of disease is a key aspect to many phytopathological research studies.
Estimation of disease severity is a basic skill required of people working on plant pathology studies.
Several applications have been developed to train people to visual assessment of disease.

This project is an attempt to reproduce such a training tool.

## Requirements

-   Php ^7.2 http://php.net/manual/fr/install.php;
-   Composer https://getcomposer.org/download/;

## Installation

1. Clone the current repository.

2. Move into the directory and create an `.env.local` file.
   Set `db_name` to **aest**.

3. Execute the following commands in your working folder to install the project:

```bash
# Install dependencies
composer install

# Create 'aest' DB
php bin/console d:d:c

# Execute migrations and create tables
php bin/console d:m:m
```

## Usage

Launch the server with the command below and follow the instructions on the homepage `/`;

```bash
$ symfony server:start
```
