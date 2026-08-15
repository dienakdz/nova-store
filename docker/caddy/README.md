# Local HTTPS

Caddy terminates HTTPS on the host and proxies requests to the WordPress
container over Docker's internal network.

Start the stack:

```bash
docker compose up -d --build
```

Open the site at `https://localhost:8443`.
Requests to `http://localhost:8080` are redirected to the HTTPS URL.

The certificate is issued by Caddy's local CA. For a quick Postman test, turn
off **Settings > General > SSL certificate verification**. Keep verification
enabled for real environments using a publicly trusted certificate.

## Access from another machine on the LAN

Set the local `.env` values to the Windows LAN address:

```dotenv
HTTPS_HOST=192.168.1.10
WP_URL=https://192.168.1.10:8443
```

Recreate WordPress and Caddy, then run PowerShell as Administrator:

```powershell
.\scripts\expose-https-lan.ps1 -LanAddress 192.168.1.10
```

The script forwards only TCP port `8443` to WSL and creates a Private-profile
Windows Firewall rule. WSL's IP may change after restart, so run the script
again when LAN access stops working.

WooCommerce REST API v3 can then use Basic Auth:

```text
GET https://localhost:8443/wp-json/wc/v3/products/reviews
Username: WooCommerce consumer key (ck_...)
Password: WooCommerce consumer secret (cs_...)
```
