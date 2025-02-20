<!-- resources/views/emails/verification_code.blade.php -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificação</title>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            /* background-color: #fff; */
            padding: 20px;
            border-radius: 8px;
            /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            margin-top: 35px
        }

        .logo {
            width: 150px;
        }

        .card {
            background-color: #f9f9f9;
            padding: 35px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 20px;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        .footer {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>

<body>
    <!-- Logo do sistema -->
    <div class="header">
        <img src="{{ asset('images/logo-futpro-primary.png') }}" alt="Logo" class="logo">
    </div>
    <div class="container">
        <!-- Card com o conteúdo do e-mail -->
        <div class="card">
            <h1>Olá, {{ $name }}!</h1>
            <p>Seu código de verificação é: <strong>{{ $verificationCode }}</strong></p>
            <p>Caso não tenha solicitado o cadastro, por favor, desconsidere este e-mail.</p>
            <br>
            <p>Saudações,<br>{{ config('app.name') }}</p>
        </div>
    </div>
    <!-- Rodapé -->
    <footer style="text-align: center; margin-top: 40px; padding: 20px;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
