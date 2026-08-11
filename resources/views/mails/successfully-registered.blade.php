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
                    <a href="{{ $regulationUrl }}" class="button button-primary" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                            fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-cloud-download-icon lucide-cloud-download">
                            <path d="M12 13v8l-4-4" />
                            <path d="m12 21 4-4" />
                            <path d="M4.393 15.269A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.436 8.284" />
                        </svg>
                        <span>Baixar regulamento</span>
                    </a>
                @endif

                @if (!empty($groupUrl))
                    <a href="{{ $groupUrl }}" class="button button-success" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-messages-square-icon lucide-messages-square">
                            <path
                                d="M16 10a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 14.286V4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                            <path
                                d="M20 9a2 2 0 0 1 2 2v10.286a.71.71 0 0 1-1.212.502l-2.202-2.202A2 2 0 0 0 17.172 19H10a2 2 0 0 1-2-2v-1" />
                        </svg> <span>Entrar no grupo</span>
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
