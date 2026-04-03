# 📌 Support Ticket Portal

## 📖 Overview

The Support Ticket Portal is a web-based **B2B ticket management system** designed to help organizations efficiently manage customer support requests.

It provides **role-based access** for Agents and Clients, enabling:

- Ticket creation & tracking
- SLA monitoring
- Communication through comments

---

## 🎯 Key Features

### 🎫 Ticket Management

- Create, update, and track tickets
- Ticket lifecycle: Open → In Progress → Resolved → Closed
- Assign tickets to agents

### 💬 Comments System

- Public comments (Client + Agent)
- Internal notes (Agent only)
- Visibility control enforced via API

### ⏱️ SLA Management

- Pending → 24 hrs
- Low → 24 hrs
- Normal → 12 hrs
- High → 6 hrs
- Critical → 2 hrs

**SLA Status:**

- On Track
- Due Soon
- Overdue

---

## 👥 Role-Based Access

### Agent

- Full access to all tickets
- Manage organizations and users
- Assign and update tickets

### Client

- Create tickets
- View organization tickets
- Add public comments

---

## 🔍 Filtering & Search

- Filter by:
    - Organization
    - Status
    - Priority
- Search by:
    - Title
    - Status
    - Priority

---

## 🧩 Architecture Diagram

[ React Frontend (SPA) ]
│
▼
[ REST API Layer ]
(Laravel Backend)
│
▼
[ Service Layer ]
│
▼
[ Repository Layer ]
│
▼
[ MySQL DB ]

---

## 🖥 Tech Stack

### Frontend

- React (SPA)
- React Router
- Axios
- Tailwind CSS
- React Query

### Backend

- Laravel 10+
- MVC Architecture
- Repository Pattern
- Service Layer
- Laravel Sanctum (Auth)
- Spatie (RBAC)

### Database

- MySQL

---

## 📡 API Overview

### 🔐 Authentication

- POST /api/login
- POST /api/register
- POST /api/logout

### 🏢 Organisations (Agent Only)

- GET /api/organisations
- POST /api/organisations
- PUT /api/organisations/{id}
- DELETE /api/organisations/{id}

### 🎫 Tickets

- GET /api/tickets
- POST /api/tickets
- PUT /api/tickets/{id}
- DELETE /api/tickets/{id}

### 💬 Comments

- GET /api/tickets/{ticket_id}/comments
- POST /api/tickets/{ticket_id}/comments

---

## 🗄 Database Design

### Main Tables

- Users
- Tickets
- Organizations
- TicketComments
- Priorities

### Relationships

- User → belongsTo Organization
- Organization → hasMany Users
- Ticket → belongsTo User
- User → hasMany Tickets
- Ticket → belongsTo Priority
- Priority → hasMany Ticket
- Ticket → hasMany Comments
- Comment → belongsTo Ticket

---

## 🔐 Security

- Laravel Sanctum (Token-based auth)
- Role-based authorization (Spatie)
- Password hashing (bcrypt)

---

## 🚀 Deployment

### Development

- Frontend: Vite
- Backend: Laravel

### Production

- Frontend: Vercel / Netlify
- Backend: Nginx / Apache
- Database: MySQL

---

## 🔐 Middleware & Access Control

- auth:sanctum → Auth required
- role:agent → Agent only
- role:client|agent → Both roles

---

## 📌 Assumptions

- Each user belongs to one organization
- SLA depends on ticket priority
- Agents manage ticket lifecycle

---

## ⏱ Timebox / Scope

**Estimated Time:** ~8 hours

### ✅ Implemented

- Roles & authentication
- Ticket lifecycle & SLA
- Client & agent views
- CRUD for tickets & comments
- Partial filter

## ⚖️ Trade-offs

Due to time constraints (~8 hours), the following decisions were made:

- Used simple search instead of full-text search to reduce complexity
- Limited automated testing coverage to focus on core features
- Chose REST API over GraphQL for simplicity and familiarity

### Future Improvements

- Add full-text search (MySQL / Elasticsearch)
- Improve test coverage (Feature & Unit tests)
- Add real-time updates (WebSockets)

---

## ⚙️ Setup Instructions

### Backend

```bash
composer install
php artisan migrate --seed
php artisan serve
```

### Frontend

```bash
npm install
npm run dev
```

🌐 Access
Frontend: http://localhost:5173
Backend: http://localhost:8000/api
