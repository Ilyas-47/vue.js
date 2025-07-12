<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'progress',
    ];

    /**
     * Les valeurs possibles pour le statut.
     */
    public const STATUS_PENDING = 'en_attente';
    public const STATUS_IN_PROGRESS = 'en_cours';
    public const STATUS_COMPLETED = 'termine';

    /**
     * Les valeurs possibles pour la priorité.
     */
    public const PRIORITY_LOW = 'faible';
    public const PRIORITY_MEDIUM = 'moyenne';
    public const PRIORITY_HIGH = 'élevée';

    /**
     * Relation : une tâche appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accesseur pour formater la date d’échéance (facultatif).
     */
    public function getFormattedDueDateAttribute()
    {
        return \Carbon\Carbon::parse($this->due_date)->format('d/m/Y');
    }
}
