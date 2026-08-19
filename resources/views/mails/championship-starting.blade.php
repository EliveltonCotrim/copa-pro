<!-- resources/views/emails/verification_code.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O campeonato comeca amanha!</title>
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
                                                    <h2 style="font-size: 20px; color: #222; margin: 0;">
                                                        Fala, {{ $userName }}! 🎮
                                                    </h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="start" style="padding-bottom: 20px;">
                                                    <p
                                                        style="font-size: 14px; line-height: 1.6; color: #555; margin: 0;">
                                                        Prepare o controle! O campeonato
                                                        <strong>{{ $championship->name }}</strong> que você se inscreveu
                                                        começa amanhã.
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="start" style="padding-bottom: 4px;">
                                                    <h3>Resumo do Torneio:</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="start" style="padding-bottom: 4px;">
                                                    <ul style="list-style: none; padding: 0; margin: 0;">
                                                        <li style="margin-bottom: 8px;"><strong>Início:</strong>
                                                            @datetime($championship->start_date)
                                                        </li>
                                                        <li style="margin-bottom: 8px;"><strong>Jogo:</strong>
                                                            {{ $championship->game->getLabel() }}
                                                        </li>
                                                        <li style="margin-bottom: 8px;"><strong>Plataforma:</strong>
                                                            {{ $championship->game_platform->getLabel() }}</li>
                                                        <li style="margin-bottom: 8px;"><strong>Formato:</strong>
                                                            {{ $championship->championship_format->getLabel() }}</li>
                                                        @if (!empty($address))
                                                            <li style="margin-bottom: 8px;"><strong>Endereço:</strong>
                                                                {{ $address }}</li>
                                                        @endif
                                                        @if (!empty($regulationUrl))
                                                            <li style="margin-bottom: 8px;">
                                                                <strong>Regulamento:</strong>
                                                                <a href="{{ $regulationUrl }}" __target="_blank"
                                                                    style="color: #3b82f6; text-decoration: none; font-weight: bold;">
                                                                    Baixar arquivo <span
                                                                        style="font-size: 10px;">⬇️</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </td>
                                            </tr>
                                            
                                            @if (!empty($groupUrl))
                                                <tr>
                                                    <td align="start">
                                                        <p>Se você ainda não entrou no nosso canal oficial de
                                                            comunicação,
                                                            acesse agora para não perder o aviso das suas partidas:</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center">
                                                        <!-- Estilizado como um botão verde padrão -->
                                                        <a href="{{ $groupUrl }}" __target="_blank"
                                                            style="display: inline-block; background-color: #22c55e; color: #fff !important; text-decoration: none; padding: 10px 15px; border-radius: 6px; font-size: 13px; font-weight: bold; margin: 5px;"
                                                            target="_blank">
                                                            <span style="margin-right: 6px;">💬</span>
                                                            <span>Entrar no grupo</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td align="start">
                                                    <p>Boa sorte e nos vemos na arena!</p>
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
