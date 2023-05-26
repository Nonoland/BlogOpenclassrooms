# BlogOpenclassrooms
Blog made as a project for OpenClassrooms. It works with PHP 8.2 and MariaDB 10.3.

## Installation & Configuration

The project can be deployed with git. To do this you need to clone the repository.
```shell
git clone https://github.com/Nonoland/BlogOpenclassrooms.git
```
Then you have to install the project dependencies.
```shell
composer install
```
Before configuring the environment variables, make sure to create the database. The SQL file for creating the database can be found in the sql folder. Import this file into your database manager.

Finally, you must configure the .env file with the environment variables that will be used to run the blog.
```dotenv
DB_HOSTNAME=
DB_USERNAME=
DB_PASSWORD=
DB_NAME=
MODE=DEV
DOMAIN=
BLOG_PATH=
IMAGE_POST_PATH=
MAIL_ADDRESS=
MAIL_SMTP_HOSTNAME=
MAIL_SMTP_USERNAME=
MAIL_SMTP_PASSWORD=
MAIL_SMTP_SECURE=
MAIL_SMTP_PORT=
FORGOTTEN_PASSWORD_DELAY=5
```
Your application is now ready to use.

## Usage

After successfully installing and configuring the project, you can start to use it.
Starting the Server

To start the server, if you're using Apache or similar, ensure it's running and then navigate to the project directory in your browser using the localhost address.

## Admin Panel

To access the admin panel, append /admin to the base url. You will then be prompted to log in with the admin credentials.

## License

This project is licensed under the GNU License. See the LICENSE file for more details.

## Contact

If you encounter any problems or have any suggestions, please open an issue or contact the project maintainers directly.
