<?php

namespace App\Http\Requests\Auth;

use App\Models\PenugasanMonev;
use App\Models\PeriodeReview;
use App\Models\PerwakilanSatker;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $this->ensureNoOtherGroupMemberIsLoggedIn();

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure no other member of the same monev/satker group is currently logged in.
     *
     * @throws ValidationException
     */
    protected function ensureNoOtherGroupMemberIsLoggedIn(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $periode = PeriodeReview::where('status', 'aktif')->first()
            ?? PeriodeReview::orderByDesc('tahun_review')->orderByDesc('tahun_lkj')->first();

        if (! $periode) {
            return;
        }

        $groupUserIds = $this->groupUserIds($user, $periode->id);

        if ($groupUserIds->isEmpty()) {
            return;
        }

        $otherIds = $groupUserIds->reject(fn (int $id) => $id === $user->id)->values();

        if ($otherIds->isEmpty()) {
            return;
        }

        $activeUserIds = DB::table('sessions')
            ->whereIn('user_id', $otherIds->all())
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->getTimestamp())
            ->pluck('user_id')
            ->unique();

        if ($activeUserIds->isEmpty()) {
            return;
        }

        $names = User::whereIn('id', $activeUserIds->all())->pluck('name')->implode(', ');

        Auth::guard('web')->logout();

        throw ValidationException::withMessages([
            'email' => "Tidak dapat login: {$names} dari tim/perwakilan yang sama sedang login pada periode ini.",
        ]);
    }

    /**
     * Get the user IDs that belong to the same group as the given user for the period.
     *
     * @return \Illuminate\Support\Collection<int, int>
     */
    protected function groupUserIds(User $user, int $periodeId): \Illuminate\Support\Collection
    {
        if ($user->role === 'monev') {
            return PenugasanMonev::where('periode_id', $periodeId)
                ->where('satker_id', $user->penugasanMonev()
                    ->where('periode_id', $periodeId)
                    ->value('satker_id'))
                ->pluck('monev_user_id')
                ->map(fn ($id) => (int) $id);
        }

        if ($user->role === 'satker') {
            return PerwakilanSatker::where('periode_id', $periodeId)
                ->where('satker_id', $user->satker_id)
                ->pluck('user_id')
                ->map(fn ($id) => (int) $id);
        }

        return collect();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
