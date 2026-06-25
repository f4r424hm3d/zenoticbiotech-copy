<?php

use App\Models\SystemSetting;

if (! function_exists('system_setting')) {
    function system_setting(string $key, mixed $default = null): mixed
    {
        try {
            return SystemSetting::getSetting($key, $default);
        } catch (Throwable) {
            return $default;
        }
    }
}

if (! function_exists('cdn_asset')) {
    function cdn_asset(string $path): string
    {
        $path = ltrim($path, '/');
        $enabled = (bool) system_setting('cdn_enabled', false);
        $cdnUrl = trim((string) system_setting('cdn_url', ''), '/');

        if ($enabled && $cdnUrl !== '') {
            return '//'.$cdnUrl.'/'.$path;
        }

        return asset($path);
    }
}

if (! function_exists('storage_file_url')) {
    function storage_file_url(?string $path): string
    {
        return cdn_asset('storage/'.ltrim((string) $path, '/'));
    }
}

if (! function_exists('active_email_settings')) {
    function active_email_settings(): array
    {
        $mode = system_setting('email_mode', 'main') === 'testing' ? 'testing' : 'main';

        return [
            'mode' => $mode,
            'to_email' => system_setting($mode.'_to_email', 'info@zenoticbiotech.com'),
            'to_name' => system_setting($mode.'_to_name', 'Zenotic Biotech'),
            'cc_email' => system_setting($mode.'_cc_email', 'info@zenoticbiotech.com'),
            'cc_name' => system_setting($mode.'_cc_name', 'Zenotic Biotech'),
            'bcc_email' => system_setting($mode.'_bcc_email', 'info@zenoticbiotech.com'),
            'bcc_name' => system_setting($mode.'_bcc_name', 'Zenotic Biotech'),
        ];
    }
}
