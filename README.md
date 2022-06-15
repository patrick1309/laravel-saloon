# Sandbox Laravel Saloon

Laravel Saloon is a package helping us writing third party API integration more easily and clean.

This repo follows the tutorial of Laravel News. Next, I practice by adding a new request to get repository commits.

## Documentation

Tutorial: [Laravel News](https://laravel-news.com/api-integrations-using-saloon-in-laravel)

Documentation: [Saloon](https://docs.saloon.dev/)

Github: [Sammyjo20/Saloon](https://github.com/Sammyjo20/Saloon)

## Installation

First, to use GitHub Api, you need to generate a new [Personal Access Token](https://github.com/settings/tokens).
Add token to your .env file : 
```env
GITHUB_API_TOKEN="<your token here>"
```

Next, install composer packages

```bash
composer install
```

## Usage

```bash
# get repo workflows
php artisan github:workflows {owner} {repo}

# get repo commits
php artisan github:commits {owner} {repo}
```