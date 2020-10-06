<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

trait ConfirmsPasswords
{
    use RedirectsUsers;

    /**
     * @return string
     */
    public function viewPrefixPath():string
    {
        return $this->viewPrefixPath ?? '';
    }

    /**
     * Display the password confirmation view.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showConfirmForm()
    {
        return view($this->viewPrefixPath() . 'auth.passwords.confirm');
    }

    /**
     * Confirm the given user's password.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function confirm(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $this->resetPasswordConfirmationTimeout($request);

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    /**
     * Get the password confirmation validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'password' => 'required|password',
        ];
    }

    /**
     * Get the password confirmation validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [];
    }

    /**
     * Reset the password confirmation timeout.
     *
     * @param Request $request
     * @return void
     */
    protected function resetPasswordConfirmationTimeout(Request $request)
    {
        $request->session()->put('auth.password_confirmed_at', time());
    }
}
