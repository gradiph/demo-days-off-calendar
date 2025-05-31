# 🌴 Demo Days Off Calendar

A simple full-stack web application that lets users select and manage their day-off schedules, with admin approval and automated Slack status integration.

---

## 🛠 Tech Stack

- **Backend**: [Laravel 12](https://laravel.com/)
- **Frontend Build**: [Inertia.js](https://inertiajs.com/) + [Vue.js](https://vuejs.org/)
- **Database**: [MySQL 8.4](https://www.mysql.com/)
- **Package Manager**: Composer (PHP) & pnpm (Node.js)

---

## 📦 Features

### ✅ Day Off Management

- Users can select one or more days off via a calendar interface.
- Users can provide a short reason for each selected day.
- Admins can approve/reject submitted day-off requests.
- Overlapping date ranges are handled gracefully (e.g., merge or reject).
- System provides a nicely formatted summary of each user's days off.

### 🗓 Frontend Calendar UI

- Interactive calendar view for users.
- Click to select days and mark them as “Day Off”.
- Input form for adding reasons for absence.
- Real-time visual feedback.

### 🛠 Backend API

- Save selected dates and reasons to the database.
- Retrieve a list of all approved day-offs by user.
- Includes an API endpoint to summarize all current day-offs (formatted).

### 🔗 Slack Integration

- Scheduled daily job (~2 AM user time):
- Detects which users have marked the current day as off.
- Updates their Slack status with a custom emoji and message (e.g. `🌴 Day off: [reason]`).
- Optionally updates their Slack presence to `away`.

---

## 🚀 Getting Started (Local Setup)

### 1. Clone the Repository

```bash
git clone https://github.com/gradiph/demo-days-off-calendar.git
cd demo-days-off-calendar
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
pnpm install
```

### 4. Run Migrations and Seed the Database

```bash
php artisan migrate --seed
```

### 5. Compile Frontend Assets and Start Local Server

```bash
composer run dev
```

## 🌐 Application Endpoints

| Name       | URL                                                                                |
| ---------- | ---------------------------------------------------------------------------------- |
| Frontend   | [http://localhost:8000](http://localhost:8000)                                     |
| Swagger UI | [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation) |

## 🔐 Sample Credentials

- Email: `test@example.com`
- Password: `password`

## 📌 Notes

- Timezone-aware scheduling is implemented for Slack status updates.
- Frontend uses an Inertia.js approach with modern Vue.js.
- API is fully documented with Swagger (auto-generated from annotations).
- User tokens for Slack are stored securely for automated updates.