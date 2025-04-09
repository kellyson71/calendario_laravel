<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'address',
        'notes',
    ];

    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Obter o usuário que possui este cliente.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obter os agendamentos para este cliente.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
