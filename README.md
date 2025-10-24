<div align="center">
  <img src="public/img/buksu-logo.png" alt="Bukidnon State University Logo" width="150">
  
  # BukSU AlumniConnect
  ### Alumni Tracking System
  
  *Connecting Generations, Building Futures*
  
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)
  [![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
  
</div>

---

## 📖 About The Project

**BukSU AlumniConnect** is a comprehensive, enterprise-grade web application designed specifically for Bukidnon State University to maintain lasting connections with alumni, track their professional journeys, and foster an engaged alumni community across all campuses and departments.

Built with modern web technologies and a multi-tenant architecture, the system empowers educational institutions to efficiently manage alumni data, facilitate communication, and generate meaningful insights about graduate outcomes.

### ✨ Key Features

#### 🏢 **Multi-Tenancy Architecture**
- Separate domains for each campus/department with isolated data
- Custom branding and configuration per tenant
- Centralized administration with tenant-specific management

#### 👥 **Comprehensive Alumni Management**
- Detailed alumni profiles with employment history tracking
- Real-time professional progress monitoring
- Achievement and milestone documentation
- Advanced search and filtering capabilities

#### 🎓 **Dedicated Portals**
- **Central Admin Portal**: System-wide management and oversight
- **Tenant Admin Portal**: Campus/department-specific administration
- **Instructor Portal**: Faculty access to alumni data and insights
- **Alumni Portal**: Self-service profile management and networking

#### 🔐 **Advanced Security**
- Role-based access control (RBAC) with 4 user levels
- Separate authentication systems for central and tenant domains
- Middleware protection for cross-domain access
- Secure password hashing and session management

#### 🎨 **Modern UI/UX**
- Split-screen BukSU-branded design system
- Custom navy, gold, and accent color palette
- Toast notification system for user feedback
- Fully responsive mobile-first design
- Smooth animations and transitions

#### 📊 **Reporting & Analytics**
- Employment tracking and statistics
- Alumni engagement metrics
- Version management and audit trails
- Support ticket analytics

#### 🎫 **Support System**
- Built-in ticketing system for user support
- Multi-level response handling
- Email notifications for ticket updates

---

## 🚀 Getting Started

### Prerequisites

Ensure you have the following installed on your system:

- **PHP** >= 8.2
- **MySQL** >= 5.7 or **MariaDB** >= 10.3
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x or **Yarn** >= 1.22

### 📦 Installation

1. **Clone the repository**

```bash
git clone https://github.com/Yosores04/AlumniTrackingSystem.git
cd AlumniTrackingSystem
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Install JavaScript dependencies**

```bash
npm install
```

4. **Configure environment**

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

5. **Configure your `.env` file**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alumni_tracking
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

6. **Set up the database**

```bash
# Run migrations for central database
php artisan migrate

# Run migrations for tenant databases
php artisan tenants:migrate

# Seed the database
php artisan db:seed

# Create central admin account
php artisan db:seed --class=CentralAdminSeeder
```

7. **Build frontend assets**

```bash
npm run build
```

8. **Run the application**

```bash
# Start the development server
php artisan serve

# In another terminal, start Vite (for development)
npm run dev
```

9. **Access the application**

- Central Admin Portal: `http://localhost:8000`
- Default Credentials (Central Admin):
  - Email: `admin@buksu.edu.ph`
  - Password: `password` (change immediately)

---

## 🏗️ Architecture

### Multi-Tenancy Structure

```
┌─────────────────────────────────────────┐
│         Central Domain (Main App)       │
│         http://localhost:8000           │
│                                         │
│  • System Administration                │
│  • Tenant Management                    │
│  • Domain Request Handling              │
│  • Central Admin Only Access            │
└─────────────────────────────────────────┘
                    │
        ┌───────────┴───────────┐
        │                       │
┌───────▼─────────┐    ┌───────▼─────────┐
│  Tenant Domain  │    │  Tenant Domain  │
│  engineering.*  │    │    coe.*        │
│                 │    │                 │
│  • Alumni       │    │  • Alumni       │
│  • Instructors  │    │  • Instructors  │
│  • Tenant Admin │    │  • Tenant Admin │
└─────────────────┘    └─────────────────┘
```

### User Roles

| Role | Access Level | Permissions |
|------|-------------|-------------|
| **Central Admin** | System-wide | Full system access, tenant management, domain requests |
| **Tenant Admin** | Tenant-specific | Manage tenant settings, users, alumni data |
| **Instructor** | Tenant-specific | View/manage alumni, add notes, generate reports |
| **Alumni** | Personal | Update profile, view employment history, submit tickets |

---

## 🛠️ Tech Stack

### Backend
- **[Laravel 11.x](https://laravel.com)** - PHP Framework
- **[Stancl Tenancy](https://tenancyforlaravel.com)** - Multi-tenancy Package
- **MySQL** - Database
- **Redis** - Caching & Queues (optional)

### Frontend
- **[Tailwind CSS 3.x](https://tailwindcss.com)** - Utility-first CSS Framework
- **[Alpine.js](https://alpinejs.dev)** - Lightweight JavaScript Framework
- **[Vite 6.x](https://vitejs.dev)** - Frontend Build Tool
- **Custom BukSU Design System** - Branded component library

### Additional Tools
- **Laravel Sanctum** - API Authentication
- **Laravel Breeze** - Authentication Scaffolding
- **Laravel Notifications** - Email & In-app Notifications
- **Faker** - Test Data Generation

---

## 📸 Screenshots

### Login & Authentication
Modern split-screen design with BukSU branding

### Alumni Dashboard
Comprehensive overview of alumni data and engagement

### Domain Request
Simple 3-field form for tenant domain requests

---

## 🔧 Configuration

### Setting Up a New Tenant

1. Request a domain via the central portal
2. Admin approves the request
3. System automatically creates tenant database
4. Tenant admin receives welcome email with credentials

### Custom Branding

Each tenant can customize:
- Logo and color scheme
- Email templates
- Dashboard widgets
- Custom fields for alumni profiles

---

## 📚 Documentation

- [Installation Guide](docs/INSTALLATION.md)
- [User Manual](docs/USER_MANUAL.md)
- [API Documentation](docs/API.md)
- [GitHub Token Setup](docs/GITHUB_TOKEN_SETUP.md)

---

## 👨‍💻 Development Team

## 👨‍💻 Development Team

This system was developed by the **WST-T83 Team** at Bukidnon State University:

| Name | Role | GitHub |
|------|------|--------|
| **Joshua James G. Yosores** | Lead Developer & System Architect | [@Yosores04](https://github.com/Yosores04) |
| **Margaret Zoe A. Neri** | Frontend Developer & UI/UX Designer | [@MargaretNeri](https://github.com/MargaretNeri) |
| **Nilo G. Garciano Jr.** | Backend Developer & Database Architect | [@GarcianoNilo](https://github.com/GarcianoNilo) |
| **Sern S. Ponce** | Project Manager & QA Lead | [@SernPonce](https://github.com/SernPonce) |

---

## 🤝 Contributing

We welcome contributions to improve the BukSU AlumniConnect system! Here's how you can help:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please ensure your code follows:
- PSR-12 coding standards
- Laravel best practices
- Includes appropriate tests
- Updates documentation as needed

---

## 🐛 Bug Reports & Feature Requests

Found a bug or have a feature request? Please open an issue on GitHub:

- [Report a Bug](https://github.com/Yosores04/AlumniTrackingSystem/issues/new?labels=bug)
- [Request a Feature](https://github.com/Yosores04/AlumniTrackingSystem/issues/new?labels=enhancement)

---

## 📝 Changelog

### Version 2.0.0 (Latest)
- ✨ Complete UI/UX overhaul with BukSU branding
- ✨ Separated central and tenant authentication systems
- ✨ Added toast notification system
- ✨ Modernized domain request page
- 🔒 Enhanced role-based access control
- 🐛 Fixed route naming conflicts
- 🎨 Custom BukSU color palette (navy/gold/accent)
- 📱 Fully responsive mobile design

### Version 1.0.0
- 🎉 Initial release
- Multi-tenancy implementation
- Alumni management system
- Support ticket system
- Instructor portal

See [CHANGELOG.md](CHANGELOG.md) for full version history.

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgements

We would like to thank:

- **Bukidnon State University** - For the opportunity and support
- **The Laravel Community** - For the excellent framework and packages
- **Stancl Tenancy** - For the robust multi-tenancy solution
- **Our Mentors** - For guidance throughout development
- **Open Source Contributors** - For the amazing tools that made this possible

---

## 📞 Contact & Support

- **Email**: alumni@buksu.edu.ph
- **Website**: https://buksu.edu.ph
- **GitHub**: https://github.com/Yosores04/AlumniTrackingSystem

For technical support or inquiries about the system, please contact the development team or submit an issue on GitHub.

---

<div align="center">
  
  ### Made with ❤️ by WST-T83 Team
  
  **Bukidnon State University**
  
  *Empowering Alumni Connections Since 2025*
  
  ---
  
  <img src="public/img/buksu-logo.png" alt="BukSU Logo" width="80">
  
</div>
