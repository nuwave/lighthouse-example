<div align="center">
  <a href="https://lighthouse-php.com">
    <img src="https://raw.githubusercontent.com/nuwave/lighthouse/master/logo.png" alt=lighthouse-logo" width="150" height="150">
  </a>
</div>

<div align="center">

# Lighthouse Example

**An example project using [nuwave/lighthouse](https://github.com/nuwave/lighthouse).**

## Setup

```shell
composer install
cp .env.example .env
touch database/database.sqlite
php artisan migrate
```

## Usage

Run the server with the following command:

```shell
php artisan serve
```

Access [the GraphiQL UI](https://github.com/graphql/graphiql/blob/main/packages/graphiql/README.md) at `/graphiql`.

</div>

## Minimalism

In order to keep maintenance as simple as possible, the project has been stripped of
all unnecessary components or files.
