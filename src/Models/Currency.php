<?php

namespace EZ\CurrencyConversion\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = "currencies";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "code",
        "name",
        "symbol",
        "html_code",
        "html_entity",
        "conversion_rates",
    ];
    
    //
    // Accessors
    //

    public function getConversionRatesAttribute($value)
    {
        return (array) json_decode(unserialize($value));
    }

    //
    // Mutators
    //

    public function setConversionRatesAttribute($value)
    {
        $this->attributes["conversion_rates"] = serialize(json_encode($value));
    }
}