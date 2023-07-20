<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Company extends Model
{
    use Notifiable;
    use HasFactory;
    protected $guarded = [];
}
