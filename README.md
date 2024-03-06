# My SQL Bot

Bot for Slack written in PHP that executes SQL queries and sends results back to a user in a thread.

Example message to run SQL query
```
@MySqlBot SELECT * FROM users LIMIT 1
```

## Features

- Execute SQL queries and get results back
- Changing format of the result message (csv_message, csv_file or sql_file)

## Roadmap

- Set max requests per minute for each user
- Use Block-Kit interactive components
- Ability to set timeout for query
- Ability to drop running query
- Multiple SQL queries in one message
- Wait for approve from another person before executing SQL query

## Creating slack bot

1. Create app on https://api.slack.com/apps
2. Add app credentials to environment variables
3. Enable Events
4. Set webhook url to endpoint `/api/v1/slack/events`
5. Subscribe to `app_mention` event

## Environment Variables

To run this project, you will need to add the environment variables to your .env file from .env.example

## Running locally

### Set environment variables

To run this project, you will need to add the environment variables to your .env file from .env.example

### Install dependencies

```shell
composer install
```

### Run web server

```shell
cd public & php -S 127.0.0.1:8000
```

### Run worker

```shell
php worker.php
```