#!/bin/bash
#
# Deploy script for erreality.ru
#
# This script is executed by GitHub Actions via SSH.
# It exports the latest data from the production database,
# builds the Next.js frontend, and deploys it to the server.
#
# Usage: ./scripts/deploy.sh
#
# Required environment variables:
#   SSH_HOST        — SSH host
#   SSH_USER        — SSH user
#   SSH_KEY         — SSH private key
#   SSH_PORT        — SSH port (default: 22)
#   DEPLOY_PATH     — Path to the project root on the server
#   DB_HOST         — Database host
#   DB_PORT         — Database port
#   DB_NAME         — Database name
#   DB_USER         — Database user
#   DB_PASS         — Database password
#   APP_URL         — Application URL
#

set -euo pipefail

echo "🚀 Starting deployment..."

# Configuration
SSH_PORT="${SSH_PORT:-22}"
DEPLOY_PATH="${DEPLOY_PATH:-/home/erreality/public_html}"
FRONTEND_PATH="${DEPLOY_PATH}/frontend"
ADMIN_PATH="${DEPLOY_PATH}/admin"
SCRIPTS_PATH="${DEPLOY_PATH}/scripts"

# Step 1: Build the Next.js frontend locally
echo ""
echo "📦 Step 1: Building Next.js frontend..."
cd frontend

# Install dependencies
npm ci --omit=dev

# Build the static export
npm run build

echo "✅ Frontend build complete"

# Step 2: Deploy via SSH
echo ""
echo "📤 Step 2: Deploying to server..."

# Create a temporary directory for the deployment package
TEMP_DIR=$(mktemp -d)
trap 'rm -rf "$TEMP_DIR"' EXIT

# Copy the built frontend
cp -r out/* "$TEMP_DIR/"

# Copy admin panel files (excluding config.php which is server-specific)
rsync -avz --delete \
  -e "ssh -p ${SSH_PORT} -i ${SSH_KEY}" \
  --exclude 'config.php' \
  --exclude 'install.php' \
  ./admin/ "${SSH_USER}@${SSH_HOST}:${ADMIN_PATH}/"

# Copy scripts
rsync -avz --delete \
  -e "ssh -p ${SSH_PORT} -i ${SSH_KEY}" \
  ./scripts/ "${SSH_USER}@${SSH_HOST}:${SCRIPTS_PATH}/"

# Copy the built frontend
rsync -avz --delete \
  -e "ssh -p ${SSH_PORT} -i ${SSH_KEY}" \
  "$TEMP_DIR/" "${SSH_USER}@${SSH_HOST}:${FRONTEND_PATH}/"

# Copy root .htaccess
scp -P "${SSH_PORT}" -i "${SSH_KEY}" \
  .htaccess "${SSH_USER}@${SSH_HOST}:${DEPLOY_PATH}/.htaccess"

echo "✅ Files deployed"

# Step 3: Run database export on the server
echo ""
echo "🗄️  Step 3: Exporting database to JSON..."
ssh -p "${SSH_PORT}" -i "${SSH_KEY}" "${SSH_USER}@${SSH_HOST}" \
  "cd ${DEPLOY_PATH} && php ${SCRIPTS_PATH}/export-json.php"

echo "✅ Database export complete"

# Step 4: Rebuild frontend with fresh data
echo ""
echo "📦 Step 4: Rebuilding frontend with fresh data..."
cd frontend

# Rebuild with the latest JSON data
npm run build

# Deploy the rebuilt frontend
rsync -avz --delete \
  -e "ssh -p ${SSH_PORT} -i ${SSH_KEY}" \
  out/ "${SSH_USER}@${SSH_HOST}:${FRONTEND_PATH}/"

echo "✅ Frontend rebuild and deploy complete"

echo ""
echo "🎉 Deployment successful!"
echo "   Site: https://erreality.ru"
echo "   Admin: https://erreality.ru/admin/"