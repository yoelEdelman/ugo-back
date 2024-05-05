## Getting Started
First, install dependencies:

```bash
composer install
```
Then, run the php server:

```bash
symfony server:start
```

Then, create database:

```bash
php bin/console doctrine:database:create
```

Then, run migration:

```bash
php bin/console doctrine:schema:update --force
```

Then, run the famous command:

```bash
php bin/console ugo:orders:import
```


Open [http://127.0.0.1:8000](http://127.0.0.1:8000) with your browser to see the result.
