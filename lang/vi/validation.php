<?php

return [
    // Override Laravel's default validation messages for Vietnamese branding
    'required' => 'Vui lòng nhập :attribute.',
    'email' => 'Email không hợp lệ.',
    'min' => ':attribute phải có ít nhất :min ký tự.',
    'max' => ':attribute không được vượt quá :max ký tự.',
    'numeric' => ':attribute phải là số.',
    'string' => ':attribute phải là chuỗi.',
    'unique' => ':attribute đã được sử dụng.',
    'url' => ':attribute không hợp lệ.',
    'integer' => ':attribute phải là số nguyên.',

    'attributes' => [
        'name' => 'Họ và tên',
        'email' => 'Email',
        'phone' => 'Số điện thoại',
        'company' => 'Công ty',
        'message' => 'Nội dung',
        'quantity' => 'Số lượng',
        'password' => 'Mật khẩu',
    ],
];
