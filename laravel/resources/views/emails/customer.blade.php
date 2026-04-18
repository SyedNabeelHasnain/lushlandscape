@extends('emails.layouts.base')

@section('content')
{{-- Greeting --}}
<p style="margin:0 0 20px 0;font-family:Arial,Helvetica,sans-serif;font-size:16px;color:#1a1a1a;line-height:1.6;">
    {{ $greeting ?? 'Hello,' }}
</p>

{{-- Body paragraphs --}}
@foreach($lines ?? [] as $line)
<p style="margin:0 0 16px 0;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#3d3d3d;line-height:1.7;">
    {{ $line }}
</p>
@endforeach

{{-- Highlighted block (for OTP codes, confirmation numbers, etc.) --}}
@if(!empty($highlightBlock))
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:24px 0;">
    <tr>
        <td style="background-color:#f0ede6;border-radius:8px;padding:24px;text-align:center;border:1px solid #e8e4dc;">
            @if(!empty($highlightLabel))
            <p style="margin:0 0 8px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#6b6560;text-transform:uppercase;letter-spacing:1px;font-weight:bold;">
                {{ $highlightLabel }}
            </p>
            @endif
            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:32px;color:#27452B;font-weight:bold;letter-spacing:4px;">
                {{ $highlightBlock }}
            </p>
            @if(!empty($highlightNote))
            <p style="margin:10px 0 0 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9a9490;">
                {{ $highlightNote }}
            </p>
            @endif
        </td>
    </tr>
</table>
@endif

{{-- CTA Button --}}
@if(!empty($actionText) && !empty($actionUrl))
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:28px 0;">
    <tr>
        <td align="center">
            <!--[if mso]>
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $actionUrl }}" style="height:48px;v-text-anchor:middle;width:220px;" arcsize="17%" strokeweight="0" fillcolor="#27452B">
                <w:anchorlock/>
                <center style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:bold;">{{ $actionText }}</center>
            </v:roundrect>
            <![endif]-->
            <!--[if !mso]><!-->
            <a href="{{ $actionUrl }}" target="_blank" style="display:inline-block;background-color:#27452B;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:bold;text-decoration:none;padding:14px 32px;border-radius:8px;mso-hide:all;">
                {{ $actionText }}
            </a>
            <!--<![endif]-->
        </td>
    </tr>
</table>
@endif

{{-- Outro paragraphs --}}
@foreach($outroLines ?? [] as $line)
<p style="margin:0 0 16px 0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#6b6560;line-height:1.6;">
    {{ $line }}
</p>
@endforeach

{{-- Sign-off --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:24px;">
    <tr>
        <td style="border-top:1px solid #f0ede6;padding-top:20px;">
            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#3d3d3d;line-height:1.6;">
                Warm regards,<br />
                <strong style="color:#27452B;">{{ $siteName ?? 'Super WMS' }}</strong>
            </p>
        </td>
    </tr>
</table>
@endsection
