IMPROVED LOGIN + REGISTRATION SYSTEM (PHP)
==========================================

FILES
- index.php       -> redirects to Login
- login.php       -> Login page + server-side validation
- register.php    -> Registration page + server-side validation
- dashboard.php   -> protected page after successful login
- logout.php      -> ends the session
- config.php      -> shared user-storage/session helpers
- style.css       -> responsive styling based on the Figma design
- script.js       -> simple show/hide password interaction
- assets/         -> artwork from the provided Figma design
- data/users.json -> local user storage; passwords are hashed

VALIDATION INCLUDED
- Required fields
- Valid email format
- Duplicate email checking
- Password minimum of 8 characters
- Password must contain at least 1 letter and 1 number
- Password confirmation must match
- Field-specific error messages
- Registration success message
- Invalid login error message
- Proper escaping of user input
- password_hash() and password_verify()
- Sessions and CSRF protection

DESIGN IMPROVEMENTS
- Cleaner visual hierarchy and spacing
- More polished cards, colors, shadows and typography
- Helpful labels, placeholders and password hints
- Show/Hide password controls
- Responsive layout for desktop, tablet and mobile
- Clear navigation between Login and Registration
- Improved success/error message styling

HOW TO RUN FROM COMMAND PROMPT
1. Install PHP 8.x and make sure "php" works in Command Prompt.
2. Open Command Prompt inside this project folder.
3. Run:

   php -S localhost:8000

4. Open:

   http://localhost:8000

HOW TO RUN WITH XAMPP
1. Copy this whole folder into C:\xampp\htdocs\
2. Start Apache in XAMPP.
3. Open http://localhost/figma_login_register/

NOTES
- This project intentionally stays simple and uses JSON instead of MySQL.
- On a web host, make sure the /data folder is writable by PHP.
- If MySQL is specifically required, the storage functions in config.php can later be replaced with PDO/MySQL.
