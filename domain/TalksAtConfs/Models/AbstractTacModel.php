<?php

namespace Domain\TalksAtConfs\Models;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractTacModel extends Model
{
    public function getConnectionName()
    {
        return config('talksatconfs.database.connection');
    }
}
