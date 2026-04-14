# User Management System

A comprehensive web application for user registration, authentication, and profile management built with modern web technologies and multiple database systems.

##  Features

- **User Registration**: Secure signup with email validation and password strength requirements
- **User Authentication**: Login system with session management
- **Profile Management**: Update personal details including age, date of birth, contact, and address
- **Responsive Design**: Mobile-friendly interface using Bootstrap
- **Security First**: Prepared statements, password hashing, input validation, and CORS headers
- **Multi-Database Architecture**: MySQL for auth, MongoDB for profiles, Redis for sessions

##  Tech Stack

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Custom styling with Bootstrap
- **JavaScript** - jQuery AJAX for API communication
- **Bootstrap 5** - Responsive UI components

### Backend
- **PHP 8+** - Server-side logic
- **MySQL** - User authentication data
- **MongoDB** - User profile details
- **Redis** - Session token storage

### Development Tools
- **Composer** - PHP dependency management
- **XAMPP** - Local development environment

##  Project Structure

```
guvi_project/
├── assets/                 # Static assets (logos, icons)
├── css/
│   └── styles.css         # Custom styles
├── js/
│   ├── register.js        # Registration logic
│   ├── login.js          # Login logic
│   └── profile.js        # Profile management
├── php/
│   ├── db.php            # Database connections
│   ├── register.php      # Registration API
│   ├── login.php         # Login API
│   └── profile.php       # Profile API
├── index.html            # Landing page
├── register.html         # Registration form
├── login.html           # Login form
├── profile.html         # Profile page
├── setup.sql            # Database schema
└── README.md            # Project documentation
```

##  Installation & Setup

### Prerequisites
- **XAMPP** (or similar PHP/MySQL environment)
- **MongoDB** installed and running
- **Redis** server running
- **Composer** for PHP dependencies

### Step-by-Step Setup

1. **Clone/Download the project**
   ```bash
   # Place the project in your web root
   # For XAMPP: C:\xampp\htdocs\guvi_project\
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Start Services**
   - Start **Apache** and **MySQL** in XAMPP Control Panel
   - Start **MongoDB** service
   - Start **Redis** server

4. **Setup Database**
   ```bash
   # Run the setup script
   mysql -u root < setup.sql
   ```

5. **Configure Database Connections**
   - Update `php/db.php` if your MySQL credentials differ from defaults
   - Default: `host=127.0.0.1`, `user=root`, `password=''`, `db=guvi_project`

6. **Access the Application**
   - Open browser: `http://localhost/guvi_project/index.html`

##  Usage

### User Flow
1. **Home Page**: Choose to register or login
2. **Registration**: Create account with name, email, password
3. **Login**: Authenticate with email and password
4. **Profile**: View and update personal information
5. **Logout**: End session and return to login

### API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `php/register.php` | POST | User registration |
| `php/login.php` | POST | User authentication |
| `php/profile.php` | GET/POST | Profile data management |

##  Security Features

- **Prepared Statements**: All MySQL queries use prepared statements
- **Password Hashing**: bcrypt algorithm for secure password storage
- **Input Validation**: Client and server-side validation
- **Session Management**: Redis-based session tokens with expiration
- **CORS Headers**: Proper cross-origin resource sharing configuration
- **Email Validation**: RFC-compliant email format checking

##  Responsive Design

- **Mobile-First**: Optimized for mobile devices
- **Bootstrap Grid**: Responsive layout system
- **Touch-Friendly**: Appropriate button sizes and spacing
- **Cross-Browser**: Compatible with modern browsers

##  Database Schema

### MySQL (Authentication)
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### MongoDB (Profile Data)
```javascript
{
  user_id: Number,
  name: String,
  email: String,
  age: Number || null,
  dob: String || null,
  contact: String || null,
  address: String || null,
  updated_at: Date
}
```

### Redis (Sessions)
```
Key: session:{token}
Value: {user_id}
TTL: 3600 seconds
```

##  Testing

### Manual Testing Checklist
- [ ] User registration with valid/invalid data
- [ ] Login with correct/incorrect credentials
- [ ] Profile update functionality
- [ ] Session persistence and expiration
- [ ] Responsive design on different screen sizes
- [ ] Form validation messages

### Browser Compatibility
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

##  Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

##  License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

##  Acknowledgments

- **Bootstrap** for responsive UI components
- **jQuery** for simplified DOM manipulation
- **MongoDB PHP Library** for database integration
- **Predis** for Redis connectivity

##  Support

For questions or issues:
- Check the setup instructions
- Verify all services are running
- Review browser console for errors
- Ensure correct file permissions

---

**Built with ❤️ for Guvi Project Requirements**
