<?php

namespace App\Models;

use App\Services\ChatService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'read_at',
    ];

    protected $with = [
        'sender',
        'receiver',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getChatService(): ChatService
    {
        return new ChatService();
    }

    public function markAsRead(): void
    {
        $this->read_at = Carbon::now();
        $this->save();
    }
}
