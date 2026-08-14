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

        .container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin: 35px 0 20px;
        }

        .logo {
            width: 150px;
        }

        .card {
            background-color: #fff;
            padding: 35px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .success {
            text-align: center;
            margin-bottom: 25px;
        }

        .success-icon {
            font-size: 55px;
            margin-bottom: 10px;
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

        .championship {
            background-color: #f7f7f7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .championship-name {
            font-size: 20px;
            font-weight: bold;
            color: #222;
            margin-bottom: 12px;
        }

        .description {
            white-space: pre-line;
        }

        .buttons {
            text-align: center;
            margin: 30px 0 10px;
        }

        .button {
            display: inline-block;
            /* Mudado de inline-flex para inline-block */
            text-align: center;
            padding: 12px 21px;
            margin: 5px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            line-height: 24px;
            /* Casando com a altura do SVG para centralizar perfeitamente */
        }

        .button svg,
        .button img,
        .button span {
            vertical-align: middle;
            display: inline-block;
        }

        .button svg,
        .button .emoji {
            margin-right: 8px;
        }

        .button-primary {
            background-color: #222;
            color: #fff !important;
        }

        .button-success {
            background-color: #28a745;
            color: #fff !important;
        }

        .button-secondary {
            background-color: #e9e9e9;
            color: #333 !important;
        }

        .info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .footer {
            font-size: 13px;
            text-align: center;
            margin-top: 25px;
            color: #777;
        }

        .footer p {
            font-size: 13px;
            color: #777;
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .card {
                padding: 25px 20px;
            }

            .button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>

<body>

    <!-- Logo -->
    <div class="header">
        <img src="{{ asset('images/logo-futpro-primary.png') }}" alt="{{ config('app.name') }}" class="logo">
    </div>

    <div class="container">

        <div class="card">

            <!-- Mensagem de sucesso -->
            <div class="success">
                <div class="success-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="#0bf427" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-circle-check-big-icon lucide-circle-check-big">
                        <path d="M21.801 10A10 10 0 1 1 17 3.335" />
                        <path d="m9 11 3 3L22 4" />
                    </svg></div>

                <h1>Inscrição realizada com sucesso!</h1>

                <p>
                    Olá, <strong>{{ $name }}</strong>!
                </p>

                <p>
                    Sua inscrição foi realizada com sucesso.
                    Confira abaixo as informações do campeonato.
                </p>
            </div>

            <!-- Informações do campeonato -->
            <div class="championship">

                <div class="championship-name">
                    {{ $championship->name }}
                </div>

                @if (!empty($championship->description))
                    <p class="description">
                        {!! $championship->description !!}
                    </p>
                @endif

            </div>

            <!-- Links -->
            <div class="buttons">

                @if (!empty($regulationUrl))
                    <a href="{{ $regulationUrl }}" class="button button-primary"
                        style="display: inline-block; background-color: #222; color: #fff !important; text-decoration: none; padding: 12px 21px; border-radius: 6px; font-size: 13px; font-weight: bold; margin: 5px;"
                        target="_blank">
                        <span style="margin-right: 6px;">⬇️</span>
                        <span>Baixar regulamento</span>
                    </a>
                @endif

                @if (!empty($groupUrl))
                    <a href="{{ $groupUrl }}" class="button button-success"
                        style="display: inline-block; background-color: #28a745; color: #fff !important; text-decoration: none; padding: 12px 21px; border-radius: 6px; font-size: 13px; font-weight: bold; margin: 5px;"
                        target="_blank">
                        <span style="margin-right: 6px;">💬</span>
                        <span>Entrar no grupo</span>
                    </a>
                @endif

            </div>

            <!-- Informações adicionais -->
            <div class="info">

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

            </div>

            <p style="margin-top: 30px;">
                Boa sorte e bom campeonato! ⚽
            </p>

            <p>
                Saudações,<br>
                <strong>{{ config('app.name') }}</strong>
            </p>

        </div>

    </div>

    <!-- Rodapé -->
    <X-layouts.footer />

</body>

</html>
