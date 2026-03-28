<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body style="margin:0; padding:0; background:#F9F5F0; font-family:'Tajawal','Cairo',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F9F5F0; padding:40px 20px;">
    <tr>
        <td align="center">
            <table width="480" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(26,23,20,0.06);">
                {{-- Header --}}
                <tr>
                    <td style="background:#1A1714; padding:28px 32px; text-align:center;">
                        <h1 style="margin:0; font-family:'Cormorant Garamond',Georgia,serif; font-size:28px; font-weight:600; color:#C9A84C; letter-spacing:0.08em;">NIDAN</h1>
                    </td>
                </tr>
                {{-- Body --}}
                <tr>
                    <td style="padding:36px 32px;">
                        <p style="margin:0 0 8px; font-size:18px; font-weight:700; color:#1A1714;">
                            مرحبًا {{ $userName }}،
                        </p>
                        <p style="margin:0 0 24px; font-size:14px; color:#3A352E; line-height:1.7;">
                            لقد طلبت إعادة تعيين كلمة المرور. استخدم الرمز التالي:
                        </p>
                        <div style="text-align:center; margin:0 0 24px;">
                            <span style="display:inline-block; background:#F9F5F0; border:2px solid #C9A84C; border-radius:8px; padding:16px 32px; font-size:32px; font-weight:700; letter-spacing:0.3em; color:#1A1714; font-family:monospace;">
                                {{ $otp }}
                            </span>
                        </div>
                        <p style="margin:0 0 4px; font-size:13px; color:#8A8278;">
                            هذا الرمز صالح لمدة <strong>10 دقائق</strong> فقط.
                        </p>
                        <p style="margin:0; font-size:13px; color:#8A8278;">
                            إذا لم تطلب هذا، يمكنك تجاهل هذه الرسالة بأمان.
                        </p>
                    </td>
                </tr>
                {{-- Footer --}}
                <tr>
                    <td style="background:#F9F5F0; padding:20px 32px; text-align:center; border-top:1px solid #E8E2D8;">
                        <p style="margin:0; font-size:12px; color:#8A8278;">
                            © {{ date('Y') }} NIDAN — صُنع ليُحَس
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
