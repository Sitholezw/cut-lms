# CHINHOYI UNIVERSITY - LEAVE MANAGEMENT SYSTEM (CUT-LMS)

## Description

CUT-LMS is a web-based Leave Management System designed for Chinhoyi University of Technology. It streamlines the process of applying for, approving, and managing staff leave requests. The system is built with PHP, MySQL, and Materialize CSS for a modern, responsive user experience.

---

## Features

- Staff leave application and tracking
- Admin leave approval and management
- Department management
- Secure authentication (with CSRF protection)
- Responsive design for desktop and mobile
- User-friendly dashboard and notifications

---

## Installation Instructions

1. **Install XAMPP**  
   Download and install XAMPP from [Apache Friends](https://www.apachefriends.org/).

2. **Run XAMPP as Administrator**  
   Right-click on `xampp.exe` and select **Run as administrator** for proper permissions.

3. **Start Apache and MySQL**  
   Open the XAMPP Control Panel and click **Start** for both Apache and MySQL.

4. **Set Up the Database**  
   - Import the provided SQL database file (e.g., `cut-lms.sql`) into phpMyAdmin or via the MySQL command line.
   - Place the project files in the `htdocs/cut-lms` directory.

5. **Configure Database Connection**  
   - Edit `includes/config.php` and update the database credentials if needed.

6. **Access the System**  
   - Open your browser and go to: [http://localhost/cut-lms](http://localhost/cut-lms)

---

## Usage

- **Staff** can log in, apply for leave, and view leave status.
- **Admins** can log in, view all leave requests, approve/decline requests, and manage employees and departments.

---

## Contributing

Feel free to fork this repository and submit pull requests for improvements or bug fixes.

---

## License

This project is licensed under the MIT License.

---

## Contact

For questions or feedback, please reach out to evidencefelixy11@gmail.com.
