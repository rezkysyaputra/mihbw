<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasRoleAccess
{
    protected static function allowedRoles(): array
    {
        return ['Admin'];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::userHasAllowedRole();
    }

    public static function canViewAny(): bool
    {
        return static::userHasAllowedRole();
    }

    public static function canCreate(): bool
    {
        return static::userHasAllowedRole();
    }

    public static function canEdit(Model $record): bool
    {
        return static::userHasAllowedRole();
    }

    public static function canDelete(Model $record): bool
    {
        return static::userHasAllowedRole();
    }

    public static function canDeleteAny(): bool
    {
        return static::userHasAllowedRole();
    }

    private static function userHasAllowedRole(): bool
    {
        return auth()->user()?->hasAnyRole(static::allowedRoles()) ?? false;
    }
}
