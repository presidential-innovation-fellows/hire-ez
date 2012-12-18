## The Project

Building a platform that makes it easier for small high-growth businesses to navigate the federal government, and enables agencies to quickly source low-cost, high-impact information technology solutions.

## What is HIRE-EZ?

HIRE-EZ Is a demonstration application that demonstrates the flexibility of RFP-EZ. In this case, the demo is about recruiting and screening a round of fellows for a project.

## Setting up your development environment

##### 1. Grab the code
`git clone git://github.com/presidential-innovation-fellows/rfpez.git`

##### 2. Install dependencies
- Install Composer (http://getcomposer.org/)
- From the root project directory, run `composer install`
- Install node.js (http://nodejs.org/download/)
- In `assets/build`, run `npm install`

##### 3. Configure your local database
- Create an empty MySQL database named "rfpez" (or whatever your heart desires)
- Copy the `application/config/local_example` directory to `application/config/local` (which will be .gitignore'd)
- Enter your database credentials in `application/config/local/database.php`
- Install the Laravel migrations table: `php artisan migrate:install --env=local`
- Give yourself some seed data: `php artisan seed --env=local`

##### 4. Run the Grunt watcher
We use the awesome [Grunt](http://gruntjs.com/) to compile, concatenate, and minify our assets. We use Stylus instead of CSS, Coffeescript instead of Javascript, and Jade templates that compile to PHP.

Running the watcher is as easy as `cd assets/build; grunt watch`.

##### 5. You're good to go!
For further reading you can check out the [Laravel documentation](http://www.laravel.com/docs). We'll be updating this readme soon with more info on how you can contribute to the development of RFP-EZ.
