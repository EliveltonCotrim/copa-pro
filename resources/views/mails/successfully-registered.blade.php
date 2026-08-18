<!-- resources/views/emails/championship-registration-success.blade.php -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrição realizada com sucesso</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            font-size: 22px;
            color: #222;
            margin: 0 0 10px;
        }

        h2 {
            font-size: 20px;
            color: #222;
            margin: 25px 0 10px;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            color: #555;
            margin: 8px 0;
        }

        .description {
            white-space: pre-line;
        }

    </style>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"
                    style="max-width: 600px; margin: 0 auto;">
                    {{-- log --}}
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
                                        <!-- Mensagem de sucesso -->
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                            role="presentation" style="max-width: 600px; margin: 0 auto;">
                                            <thead>
                                                <tr>
                                                    <th align="center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="#0bf427" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-circle-check-big-icon lucide-circle-check-big">
                                                            <path d="M21.801 10A10 10 0 1 1 17 3.335" />
                                                            <path d="m9 11 3 3L22 4" />
                                                        </svg>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th align="center">
                                                        <h1 style="font-size: 22px; color: #222; margin: 0 0 10px;">
                                                            Inscrição realizada com sucesso!
                                                        </h1>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td align="center">
                                                        <p style="font-size: 14px; margin: 0 0 15px;">Olá,
                                                            <strong>{{ $name }}</strong>!
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center">
                                                        <p style="font-size: 14px; margin: 0 0 0;">Sua inscrição foi
                                                            realizada com sucesso.</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center">
                                                        <p style="font-size: 14px; margin: 0 0 15px;">Confira abaixo as
                                                            informações do campeonato.
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        {{-- Informações do campeonato --}}
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                            role="presentation">
                                            <tr>
                                                <td
                                                    style="background-color: #f7f7f7; border-radius: 8px; padding: 20px;">
                                                    <h3
                                                        style="font-size: 20px; font-weight: bold; color: #222; margin: 10px auto 10px;">
                                                        {{ $championship->name }}
                                                    </h3>

                                                    @if (!empty($championship->description))
                                                        <p class="description" style="margin: 0;">
                                                            {!! $championship->description !!}
                                                        </p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Links -->
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                            role="presentation" style="margin: 30px 0 10px;">
                                            <tr>
                                                <td align="center">
                                                    @if (!empty($regulationUrl))
                                                        <a href="{{ $regulationUrl }}"
                                                            style="display: inline-block; background-color: #222; color: #fff !important; text-decoration: none; padding: 12px 21px; border-radius: 6px; font-size: 13px; font-weight: bold; margin: 5px;"
                                                            target="_blank">
                                                            <span style="margin-right: 6px;">⬇️</span>
                                                            <span>Baixar regulamento</span>
                                                        </a>
                                                    @endif

                                                    @if (!empty($groupUrl))
                                                        <a href="{{ $groupUrl }}"
                                                            style="display: inline-block; background-color: #28a745; color: #fff !important; text-decoration: none; padding: 12px 21px; border-radius: 6px; font-size: 13px; font-weight: bold; margin: 5px;"
                                                            target="_blank">
                                                            <span style="margin-right: 6px;">💬</span>
                                                            <span>Entrar no grupo</span>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Informações adicionais -->
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                            role="presentation"
                                            style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                                            <tr>
                                                <td>
                                                    <p>
                                                        <strong>Importante:</strong>
                                                        recomendamos que você leia o regulamento do campeonato
                                                        antes do início das atividades.
                                                    </p>

                                                    @if (!empty($groupUrl))
                                                        <p>
                                                            Entre também no grupo oficial para acompanhar
                                                            comunicados, informações e atualizações do campeonato.
                                                        </p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin-top: 30px;">
                                            Boa sorte e bom campeonato! ⚽
                                        </p>

                                        <p style="margin: 0;">
                                            Saudações,<br>
                                            <strong>{{ config('app.name') }}</strong>
                                        </p>

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
