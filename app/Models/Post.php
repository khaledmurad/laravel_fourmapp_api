<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content'];
    protected $appends = ['liked'];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes():HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments():HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getLikedAttribute(){
        return $this->likes()->where('post_id',$this->id)->where('user_id',auth()->id())->exists();
    }
}
