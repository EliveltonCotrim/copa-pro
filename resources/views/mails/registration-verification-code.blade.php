<!-- resources/views/emails/verification_code.blade.php -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificação</title>
    <style>
        /* Estilos básicos */
        h1 {
            font-size: 20px;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
                    style="max-width: 600px; margin: 0 auto;">
                    {{-- logo --}}
                    <tr>
                        <td align="center" style="padding: 45px 0 15px;">
                            <img style="width: 150px; display: block;"
                                src="{{ asset('images/logo-futpro-primary.png') }}" alt="{{ config('app.name') }}">
                        </td>
                    </tr>

                    {{-- card --}}
                    <tr>
                        <td style="padding: 10px 20px 30px">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
                                style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                                <tr>
                                    <td class="card-padding" style="padding: 35px 30px;">

                                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                            role="presentation">
                                            <tr>
                                                <td align="start" style="padding-bottom: 8px;">
                                                    <h1 style="font-size: 20px; color: #222; margin: 0;">
                                                        Olá, {{ $name }}!
                                                    </h1>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="start" style="padding-bottom: 20px;">
                                                    <p
                                                        style="font-size: 14px; line-height: 1.6; color: #555; margin: 0;">
                                                        Use o código abaixo para confirmar seu cadastro. Ele expira em
                                                        alguns minutos.
                                                    </p>
                                                </td>
                                            </tr>

                                            {{-- Código de verificação em destaque --}}
                                            <tr>
                                                <td align="center" style="padding: 10px 0 25px;">
                                                    <table cellpadding="0" cellspacing="0" border="0"
                                                        role="presentation">
                                                        <tr>
                                                            <td align="center"
                                                                style="background-color: #f7f7f7; border: 1px dashed #ccc; border-radius: 8px; padding: 16px 32px;">
                                                                <span
                                                                    style="font-size: 30px; font-weight: bold; letter-spacing: 6px; color: #222; font-family: 'Courier New', Courier, monospace;">
                                                                    {{ $verificationCode }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="start" style="padding-bottom: 4px;">
                                                    <p
                                                        style="font-size: 13px; line-height: 1.6; color: #888; margin: 0; padding-top: 15px; border-top: 1px solid #eee;">
                                                        Caso não tenha solicitado o cadastro, por favor, desconsidere
                                                        este e-mail.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="start" style="padding-top: 20px;">
                                                    <p style="font-size: 14px; color: #555; margin: 0;">
                                                        Saudações,<br>
                                                        <strong>{{ config('app.name') }}</strong>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Rodapé -->
                    <tr>
                        <td align="center" style="padding: 10px 0 30px;">
                            <p style="font-size: 14px; line-height: 20px; color: #6b7280; margin: 0;">
                                © {{ now()->year }} Championship Organization. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
