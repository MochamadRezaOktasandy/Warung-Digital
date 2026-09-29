<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
  function setting($key, $default = null)
  {
    // Cache per request atau query langsung jika ada perubahan
    $setting = Setting::where('key', $key)->first();
    return ($setting && !is_null($setting->value) && $setting->value !== '') ? $setting->value : $default;
  }
}
