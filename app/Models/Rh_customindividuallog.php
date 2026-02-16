<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rh_customindividuallog extends Model
{
    protected $table = 'rh_customindividuallogs';
    protected $primaryKey = 'cil_id';
    public $incrementing = true;
    protected $keyType = 'int';
    const CREATED_AT = 'cil_created_at';
    const UPDATED_AT = 'cil_updated_at';

    protected $fillable = [
        'cil_user_id',
        'cil_modal_path',
        'cil_modal_name',
        'cil_action',
        'cil_from_value',
        'cil_to_value',
        'cil_created_at',
        'cil_created_by',
        'cil_updated_at',
        'cil_updated_by',
    ];

    protected $casts = [
        'cil_from_value' => 'array',
        'cil_to_value' => 'array',
    ];
}
