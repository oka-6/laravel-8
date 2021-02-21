<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class Mens extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'celular',
        'mensagem',
    ];
    static function createFromForm($data){
        try {
            self::create($data);
            Log::info('Mens saved', ['data'=>$data]);
            return true;
        }catch (QueryException $e){
            Log::error('Error save mens', ['exception'=>$e->getMessage(), 'data'=>$data]);
            return false;
        }
    }

}
