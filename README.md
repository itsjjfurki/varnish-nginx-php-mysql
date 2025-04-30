# Varnish Request Coalescing Demo

This is a simple Dockerized demo that showcases how [Varnish](https://varnish-cache.org/) handles **request coalescing**—a powerful feature that prevents your backend from being overwhelmed by simultaneous identical requests.

The setup includes:
- Nginx backend server pointing to PHP
- A Varnish container pointing to Nginx
- PHP 8.4 with a demo app
- MySQL
- wrk container to simulate concurrent requests

## 🔗 Read the full article

Learn more about request coalescing in the accompanying blog post:

👉 [Exploring Request Coalescing with Varnish](https://furkanozturk.dev/2025/05/01/exploring-request-coalescing-with-varnish/)

## 🚀 Getting Started

**1 - Clone the repo and run the demo with Docker:**

```bash
git clone https://github.com/itsjjfurki/varnish-nginx-php-mysql.git
cd varnish-nginx-php-mysql
docker-compose up --build
```

**2 - Auto-install:**

Wait for the app to build and also to seed the test data it needs

**3 - Verify the app is running**

Please visit http://localhost to ensure you see the hello message

## 💻 Commands to be used while testing

To run tests against http://localhost/without-varnish that is _**not protected**_ by Varnish:

```bash
docker compose run --rm wrk -t4 -c50 -d10s http://varnish/without-varnish
```

To run tests against http://localhost/with-varnish that is _**protected**_ by Varnish (make sure to purge Varnish cache first to emulate requests directly hitting the Nginx backend):

```bash
docker compose run --rm wrk -t4 -c50 -d10s http://varnish/with-varnish
```

To purge Varnish cache:

```bash
curl -X PURGE http://localhost/
```

## 🤔 How the test works?

The demo application creates a MySQL table named `employees` with the following columns:

- `id`: integer (primary key)
- `name`: string
- `bio`: text

It then **seeds the table with 50,000 records** to simulate a realistic dataset.

To create a performance bottleneck, the PHP backend executes a **`LIKE` query** on the `bio` column—something that typically causes a **full table scan** and is computationally expensive.

- When a **single request** hits the server, the query completes without issue.
- But when **multiple concurrent requests** are made, **MySQL slows down significantly**, as it struggles to handle repeated heavy queries in parallel.

This is where **Varnish’s request coalescing** comes in:

Instead of hammering the database with the same query repeatedly, **Varnish ensures only the first request reaches the backend**, while all others wait and receive the same response from the cache.

✅ **Result:** Drastically reduced backend load and significantly faster response times under pressure.