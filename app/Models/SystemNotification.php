<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    protected $fillable = ['sender_id', 'recipient_type', 'receiver', 'message_subject', 'message_body', 'attachment_path',];
    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
}