<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Customer extends Model
{
    use LogsActivity;

    protected $table = 'customers';
    
    protected $fillable = [
        'customer_name','customer_phone','office_id','address','alternate_phone','place_id',
        'created_by','customer_type_id','customer_classification_id','followup_type','is_critical'
    ];

    public $timestamps = true;

    public function place()
    {
    	return $this->belongsTo('App\Places','place_id');
    }

    public function user()
    {
    	return $this->belongsTo('App\User','created_by');
    }

    public function customerType()
    {
    	return $this->belongsTo('App\CustomerType','customer_type_id');
    }

    public function classification()
    {
        return $this->belongsTo('App\CustomerClassification', 'customer_classification_id');
    }

    public function collectionFollowups()
    {
        return $this->hasMany(CollectionFollowupLog::class);
    }

    public function tallyBillOutstandings()
    {
        return $this->hasMany(TallyBillOutstanding::class, 'alias');
    }
}
