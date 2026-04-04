# 📌 Support Ticket Portal – Backend

## 📖 Overview

The Support Ticket Portal backend is a RESTful API built with Laravel, designed to manage support tickets in a B2B environment.

It provides secure, scalable, and role-based access for managing tickets, comments, organizations, and SLA tracking.

---

## 📚 Documentation

Detailed system documentation:

* 📄 SRS Document: https://drive.google.com/<your-srs-link>
* 📘 User Guide: https://drive.google.com/<your-user-guide-link>
* 🧾 Technical Specification: https://drive.google.com/<your-tech-spec-link>

---

## 🧩 Architecture

```
[ React Frontend ]
        ↓
[ Laravel REST API ]
        ↓
[ Service Layer ]
        ↓
[ Repository Layer ]
        ↓
[ MySQL Database ]
```

---

## 🎯 Key Features

### 🎫 Ticket Management

* Ticket lifecycle: Open → In Progress → Resolved → Closed
* Assign tickets to agents
* Organization-based access

### 💬 Comments System

* Public comments (Client + Agent)
* Internal notes (Agent only)
* Visibility controlled via API

### ⏱️ SLA Management

| Priority | Time |
| -------- | ---- |
| Pending  | 24h  |
| Low      | 24h  |
| Normal   | 12h  |
| High     | 6h   |
| Critical | 2h   |

**SLA Status:**

* On Track
* Due Soon
* Overdue

### 🔍 Filtering & Search

* Filter by: Organization, Status, Priority
* Search by: Title, Status, Priority

---

## 👥 Role-Based Access

### Agent

* Full access to all tickets
* Manage users & organizations
* Assign and update tickets

### Client

* Create tickets
* View organization tickets
* Add public comments

---

## 🛠 Tech Stack

* Laravel 10+
* MySQL
* Redis (Queue & Cache)
* Laravel Sanctum (Authentication)
* Spatie (Role & Permission)
* MVC + Service Layer + Repository Pattern

---

## 📡 API Base URL

```
http://localhost:8000/api
```

---

## 📡 API Overview

### 🔐 Authentication

* POST /api/login
* POST /api/register
* POST /api/logout

### 🏢 Organisations (Agent Only)

* GET /api/organisations
* POST /api/organisations
* PUT /api/organisations/{id}
* DELETE /api/organisations/{id}

### 🎫 Tickets

* GET /api/tickets
* POST /api/tickets
* PUT /api/tickets/{id}
* DELETE /api/tickets/{id}

### 💬 Comments

* GET /api/tickets/{ticket_id}/comments
* POST /api/tickets/{ticket_id}/comments

---

## 🗄 Database Design

### Tables

* Users
* Tickets
* Organizations
* TicketComments
* Priorities

### Relationships

* User → belongsTo Organization
* Organization → hasMany Users
* Ticket → belongsTo User
* User → hasMany Tickets
* Ticket → belongsTo Priority
* Ticket → hasMany Comments

---

## 🔐 Security

* Laravel Sanctum (Token-based authentication)
* Role-based authorization (Spatie)
* Password hashing (bcrypt)

---

## ⚙️ Setup Options

You can run this project in two ways:

### 🐳 Option 1: Docker (Recommended)

#### 🚀 Start Project

```bash
git clone https://github.com/Yin-Nyein-Aye/Support_Ticket_Portal_Backend
cd SupportTicketPortal
cp .env.example .env
docker-compose up -d --build
```

#### 🛠 Setup

```bash
docker exec -it support_ticket_portal_app composer install
docker exec -it support_ticket_portal_app php artisan migrate --seed
```

#### 🌐 Access

```
http://localhost:8000
```

---

### 🛠 Option 2: Manual Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## ⚙️ Environment Configuration

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_STORE=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis
```

---

## 🔧 Background Services

* Queue Worker (Redis)
* Scheduler (runs every minute)

---

## 🐳 Docker Services

* app (Laravel PHP-FPM)
* nginx (web server)
* mysql (database)
* redis (cache & queue)
* queue (job worker)
* scheduler (cron jobs)

---

## 🔧 Useful Commands

```bash
docker-compose down
docker-compose up -d
docker ps
docker exec -it support_ticket_portal_app bash
docker exec -it support_ticket_portal_app php artisan <command>
```

---

## ⚠️ Notes

* Do NOT use `php artisan serve` when using Docker
* Ensure ports 8000, 3306, 6379 are available
* For 504 errors → check containers with `docker ps`

---

## 📌 Assumptions

* Each user belongs to one organization
* SLA depends on ticket priority
* Agents manage ticket lifecycle

---

## ⏱ Timebox / Scope

**Estimated Time:** ~8 hours

### ✅ Implemented

* Authentication & roles
* Ticket lifecycle & SLA
* Comments system
* Filtering & search

---

## ⚖️ Trade-offs

* REST API instead of GraphQL (simplicity)
* Basic search instead of full-text search
* Limited test coverage

---

## 🚀 Future Improvements

* Full-text search (Elasticsearch)
* Real-time updates (WebSockets)
* Improved test coverage

---

## 🔗 Frontend Repository

👉 https://github.com/https://github.com/Yin-Nyein-Aye/Support_Ticket_Portal_Frontend
