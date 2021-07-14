<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait VerifiesEmails
{
    use RedirectsUsers;

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function user(Request $request)
    {
        return $request->user();
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        return $this->user($request)->hasVerifiedEmail()
                        ? redirect($this->redirectPath())
                        : view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $this->user($request)->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($this->user($request)->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($this->user($request)->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? new JsonResponse([], 204)
                        : redirect($this->redirectPath());
        }

        if ($this->user($request)->markEmailAsVerified()) {
            event(new Verified($this->user($request)));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * The user has been verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function verified(Request $request)
    {
        //
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        if ($this->user($request)->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? new JsonResponse([], 204)
                        : redirect($this->redirectPath());
        }

        $this->user($request)->sendEmailVerificationNotification();

        return $request->wantsJson()
                    ? new JsonResponse([], 202)
                    : back()->with('resent', true);
    }
}
