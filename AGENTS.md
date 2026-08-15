# AGENTS.md

## Project

Nova Store is a Docker Compose-based WordPress and WooCommerce storefront. It supports a hackathon demo that collects product reviews for an external analysis system.

Read `README.md` when setup commands, LAN HTTPS, or REST API usage are relevant. Keep this file concise because Codex loads it as project context.

## Sources of truth

- `docker-compose.yml`: services, volumes, ports, and environment wiring.
- `.env.example`: portable defaults; `.env` contains machine-specific values.
- `scripts/setup.sh`: installs WordPress and WooCommerce and activates the theme.
- `scripts/seed.sh` and `scripts/seed.php`: seed categories, products, and reviews.
- `app/wp-content/themes/nova-theme/`: project-owned theme.
- `app/wp-content/plugins/nova-review-bridge/`: reserved for the future review/webhook integration plugin.
- `docker/caddy/`: HTTPS reverse proxy; WordPress receives internal HTTP from Caddy.

## Working rules

- Communicate with the user in concise Vietnamese and explain current behavior before editing it.
- Inspect the actual source, configuration, and runtime path before drawing conclusions.
- Run Docker commands from WSL at `/mnt/d/Hackathon/nova-store`.
- Do not hardcode a machine's LAN IP in tracked files. Keep `localhost` in `.env.example` and machine-specific values in `.env`.
- Preserve existing worktree changes. Do not commit or push unless explicitly requested.
- Use `apply_patch` for manual file edits and keep changes inside the requested scope.

## Git and data boundaries

- Track only project-owned code. Do not add WordPress core, WooCommerce, third-party plugins, uploads, caches, or default themes to Git.
- Never commit `.env`, Consumer Keys or Secrets, database dumps, certificates, or private keys.
- Never run `docker compose down -v`, delete named volumes, reset the database, or run destructive seeds without explicit approval.
- WooCommerce REST API v3 already reads reviews at `/wp-json/wc/v3/products/reviews`; do not create a duplicate custom pull endpoint.
- Implement `nova-review-bridge` only when a webhook, review push, or unsupported custom contract is explicitly requested.

## Validation

Run the checks relevant to the change:

```bash
docker compose --env-file .env config --quiet
git diff --check
```

- Lint every changed PHP file with `php -l` locally or inside a container.
- For runtime changes, inspect `docker compose ps`, relevant service logs, and the HTTPS URL configured in `.env`.
- Report what was verified and what remains untested. Do not treat a single HTTP 200 response as proof that the complete flow works.
