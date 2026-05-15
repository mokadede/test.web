<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'is_published',
    ];
    public function getExcerptAttribute()
    {
        $content = json_decode($this->content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return \Illuminate\Support\Str::limit(strip_tags($this->content), 100);
        }

        $text = '';
        if (isset($content['blocks'])) {
            foreach ($content['blocks'] as $block) {
                if ($block['type'] === 'paragraph') {
                    $text .= $block['data']['text'] . ' ';
                }
                if (strlen($text) > 200) break;
            }
        }

        return \Illuminate\Support\Str::limit(strip_tags($text), 100);
    }
}
