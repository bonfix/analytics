<?php

namespace Bonfix\DaliliAnalytics\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsSessionActivity extends Model
{
    protected $fillable = ["name","type_id","type","source","session_id","at"];
    public function __construct(array $attributes = array())
    {
        $this->setTable(config('analytics.table_prefix', '').'session_'.'activities');
        parent::__construct($attributes);
    }
}
