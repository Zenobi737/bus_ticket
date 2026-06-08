# bus_ticket @kadefue@yahoo.co.uk
a bus ticket developed by ZENOBI YAMET with reg no 14325122/T.24
# Mzumbe University Bus Ticket System
### *A Case Study in Secure, Layered Web Architecture (CSS 221)*

Welcome to the official repository for the **Mzumbe University Bus Ticket System**. This web application serves as a complete implementation of a database-backed, dynamic transactional platform. It demonstrates how to decouple concerns cleanly across structural, presentation, behavioral, and server-side programmatic boundaries.

---

## 📑 Table of Contents
1. [Architectural Blueprint](#-architectural-blueprint)
2. [Security Framework](#-security-framework)
3. [System Prerequisites](#-system-prerequisites)
4. [Directory & Files Structure](#-directory--files-structure)
5. [Installation & Deployment Guide](#-installation--deployment-guide)
6. [Core Technical Implementations](#-core-technical-implementations)

---

## 🏗 Architectural Blueprint

Following the explicit guidelines taught in your lecture modules, this application rejects monolithic designs in favor of the **Separation of Concerns (SoC)** principle:

* **The Structural Layer (`index.php` / XHTML):** Built using strict XHTML markup standards to enforce clean, predictable DOM mapping that can be securely traversed by native engines.
* **The Presentation Layer (CSS Embedded):** Implements typography stacks (`Arial, Helvetica, sans-serif`) and leverages localized university branding rules, utilizing precise hexadecimal (`#FFCC00` for Mzumbe Gold) and functional rgb definitions (`rgb(0, 102, 51)` for Mzumbe Green).
* **The Behavioral Layer (jQuery & AJAX):** Shifts the application from the traditional "Click-and-Wait" synchronous paradigm into an asynchronous framework. By intercepting user submit hooks via `preventDefault()`, payload strings are dispatched silently in the background, updating views dynamically via JSON without forcing browser refreshes or screen flickering.
* **The Server-Side Engine (`process_booking.php` & PDO):** A persistent background engine written in PHP that acts as the secure intermediary between the client's transient inputs and the central relational data storage engine.

---

## 🛡 Security Framework

This system implements defensive software engineering constraints to prevent exploitation:

1. **SQL Injection (SQLi) Elimination:** Raw user-supplied criteria are never directly concatenated into database query strings. The application utilizes native **PDO Prepared Statements** with explicit type parameters, neutralizing structural manipulation.
2. **Cross-Site Scripting (XXSS) Mitigation:** To defend the DOM from malicious scripts, all user outputs rendered back onto the browser are sanitized through the application of `htmlspecialchars()`.
3. **Concurrency Control (Race Conditions):** To handle simultaneous booking requests for the same remaining seats, the processing script implements **ACID-compliant Database Transactions** wrapped within explicit `FOR UPDATE` row-locking parameters. This ensures accurate updates even under concurrent load.
4. **Error Silencing / Exception Masking:** Database connectivity execution loops are isolated inside `try...catch` blocks. If an unhandled infrastructure exception triggers, detailed error stack outputs are intentionally suppressed and replaced with clean system failure messages to hide server path information from end-users.

---

## 💻 System Prerequisites

Before initializing this application, verify your localized environment hosts the following dependency stacks (standardized within tools like **XAMPP**):

* **Web Server Engine:** Apache 2.4+
* **Server Scripting Interpreter:** PHP 7.4 or PHP 8.x+
* **Database Management System:** MySQL / MariaDB 10.4+
* **Client Network Requirements:** Active Internet Connection (Required to load the Google Hosted jQuery CDN framework securely).

---

## 📁 Directory & Files Structure

Maintain the structural map precisely as shown below inside your local web root directory:

```text
C:\xampp\htdocs\bus_system\
│
├── database.sql           # Schema definitions, table relationships, and seed data
├── db_connect.php         # Secure database connection instantiator via isolated PDO
├── index.php              # Strict XHTML structural markup & jQuery behavioural logic
├── fetch_schedules.php    # Server-side asynchronous endpoint providing dynamic table feeds
└── process_booking.php    # Core transactional processor (Validates, Sanitizes, and Books)
