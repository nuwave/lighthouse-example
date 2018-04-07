## Walkthrough

### Step 1: Download

Clone the repo and checkout the `pre-lighthouse` branch.

Run the seeder

```bash
php artisan db:seed
```

### Step 2: Install

Install the lighthouse package

```bash
$ composer require nuwave/lighthouse
```

Publish the configuration

```bash
$ php artisan vendor:publish --provider="Nuwave\Lighthouse\Providers\LighthouseServiceProvider"
```

### Step 3: Schema

Create a schema file

```graphql
# /routes/graphql/schema.graphql

type Job {
  title: String!
}

type Query {
  jobs: [Job!]! @field(resolver: "App\\Http\\GraphQL\\Query@jobs")
}
```

Create resolver

```php
// /app/Http/GraphQL/Query.php

namespace App\Http\GraphQL;

class Query
{
    public function jobs($root, array $args, $context, $info)
    {
        return \App\Job::all();
    }
}
```

### Step 4: Expand Schema

```graphql
type Task {
  title: String!
}

type User {
  name: String!
  email: String!
}

type Job {
  title: String!
  user: User @belongsTo
  tasks: [Task!]! @hasMany(type: "paginator")
}

type Query {
  # Eliminates the need for a query resolver
  jobs: [Job!]! @paginate(model: "App\\Job")
}
```

### Step 5: Authentication

Update Schema

```graphql
type AuthToken {
    token_type: String!
    expires_in: Int!
    access_token: String!
    refresh_token: String!
}

type LoginPayload {
    auth_token: AuthToken
    user: User
}

type Query {
  me: User @auth
  jobs: [Job!]! @paginate(model: "App\\Job")
}

type Mutation {
  login(
    username: String!
    password: String!
    client_id: Int!
    client_secret: String!
  ): LoginPayload @field(resolver: "App\\Http\\GraphQL\\Mutations\\AccountMutator@login")
}
```

Create Mutator

```php
// app/Http/GraphQL/Mutations/AccountMutator
namespace App\Http\GraphQL\Mutations;

use Illuminate\Http\Request;

class AccountMutator
{
    public function login($root, array $args)
    {
        $data = array_merge($args, [
            'grant_type' => 'password',
            'scope'      => '',
        ]);

        $request = Request::create('oauth/token', 'POST', $data, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);

        $response = app()->handle($request);
        $auth_token = json_decode($response->getContent(), true);
        $user = \App\User::where('email', $args['username'])->first();

        return compact('auth_token', 'user');
    }
}
```

### Step 6

Create Jobs

```graphql
type Mutation @group(namespace: "App\\Http\\GraphQL\\Mutations") {
  login(
    username: String!
    password: String!
    client_id: Int!
    client_secret: String!
  ): LoginPayload @field(resolver: "AccountMutator@login")

  createJob(title: String! @validate(rules: ["min:3"])): Job
    @create(model: "App\\Job")
    @inject(context: "user.id" name: "user_id")
    @event(fire: "App\\Events\\JobCreated")
}
```

### Step 7

Split up graphql schema files

```graphql
# routes/graphql/account.graphql
type AuthToken {
    token_type: String!
    expires_in: Int!
    access_token: String!
    refresh_token: String!
}

type LoginPayload {
    auth_token: AuthToken
    user: User
}

extend type Mutation @group(namespace: "App\\Http\\GraphQL\\Mutations") {
  login(
    username: String!
    password: String!
    client_id: Int!
    client_secret: String!
  ): LoginPayload @field(resolver: "AccountMutator@login")
}

# routes/graphql/schema.graphql
#import ./account.graphql

type Task {
  title: String!
}

type User {
  name: String!
  email: String!
  jobs: [Job!]! @hasMany
}

type Job {
  title: String!
  user: User @belongsTo
  tasks: [Task!]! @hasMany(type: "paginator")
}

type Query {
  me: User @auth
  jobs: [Job!]! @paginate(model: "App\\Job")
}

type Mutation @group(namespace: "App\\Http\\GraphQL\\Mutations") {
  createJob(title: String! @validate(rules: ["min:3"])): Job
    @create(model: "App\\Job")
    @inject(context: "user.id" name: "user_id")
    @event(fire: "App\\Events\\JobCreated")
}
```
