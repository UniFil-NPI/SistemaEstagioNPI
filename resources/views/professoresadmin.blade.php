<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle Alunos</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            margin-top: 20px;
        }

        .user-list tbody td > img {
            position: relative;
            max-width: 50px;
            float: left;
            margin-right: 15px;
        }

        .user-list tbody td .user-link {
            display: block;
            font-size: 1.25em;
            padding-top: 3px;
            margin-left: 60px;
        }

        .user-list tbody td .user-subhead {
            font-size: 0.875em;
            font-style: italic;
        }

        /* TABLES */
        .table {
            border-collapse: separate;
        }

        .table-hover > tbody > tr:hover > td,
        .table-hover > tbody > tr:hover > th {
            background-color: #eee;
        }

        .table thead > tr > th {
            border-bottom: 1px solid #C2C2C2;
            padding-bottom: 0;
        }

        .table tbody > tr > td {
            font-size: 0.875em;
            background: #f5f5f5;
            border-top: 10px solid #fff;
            vertical-align: middle;
            padding: 12px 8px;
        }

        .table tbody > tr > td:first-child,
        .table thead > tr > th:first-child {
            padding-left: 20px;
        }

        .table thead > tr > th span {
            border-bottom: 2px solid #C2C2C2;
            display: inline-block;
            padding: 0 5px;
            padding-bottom: 5px;
            font-weight: normal;
        }

        .table thead > tr > th > a span {
            color: #344644;
        }

        .table thead > tr > th > a span:after {
            content: "\f0dc";
            font-family: FontAwesome;
            font-style: normal;
            font-weight: normal;
            text-decoration: inherit;
            margin-left: 5px;
            font-size: 0.75em;
        }

        .table thead > tr > th > a.asc span:after {
            content: "\f0dd";
        }

        .table thead > tr > th > a.desc span:after {
            content: "\f0de";
        }

        .table thead > tr > th > a:hover span {
            text-decoration: none;
            color: #2bb6a3;
            border-color: #2bb6a3;
        }

        .table.table-hover tbody > tr > td {
            -webkit-transition: background-color 0.15s ease-in-out 0s;
            transition: background-color 0.15s ease-in-out 0s;
        }

        .btn-group.pull-right {
            padding-left: 10px;
        }

        .breadcrumb {
            background: #fff;
            margin-bottom: 10px;
        }

        
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a href="/admin" class="btn-back">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div class="ms-auto">
                <a href="/admin/professoresdesativados" class="btn btn-outline-primary">Professores Desativados</a>
            </div>
        </div>
    </nav>

    
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box no-header clearfix">
                    <div class="main-box-body clearfix">
                        <div class="table-responsive">
                            <table class="table user-list">
                                <thead>
                                    <tr>
                                        <th class="text-center"><span>Nome</span></th>
                                        <th class="text-center"><span>Email</span></th>
                                        <th class="text-center"><span>Ações</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $users)
                                        <tr>
                                            <td class="text-center">
                                                {{$users->nome }}
                                            </td>
                                            <td class="text-center">
                                                {{ $users->email }}
                                            </td>
                                            <td class="text-end">
                                                <span class="label label-default">
                                                    <a href="#" class="btn btn-sm btn-primary">Controlar Alunos</a>
                                                    @if($users->admin == FALSE)
                                                    <a href="/bimestres/admin/professores/darprivilegios" class="btn btn-sm btn-success">Tirar Privilégios</a>
                                                    @else
                                                    <a href="#" class="btn btn-sm btn-success">Dar Privilégios</a>
                                                    @endif
                                                    <a href="#" class="btn btn-sm btn-success">Desativar</a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
