<?php

namespace App\Enums\Blog;

enum PostTypeSlug: string
{
    case NEWS = 'news';
    case TUTORIAL = 'tutorial';
    case ANNOUNCEMENT = 'announcement';
    case ARTICLE = 'article';
}
