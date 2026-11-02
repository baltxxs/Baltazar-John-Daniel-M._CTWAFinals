PHP SIGN IN + REGISTER PROJECT
==============================

REQUIREMENTS
- VS Code
- XAMPP (Apache + MySQL)
- PHP 8.0+ recommended
- phpMyAdmin (included with XAMPP)

INSTALLATION
1. Extract the project folder.
2. Copy the folder "php_signin_register" into:
   C:\xampp\htdocs\
3. Open XAMPP Control Panel and start Apache and MySQL.
4. Open http://localhost/phpmyadmin
5. Select the Import tab, choose database.sql from this project, then click Import.
   This creates the database "signin_register" and its "users" table.
6. Open config/db.php in VS Code.
   Default XAMPP settings are:
   host = localhost
   database = signin_register
   username = root
   password = blank
   Change them if your MySQL setup is different.
7. Visit:
   http://localhost/php_signin_register/

HOW TO TEST
- Click "Create one" to register.
- Sign in using the username or email and password you registered.
- The dashboard displays your account details.
- Click "Sign out" to end the session.

FILES
- register.php       Registration form and account creation
- login.php          Sign-in form and password verification
- dashboard.php      Protected page shown after login
- logout.php         Secure sign-out
- config/db.php      MySQL/PDO connection
- config/functions.php session, escaping and CSRF helpers
- database.sql      Database and table setup
- assets/style.css   Page design

SECURITY INCLUDED
- Passwords are stored with password_hash(), never as plain text.
- Login checks passwords with password_verify().
- SQL uses PDO prepared statements.
- Output is escaped to reduce cross-site scripting risk.
- Forms use CSRF tokens.
- Session ID is regenerated after login.

For a real public website, also use HTTPS, secure cookie settings, rate limiting,
email verification, and a password-reset flow.
