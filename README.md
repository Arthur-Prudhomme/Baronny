# Clone the repo

```bash
git clone git@github.com:Arthur-Prudhomme/Baronny.git
```

# Composer

```bash
composer install
```

# JWT

```bash
php bin/console lexik:jwt:generate-keypair
```

# Environment

create a .env.local and add inside

```bash
APP_SECRET=<app_secret>
DATABASE_URL="mysql://root@127.0.0.1:3306/<DB_name>?serverVersion=8&charset=utf8mb4"

JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=<passphrase>
```

# DB

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

# Start

```bash
symfony serve
```
