# My SQL Bot

Bot for Slack written in PHP that executes SQL queries and sends results back to a user in a thread.

## Features

- Execute SQL queries and get results back

## Roadmap

- Set max requests per minute for each user
- Use Block-Kit interactive components
- Ability to set timeout for query
- Ability to drop running query
- Multiple SQL queries in one message
- Selecting result output (File, Message)
- Selecting formats for results (CSV, SQL)
- Wait for approve from another person before executing SQL query

## Environment Variables

To run this project, you will need to add the environment variables to your .env file from .env.example

## Running locally

### Web server

```shell
cd public & php -S 127.0.0.1:8000
```

### Worker

```shell
php worker.php
```