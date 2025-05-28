# Adik Cosmetics E-Commerce Platform 💄🛒

This is a full-featured e-commerce web application developed as part of my Final Year Project at Universiti Tun Hussein Onn Malaysia (UTHM). The platform is designed for a local cosmetics brand based in Kelantan, featuring admin and user functionality, payment integration, and AI-powered chatbot support.

---

## ✨ Key Features

- 🔐 User Registration & Login (Firestore Authentication)
- 🛍️ Product Catalogue (Search, Filter, Categories)
- 🛒 Shopping Cart with Live Updates
- 💳 Payment Integration using ToyyibPay
- 🤖 AI Chatbot (GPT-4) for customer queries
- 📦 Order Management (View, History, Admin Control)
- 📊 Sales Report Generator for Admin
- 📄 Invoice Generation (PDF)
- 📢 Ad Management Module (Admin)

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** Laravel (PHP)
- **Database:** Firebase Firestore (NoSQL)
- **APIs:** RESTful APIs, OpenAI (GPT-4)
- **Payment Gateway:** ToyyibPay
- **Tools:** Visual Studio Code, GitHub, Postman

```bash
git clone https://github.com/mikaaee/Adik-Cosmetics-E-commerce.git
cd Adik-Cosmetics-E-commerce
composer install
php artisan migrate
php artisan serve
