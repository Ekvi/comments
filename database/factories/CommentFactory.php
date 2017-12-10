<?php

use App\Models\Comment;
use Faker\Generator as Faker;

$click = $factory->defineAs(Comment::class, 'firstLevel', function (Faker $faker) use($factory) {
    return [
        'id' => 2,
        'left' => 2,
        'right' => 3,
        'level' => 1,
        'message' => 'First Level',
    ];
});