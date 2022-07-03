# Amazingzon

*Repository for student project 'Amazingzon', originally developed
in [this](https://github.com/Blo0dR0gue/PreAmazingzon) repository.*

This project is created as part of the 'Web Engineering 2' lecture in summer semester 2022 in the Applied Computer
Science course at DHBW Mannheim.
The aim is the creation of a web-shop, named *Amazingzon*.

![Home Page Image](assets/images/screenshot_page_home.png)<br>

## Getting Started

The project is designed to be executed in a local xampp installation or via Gitpod.
For setting up the MySQL database there is an `install.sql` file located in the project root.
To install the store, just copy the files and folders to the webroot.
Inside the `assets/config` folder are the configuration files. <br>
- The `config.php` file contains general configuration parameters.
  - If the store is not located inside the webroot at least the configuration `ROOT_PATH_OFFSET` inside the `config.php` need to be set.
- The `database_config.php` file contains the configuration for the database.

## Database installation via phpmyadmin
Step-by-step setup:
1. To set up the database login to your mysql server using phpmyadmin. 
2. Click on "Import" in the header.
3. Click on "Browse".
4. Select the `install.sql` file located in the project root.
5. Click on "Open".
6. Click on "OK".
7. The database with a default user will be created. (Database-Username: `amazingzon`, Password: `sh7up#KT!`)

## Features

Below is the list of agreed features, their implementation state and where to find them if necessary.

- [x] products
    - [x] categories
    - [x] price inclusive shipping cost
    - [x] description
    - [x] quantity / stock
    - [x] multiple images
    - [x] search
    - [x] ratings including comments
- [x] User area
    - [x] register
    - [x] login and logout
    - [x] change personal data e.g. email
    - [x] change password
    - [x] manage multiple delivery addresses
    - [x] order overview / history
    - [x] invoice generation (as pdf)
    - [x] shopping cart
- [x] admin area
    - [x] product management
        - [x] show & create products
        - [x] edit product e.g. change price, images, description, quantity
    - [x] user management
        - [x] deactivate or delete users
    - [x] category management
    - [x] order management
- [ ] ~~auctions~~
- [x] use of pagination (where appropriate)
- [x] modern design
- [x] logging system

## Technical Details

This project is based on `HTML`, `CSS`, `PHP`, `MySQL` and `Javascript`.

### Libraries and APIs

The following libraries or APIs are used in the project in advance to the above stated technologies:

* [jQuery](https://jquery.com/): for simplifying some JavaScript statements
* [Bootstrap](https://getbootstrap.com/) CSS & JS: for styling and GUI related things
* [Popper](https://popper.js.org/): for better tooltips (recommended by Bootstrap)
* [TCPDF](https://github.com/tecnickcom/tcpdf): PHP library for generating PDF documents on-the-fly

### Directory Structure

| Dir           | Description                                               |
|---------------|-----------------------------------------------------------|
| assets        | Other assets, e.g. config files, images, js and css.      |
| controller    | Controller skripts as part of the MVC.                    |
| include       | Scripts and elements included in pages.                   |
| model         | Model skripts as part of the MVC.                         |
| pages         | Sub pages of the website.                                 |
| `index.php`   | Main entry point of the website.                          |
| `install.sql` | File for setting up the MySQL database.                   |
| `README.md`   | Markdown file giving basic information about the project. |

### Database

The `MySQL` database is designed using [MySQL Workbench](https://www.mysql.com/de/products/workbench/), following this
diagram:
![Database Diagram Image](assets/images/database_design.png)

### Test Data

The project comes with some test data already inserted into the database by the `install.sql`-script.
Important values for testing are:

**Predefined Users:**

| Role        | Email/Username       | Password    |
|-------------|----------------------|-------------|
| normal user | user@user.de         | sh7up#KT!   |
| admin       | admin@admin.de       | sh7up#KT!   |
| normal user | tom.harris@gmail.com | pDGi5{7@    |

## Code Style

The Code Style is based on code styling of [PhpStorm](https://www.jetbrains.com/help/phpstorm/settings-code-style.html)
and the style guides of [SonarLint](https://www.sonarlint.org/).

## Screenshots

![Home Page Image](assets/images/screenshot_page_home.png)<br>
![Login Page Image](assets/images/screenshot_page_login.png)<br>
![Product Page Image](assets/images/screenshot_page_product.png)

## Authors

Project developed by [Daniel Czeschner](https://github.com/Blo0dR0gue)
and [Frederik Wolter](https://github.com/FrederikWolter).
