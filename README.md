# Laravel Pro Kit - Docker Installation and Setup Guide
## Prerequisites

Before you begin, ensure you have the following prerequisites installed on your system:

- Docker: Make sure Docker is installed and running on your machine. You can download it from [Docker's official website](https://www.docker.com/get-started).

## Step 1: Clone the Repository

First, clone the Laravel Pro Kit repository to your local machine using the following command:

```bash
git clone https://github.com/mehadi/laravel-pro-kit.git
```

## Step 2: Navigate to the Project Directory

Change your working directory to the cloned repository:

```bash
cd laravel-pro-kit
```

## Step 3: Configure Environment Variables

Copy the example environment file and configure the necessary environment variables:

```bash
cp .env.example .env
```
Open the .env file in a text editor of your choice and update the following variables as needed:

DB_HOST: Set this to the name of the Docker service for the database container, typically mysql.
DB_DATABASE, DB_USERNAME, DB_PASSWORD: Set these to your desired database name, username, and password.

## Step 4: Build and Start Docker Containers

Build and start the Docker containers using `docker-compose`:

```bash
docker-compose up -d --build
```
This command will build the necessary containers and start the services in the background.


## Step 5: Install Laravel Dependencies

Access the Laravel workspace container:

```bash
docker-compose exec app bash
```
Inside the container's shell, install the Laravel dependencies:
```bash
composer install
```

## Step 6: Generate Application Key
Generate the Laravel application key:

```bash
php artisan key:generate
```

## Step 7: Access the Application
You can access the Laravel application in your browser at http://localhost. The application should now be up and running.

Stopping Containers: To stop the Docker containers, run:
```bash
docker-compose down
```

Congratulations! You have successfully installed and set up Laravel with Docker using the Laravel Pro Kit repository. If you encounter any issues or have questions, please refer to the repository's documentation or seek assistance from the community.

Happy coding! 🚀
