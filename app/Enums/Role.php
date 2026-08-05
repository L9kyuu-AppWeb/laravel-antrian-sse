<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Operator = 'operator';
    case Penjaga = 'penjaga';
    case Monitor = 'monitor';
}
