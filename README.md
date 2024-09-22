# Waste Management with Mobile Notification System for CENRO

This project comprises both a **web application** and a **mobile application** to streamline the waste management process in Santiago City. The system optimizes garbage collection schedules, monitors garbage truck routes, and improves communication between CENRO officials, barangay officials, garbage truck drivers, and residents.

## Table of Contents
1. [Introduction](#introduction)
2. [System Features](#system-features)
3. [Technologies Used](#technologies-used)
4. [Web Application](#web-application)
   - [Installation](#installation)
   - [Configuration](#configuration)
   - [Usage](#usage)
   - [API Endpoints](#api-endpoints)
5. [Mobile Application](#mobile-application)
   - [Installation](#mobile-installation)
   - [Configuration](#mobile-configuration)
   - [Usage](#mobile-usage)
6. [License](#license)

## Introduction

The **Waste Management with Mobile Notification System for CENRO** provides both a web and mobile platform to improve waste management in Santiago City. It addresses several problems like missed collection hours, redundant routes, poor communication, and lack of tracking for garbage truck itineraries.

## System Features

- **Real-time tracking** of garbage trucks and optimization of routes.
- **Centralized waste management** system for waste collection and disposal.
- **Communication platform** for residents, truck drivers, barangay officials, and CENRO.
- **Spatial mapping** to track and manage waste complaints in various barangays.
- **Mobile notifications** for real-time garbage collection schedules and updates.

## Technologies Used

### Web Application:
- **Laravel Framework** (PHP)
- **Vue.js** (Frontend)
- **Inertia.js** (SPA bridging Laravel and Vue)
- **MySQL** (Database)
- **Leaflet.js** (Interactive maps)
- **Tailwind CSS** (CSS framework)

---

# Web Application

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/FutureProgrammer-Inc/wmmns.git

2. **Install Dependencies**:
    ```bash
    composer install
    npm install

3. **Configure Environment variables**
    ```bash
    cp .env.example .env

4. **Generate application key**:
    ```bash
    php artisan key:generate

5. **Setup database**: Update `.env`    file with your database details
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

6. **Run migrations**:
    ```bash
    php artisan migrate --seed

7. **Compile assets**:
    ```bash
    npm install && npm run dev

8. **Serve the application**:
    ```bash 
    php artisan serve

### Configuration
- **API and Notification Integration**: Configure your `.env` file with API keys for notification (used: Firebase).
- **Leaflet.js**: Ensure proper configuration for mapping garbage truck routes in the frontend.

### Usage
#### User roles and features:
- **CENRO Administrators**: Manage users, routes, schedules, generate reports, monitor trucks.
- **Barangay Officials**: Manage route plans, monitor reports, track garbage truck itineraries.
- **Garbage Truck Drivers**: View assigned routes, collection schedules, and track route progress.

### API Endpoints
#### Authentication
- `POST /api/login`: Authenticate users
- `POST /api/register`: Users Registration
- `POST /api/forgot-password`: Forgot Password
- `POST /api/reset-password`: Reseting Password
- `POST /api/password`: Updating Password
- `POST /api/logout`: Log out user
- `GET /api/user`: Check Authenticated User

#### Complaint
- `POST /api/complaints/file/store`: Storing Complaints
- `GET /api/complaints/show/{reference_number}`: Get Complaint Details by Reference ID
- `GET /api/complaints/`: Get Complaints List

#### Route
- `GET /api/routes/`: Get Route List
- `GET /api/routes/{id}`: Get Route Details by ID
- `GET /api/routes/get-by-barangay/{barangay}`: Get Route List by Barangay

#### Roam
- `POST /api/roams/start`: Start Roaming
- `POST /api/roams/end/{id}`: Stop Roaming
- `POST /api/roams/cancel/{id}`: Cancel Roaming
- `POST /api/roams/location/update`: Send Driver Location to server

#### Schedule
- `GET /api/schedule/`: Get schedule list
- `GET /api/schedule/get-by-id/{id}`: Get Schedule by id
- `GET /api/schedule/get-by-user/{id}`: Get Schedule by user
- `GET /api/schedule/get-by-truck/{id}`: Get Schedule by truck
- `GET /api/schedule/get-by-day/{day}`: Get Schedule by day (eg. `monday, tuesday...`)
- `GET /api/schedule/get-trucks-today`: Get Trucks that have schedule today

#### User
- `POST /api/profile/driver`: Update Driver Profile
- `POST /api/profile/resident`: Update Resident Profile

---

# Mobile Application


https://github.com/FutureProgrammer-Inc/wmmns-mobile

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.
