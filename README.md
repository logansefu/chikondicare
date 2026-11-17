# ChikondiCare

A PHP 8.2+, MySQL and PDO OOP starter implementation for the Web Programming Assignment 1.

## Requirements
- PHP 8.2 or newer
- MySQL 8+
- Composer
- XAMPP/WAMP/LAMP or PHP's built-in server
- VS Code

## Setup in VS Code
1. Extract this folder.
2. Open `ChikondiCare` in VS Code.
3. Create a MySQL database named `chikondicare`.
4. Import `database/schema.sql` using phpMyAdmin or the MySQL command line.
5. Copy `.env.example` to `.env` and set your database credentials.
6. Run `composer install`.
7. Run `composer dump-autoload`.
8. From the project folder, start the server:
   `php -S localhost:8000 -t public`
9. Open `http://localhost:8000`.

## Initial administrator
After importing the database, run this one-time command from the project directory:
`php scripts/create_admin.php`

Default login:
- Username: admin
- Password: ChangeMe123!

Change the password immediately after first login. The starter requires a password change at first login.

## Important
This is a learning starter, not a production-ready clinical system. Before real use, add HTTPS, backups, stronger audit review, privacy/legal controls, tested transactional workflows, deployment hardening and independent security testing. Use fictional patient data while developing.

## Modules included
- Staff login/logout, password change, session timeout and role checks
- Admin staff and role management foundations
- Doctor and patient registration
- Appointment booking and cancellation
- Check-in
- Consultation and prescription recording
- Payment recording and printable receipt
- Search and pagination foundations
- PDF and Excel package dependencies

Some advanced report layouts and administrative screens are intentionally marked as extension points. Implement and test each feature with your group and be ready to explain your own code.
