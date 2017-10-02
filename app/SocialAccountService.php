<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 09.10.2016
 * Time: 20:58
 */

namespace App;

use App\Models\SocialAccount;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\Provider;

class SocialAccountService
{
    public function createOrGetUser(Provider $provider)
    {
        $providerUser = $provider->user();
        $providerName = class_basename($provider);

        $account = SocialAccount::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account && User::find($account->user)) {
            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName
            ]);

            $user = User::whereEmail($providerUser->getEmail())
                ->orWhere("email",DB::raw($providerUser->getId()))
                ->first();

            if (!$user) {

                $user = User::create([
                    'email' => $providerUser->getEmail() ?: $providerUser->getId(),
                    'name' => $providerUser->getName(),
                    'password' => bcrypt($providerUser->getName().time()),
                    'avatar' => $providerUser->getAvatar() ?: asset('public/images/no-avatar.png'),
                    'has_current_password' => 0
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }

    }
}