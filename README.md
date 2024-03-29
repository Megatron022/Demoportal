<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Portal
This Laravel project is a versatile web application featuring a robust admin panel that facilitates Create, Read, Update, and Delete (CRUD) operations. Designed for ease of use and flexibility, the admin panel empowers administrators to manage various aspects of the application effortlessly.

* **User Management**: Create, Read, Update, and Delete Users: Admins can manage user accounts with ease. CRUD operations for maintaining user data. Role-Based Access Control (RBAC): Assign roles to users for fine-grained access control. Define permissions to regulate user actions. User Activity Tracking: Monitor and log user activities.

* **Brand Management**: Create and Manage Brands Add new brands and manage existing ones. Organize products or items under specific brands. Brand Details and Image Upload: Capture essential brand information. Upload and manage brand logos or images.

* **Contact Management**: CRUD Operations for Contacts Maintain a comprehensive list of contacts. Easily add, edit, and delete contact information. Categorize Contacts: Organize contacts into categories for better segmentation. Streamline communication with targeted groups.

* **Category Management**: Dynamic Category Creation: Create and manage product or content categories. Easily adapt to changing organizational needs. Hierarchical Category Structure: Implement a nested category structure for better organization. Simplify navigation and content classification.

*  **Order Management**: The order management module seamlessly integrates into the system, enabling admins to perform CRUD operations on customer orders. It tracks and updates order statuses in real-time, notifying users of changes. The system supports secure payment processing, automated invoice generation, and shipping information management.

# Installation
These steps are common for all operating systems:

1. Clone the repository to your local machine:

	1. With HTTPS:

			git clone https://github.com/droghers-hub/portal.git

	2. With SSH:

			git clone git@github.com:droghers-hub/portal.git

2. Create a copy of the `.env.example` file and rename it to `.env`:

		cp .env.example .env

3. Generate the application key:

		php artisan key:generate

4. Run database migrations:

		php artisan migrate

5. Use artisan to serve the application:

		php artisan serve

# Technologies

1. For Windows:
	1. Install the latest version of [Laragon](https://laragon.org/download/index.html).
	2. Install the latest version of [Composer](https://getcomposer.org/download/).
	3. Install project dependencies using Composer:

		1. Install Dependencies

				composer install

		2. Update Dependencies

				composer update

2. For macOS:

	1. Install the latest version of [Homebrew](https://brew.sh/).

	2. Install MySQL server:

			brew install mysql

	3. Install PHP:

			brew install php

	4. Install Composer:

			brew install composer

2. For Linux:

	1. Update apt:

			sudo apt-get update

	2. Install MySQL server:

			sudo apt-get install mysql

	3. Install PHP:

			sudo apt-get install php

	4. Install Composer:

			sudo apt-get install composer

# Extentions
The extentions below must me enabled in `php.ini` file:

- `extension=curl`
- `extension=fileinfo`
- `extension=mysqli`
- `extension=openssl`
- `extension=pdo_mysql`

To find the `php.ini` file use these commands:

1. For Windows:

		php -r "echo php_ini_loaded_file();"

2. For macOS and Linux:

		php -i | grep "php.ini"

# About
Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects.

# License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).#   D e m o p o r t a l  
 