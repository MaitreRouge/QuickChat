<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $guarded = [];

    /*
     * Model variables
     */

    public int $id; //autoincrement
    public ?string $firstname;
    public ?string $lastname;
    public ?string $glpi_user;
    public string $username;
    public string $ip;

    public function getShortName()
    {
        return strtolower($this->firstname) . " " . ucfirst(substr($this->lastname, 0,1));
    }
}
