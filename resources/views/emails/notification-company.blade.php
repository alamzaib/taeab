<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $notification->title }} - Taeab.com</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f5f7fa; font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f7fa;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <!-- Main Email Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); padding: 30px 40px; text-align: center;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                            Taeab.com
                                        </h1>
                                        <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 400;">
                                            Job Portal UAE
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Greeting -->
                            <p style="margin: 0 0 20px 0; color: #1f2937; font-size: 16px; line-height: 1.6;">
                                Hello {{ $recipient->company_name ?? $recipient->name ?? 'Valued Partner' }},
                            </p>
                            
                            <!-- Notification Title -->
                            <h2 style="margin: 0 0 20px 0; color: #235181; font-size: 22px; font-weight: 600; line-height: 1.4;">
                                {{ $notification->title }}
                            </h2>
                            
                            <!-- Notification Message -->
                            <div style="background-color: #f8f9fa; border-left: 4px solid #235181; padding: 20px; margin: 20px 0; border-radius: 4px;">
                                <p style="margin: 0; color: #374151; font-size: 15px; line-height: 1.7; white-space: pre-line;">
                                    {{ $notification->message }}
                                </p>
                            </div>
                            
                            <!-- Action Button -->
                            @if($notification->jobApplication)
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url(route('company.applications.show', $notification->jobApplication)) }}" 
                                           style="display: inline-block; padding: 14px 32px; background-color: #235181; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 15px; font-weight: 600; text-align: center;">
                                            View Application Details
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif
                            
                            <!-- Additional Info -->
                            <p style="margin: 30px 0 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                If you have any questions or need assistance, please don't hesitate to contact our support team.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px 40px; border-top: 1px solid #e5e7eb;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 20px;">
                                        <p style="margin: 0; color: #235181; font-size: 18px; font-weight: 600;">
                                            Taeab.com
                                        </p>
                                        <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 13px;">
                                            Connecting talent with opportunity
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 20px; border-top: 1px solid #e5e7eb;">
                                        <p style="margin: 0 0 10px 0; color: #9ca3af; font-size: 12px; line-height: 1.6;">
                                            This is an automated notification from Taeab.com<br>
                                            You are receiving this email because you have an active company account with us.
                                        </p>
                                        <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                            © {{ date('Y') }} Taeab.com. All rights reserved.
                                        </p>
                                        <p style="margin: 15px 0 0 0;">
                                            <a href="{{ url('/') }}" style="color: #235181; text-decoration: none; font-size: 12px; margin: 0 10px;">Home</a>
                                            <a href="{{ url('/jobs') }}" style="color: #235181; text-decoration: none; font-size: 12px; margin: 0 10px;">Jobs</a>
                                            <a href="{{ url('/companies') }}" style="color: #235181; text-decoration: none; font-size: 12px; margin: 0 10px;">Companies</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
