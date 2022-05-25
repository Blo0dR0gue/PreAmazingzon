# (Pre-) Amazingzon

Repository for student project "Amazingzon".
The projekt is developed in [this](https://github.com/Blo0dR0gue/PreAmazingzon) repository.

This project is created as part of the 'Web Engineering 2' lecture in summer semester 2022 in the Applied Computer
Science course at DHBW Mannheim.
The aim is the creation of a simple web-shop, named *Amazingzon*.

## Authors

Project developed by:

* [Daniel Czeschner](https://github.com/Blo0dR0gue)
* [Frederik Wolter](https://github.com/FrederikWolter)

## Getting Started

The project is designed to be executed in a local xampp installation or via GitPot.
For setting up the MySQL database there is an 'install.sql' file located in the project root.

## Technical Details

This project is based on HTML, CSS, PHP, Javascript

### Libraries/APIs

The following libraries or APIs are used in the project:

* [jQuery](https://jquery.com/): for simplifying some JavaScript statements
* [Bootstrap](https://getbootstrap.com/) CSS & (JS): for styling and GUI related things
* [Popper](https://popper.js.org/): for better tooltips (recommended by Bootstrap)
* [TCPDF](https://github.com/tecnickcom/tcpdf): PHP library for generating PDF documents on-the-fly

### Dir-Structure

| Dir         | Description                                            |
|-------------|--------------------------------------------------------|
| assets      | Other assets , e. g. config files, images, js and css. |
| controller  | Controller skripts as part of the MVC.                 |
| include     | Scripts and elements included in pages.                |
| model       | Model skripts as part of the MVC.                      |
| pages       | Sub pages of the website.                              |
| index.php   | Main entry point of the website.                       |
| install.sql | File for setting up the MySQL database.                |
| README.md   | Markdown file giving basic information to the project. |

### Database

Following the design of the database using [MySQL Workbench](https://www.mysql.com/de/products/workbench/):
![](assets/images/database_design.png)

### Test Data

The project comes with some test data already inserted into the database by the install-sql-script.
Important values are:

#### predefined Users:

| Role        | Email/Username | Password   |
|-------------|----------------|------------|
| normal user | user@user.de   | sh7up#KT!  |
| admin       | admin@admin.de | sh7up#KT!  |
