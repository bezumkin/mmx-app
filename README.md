mmxApp
---
Template for creating a new mmx application.

> This extra is part of **MMX** initiative - the **M**odern **M**OD**X** approach.

### Quick start

The default name of extra is `mmx-app` so first you need to rename it with built-in script:
```sh
cp .env.dist .env
./run-rename.py
```

Script will rename all occurrences in source files, including `.env` and `docker-compose.yml`.

Now you can start Docker:
```sh
docker compose up --build -d
```

Install MODX with adding new extra inside system:
```sh
./run-install.sh
```

Enter http://127.0.0.1:8080/manager with login `admin` and password `adminadmin`.