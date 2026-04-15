@extends('emails.layouts.base')

@section('content')
{{-- Notification badge --}}
@if(!empty($badge))
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
    <tr>
        <td style="background-color:{{ $badgeColor ?? '#27452B' }};border-radius:4px;padding:4px 12px;">
            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#ffffff;text-transform:uppercase;letter-spacing:1px;font-weight:bold;">{{ $badge }}</span>
        </td>
    </tr>
</table>
@endif

{{-- Title --}}
<h1 style="margin:0 0 8px 0;font-family:Arial,Helvetica,sans-serif;font-size:20px;color:#1a1a1a;font-weight:bold;line-height:1.3;">
    {{ $title ?? 'New Notification' }}
</h1>

{{-- Subtitle --}}
@if(!empty($subtitle))
<p style="margin:0 0 24px 0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#6b6560;line-height:1.5;">
    {{ $subtitle }}
</p>
@endif

{{-- Data table --}}
@if(!empty($dataRows))
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:20px 0;border:1px solid #e8e4dc;border-radius:8px;overflow:hidden;">
    @foreach($dataRows as $i => $row)
    <tr>
        <td style="padding:12px 16px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#6b6560;font-weight:bold;text-transform:uppercase;letter-spacing:0.5px;width:140px;vertical-align:top;{{ $i > 0 ? 'border-top:1px solid #f0ede6;' : '' }}background-color:#faf8f5;">
            {{ $row['label'] ?? '' }}
        </td>
        <td style="padding:12px 16px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#1a1a1a;line-height:1.5;{{ $i > 0 ? 'border-top:1px solid #f0ede6;' : '' }}">
            {{ $row['value'] ?? '' }}
        </td>
    </tr>
    @endforeach
</table>
@endif

{{-- Body lines (optional additional context) --}}
@foreach($lines ?? [] as $line)
<p style="margin:0 0 12px 0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#3d3d3d;line-height:1.6;">
    {{ $line }}
</p>
@endforeach

{{-- CTA Button --}}
@if(!empty($actionText) && !empty($actionUrl))
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:24px 0;">
    <tr>
        <td align="left">
            <!--[if mso]>
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $actionUrl }}" style="height:44px;v-text-anchor:middle;width:200px;" arcsize="17%" strokeweight="0" fillcolor="#27452B">
                <w:anchorlock/>
                <center style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:bold;">{{ $actionText }}</center>
            </v:roundrect>
            <![endif]-->
            <!--[if !mso]><!-->
            <a href="{{ $actionUrl }}" target="_blank" style="display:inline-block;background-color:#27452B;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:bold;text-decoration:none;padding:12px 28px;border-radius:8px;mso-hide:all;">
                {{ $actionText }}
            </a>
            <!--<![endif]-->
        </td>
    </tr>
</table>
@endif

{{-- Meta info --}}
@if(!empty($meta))
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:24px;border-top:1px solid #f0ede6;padding-top:16px;">
    @foreach($meta as $key => $val)
    <tr>
        <td style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#9a9490;line-height:1.6;padding:1px 0;">
            <strong>{{ $key }}:</strong> {{ $val }}
        </td>
    </tr>
    @endforeach
</table>
@endif
@endsection
