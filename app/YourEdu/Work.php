<?php

namespace App\YourEdu;

use App\Traits\HasAddedbyTrait;
use App\Traits\HasMarkableTrait;
use Database\Factories\WorkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    use SoftDeletes,
        HasFactory,
        HasMarkableTrait,
        HasAddedbyTrait;

    const VALID_STATUSES = ['PENDING', 'DONE'];
    const PENDING = 'PENDING';
    const DONE = 'DONE';

    protected $fillable = ['status', 'addedby_type', 'addedby_id'];

    protected $table = 'work';

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function reportDetail()
    {
        return $this->belongsTo(ReportDetail::class);
    }

    public function scopeWhereAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }

    public function scopeWhereStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWhereDone($query)
    {
        return $query->where('status', self::DONE);
    }

    public function scopeWherePending($query)
    {
        return $query->where('status', self::PENDING);
    }

    public function scopeWhereMarksMarkedby($query, $account)
    {
        return $query
            ->whereHas('marks', function ($query) use ($account) {
                $query->whereMarkedby($account);
            });
    }

    public static function newFactory()
    {
        return WorkFactory::new();
    }
}
