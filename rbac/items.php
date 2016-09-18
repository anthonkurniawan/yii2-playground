<?php
return [
    'reader' => [
        'type' => 2,
        'description' => 'Reader Users',
    ],
    'createPost' => [
        'type' => 2,
        'description' => 'Create a post',
    ],
    'updatePost' => [
        'type' => 2,
        'description' => 'Update post',
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'author',
            'super user',
            'createUser',
            'updateUser',
            'deleteUser',
        ],
    ],
    'updateOwnPost' => [
        'type' => 2,
        'description' => 'Update own post',
        'ruleName' => 'isAuthor',
        'children' => [
            'updatePost',
        ],
    ],
    'test' => [
        'type' => 2,
        'description' => 'a test operation',
    ],
    'super user' => [
        'type' => 1,
        'description' => 'super user role',
        'children' => [
            'author',
            'test',
        ],
    ],
    'author' => [
        'type' => 1,
        'children' => [
            'createPost',
            'updateOwnPost',
        ],
    ],
    'a' => [
        'type' => 2,
    ],
    'createUser' => [
        'type' => 2,
        'description' => 'Create User',
    ],
    'updateUser' => [
        'type' => 2,
        'description' => 'Update User',
    ],
    'deleteUser' => [
        'type' => 2,
        'description' => 'Delete a User',
    ],
];
