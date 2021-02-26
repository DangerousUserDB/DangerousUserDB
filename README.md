# DangerousUserDB

Keep your eyes on bad discord users.

https://discord.riverside.rocks

## Screenshots

(none right now, the website is up if you would like to try it out)

## You will need:

- Apache2 (you can run this on Nginx, but our main instance runs Apache)
- PHP 7 or higher (our main instance runs on PHP 7.3)
- MySQL

## Getting Started

After cloning the repo, copy the env.example file to the .env file. Make sure that this is blocked from the internet! (Cloudflare is the best way to do this). **If you ignore this step, your tokens and passwords will be on the Internet!** Fill out the needed details. You will need to create an app at Discord: 

https://discord.com/developers/applications

Next, install all dependencies. You can do this my running:

`composer install`

If you do not have composer, you get download it here:

https://getcomposer.org

Now, create a `history` folder by running `mkdir history`

Finally, set your tables. Be sure that these are under the `discord` database. You can always change this in the .env file.

```
SOURCE installers/1.sql;
SOURCE installers/2.sql;
SOURCE installers/3.sql;
```

You are all set! Happy reporting!

## Terminology

### Abuse Score

The score based upon the number of reports a user recives.

### Total Reports

The total number of reports filed against a user.

### Reports

The total number of users that have reported an account.

## API

### See /docs/index.php on your instance


## Steps taken to prevent abuse

Recently we changed up how we count reports. In an older version of this software, we counted the abuse score by the number of reports. If there where three reports by anonymous users and one report by Riverside Rocks, we would count four reports, and give the account in question an abuse score of ~40%.

The issue with the old system was it was prone to abuse. For example, an anonymous user could file 10 reports and easily give the account in question an abuse score of 100%. In the new system, this was fixed by counting reports by unique user. 3 anonymous reports and 1 report from a signed in user now counts as 2 reports and would give an abuse score of ~20%.
