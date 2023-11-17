# Project Setup

This section guides you through the initial setup of the project, including configuring the PostgreSQL database.

## PostgreSQL Configuration

To set up PostgreSQL for this project, you will need to configure your database connection settings. Use the 
following environment variables in your `.env` file:


```bash
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5433
DB_DATABASE=database
DB_USERNAME=postgres
DB_PASSWORD=postgres
```



Make sure to replace the values according to your PostgreSQL installation and desired database settings.

## Application Setup

After configuring the database, run the following command to set up the application:

```bash
php artisan app:setup
```


This command will perform migrations, seed the database, and carry out any necessary initializations required for the application.