# MableBank

A PHP-based transfer reader and processor.

## Requirements

PHP 8.4 and Composer installed on your system, or Docker with Docker Compose.

## Setup and run

```sh
git clone git@github.com:bmoleda/MableBank.git MableBank
cd MableBank
composer install
```
or with Docker:
```sh
git clone git@github.com:bmoleda/MableBank.git MableBank
cd MableBank
docker compose up -d --build
```

## Try it out

In your project directory:
```sh
./mable <your_balances.csv> <your_transfers.csv>
```
or to process the example files that were provided:
```sh
./mable tests/Fixtures/mable_account_balances.csv tests/Fixtures/mable_transactions.csv
```

With Docker, prefix with `docker compose exec app`:
```sh
docker compose exec app ./mable tests/Fixtures/mable_account_balances.csv tests/Fixtures/mable_transactions.csv
```

### Run the tests

```sh
./vendor/bin/pest
```
or using Docker:
```sh
docker compose exec app ./vendor/bin/pest
```

Those run all tests (doesn't take long).
