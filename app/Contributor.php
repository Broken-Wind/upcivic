<?php

namespace App;

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

    protected const STATUSES = Program::STATUSES;

    public function getFormattedInvoiceAmountAttribute()
    {
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

    public function shouldDisplayOrganizationContacts()
    {
        return $this->organization->administrators->count() > 0;
    }

    public function isPublished()
    {
        return ! empty($this['published_at']) && $this['published_at'] <= now();
    }

    public function willPublish()
    {
        return ! empty($this['published_at']) && ! $this->isPublished();
    }

    public function getPercentageOfTotalFeeAttribute()
    {
        $sum = $this->program->contributors->pluck('invoice_amount')->sum();
        if ($sum > 0) {
            return number_format(100 * ($this->invoice_amount / $sum), 1);
        }

        return 0;
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id')->withDefault([
            'first_name' => '',
            'last_name' => '',
            'email' => '',
        ]);
    }

    public function getStatus()
    {
        switch (true) {
            case ($this->isPublished()):
                return 'published';
            case ($this->willPublish()):
                return 'will_publish';
            case ($this->isApproved()):
                return 'approved';
            case ($this->program->isProposed()):
                return 'proposed';
            default:
                return 'unsent';
        }
    }

    public function isApproved()
    {
        return $this->approved_by_user_id && $this->approved_at;
    }

    public function getClassStringAttribute()
    {
        return self::STATUSES[$this->getStatus()]['class_string'];
    }

    public function getStatusStringAttribute()
    {
        $statusStrings = [
            'unsent' => 'Not yet sent.',
            'proposed' => 'Proposed by ' . $this->program->proposer->name,
            'approved' => 'Approved by ' . $this->approver->name,
            'will_publish' => !empty($this->published_at) ? 'Publishing on ' . $this->published_at->format('m/d/Y') : 'Status error.',
            'published' => !empty($this->published_at) ? 'Published on ' . $this->published_at->format('m/d/Y') : 'Status error.',
        ];
        return $statusStrings[$this->getStatus()];
    }
}
