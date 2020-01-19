<?php

namespace Laravel\Ui;

class AuthRouteMethods
{
    /**
     * Register the typical authentication routes for an application.
     *
     * @param  array  $options
     * @return void
     */
    public function auth()
    {
        return function ($options) {
            // Authentication Routes...
            if (! $options['noforms'] ?? false) {
                $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
            }
            $this->post('login', 'Auth\LoginController@login')->name('login');
            $this->post('logout', 'Auth\LoginController@logout')->name('logout');

            // Registration Routes...
            if ($options['register'] ?? true) {
                if (! $options['noforms'] ?? false) {
                    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
                }
                $this->post('register', 'Auth\RegisterController@register')->name('register');
            }

            // Password Reset Routes...
            if ($options['reset'] ?? true) {
                $this->resetPassword($options['noforms'] ?? false);
            }

            // Password Confirmation Routes...
            if ($options['confirm'] ??
                class_exists($this->prependGroupNamespace('Auth\ConfirmPasswordController'))) {
                $this->confirmPassword($options['noforms'] ?? false);
            }

            // Email Verification Routes...
            if ($options['verify'] ?? false) {
                $this->emailVerification($options['noforms'] ?? false);
            }
        };
    }

    /**
     * Register the typical reset password routes for an application.
     *
     * @return void
     */
    public function resetPassword($noForms = false)
    {
        return function () use ($noForms) {
            if (! $noForms) {
                $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
                    ->name('password.request');
                $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')
                    ->name('password.reset');
            }
            $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            $this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
        };
    }

    /**
     * Register the typical confirm password routes for an application.
     *
     * @return void
     */
    public function confirmPassword($noForms = false)
    {
        return function () use ($noForms) {
            if (! $noForms) {
                $this->get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')
                    ->name('password.confirm');
            }
            $this->post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
        };
    }

    /**
     * Register the typical email verification routes for an application.
     *
     * @return void
     */
    public function emailVerification($noForms)
    {
        return function () use ($noForms) {
            if (! $noForms) {
                $this->get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
            }
            $this->get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
            $this->post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
        };
    }
}
