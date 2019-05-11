# How to run ?

1. Pull repo from remote repo
   "git clone git@github.com:heduo/atdw.git"
2. Enter into

Build and run docker containers
Command: "docker-compose run up -d"
Every time you make a change in .env, run commmand:php artisan config:cache to make the change available.

2.

when show log permission errors
php artisan optimize:clear
