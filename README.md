# Chirper - Learning Project

This sole purpose of this project is for me to gain a better understanding of Laravel. "Chirper" is a web app created in the Laravel Bootcamp (with Blade) - https://bootcamp.laravel.com/blade/installation. 

This initial commit is the end product of the tutorial - from here, I will be adding additional features.

# Features to add

- Landing page (instead of Laravel default page)
- Liking and comment features
- Follow users
- Populate dashboard (trending page?)
- Site moderator and admin roles
- Profile pictures
- Dark mode
- Image support
- User preferences/settings

# Useful commands

This project uses Sail (which uses docker + docker compose). Probably worth creating an alias in your terminal for "./vendor/bin/sail". 

./vendor/bin/sail up -d

./vendor/bin/sail php artisan migrate

./vendor/bin/sail npm run dev

./vendor/bin/sail down --volumes
