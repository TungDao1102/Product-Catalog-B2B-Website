<?php

return [
    // Override Laravel's default validation messages for English branding
    'required' => 'The :attribute field is required.',
    'email' => 'Invalid email address.',
    'min' => ':attribute must be at least :min characters.',
    'max' => ':attribute must not exceed :max characters.',
    'numeric' => ':attribute must be a number.',
    'string' => ':attribute must be a string.',
    'unique' => ':attribute has already been taken.',
    'url' => ':attribute is not a valid URL.',
    'integer' => ':attribute must be an integer.',

    'attributes' => [
        'name' => 'Full Name',
        'email' => 'Email',
        'phone' => 'Phone Number',
        'company' => 'Company',
        'message' => 'Message',
        'quantity' => 'Quantity',
        'password' => 'Password',
    ],
];
