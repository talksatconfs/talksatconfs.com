# talksatconfs.com

The source code for [talksatconfs.com](https://talksatconfs.com)

## Requirements

The following tools are required in order to start the installation.

- PHP 8.1
- [Composer](https://getcomposer.org/download/)
- [NPM](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

## Setup
1. Clone this repository `git clone git@github.com:talksatconfs/talksatconfs.com.git`
2. Copy the `.env.example` to `.env`
3. Install the dependencies `composer install`
4. Generate a new app key `php atisan key:generate`
5. Run the database migrations with seed option `php artisan migration --seed`
6.
