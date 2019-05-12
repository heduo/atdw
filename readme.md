# How to run ?

1. Download repo from github
    - Run command: "git clone git@github.com:heduo/atdw.git"
2. Enter into project folder 'atdw'
    - Run command: "cd atdw"
3. Install php packages
    - Run command: "composer install"
4. Install npm packages
    - Run command: "npm install"
5. Create '.env' file and copay '.env.example' content to '.env' file
    - You can even change '.env.example' to '.env', because '.env.example' has all the correct config info to run already
    - Every time you make a change in .env, run commmand "php artisan config:cache" to make the change available.
6. Build and run docker containers
    - Run command: "docker-compose run up -d"
7. Build Vue code (optional)
    - Run command: "npm run prod"
8. Open a browser, and visit 'localhost'

# UI design

-   user select a region
-   when region is selected, areas options belong to that region would be created
-   user select a area
-   user input search text in search bar
-   user click search icon to filter the accommadations/products

# Possible problems

1. when show problem similar to 'log permission errors'
    - Run command 'php artisan optimize:clear'

# How to stop running containers ?

Run command 'docker-composer down'
