<?php

namespace App\Http\GraphQL;

class Query
{
    public function jobs($root, array $args, $context, $info)
    {
        return \App\Job::all();
    }
}
