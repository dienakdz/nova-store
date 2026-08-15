# Nova Store

Nova Store is a WordPress and WooCommerce fashion storefront built for a hackathon demo. Its main purpose is to provide products and customer reviews that can be collected by an external analysis system.

## Tech stack

- WordPress 7.0.2 with PHP 8.3 and Apache
- WooCommerce 11.0.1
- MariaDB 11.4
- Caddy as the local HTTPS reverse proxy
- WP-CLI for automated setup and demo data
- Docker Compose for a reproducible team environment

## Quick start

Run Docker commands from WSL:

```bash
cd /mnt/d/Hackathon/nova-store
cp .env.example .env
bash scripts/setup.sh
bash scripts/seed.sh
```

- Storefront: `https://localhost:8443`
- WordPress Admin: `https://localhost:8443/wp-admin`
- HTTP requests to `http://localhost:8080` redirect to HTTPS.
- DBeaver: connect to `localhost:3308` with the database credentials from `.env`.

`setup.sh` installs WordPress and WooCommerce, then activates `nova-theme`. `seed.sh` creates 3 product categories, 6 products, and 12 reviews. The seed can be run again without duplicating data created by the script.

Caddy uses a local certificate authority, so browsers may show a certificate warning. For local Postman testing, SSL certificate verification can be disabled. Keep certificate verification enabled in real environments.

## HTTPS access from another LAN device

Set the Windows host's LAN address in `.env`:

```dotenv
HTTPS_HOST=192.168.1.10
WP_URL=https://192.168.1.10:8443
```

Recreate WordPress and Caddy from WSL:

```bash
docker compose --env-file .env up -d --force-recreate wordpress caddy
```

Then open PowerShell as Administrator in the project directory:

```powershell
.\scripts\expose-https-lan.ps1 -LanAddress 192.168.1.10
```

The script forwards TCP port `8443` from Windows to WSL and creates a Private-profile Windows Firewall rule. WSL's IP address may change after a restart, so run the script again if LAN access stops working. See [docker/caddy/README.md](docker/caddy/README.md) for more details.

## Retrieve reviews with the WooCommerce REST API

Create an API key in **WooCommerce > Settings > Advanced > REST API** with **Read** permission.

```http
GET https://localhost:8443/wp-json/wc/v3/products/reviews?status=approved&per_page=100
```

Use **Basic Auth** in Postman:

- Username: Consumer Key, starting with `ck_`
- Password: Consumer Secret, starting with `cs_`

Optional filters include `product=<id>`, `after=<ISO-8601>`, `page`, and `per_page`. Refer to the [WooCommerce Product Reviews API](https://developer.woocommerce.com/docs/apis/rest-api/v3/product-reviews) and [authentication guide](https://developer.woocommerce.com/docs/apis/rest-api/authentication).

Never commit a Consumer Secret to source code, documentation, screenshots, or a shared Postman collection.

## Useful commands

```bash
# Check container status
docker compose ps

# Follow WordPress and Caddy logs
docker compose logs -f wordpress caddy

# Run a WP-CLI command
docker compose run --rm wpcli wp option get home

# Stop containers while preserving data
docker compose down
```

Do not run `docker compose down -v` unless you intentionally want to delete the database and Caddy data stored in Docker volumes.

## Project structure

```text
nova-store/
├── app/                         # WordPress runtime source
│   └── wp-content/
│       ├── themes/nova-theme/   # Project-owned theme
│       └── plugins/             # Local and third-party plugins
├── docker/
│   ├── caddy/                   # HTTPS reverse proxy
│   ├── wordpress/               # WordPress image and PHP configuration
│   └── wpcli/                   # WP-CLI image
├── scripts/
│   ├── setup.sh                 # Install and configure the stack
│   ├── seed.sh                  # Run the seed through WP-CLI
│   ├── seed.php                 # Demo categories, products, and reviews
│   └── expose-https-lan.ps1     # Forward Windows HTTPS traffic to WSL
├── .env.example                 # Safe, portable configuration template
└── docker-compose.yml
```

WordPress core, WooCommerce, uploads, and caches remain under `app/` at runtime but are ignored by Git because they can be recreated. Git tracks only the project-owned `nova-theme` and the future `nova-review-bridge` plugin.

## Safety rules

- Do not commit `.env`, API secrets, database dumps, or private certificate keys.
- Do not modify WordPress core or WooCommerce source directly.
- Do not delete Docker volumes or destructively reset/seed data without confirming the exact scope.
- Do not commit or push changes unless explicitly requested.
