<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['candidate_id', 'announcement_id', 'cv_path', 'motivation_letter_path', 'status'];

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}
