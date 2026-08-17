# MableBank

A PHP-based transfer reader and processor.

Please scroll down to see my comments on [the work](#my-work).

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

## My work

- I created a simple application in plain PHP, with a testing framework Pest.
- I spent about 4 hours on it, plus a bit of extra time for this documentation.
- Having experience with application frameworks (like Laravel or Symfony), I sorted my classes into models, services, immutable DTOs and business logic-independent helpers:
    - I modelled accounts and money to the extent necessary in this exercise. 
        - I used integer cents as the base value to represent money, so it's precise and easier to handle, as opposed to the floating-point type.
    - I created separate classes for reading the files and creating summaries to display, as well as data loader and data processor.
    - I passed dependencies into constructors, for easier testing and flexibility.
    - I implemented BaseAccount and MableAccount to add flexibility and to showcase inheritance.
    - Introduction of the data transfer objects and the enum helped with data consistency and validation.
- I deliberately didn't persist the data and didn't use an elaborate user interface, given the scope of the task.
- I used PHPDoc not only for documentation, but to tighten the types where PHP alone doesn't offer enough help. Those comments are used by code quality tools like PHPStan, Mess Detector etc. 
- I added a simple Docker setup for easier use on machines without PHP and Composer.
- I used an AI agent to setup a composer project and to add Docker. The models, structure, business logic, and the fixes to what AI set up, are mine.
