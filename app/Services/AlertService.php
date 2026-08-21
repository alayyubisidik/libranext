<?php

namespace App\Services;

use function Flasher\Notyf\Prime\notyf;

class AlertService
{
    public static function success($message = null)
    {
        notyf()->success($message ? $message : 'Success');
    }

    public static function created($message = null)
    {
        notyf()->success($message ? $message : 'Created Successfully');
    }

    public static function updated($message = null)
    {
        notyf()->success($message ? $message : 'Updated Successfully');
    }

    public static function deleted($message = null)
    {
        notyf()->success($message ? $message : 'Deleted Successfully');
    }

    public static function error($message = null)
    {
        notyf()->error($message ? $message : 'Something went wrong');
    }
}
