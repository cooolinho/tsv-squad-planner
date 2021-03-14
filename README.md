# TSV Squad Planner

## Setup

### 1. Docker laden

```
docker-compose build
docker-compose up -d
```

### 2. MySql Datenbank einrichten

```
php bin/console doctrine:scheme:create
```

oder

```
php bin/console doctrine:migrations:migrate
```

### 3. Demo-Daten einspielen

```
php bin/console doctrine:fixtures:load --no-interaction
```

### 4. TSV Squad Planner im Browser öffnen

> open [http://localhost]

### Problems

#### Debug Toolbar Error

``
composer require symfony/apache-pack
``

## [Admin-Dashboard]

```
E-Mail = admin@example.com
Passwort = secret
```

## [Trainer-Dashboard]

```
E-Mail = <a-g>-youth-trainer@example.com
Passwort = secret
```

#### Database Connection

> **Note:** look in .env - File

## DEV Tools

### [Adminer]

```
Benutzer = root
Passwort = db_root_password
```

### [Mailcatcher]

[http://localhost]: <http://localhost>

[Adminer]: <http://localhost:8080>

[Mailcatcher]: <http://localhost:1080>

[Admin-Dashboard]: <http://localhost/admin>

[Trainer-Dashboard]: <http://localhost/admin>
