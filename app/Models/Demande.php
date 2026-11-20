<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property array<int, string>|null $difficulties  liste des difficultés cochées (JSON en base)
 */
class Demande extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demandes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'activity',
        'has_website',
        'difficulties',
        'main_difficulty',
        'objective',
        'need',
        'budget',
        'budget_unknown',
        'full_name',
        'email',
        'phone',
        'consent',
        'consent_at',
        'statut',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'difficulties' => 'array',
            'budget_unknown' => 'boolean',
            'consent' => 'boolean',
            'consent_at' => 'datetime',
        ];
    }
}

