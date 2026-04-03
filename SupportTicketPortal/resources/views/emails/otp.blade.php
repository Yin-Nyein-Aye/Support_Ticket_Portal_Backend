@component('mail::message')

{{-- ═══════════════════════════════════════ --}}
{{-- HEADER BANNER                           --}}
{{-- ═══════════════════════════════════════ --}}
<div style="background:#4f46e5;padding:20px 32px;margin:-32px -32px 28px;display:flex;align-items:center;gap:12px;">
    <div
        style="width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
    </div>
    <div>
        <p style="margin:0;color:white;font-size:15px;font-weight:600;">Security Verification</p>
        <p style="margin:0;color:rgba(255,255,255,0.75);font-size:12px;">One-Time Password (OTP)</p>
    </div>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- ALERT INTRO                             --}}
{{-- ═══════════════════════════════════════ --}}
<div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:24px;">
    <div
        style="width:32px;height:32px;border-radius:50%;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
    </div>
    <div>
        <p style="margin:0 0 4px;font-size:16px;font-weight:600;color:#1a1a1a;">Verify your identity</p>
        <p style="margin:0;font-size:14px;color:#5f6368;line-height:1.6;">
            To complete your request, please use the following security code. This code ensures that only you can access
            your account.
        </p>
    </div>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- OTP DISPLAY                             --}}
{{-- ═══════════════════════════════════════ --}}
<div
    style="border:1px solid #e8eaed;border-radius:8px;overflow:hidden;margin-bottom:24px;text-align:center;padding:24px;background:#f8f9fa;">
    <p
        style="margin:0 0 12px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;color:#5f6368;">
        Your Verification Code
    </p>
    <div
        style="font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: 700; color: #4f46e5; letter-spacing: 8px; padding: 12px; background: white; border: 1px dashed #ced4da; border-radius: 4px; display: inline-block;">
        {{ $otp }}
    </div>
    <p style="margin:16px 0 0;font-size:13px;color:#c5221f;font-weight:500;">
        Expires in 5 minutes
    </p>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- SECURITY NOTICE                         --}}
{{-- ═══════════════════════════════════════ --}}
<div style="background:#fff4e5;padding:12px 16px;border-radius:6px;margin-bottom:24px;border-left:4px solid #ffa900;">
    <p style="margin:0;font-size:13px;color:#664d03;line-height:1.5;">
        <strong>Didn't request this?</strong> If you did not attempt to sign in or reset your password, please ignore
        this email or contact support if you have concerns.
    </p>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- FOOTER                                  --}}
{{-- ═══════════════════════════════════════ --}}
<div
    style="border-top:1px solid #e8eaed;padding-top:16px;margin-top:8px;display:flex;align-items:center;justify-content:space-between;">
    <div style="display:flex;align-items:center;gap:8px;">
        <div
            style="width:24px;height:24px;border-radius:50%;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
        </div>
        <span style="font-size:12px;color:#5f6368;">{{ config('app.name') }} Security</span>
    </div>
    <span style="font-size:11px;color:#9aa0a6;">Automated security message</span>
</div>

@endcomponent
