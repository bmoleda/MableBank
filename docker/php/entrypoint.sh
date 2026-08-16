#!/bin/sh
set -e

cd /MableBank

# Check for the dependencies and install if necessary:
if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
    echo "[entrypoint] vendor/ missing - running composer install..."
    composer install --no-interaction --prefer-dist --no-progress
fi

exec "$@"
