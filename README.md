# Currency Conversion API

A simple RESTful API for currency conversion for the php assignment.

## Table of Contents

- [Features](#features)
- [Prerequisites](#prerequisites)
- [Project Structure](#project-structure)
- [Setup & Installation](#setup--installation)
- [Running the Application](#running-the-application)
- [API Usage](#api-usage)
- [Running Tests](#running-tests)
- [Deployment with Docker](#deployment-with-docker)
- [Setup & Run Locally (Without Docker)](#setup--run-locally-without-docker)

## Features

- ✅ The service is written in PHP using Laravel 
- ✅ Validation, testing, caching implemented
- ✅ Convert between currencies, the result is formatted using the Web i18n framework
- ✅ CSRF abd CSP implemented
- ✅ The app is containerizied with Docker
- ✅ The app has Vue.js user interface
- ❌ Not implemented yet: Add instrumentation to the codebase. Use InfluxDB to log data from the
backend and integrate it with Grafana for real-time monitoring and analytics 

## Prerequisites

Choose one of the following setups:

### 1. Running with Docker (Recommended)

- [Docker Desktop](https://www.docker.com/products/docker-desktop) (includes Docker Compose)
- No need to install PHP, Node.js, or npm locally

### 2. Running Locally (Without Docker)

- [PHP 8.2+](https://www.php.net/)
- [Composer](https://getcomposer.org/) (for PHP dependencies)
- [Node.js](https://nodejs.org/) (v14+)
- [npm](https://www.npmjs.com/) (v6+)
---

## Project Structure

```
.
├── app/                # Laravel backend code
├── resources/
│   ├── js/             # Vue.js frontend code
│   └── views/          # Blade templates
├── public/             # Public assets
├── dockerfiles/        # Dockerfiles for PHP, Node, Composer
├── docker-compose.yaml # Docker Compose config
├── package.json        # Node dependencies
├── composer.json       # PHP dependencies
└── ...
```

---


## Setup & Installation

### 1. Clone the repository

```sh
git clone https://github.com/vvduth/currency-conversion.git
cd currency-conversion
```

### 2. Copy and configure environment variables

```sh
cp .env.example .env
# Edit .env as needed (API keys, etc.)
```

### 3. Build and start the containers

```sh
docker compose up --build
```

This will:
- Build PHP, Node, and Composer containers
- Install PHP and Node dependencies
- Start Nginx on [http://localhost:8000](http://localhost:8000)
- Start Vite dev server on [http://localhost:3000](http://localhost:3000) (for hot reloading)

---

## Running the Application

- Visit [http://localhost:8000](http://localhost:8000) in your browser.
- The Vue.js frontend will be mounted in the `<div id="app"></div>` of `welcome.blade.php`.
- Any changes to Vue components or Tailwind CSS will hot-reload if Vite is running.

---

## API Usage

### **Base URL:**  
`http://localhost:8000/api`

### **Endpoints:**

#### 1. Convert Currency

- **POST** `/api/convert`
- **Body:**
    ```json
    {
      "base_currency": "EUR",
      "quote_currency": "USD",
      "amount": 100
    }
    ```
- **Response:**
    ```json
    {
    "success": true,
    "converted_amount": "€100.00"
    }
    ```

#### 2. Get Available Currencies

- **GET** `/api/currencies`
- **Response:**
    ```json
    {
      
      "success": true,
      "currencies": [
        {
            "code": "AED",
            "numeric_code": "784",
            "decimal_digits": 2,
            "name": "United Arab Emirates dirham",
            "active": true
        },
        {
            "code": "AFN",
            "numeric_code": "971",
            "decimal_digits": 2,
            "name": "Afghan afghani",
            "active": true
        },
        ...
    ]
    }
    ```

---

## Running Tests

### **PHP/Laravel Tests**

```sh
docker compose run --rm artisan test
```


## Deployment with Docker

1. **Build assets for production:**

    ```sh
    docker compose run --rm npm run build
    ```

2. **Start containers (without Vite dev server):**

    ```sh
    docker compose up -d
    ```

3. **Nginx will serve the built assets from `public/build`.**

---
## Setup & Run Locally (Without Docker)

If you prefer to run the application directly on your machine (without Docker), follow these steps:

### 1. Install PHP, Composer, Node.js, and npm

- Make sure you have **PHP 8.2+**, [Composer](https://getcomposer.org/), [Node.js](https://nodejs.org/), and [npm](https://www.npmjs.com/) installed.

### 2. Clone the repository

```sh
git clone https://github.com/vvduth/currency-conversion.git
cd currency-conversion
```

### 3. Copy and configure environment variables

```sh
cp .env.example .env
# Edit .env as needed (API keys, etc.)
```

### 4. Install PHP dependencies

```sh
composer install
```

### 5. Install Node.js dependencies

```sh
npm install
```

### 6. Build frontend assets (for production) or start Vite dev server (for development)

For development with hot reload:
```sh
npm run dev
```

For production build:
```sh
npm run build
```

### 7. Run Laravel migrations (if needed)

```sh
php artisan migrate
```

### 8. Start the Laravel development server

```sh
php artisan serve
```

### 9. Access the application

- Visit [http://localhost:8000](http://localhost:8000) in your browser.

---

**Note:**  
- For full functionality, ensure required PHP extensions are installed (pdo, intl, mbstring, etc.).
- You may need to configure your `.env` file with the correct API keys.
