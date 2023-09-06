<?php

namespace Database\Factories;

use App\Classes\Helper\ReferralCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;
    protected $referralCode;

    public function __construct(
        $count = NULL,
        ?Collection $states = NULL,
        ?Collection $has = NULL,
        ?Collection $for = NULL,
        ?Collection $afterMaking = NULL,
        ?Collection $afterCreating = NULL,
        $connection = NULL
    ) {
        parent::__construct( $count, $states, $has, $for, $afterMaking,
            $afterCreating, $connection );
        $this->referralCode = new ReferralCode();
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'user_name' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'referral_code' => $this->referralCode->createReferralCode(),
            'remember_token' => Str::random(10),
            'profile_image' => "users/image/" . $this->faker->image('storage/app/public/users/image',640,480, 'people', false)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
