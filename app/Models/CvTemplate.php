<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;

class CvTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'html',
        'css',
        'options',
        'is_system',
    ];

    protected $casts = [
        'options' => 'array',
        'is_system' => 'boolean',
    ];

    public function buildCss(): string
    {
        $options = $this->options ?? [];
        $vars = [];

        if (!empty($options['primary_color'])) {
            $vars[] = '--primary-color: ' . $options['primary_color'] . ';';
        }
        if (!empty($options['accent_color'])) {
            $vars[] = '--accent-color: ' . $options['accent_color'] . ';';
        }
        if (!empty($options['font_family'])) {
            $vars[] = '--font-family: ' . $options['font_family'] . ';';
        }
        if (!empty($options['base_font_size'])) {
            $vars[] = '--base-font-size: ' . $options['base_font_size'] . ';';
        }
        if (!empty($options['heading_font_size'])) {
            $vars[] = '--heading-font-size: ' . $options['heading_font_size'] . ';';
        }

        $varsBlock = $vars ? ":root {\n    " . implode("\n    ", $vars) . "\n}\n" : '';

        return $varsBlock . ($this->css ?? '');
    }

    public function render(array $data): string
    {
        $css = $this->buildCss();
        $template = "<style>\n{$css}\n</style>\n" . ($this->html ?? '');

        return Blade::render($template, $data);
    }

    public function scopeAvailableForUser($query, int $userId)
    {
        return $query->where(function ($sub) use ($userId) {
            $sub->where('is_system', true)->orWhere('user_id', $userId);
        });
    }
}
