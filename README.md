<div align="center">
  <a href="https://lighthouse-php.com">
    <img src="https://raw.githubusercontent.com/nuwave/lighthouse/master/logo.png" alt="lighthouse-logo" width="150" height="150">
  </a>
</div>

# Lighthouse Example

**An example [Laravel](https://laravel.com) project using [nuwave/lighthouse](https://github.com/nuwave/lighthouse).**

## Setup

```shell
composer install
cp .env.example .env
touch database/database.sqlite
php artisan migrate --seed
```

## Usage

Run the server with the following command:

```shell
php artisan serve
```

Access [the GraphiQL UI](https://github.com/graphql/graphiql/blob/main/packages/graphiql/README.md) at `/graphiql`.

In order to log in through Sanctum, find out the email of the seeded user:

```graphql
{
  users {
    data {
      email
    }
  }
}
```

Then, log in with the following mutation:

```graphql
mutation {
  login(email: "<email>", password: "password") {
    id
  }
}
```

To validate you are in fact logged in, run the following query:

```graphql
{
  me {
    id
  }
}
```

## Minimalism

In order to keep maintenance as simple as possible,
the project has been stripped of all unnecessary components or files.
