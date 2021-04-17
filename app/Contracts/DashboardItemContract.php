<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

class DashboardItemContract extends Model
{
    const FREE = 'FREE';
    const INTRO = 'INTRO';
    const PENDING = 'PENDING';
    const DECLINED = 'DECLINED';
    const DELETED = 'DELETED';
    const ACCEPTED = 'ACCEPTED';
    const VALIDITEMTYPEPLURAL = [
        'classes', 'courses', 'programs', 'extracurriculums'
    ];

}