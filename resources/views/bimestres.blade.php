<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bimestres</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            background-color: lightgray; /* Light gray background */
            margin-top: 20px;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: #F29400; /* Laranja */
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 65px; /* Mantém o tamanho fixo do navbar */
        }

        .navbar .logo {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar .logo img {
            height: 70%;
            width: 70%;
        }

        .navbar .btn-back {
            font-size: 1.5em;
            color: #707173; /* Cinza */
        }

        .navbar .btn-back:hover {
            color: #FFF; /* Branco */
        }

        .navbar .btn-outline-primary {
            color: #707173; /* Cinza */
            border-color: #707173; /* Cinza */
            transition: background-color 0.3s ease;
        }

        .navbar .btn-outline-primary:hover {
            background-color: #707173; /* Cinza */
            color: #FFF;
        }

        .bimestres-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            flex-wrap: wrap;
        }

        .bimestre-box {
            background-color: #FFF;
            width: 220px;
            height: 270px;
            margin: 20px;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #FFF;
            font-weight: bold;
            font-size: 1.2em;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            text-decoration: none; /* Remove o sublinhado do texto */
        }

        .bimestre-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            filter: blur(5px) brightness(0.7);
            z-index: 1;
            transition: filter 0.3s ease;
        }

        /* Background específico para cada bimestre */
        .bimestre-box:nth-child(1)::before {
            background-image: url("{{asset('/images/planejamento.png')}}");
        }

        .bimestre-box:nth-child(2)::before {
            background-image: url("{{asset('/images/desenvolvimento1.png')}}");
        }

        .bimestre-box:nth-child(3)::before {
            background-image: url("{{asset('/images/desenvolvimento2.png')}}");
        }

        .bimestre-box:nth-child(4)::before {
            background-image: url("{{asset('/images/defesa.png')}}");
        }

        .bimestre-box:hover::before {
            filter: blur(0px) brightness(0.9);
        }

        .bimestre-box span {
            position: relative;
            z-index: 2;
            color: #FFF;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); /* Maior destaque no texto */
        }

        .bimestre-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
        }

        .bimestre-box:active {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a href="/" class="btn-back">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{asset('/images/logounifil.png')}}" alt="Logo Unifil">
        </div>
        <div>
            <a href="/admin" class="btn btn-outline-primary">Admin</a>
        </div>
    </nav>

    <div class="bimestres-container">
        <a href="{{ url('/listaralunos/1') }}" class="bimestre-box">
            <span>ESTÁGIO I<br>PLANEJAMENTO</span>
        </a>
        <a href="{{ url('/listaralunos/2') }}" class="bimestre-box">
            <span>ESTÁGIO I<br>DESENVOLVIMENTO</span>
        </a>
        <a href="{{ url('/listaralunos/3') }}" class="bimestre-box">
            <span>ESTÁGIO II<br>DESENVOLVIMENTO</span>
        </a>
        <a href="{{ url('/listaralunos/4') }}" class="bimestre-box">
            <span>ESTÁGIO II<br>FINAL</span>
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
