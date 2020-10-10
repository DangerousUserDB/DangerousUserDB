# DangerousUserDB

Keep your eyes on bad discord users.

https://discord.riverside.rocks

## You will need:

- Apache2 (you can run this on Nginx, but our main instance runs Apache)
- PHP 7 or higher (our main instance runs on PHP 7.3)
- MySQL

## Getting Started

After cloning the repo, copy the env.example file to the .env file. Make sure that this is blocked from the internet! (Cloudflare is the best way to do this). Fill out the needed details. You will need to create an app at Discord: 

https://discord.com/developers/applications

Next, install all dependencies. You can do this my running:

`composer install`

If you do not have composer, you get download it here:

https://getcomposer.org

You are all set! Happy reporting!