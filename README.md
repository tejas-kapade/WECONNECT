# WeConnect 💬

> A live, anonymous group chat web application built with PHP, MySQL, HTML/CSS, and vanilla JavaScript.

---

## Features

- **Anonymous chat rooms** — join or create "pools" (chat rooms) without revealing your identity
- **Real-time messaging** — lightweight polling keeps messages fresh
- **User authentication** — session-based login and registration
- **Admin panel** — manage users and pools
- **Responsive UI** — works on desktop and mobile

---

## Tech Stack

| Layer      | Technology              |
|------------|-------------------------|
| Frontend   | HTML5, CSS3, JavaScript |
| Backend    | PHP 8+                  |
| Database   | MySQL / MariaDB         |
| Sessions   | PHP native sessions     |

---

## Project Structure

```
.
├── Dockerfile                        # App image definition
├── .dockerignore                     # Files excluded from the image
├── docker-compose.yml                # Local dev stack
├── wecondb_bak.sql                   # Database schema backup
├── app/                              # Application source code
│   ├── index.html                    # Main page
│   ├── *.php                         # PHP scripts
│   ├── *.js                          # JavaScript files
│   └── *.css                         # Stylesheets
├── scripts/                          # Setup and utility scripts
│   ├── weconnect-setup.sh            # Setup script
│   └── weconnect-setup-updated.sh    # Updated setup script
├── docker/
│   ├── apache-weconnect.conf         # Apache virtual host config
│   └── php.ini                       # PHP runtime settings
└── k8s/
    ├── 00-namespace.yaml             # Namespace: weconnect
    ├── 01-secret.yaml                # DB + admin credentials
    ├── 02-pvc.yaml                   # 5 Gi persistent volume for MariaDB
    ├── 03-configmap.yaml             # DB schema SQL (init on first boot)
    ├── 04-db-statefulset.yaml        # MariaDB StatefulSet + headless Service
    ├── 05-app-deployment.yaml        # PHP/Apache Deployment + ClusterIP Service
    ├── 06-ingress.yaml               # Ingress (nginx) — HTTP/HTTPS routing
    ├── 07-hpa.yaml                   # Horizontal Pod Autoscaler
    └── 08-network-policy.yaml        # NetworkPolicies (least-privilege)
```

---

## Getting Started

### 1. Requirements

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.4+
- Apache or Nginx with mod_rewrite enabled

### 2. Database Setup

```bash
mysql -u root -p < wecondb_bak.sql
```

### 3. Environment Variables

Set your DB password and admin password as environment variables instead of hardcoding them:

```bash
export WECON_DB_PASS="your_db_password"
export WECON_ADMIN_PASS="your_admin_password"
```

Or configure them in your web server's virtual host / `.env` file.

### 4. Deploy

Copy all files to your web server's document root (e.g., `/var/www/html/weconnect/`) and ensure PHP has read/write access.

---

## Security Notes

> ⚠️ This project is a learning/demo application. Before deploying to production:

- **Hash passwords** — replace plain-text storage with `password_hash()` / `password_verify()`
- **Use HTTPS** — always serve over TLS
- **Remove `test.php`** — never expose debug/test files in production
- **Rotate credentials** — never commit real passwords to version control
- **Rate limiting** — add rate limiting to auth endpoints to prevent brute force
- **CSRF protection** — add CSRF tokens to state-changing requests

---

## API Endpoints

| Method | Endpoint            | Description                        | Auth Required |
|--------|---------------------|------------------------------------|---------------|
| POST   | `query.php`         | Register a new user                | No            |
| POST   | `login.php`         | Authenticate a user                | No            |
| GET    | `logout.php`        | Destroy session                    | Yes           |
| POST   | `create_pool.php`   | Create a new chat pool             | Yes           |
| POST   | `join_pool.php`     | Join an existing pool              | Yes           |
| POST   | `send_message.php`  | Send a message to the current pool | Yes           |
| GET    | `get_messages.php`  | Get messages (`?since=<id>`)       | Yes           |
| POST   | `delete_pool.php`   | Delete a pool                      | Admin only    |

---

## License

MIT — feel free to use and adapt for your own projects.
