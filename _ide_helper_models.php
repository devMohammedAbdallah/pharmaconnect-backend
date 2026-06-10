<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $category
 * @property string|null $description
 * @property int $stock
 * @property bool $is_available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pharmacy> $pharmacies
 * @property-read int|null $pharmacies_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereUpdatedAt($value)
 */
	class Medicine extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string $phone
 * @property string|null $working_hours
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medicine> $medicines
 * @property-read int|null $medicines_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pharmacy whereWorkingHours($value)
 */
	class Pharmacy extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property int|null $pharmacy_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Pharmacy|null $pharmacy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePharmacyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

