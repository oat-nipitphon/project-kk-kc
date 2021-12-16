<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionGood extends Model
{
    use SoftDeletes;

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function getGoodBalanceAmountAttribute()
    {
        $goodBalanceAmount = 0;
        $goodView = GoodView::where('good_id', $this->good_id)->where('warehouse_id', $this->requisition->warehouse_id)->first();
        if ($goodView != null) {
            $goodBalanceAmount = $goodView->balance_amount;
        }
        return $goodBalanceAmount;
    }
}
