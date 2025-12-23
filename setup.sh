#!/bin/bash

# E-Commerce REST API Setup Script
# This script will set up the Laravel REST API with Passport

echo "========================================="
echo "E-Commerce REST API Setup"
echo "========================================="

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

echo ""
echo "Please configure your database in .env file"
echo "Press Enter when ready to continue..."
read

echo ""
echo "Running migrations..."
php artisan migrate

echo ""
echo "Installing Passport..."
php artisan passport:install

echo ""
echo "Creating personal access client..."
echo "users" | php artisan passport:client --personal --name="E-Commerce API Personal Access Client"

echo ""
echo "Creating storage link..."
php artisan storage:link

echo ""
echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo ""
echo "========================================="
echo "Setup Complete!"
echo "========================================="
echo ""
echo "To start the development server, run:"
echo "php artisan serve"
echo ""
echo "API will be available at: http://localhost:8000/api"
echo ""
echo "See API_DOCUMENTATION.md for complete API documentation"
echo "Import postman_collection.json into Postman for testing"
echo ""
