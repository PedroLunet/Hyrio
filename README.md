# Hyrio - ltw01g01

A Freelancing Website.

## Team Members

- João Miguel Lopes (202307809)
- João Pedro Pinto Lunet (202207150)
- Pedro André Freitas Monteiro (202307242)

## Info about the project

- All accounts have their passwords set as: 123456
- If you encounter any issues try rebuilding the database:
```bash
cd database/

sqlite3 site.db < create.sql
sqlite3 site.db < populate.sql
```

## External Libraries

- php-gd

## Features

**User:**
- [x] Register a new account.
- [x] Log in and out.
- [x] Edit their profile, including their name, username, password, and email.

**Freelancers:**
- [x] List new services, providing details such as category, pricing, delivery time, and service description, along with images or videos.
- [x] Track and manage their offered services.
- [x] Respond to inquiries from clients regarding their services and provide custom offers if needed.
- [x] Mark services as completed once delivered.

**Clients:**
- [x] Browse services using filters like category, price, and rating.
- [x] Engage with freelancers to ask questions or request custom orders.
- [x] Hire freelancers and proceed to checkout (simulate payment process).
- [x] Leave ratings and reviews for completed services.

**Admins:**
- [x] Elevate a user to admin status.
- [x] Introduce new service categories and other pertinent entities.
- [x] Oversee and ensure the smooth operation of the entire system.

**Extra:**
- [x] Users can save services on their profile by putting an heart on them.

## Running

    php -S localhost:9000

## Credentials

- arestivo@fe.up.pt/123456
- john@example.com/123456