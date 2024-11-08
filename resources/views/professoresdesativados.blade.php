<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle Alunos</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin-top: 20px;
            background-color: lightgray;
        }

        /* Estilo da navbar */
        .navbar {
            background-color: #F29400;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 65px;
        }

        /* Logo centralizada */
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
            color: #707173;
        }

        .navbar .btn-back:hover {
            color: #FFF;
        }

        /* Espaçamento entre navbar e conteúdo principal */
        .content-container {
            margin-top: 80px; /* Espaço entre navbar e filtros/tabela */
            padding: 20px;
        }

        /* Estilo para a tabela de alunos com fundo e bordas arredondadas */
        .table-container {
            width: 100%;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Botão flutuante no canto superior esquerdo */
        .float-button {
            position: fixed;
            top: 15%;
            left: 5%;
            background-color: #007bff;
            color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.3);
            cursor: pointer;
        }

        .float-button:hover {
            background-color: #0056b3;
        }

        /* Estilo do status */
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-size: 0.875em;
            font-weight: bold;
        }

        .status-normal {
            background-color: #28a745;
        }

        .status-pendente {
            background-color: #dc3545;
        }

        /* Dropdown compacto dentro da tabela */
        .dropdown-menu {
            min-width: auto;
        }

        /* Custom Modal Style */
        .modal-content {
            border-radius: 8px;
            border: 1px solid #f29400;
        }

        .modal-header {
            background-color: #f29400;
            color: #fff;
        }

        .modal-footer .btn-primary {
            background-color: #f29400;
            border-color: #f29400;
        }

        .modal-footer .btn-secondary {
            background-color: #707173;
            border-color: #707173;
        }

        .dropzone {
            border: 2px dashed #f29400;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .dropzone:hover {
            background-color: #f7f7f7;
        }

        .dropzone input[type="file"] {
            display: none;
        }

        .mask-custom {
            background: white;
            border-radius: 2em;
            backdrop-filter: blur(25px);
            border: 2px solid rgba(255, 255, 255, 0.05);
            background-clip: padding-box;
            box-shadow: 10px 10px 10px rgba(46, 54, 68, 0.03);
            width: 100%;
        }

        .button-group {
            margin: 20px 0;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a href="/admin/professoresadmin" class="btn-back">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{asset('/images/logounifil.png')}}" alt="Logo Unifil">
        </div>
        <div>
            <p style="color:red">ADMIN/COORDENADORES</p>
        </div>
    </nav>

    <!-- Seção principal com filtros e lista de usuários -->
    <section class="content-container container">
        
        <div class="container mb-4">

        <!-- Tabela de usuários com fundo branco, borda arredondada e sombra -->
        <div class="table-container">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center"><span>Nome</span></th>
                        <th class="text-center"><span>Email</span></th>
                        <th class="text-center"><span>Ações</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="text-center">{{ $user->nome }}</td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center">
                                <span class="label label-default">
                                    <a href="/admin/professoresadmin/reativar/{{$user->email}}" class="btn btn-sm btn-primary">Reativar</a>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

</body>
</html>
