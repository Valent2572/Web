# ToDo - Premium Task Management System

A high-end, aesthetically pleasing ToDo application built with **Laravel 12**, **MySQL (XAMPP)**, and a custom **Premium Noir** design system.

## ✨ Features

- **Premium UI/UX**: Sophisticated dark theme using Gun Metal Grey, Gold accents, and Glassmorphism.
- **Smart Task Management**:
    - Create, Edit, and Delete tasks with ease.
    - **Priority Levels**: Color-coded priorities (Low, Medium, High).
    - **Deadline Tracking**: Set due dates and times for your tasks.
    - **Late Detection**: Automatic "LATE" badges for overdue tasks.
    - **Quick Toggle**: Mark tasks as completed directly from the dashboard.
- **Completion Timestamps**: Records exactly when you finished your tasks.
- **Statistics Dashboard**: Real-time tracking of total and completed tasks.
- **One-Click Startup**: Includes a batch script for instant launch.

## 🚀 Getting Started

### Prerequisites

- **XAMPP** (with PHP 8.2+ and MySQL)
- **Composer** (optional, for development)

### Installation & Setup

1. **Clone the repository** to your XAMPP `htdocs` or any preferred directory.
2. **Start MySQL** in your XAMPP Control Panel.
3. **Import the Database**:
   - Open phpMyAdmin (`http://localhost/phpmyadmin`).
   - Create a new database named `todo_app`.
   - Import the `script.sql` file provided in the root directory.
4. **Environment Config**:
   - The `.env` file is already configured for default XAMPP settings.

### Running the App

Simply double-click the **`start-app.bat`** file. This will automatically:
- Start the development server.
- Open your default browser to `http://127.0.0.1:8000`.

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x
- **Database**: MariaDB / MySQL
- **Frontend**: Blade Templating, Vanilla CSS, FontAwesome 6, Google Fonts (Outfit)
- **Environment**: XAMPP

## 📜 License

This project is open-source and available under the [MIT License](LICENSE).
