#!/usr/bin/env bash

set -Eeuo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
ENV_FILE="${ENV_FILE:-.env}"

cd "${PROJECT_ROOT}"

if ! command -v docker >/dev/null 2>&1; then
	echo "[seed] Không tìm thấy Docker trong môi trường hiện tại." >&2
	exit 1
fi

if ! docker compose version >/dev/null 2>&1; then
	echo "[seed] Docker Compose chưa sẵn sàng." >&2
	exit 1
fi

if [[ ! -f "${ENV_FILE}" ]]; then
	cp .env.example "${ENV_FILE}"
	echo "[seed] Đã tạo ${ENV_FILE} từ .env.example."
fi

compose=(docker compose --env-file "${ENV_FILE}")

echo "[seed] Khởi động MariaDB và WordPress..."
"${compose[@]}" up -d --wait --wait-timeout 120 db wordpress

echo "[seed] Chuẩn bị WP-CLI..."
"${compose[@]}" build wpcli

echo "[seed] Tạo dữ liệu demo Nova Store..."
"${compose[@]}" run --rm --no-deps --entrypoint sh wpcli -ec '
	if ! wp core is-installed --skip-plugins --skip-themes; then
		echo "[seed] WordPress chưa được cài. Hãy chạy: bash scripts/setup.sh" >&2
		exit 1
	fi

	if ! wp plugin is-active woocommerce --skip-plugins --skip-themes; then
		echo "[seed] WooCommerce chưa được kích hoạt. Hãy chạy: bash scripts/setup.sh" >&2
		exit 1
	fi

	wp eval-file /project/scripts/seed.php
'

echo "[seed] Hoàn tất. Có thể chạy lại script mà không tạo dữ liệu trùng."
