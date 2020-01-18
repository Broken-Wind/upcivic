<?php
namespace Upcivic;
use Illuminate\Database\Eloquent\Model;
class Contributor extends Model
{
    //
    protected $fillable = [
        'internal_name',
        'invoice_amount',
        'invoice_type',
    ];
    protected $dates = [
        'published_at',
    ];
    public function getFormattedInvoiceAmountAttribute() {
        return isset($this->invoice_amount) ? number_format($this->invoice_amount / 100, 2, '.', '') : null;
    }
    public function getNameAttribute()
    {
        return $this->organization->name;
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function tenant()
    {
        return $this->organization->tenant;
    }
    public function publish($publishedAt = null)
    {
        $this['published_at'] = $publishedAt ?? now();
        $this->save();
        return $this;
    }
    public function unpublish()
    {
        $this['published_at'] = null;
        $this->save();
        return $this;
    }
    public function isPublished()
    {
        return $this['published_at'] <= now();
    }
    public function willPublish()
    {
        return !empty($this['published_at']);
    }
    public function getPercentageOfTotalFeeAttribute()
    {
        $sum = $this->program->contributors->pluck('invoice_amount')->sum();
        if ($sum > 0) {
            return number_format(100 * ($this->invoice_amount / $sum), 1);
        }
        return 0;
    }
}
