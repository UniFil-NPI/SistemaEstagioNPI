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
            background-color: #d3d3d3; /* Light gray background */
            margin-top: 20px;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.5); /* Transparência */
        }

        .navbar .btn-back {
            font-size: 1.5em;
        }

        .bimestres-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            flex-wrap: wrap;
        }

        .bimestre-box {
            background-image: url("{{asset('/images/bim-button-bg.jpg')}}"); /* Path to the image */
            background-size: cover;
            background-position: center;
            width: 150px;
            height: 200px;
            margin: 20px;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
            font-weight: bold;
            font-size: 1.2em;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .bimestre-box:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a href="javascript:history.back()" class="btn-back">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div class="ms-auto">
                <a href="/admin" class="btn btn-outline-primary">Admin</a>
            </div>
        </div>
    </nav>


    <div class="bimestres-container">
        <a href="{{ url('/listaralunos/1') }}" class="bimestre-box">1º BIMESTRE</a>
        <a href="{{ url('/listaralunos/2') }}" class="bimestre-box">2º BIMESTRE</a>
        <a href="{{ url('/listaralunos/3') }}" class="bimestre-box">3º BIMESTRE</a>
        <a href="{{ url('/listaralunos/4') }}" class="bimestre-box">4º BIMESTRE</a>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
