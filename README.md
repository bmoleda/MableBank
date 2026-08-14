# MableBank

A PHP-based transfer reader and processor.

## Requirements

PHP 8.4 and Composer installed in your system.

## Setup and run

```sh
git clone git@github.com:bmoleda/MableBank.git MableBank
cd MableBank
composer install
```

## Try it out

In your project directory:
```sh
./mable <your_balances.csv> <your_transfers.csv>
```
or with example files:
```sh
./mable tests/Fixtures/mable_account_balances.csv tests/Fixtures/mable_transactions.csv
```

### Run the tests

```sh
./vendor/bin/pest
```

That runs all tests (doesn't take long).
