<?php

namespace App\Models;

/**
 * IDE Helper for Laravel Models
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $profile_image
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @method \Laravel\Passport\PersonalAccessTokenResult createToken(string $name, array $scopes = [])
 * @method \Laravel\Passport\Token token()
 * @method \Illuminate\Database\Eloquent\Collection tokens()
 *
 * This is an IDE helper file for better code completion.
 * The actual User model is in app/Models/User.php
 */
class UserIdeHelper extends User
{
    //
}
