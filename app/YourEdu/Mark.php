<?php

namespace App\YourEdu;

use Database\Factories\MarkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark extends Model
{
    use SoftDeletes,
        HasFactory;

    protected $fillable = ['remark', 'score', 'score_over', 'user_id'];

    public function markable()
    {
        return $this->morphTo();
    }

    public function markedby()
    {
        return $this->morphTo();
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    public function isMarkedbyUser($userId)
    {
        return $this->whereMarkedbyUser($userId)->exists();
    }

    public function scopeWhereMarkedby($query, $account)
    {
        return $query->where(function ($query) use ($account) {
            $query
                ->where('markedby_type', $account::class)
                ->where('markedby_id', $account->id);
        });
    }

    public function scopeWhereMarkedbyUser($query, $userId)
    {
        return $query->where(function ($query) use ($userId) {
            $query
                ->whereHasMorph('markedby', '*', function ($query) use ($userId) {
                    $query->whereUser($userId);
                });
        });
    }

    public function scopeWhereAssessment($query, $assessmentId, $markable = 'App\\YourEdu\\Answer')
    {
        return $query
            ->whereHasMorph(
                'markable',
                $markable,
                function ($query) use ($assessmentId) {
                    $query->whereAssessment($assessmentId);
                }
            );
    }

    protected static function newFactory()
    {
        return MarkFactory::new();
    }
}
