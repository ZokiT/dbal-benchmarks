#!/bin/bash

# Function to check if the script is running on MSYS or Cygwin
is_windows() {
    local lower_ostype
    lower_ostype=$(tr '[:upper:]' '[:lower:]' <<<"$OSTYPE")

    if [[ "$lower_ostype" == *msys* || "$lower_ostype" == *cygwin* ]]; then
        return 0
    else
        return 1
    fi
}

# Determine command prefix based on the OS
if is_windows; then
    command_prefix="winpty "
else
    command_prefix=""
fi

if [ "$1" = "init" ]; then
    # Check if Docker is installed
    if ! type docker &> /dev/null; then
        echo "Docker is not installed. Please install Docker before using this script."
        exit 1
    fi

    # Check if Docker Compose is installed
    if ! type docker-compose &> /dev/null; then
        echo "Docker Compose is not installed. Please install Docker Compose before using this script."
        exit 1
    fi

    # Start Docker containers
    echo "Starting Docker containers..."
    docker-compose up -d

    # Wait for the containers to be ready (adjust as needed)
    echo "Waiting for containers to be ready..."
    sleep 10

    # Install Composer inside the Docker container
    echo "Installing Composer inside the container..."
    ${command_prefix}docker exec -it app-dbal-benchmarks sh -c "composer install"
    echo "Composer installed inside the container."

    echo "creating the DB tables and fill them with data"
    ${command_prefix}docker exec -it db-dbal-benchmarks sh -c "psql -U user -c \"DROP DATABASE IF EXISTS dbal_benchmarks;\""
    ${command_prefix}docker exec -it db-dbal-benchmarks sh -c "psql -U user -c \"CREATE DATABASE dbal_benchmarks;\""
    ${command_prefix}docker exec -it db-dbal-benchmarks sh -c "psql -U user dbal_benchmarks < /var/lib/pgsql/dbal-benchmarks-dump.sql"
    echo "migrating the tables"

    # Exec into the container
    echo "Executing shell in app-dbal-benchmarks..."
    ${command_prefix}docker exec -it app-dbal-benchmarks sh
elif [ "$1" = "exec" ]; then
    ${command_prefix}docker exec -it app-dbal-benchmarks sh
elif [ "$1" = "fresh" ]; then
    echo "recreating the DB tables"
    ${command_prefix}docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/drop-tables.sql"
    ${command_prefix}docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/migrations.sql"
elif [ "$1" = "seed" ]; then
    echo "recreating and seeding the DB tables"
    ${command_prefix}docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/drop-tables.sql"
    ${command_prefix}docker exec -it db-dbal-benchmarks sh -c "psql -U user dbal_benchmarks < dbal-benchmarks-dump.sql"
else
    echo "Invalid command. Usage: sh.chiron [init|exec|fresh|seed]"
fi
