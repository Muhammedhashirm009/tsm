<div align="center">
  <h1 align="center">Masjid Financial Management System (TSM)</h1>
  <p align="center">
    A comprehensive, secure, and intuitive web application designed specifically for managing the financial operations of a Masjid.
    <br />
    <br />
    <a href="#key-features"><strong>Explore the features »</strong></a>
    <br />
    <br />
    <a href="#installation">Installation</a>
    ·
    <a href="#deployment">Deployment</a>
    ·
    <a href="#tech-stack">Tech Stack</a>
  </p>
</div>

<details open>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#tech-stack">Tech Stack</a></li>
      </ul>
    </li>
    <li><a href="#key-features">Key Features</a></li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#deployment">Deployment</a></li>
    <li><a href="#role-based-access-control-rbac">Role-Based Access Control</a></li>
    <li><a href="#license">License</a></li>
  </ol>
</details>

## About The Project

The **Masjid Financial Management System** is a full-featured Laravel application tailored to meet the specific accounting and administrative needs of a Masjid. It streamlines complex financial workflows, including donations, vouchers, receipts, debt management, and general bookkeeping.

With an emphasis on transparency, security, and ease of use, the platform offers a robust Role-Based Access Control (RBAC) system to ensure that committee members—from the President to Collectors—have precise access to the tools they need.

### Tech Stack

* **Framework:** [Laravel 12](https://laravel.com/) (PHP 8.2+)
* **Frontend:** [Blade Templates](https://laravel.com/docs/blade) & [Tailwind CSS](https://tailwindcss.com/)
* **Database:** MySQL / SQLite
* **Authentication & Authorization:** Laravel Breeze & [Spatie Permission](https://spatie.be/docs/laravel-permission)
* **Assets:** [Vite](https://vitejs.dev/)

## Key Features

* 📊 **Financial Dashboard:** Get a real-time overview of the Masjid's financial health, recent transactions, and account balances.
* 💵 **Transaction Management:** Seamlessly manage Receipts (Income) and Vouchers (Expenses) with categorized bookkeeping.
* 📖 **Book & Account Tracking:** Organize funds into specific accounts and books for precise financial categorization and numbering.
* ❤️ **Mahal Donations:** Dedicated module for tracking and managing Mahal donations with robust reporting and soft-delete capabilities.
* 🤝 **Debt Management:** Keep track of debts and loans with easy-to-use interfaces.
* 🔐 **Advanced RBAC:** Secure access with granular permissions for distinct roles (Admin, Secretary, Joint Secretary, President, Collector).
* 📱 **Responsive Design:** A polished, modern UI that works flawlessly on desktop and mobile devices.

## Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

* PHP >= 8.2
* Composer
* Node.js & npm
* MySQL or SQLite

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Muhammedhashirm009/tsm.git
   cd tsm
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM packages and build assets**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database credentials in the `.env` file.*

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Start the local development server**
   ```bash
   php artisan serve
   ```
   *The application will be available at `http://localhost:8000`.*

## Deployment

This application includes an automated deployment script tailored for **Hostinger** (or similar cPanel/SSH environments).

1. Upload your files to the server.
2. Execute the included deployment script via SSH from the project root:
   ```bash
   bash deploy_hostinger.sh
   ```
   This script automates setting file permissions, installing Composer dependencies, optimizing Laravel caches, running migrations, and creating the storage symlink.

## Role-Based Access Control (RBAC)

The system is pre-configured with the following roles, each possessing specific capabilities to maintain operational security and data integrity:

*   **Admin:** Full system access, including User Management and Role assignment.
*   **President:** High-level overview and approval capabilities.
*   **Secretary:** Comprehensive access to financial records and reporting.
*   **Joint Secretary:** Assisting access rights, supporting the Secretary's duties.
*   **Collector:** Restricted access focused strictly on data entry for collections and donations.

*Note: Public registration is disabled by default to maintain strict access control. New users must be manually added by an Administrator.*

## Contributing

If you are a part of the development team, please create feature branches and submit pull requests for review before merging into `main`. Ensure all tests pass and follow the established coding standards.

## License

This project is proprietary and confidential. Unauthorized copying, distribution, or modification of this project is strictly prohibited unless explicitly authorized by the project owners.

---
<p align="center">Developed with ❤️ for the community.</p>
