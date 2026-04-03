<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Ticket Created</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f0f4f8; padding: 32px 16px; font-family: Georgia, 'Times New Roman', serif; color: #1a1a1a; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: #1a1a2e; padding: 28px 40px; }
        .header-eyebrow { font-family: 'Courier New', Courier, monospace; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255, 255, 255, 0.4); margin: 0 0 6px; }
        .header-title { font-size: 22px; font-weight: 400; color: #ffffff; margin: 0; line-height: 1.3; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 15px; color: #5f6368; line-height: 1.75; margin: 0 0 28px; }
        .divider { border: none; border-top: 1px solid #e8eaed; margin: 0 0 24px; }
        .section-label { font-family: 'Courier New', Courier, monospace; font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; color: #5f6368; margin: 0 0 14px; }
        .details-table { width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 32px; }
        .details-table tr { border-bottom: 1px solid #e8eaed; }
        .details-table tr:last-child { border-bottom: none; }
        .details-table td { padding: 11px 0; vertical-align: middle; }
        .td-label { color: #5f6368; width: 38%; font-size: 14px; }
        .td-value { text-align: right; font-weight: 500; color: #1a1a1a; }
        .td-ref { font-family: 'Courier New', Courier, monospace; font-size: 13px; color: #6366f1; font-weight: 400; }
        .cta { margin: 0 0 32px; }
        .cta a { display: inline-block; background: #1a1a2e; color: #ffffff; text-decoration: none; font-family: 'Courier New', Courier, monospace; font-size: 13px; letter-spacing: 0.04em; padding: 12px 28px; border-radius: 6px; }
        .footer { border-top: 1px solid #e8eaed; padding: 16px 40px; display: flex; align-items: center; justify-content: space-between; }
        .footer-brand { font-family: 'Courier New', Courier, monospace; font-size: 12px; color: #5f6368; }
        .footer-note { font-size: 11px; color: #9aa0a6; }
    </style>
</head>

<body>
    <div class="wrapper">

        {{-- HEADER --}}
        <div class="header">
            <p class="header-eyebrow">Automated alert · {{ config('app.name') }}</p>
            <p class="header-title">New Ticket Created</p>
        </div>

        {{-- BODY --}}
        <div class="body">
            <p class="greeting">
                A new support ticket has been created and requires your attention.
            </p>

            <hr class="divider">

            <p class="section-label">Ticket details</p>

            <table class="details-table">
                <tr>
                    <td class="td-label">Reference</td>
                    <td class="td-value"><span class="td-ref">{{ $ticket->reference_number }}</span></td>
                </tr>
                <tr>
                    <td class="td-label">Title</td>
                    <td class="td-value">{{ $ticket->title }}</td>
                </tr>
                <tr>
                    <td class="td-label">Status</td>
                    <td class="td-value">
                        @if($ticket->status === 'open')
                        <span class="badge badge-open">open</span>
                        @elseif($ticket->status === 'in_progress')
                        <span class="badge badge-inprogress">in progress</span>
                        @else
                        <span class="badge badge-default">{{ $ticket->status }}</span>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- CTA --}}
            <div class="cta">
                <a href="{{ url('/tickets/' . $ticket->id) }}">View ticket →</a>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <span class="footer-brand">{{ config('app.name') }}</span>
            <span class="footer-note">Automated notification · do not reply</span>
        </div>

    </div>
</body>

</html>