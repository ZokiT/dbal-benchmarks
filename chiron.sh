#!/bin/bash

if [ "$1" = "init" ]; then
    # Check if Docker is installed
    if ! command -v docker &> /dev/null; then
        echo "Docker is not installed. Please install Docker before using this script."
        exit 1
    fi

    # Check if Docker Compose is installed
    if ! command -v docker-compose &> /dev/null; then
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
    docker exec -it app-dbal-benchmarks sh -c "./app/composer install"
    echo "Composer installed inside the container."

    echo "creating the DB tables"
    docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/migrations.sql"
    echo "migrating the tables"
#    docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/seeds.sql"

    # Exec into the container
    echo "Executing shell in app-dbal-benchmarks..."

    # Check if running on Windows
    if [[ "$OSTYPE" == "msys" || "$OSTYPE" == "cygwin" ]]; then
        winpty docker exec -it app-dbal-benchmarks sh
    else
        docker exec -it app-dbal-benchmarks sh
    fi
elif [ "$1" = "exec" ]; then
    # Check if running on Windows
    if [[ "$OSTYPE" == "msys" || "$OSTYPE" == "cygwin" ]]; then
        winpty docker exec -it app-dbal-benchmarks sh
    else
        docker exec -it app-dbal-benchmarks sh
    fi
elif [ "$1" = "fresh" ]; then
    # Check if running on Windows
    if [[ "$OSTYPE" == "msys" || "$OSTYPE" == "cygwin" ]]; then
        echo "recreating the DB tables"
        winpty docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/migrations.sql"
        echo "migrating the tables"
  #    winpty docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/seeds.sql"
    else
        echo "recreating the DB tables"
        docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/migrations.sql"
        echo "migrating the tables"
    #    winpty docker exec -it db-dbal-benchmarks sh -c "psql -U user -d dbal_benchmarks -f /var/lib/pgsql/seeds.sql"
    fi
else
    echo "Invalid command. Usage: sh.chiron [init|exec|fresh]"
fi
