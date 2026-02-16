<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionEnum: string
{
    case MANAGE_POSTS = 'manage.posts';
    case MANAGE_USERS = 'manage.users';
    case MANAGE_ROLES = 'manage.roles';
    case MANAGE_TAGS = 'manage.tags';
    case MANAGE_COMMENTS = 'manage.comments';
    case MANAGE_SETTINGS = 'manage.settings';
}
