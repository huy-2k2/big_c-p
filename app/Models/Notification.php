<?php

namespace App\Models;

use App\Events\CreateNotifiEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notification')->withPivot('readed_at');
    }

    public static function _create($title, $content, $users)
    {
        $notification = self::create([
            'title' => $title,
            'content' => $content
        ]);
        foreach ($users as $id) {
            $notification->users()->attach($id);
            broadcast(new CreateNotifiEvent(['user_id' => $id, 'notification' => $notification, 'time' => $notification->created_at->toDateTimeString()]));
        }
    }
}
