<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'identifier' => ['required', 'string'],
        'password'   => ['required', 'string'],
    ];
}

public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    $field = filter_var($this->identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'identifier';

    if (! Auth::attempt(
        [$field => $this->identifier, 'password' => $this->password],
        $this->boolean('remember')
    )) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => 'NIM/NIDN/email atau password salah.',
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}

public function ensureIsNotRateLimited(): void
{
    if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
        return;
    }

    event(new Lockout($this));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
        'identifier' => trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]),
    ]);
}

public function throttleKey(): string
{
    return Str::transliterate(Str::lower($this->string('identifier')).'|'.$this->ip());
}
}
