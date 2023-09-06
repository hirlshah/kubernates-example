<?php

namespace App\Classes\Helper;

use App\Models\User;

class ReferralCode extends User
{
    /**
     * Referral Code
     *
     * @var string
     */
    protected $referralCode;

    /**
     * Create a referral code and store it on the User.
     *
     * @return string
     */
    public function createReferralCode(): string
    {
        if (empty($this->referralCode)) {
            // attempt to create a referral code until the one you have is unique
            do {
                $referralCode = $this->generateReferralCode();
            } while (!$this->hasUniqueReferralCode($referralCode));

            $this->referralCode = $referralCode;
        }

        return $this->referralCode;
    }

    /**
     * Generate a referral code.
     *
     * @return string
     */
    protected function generateReferralCode(): string
    {
        // generate crypto secure byte string
        $bytes = random_bytes(8);

        // convert to alphanumeric (also with =, + and /) string
        $encoded = base64_encode($bytes);

        // remove the chars we don't want
        $stripped = str_replace(['=', '+', '/'], '', $encoded);

        // get the prefix from the user name
        $prefix = substr($this->user_name, 0, 0);

        // format the final referral code
        return strtoupper($prefix . $stripped);
    }

    /**
     * Check if the referral code is unique.
     *
     * @param  string  $referralCode
     *
     * @return boolean
     */
    protected function hasUniqueReferralCode(string $referralCode): bool
    {
        return User::where('referral_code', $referralCode)->count() === 0;
    }
}