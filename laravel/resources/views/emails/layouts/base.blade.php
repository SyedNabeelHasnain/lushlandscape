@php
    use App\Models\Setting;
    use App\Models\MediaAsset;

    $siteName   = $siteName ?? Setting::get('site_name', 'Super WMS');
    $tagline    = $tagline ?? Setting::get('site_tagline', '');
    $phone      = $phone ?? Setting::get('phone', '');
    $email      = $contactEmail ?? Setting::get('email', '');
    $address    = $address ?? Setting::get('address', '');
    $hoursWk    = $hoursWeekday ?? Setting::get('business_hours_weekday', '');
    $hoursWe    = $hoursWeekend ?? Setting::get('business_hours_weekend', '');
    $appUrl     = rtrim(config('app.url'), '/');
    $year       = date('Y');
    $phoneClean = preg_replace('/[^+\d]/', '', $phone);

    // Logo cascade: email_logo → desktop_logo → null
    $emailLogoId   = Setting::get('email_logo_media_id', '');
    $desktopLogoId = Setting::get('logo_desktop_media_id', '');
    $logoAsset     = null;
    if ($emailLogoId)   $logoAsset = MediaAsset::find((int) $emailLogoId);
    if (!$logoAsset && $desktopLogoId) $logoAsset = MediaAsset::find((int) $desktopLogoId);
    $logoUrl = $logoAsset ? $logoAsset->url : null;

    // Social links
    $fbUrl        = Setting::get('facebook_url', '');
    $igUrl        = Setting::get('instagram_url', '');
    $ytUrl        = Setting::get('youtube_url', '');
    $googleBizUrl = Setting::get('google_business_url', '');
    $hasSocial    = $fbUrl || $igUrl || $ytUrl || $googleBizUrl;

    // Preheader
    $preheader = $preheader ?? '';
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta name="color-scheme" content="light dark" />
    <meta name="supported-color-schemes" content="light dark" />
    <title>{{ $siteName }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <style>
        table { border-collapse: collapse; }
        td { font-family: Arial, Helvetica, sans-serif; }
    </style>
    <![endif]-->
</head>
<body style="margin:0;padding:0;width:100%;background-color:#f4f1eb;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100;">
    {{-- Preheader (hidden preview text) --}}
    @if($preheader)
    <div style="display:none;font-size:1px;color:#f4f1eb;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
        {{ $preheader }}&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
    </div>
    @endif

    {{-- Outer wrapper --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f4f1eb;">
        <tr>
            <td align="center" style="padding:24px 16px;">

                {{-- Main container (600px) --}}
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">

                    {{-- ═══ HEADER ═══ --}}
                    <tr>
                        <td style="background-color:#27452B;padding:28px 40px;text-align:center;">
                            @if($logoUrl)
                            <a href="{{ $appUrl }}" target="_blank" style="text-decoration:none;">
                                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" width="180" style="display:inline-block;max-width:180px;height:auto;border:0;outline:none;" />
                            </a>
                            @else
                            <a href="{{ $appUrl }}" target="_blank" style="text-decoration:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:bold;letter-spacing:0.5px;">
                                {{ $siteName }}
                            </a>
                            @endif
                            @if($tagline)
                            <p style="margin:8px 0 0 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:rgba(255,255,255,0.65);line-height:1.4;">
                                {{ $tagline }}
                            </p>
                            @endif
                        </td>
                    </tr>

                    {{-- ═══ BODY CONTENT ═══ --}}
                    <tr>
                        <td style="padding:40px 40px 32px 40px;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- ═══ FOOTER ═══ --}}
                    <tr>
                        <td style="background-color:#f9f7f3;border-top:1px solid #e8e4dc;padding:28px 40px;">

                            {{-- Social links --}}
                            @if($hasSocial)
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:20px;">
                                <tr>
                                    <td align="center">
                                        @if($fbUrl)<a href="{{ $fbUrl }}" target="_blank" style="display:inline-block;margin:0 6px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#27452B;text-decoration:none;font-weight:bold;">Facebook</a>@endif
                                        @if($igUrl)<a href="{{ $igUrl }}" target="_blank" style="display:inline-block;margin:0 6px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#27452B;text-decoration:none;font-weight:bold;">Instagram</a>@endif
                                        @if($ytUrl)<a href="{{ $ytUrl }}" target="_blank" style="display:inline-block;margin:0 6px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#27452B;text-decoration:none;font-weight:bold;">YouTube</a>@endif
                                        @if($googleBizUrl)<a href="{{ $googleBizUrl }}" target="_blank" style="display:inline-block;margin:0 6px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#27452B;text-decoration:none;font-weight:bold;">Google</a>@endif
                                    </td>
                                </tr>
                            </table>
                            @endif

                            {{-- Contact info --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#6b6560;line-height:1.6;">
                                        @if($phone)
                                        <a href="tel:{{ $phoneClean }}" style="color:#27452B;text-decoration:none;font-weight:bold;">{{ $phone }}</a>
                                        @if($email)&nbsp;&nbsp;&#8226;&nbsp;&nbsp;@endif
                                        @endif
                                        @if($email)
                                        <a href="mailto:{{ $email }}" style="color:#27452B;text-decoration:none;">{{ $email }}</a>
                                        @endif
                                    </td>
                                </tr>
                                @if($address)
                                <tr>
                                    <td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9a9490;line-height:1.5;padding-top:4px;">
                                        {{ $address }}
                                    </td>
                                </tr>
                                @endif
                                @if($hoursWk || $hoursWe)
                                <tr>
                                    <td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9a9490;line-height:1.5;padding-top:4px;">
                                        @if($hoursWk){{ $hoursWk }}@endif
                                        @if($hoursWk && $hoursWe)&nbsp;&nbsp;|&nbsp;&nbsp;@endif
                                        @if($hoursWe){{ $hoursWe }}@endif
                                    </td>
                                </tr>
                                @endif
                            </table>

                            {{-- Divider --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:20px 0;">
                                <tr>
                                    <td style="border-top:1px solid #e8e4dc;font-size:0;line-height:0;">&nbsp;</td>
                                </tr>
                            </table>

                            {{-- Copyright --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#b5b0ab;line-height:1.5;">
                                        &copy; {{ $year }} {{ $siteName }}. All rights reserved.<br />
                                        Licensed &amp; Insured. Serving Our Region, Canada.
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#b5b0ab;line-height:1.5;padding-top:8px;">
                                        <a href="{{ $appUrl }}" target="_blank" style="color:#9a9490;text-decoration:underline;">Visit our website</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                {{-- End main container --}}

            </td>
        </tr>
    </table>
</body>
</html>
