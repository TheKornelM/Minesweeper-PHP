# Developer Documentation for Minesweeper

## Environment
Developed for PHP 7.0

## Deployment

You can easily deploy the database using Docker.

You need to pass the following environment variables:
- `POSTGRES_USER`: Default Postgres user
- `POSTGRES_PASSWORD`: Default Postgres user password
- `POSTGRES_DB`: Default database name

Run the `docker-compose` command from the project's root directory. You can pass environment variables as arguments or within an environment file. For example, you can run
`docker-compose --env-file .env up` where `.env` is the name of the environment file name.

This will create a PostgreSQL Docker container with the initialized schema.

Don't forget to configure the database connection data in `env.php`. Check `env.php.example` for the correct configuration.
