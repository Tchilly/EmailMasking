<?php

declare(strict_types=1);

namespace Tchilly\EmailMasking\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasMaskedEmail
{
    /**
     * Get the masked email address.
     */
    protected function emailMasked(): Attribute
    {
        return new Attribute(
            get: fn () => static::maskEmail($this->email),
        );
    }

    /**
     * Get the masked email address with custom configuration.
     *
     * @param  string|null  $email  The email to mask (defaults to model's email)
     * @param  int|null  $maxAsterisks  The maximum number of asterisks to use
     * @return string The masked email
     */
    public function getMaskedEmail(?string $email = null, ?int $maxAsterisks = null): string
    {
        return static::maskEmail($email ?? $this->email, $maxAsterisks);
    }

    /**
     * Mask an email address.
     *
     * @param  string  $email  The email address to mask
     * @param  int|null  $maxAsterisks  The maximum number of asterisks to use
     * @return string The masked email address
     */
    public static function maskEmail(string $email, ?int $maxAsterisks = null): string
    {
        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        [$username, $domain] = explode('@', $email, 2);

        // Show only the first character
        $firstChar = mb_substr($username, 0, 1);

        // Get length of username minus the first character
        $length = mb_strlen($username) - 1;

        // Apply maximum asterisks if specified
        if ($maxAsterisks !== null && $length > $maxAsterisks) {
            $length = $maxAsterisks;
        }

        // Create masked username
        $maskedUsername = $firstChar.str_repeat('*', $length);

        return $maskedUsername.'@'.$domain;
    }
}
