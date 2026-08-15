#!/usr/bin/env bash

set -Eeuo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
ENV_FILE="${ENV_FILE:-.env}"

cd "${PROJECT_ROOT}"

if ! command -v docker >/dev/null 2>&1; then
	echo "[setup] Không tìm thấy Docker trong môi trường hiện tại." >&2
	exit 1
fi

if ! docker compose version >/dev/null 2>&1; then
	echo "[setup] Docker Compose chưa sẵn sàng." >&2
	exit 1
fi

if [[ ! -f "${ENV_FILE}" ]]; then
	cp .env.example "${ENV_FILE}"
	echo "[setup] Đã tạo ${ENV_FILE} từ .env.example."
fi

compose=(docker compose --env-file "${ENV_FILE}")

echo "[setup] Khởi động MariaDB, WordPress và HTTPS proxy..."
"${compose[@]}" up -d --build --wait --wait-timeout 120 db wordpress caddy

for _ in $(seq 1 60); do
	if [[ -f app/wp-config.php && -f app/wp-includes/version.php ]]; then
		break
	fi
	sleep 2
done

if [[ ! -f app/wp-config.php || ! -f app/wp-includes/version.php ]]; then
	echo "[setup] WordPress core chưa sẵn sàng sau 120 giây." >&2
	"${compose[@]}" logs --tail=80 wordpress >&2
	exit 1
fi

echo "[setup] Build WP-CLI..."
"${compose[@]}" build wpcli

echo "[setup] Cấu hình WordPress và WooCommerce..."
"${compose[@]}" run --rm --no-deps --entrypoint sh wpcli -ec '
	if wp core is-installed --skip-plugins --skip-themes; then
		echo "[setup] WordPress đã được cài, bỏ qua core install."
	else
		wp core install \
			--url="${WP_URL}" \
			--title="${WP_TITLE}" \
			--admin_user="${WP_ADMIN_USER}" \
			--admin_password="${WP_ADMIN_PASSWORD}" \
			--admin_email="${WP_ADMIN_EMAIL}" \
			--skip-email
	fi

	if wp plugin is-installed woocommerce --skip-plugins --skip-themes; then
		if wp plugin is-active woocommerce --skip-plugins --skip-themes; then
			echo "[setup] WooCommerce đã được kích hoạt."
		else
			wp plugin activate woocommerce --skip-plugins --skip-themes
		fi
	else
		wp plugin install woocommerce --version="${WOOCOMMERCE_VERSION}" --activate
	fi

	if ! wp theme is-installed nova-theme --skip-plugins --skip-themes; then
		echo "[setup] Không tìm thấy theme nova-theme trong wp-content/themes." >&2
		exit 1
	fi

	wp theme activate nova-theme --skip-plugins --skip-themes
	wp eval "
		if ( class_exists( \"WC_Install\" ) ) {
			WC_Install::create_pages();
		}

		update_option( \"blogname\", getenv( \"WP_TITLE\" ) );
		update_option( \"blogdescription\", getenv( \"WP_TAGLINE\" ) );
		update_option( \"timezone_string\", \"Asia/Ho_Chi_Minh\" );
		update_option( \"default_comment_status\", \"open\" );
		update_option( \"woocommerce_enable_reviews\", \"yes\" );
		update_option( \"woocommerce_coming_soon\", \"no\" );
		update_option( \"woocommerce_store_pages_only\", \"no\" );
		update_option( \"permalink_structure\", \"/%postname%/\" );
		flush_rewrite_rules( true );

		\$theme = wp_get_theme( \"nova-theme\" );
		printf(
			\"[setup] WordPress %s\\n[setup] WooCommerce %s\\n[setup] Theme %s\\n\",
			get_bloginfo( \"version\" ),
			defined( \"WC_VERSION\" ) ? WC_VERSION : \"unknown\",
			\$theme->get( \"Version\" )
		);
	"
	wp rewrite flush --hard
'

echo "[setup] Hoàn tất: website sẵn sàng tại URL đã cấu hình trong ${ENV_FILE}."
