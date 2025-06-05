# Hyrio - Freelancing Platform

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

A comprehensive freelancing website developed in PHP for the LTW (Web Languages and Technologies) course at FEUP. This project demonstrates full-stack web development with user authentication, database management, and responsive design.

## 🌐 About

Hyrio is a complete freelancing platform that connects clients with service providers, featuring:
- User authentication and role-based access control
- Service marketplace with advanced filtering
- Real-time messaging system between clients and freelancers
- Payment simulation and order management
- Admin panel for platform management
- Responsive design with modern UI/UX

## 🚀 Features

### 👤 User Management
- **Account Registration**: Create new user accounts with validation
- **Authentication**: Secure login/logout system
- **Profile Management**: Edit name, username, password, and email
- **Role-based Access**: Different permissions for clients, freelancers, and admins

### 💼 Freelancer Features
- **Service Listing**: Create detailed service offerings with categories, pricing, and media
- **Portfolio Management**: Track and manage all offered services
- **Client Communication**: Respond to inquiries and provide custom offers
- **Order Fulfillment**: Mark services as completed upon delivery

### 🛒 Client Features
- **Service Discovery**: Browse services with advanced filters (category, price, rating)
- **Direct Communication**: Engage with freelancers for questions and custom requests
- **Secure Checkout**: Hire freelancers with simulated payment processing
- **Review System**: Leave ratings and reviews for completed services
- **Wishlist**: Save favorite services with heart functionality

### ⚙️ Admin Panel
- **User Management**: Elevate users to admin status
- **Category Management**: Add new service categories and entities
- **Platform Oversight**: Monitor and ensure smooth system operation

## 🛠 Technical Implementation

### Tech Stack
- **Backend**: PHP with custom MVC architecture
- **Database**: SQLite with structured schema
- **Frontend**: HTML5, CSS3, JavaScript
- **Libraries**: php-gd for image processing

### System Architecture
```
┌─────────────────┐
│   Frontend UI   │
├─────────────────┤
│  PHP Backend    │
├─────────────────┤
│ SQLite Database │
└─────────────────┘
```

### Database Schema
- User management with role-based permissions
- Service listings with categories and pricing
- Order tracking and payment simulation
- Review and rating system

## 🏗 Setup and Installation

### Prerequisites
- PHP 7.4 or higher
- SQLite3
- php-gd extension

### Quick Start
```bash
# Clone the repository
git clone [repository-url]
cd hyrio

# Set up database
cd database/
sqlite3 site.db < create.sql
sqlite3 site.db < populate.sql

# Start development server
php -S localhost:9000
```

### Troubleshooting
If you encounter any issues, try rebuilding the database:
```bash
cd database/
sqlite3 site.db < create.sql
sqlite3 site.db < populate.sql
```

## 🔐 Test Credentials

All accounts use the password: `123456`

**Admin Account:**
- Email: `arestivo@fe.up.pt`
- Password: `123456`

**Regular User:**
- Email: `john@example.com`
- Password: `123456`

## 📁 Project Structure

```
hyrio/
├── database/          # Database schema and seed data
├── controllers/       # PHP controllers
├── models/           # Data models
├── views/            # HTML templates
├── public/           # Static assets (CSS, JS, images)
└── config/           # Configuration files
```

## 🎯 Key Features Implemented

- ✅ Complete user authentication system
- ✅ Role-based access control (Client/Freelancer/Admin)
- ✅ Service marketplace with filtering
- ✅ Real-time messaging between users
- ✅ Order management and tracking
- ✅ Review and rating system
- ✅ Admin dashboard for platform management
- ✅ Responsive design for mobile compatibility
- ✅ Wishlist functionality
- ✅ Payment simulation

## 🏆 Learning Outcomes

Through this project, I gained practical experience with:
- **Full-stack Development**: End-to-end web application development
- **Database Design**: Schema design and SQL optimization
- **User Experience**: Responsive design and intuitive interfaces
- **Security**: Authentication, authorization, and data validation
- **Project Management**: Team collaboration and version control

## 👥 Development Team

This project was developed by:
- Pedro Lunet
- [João Miguel Lopes](https://github.com/joaolopes15)
- [Pedro André Freitas Monteiro](https://github.com/pedroafmonteiro)

## 📜 Course Information

Developed for the LTW (Web Languages and Technologies) course at FEUP (Faculty of Engineering, University of Porto).

---

*Access the platform at `localhost:9000` after setup. The application demonstrates modern web development practices with a focus on user experience and functionality.*
