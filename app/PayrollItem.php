<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable=['payroll_id','type','amount','title'];

    public function payroll(){
        return $this->belongsTo(Payroll::class);
    }
}
