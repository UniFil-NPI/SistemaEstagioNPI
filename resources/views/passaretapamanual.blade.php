<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

</head>
<body>
<div class="container">
    <h1>Avaliar Alunos por Etapa</h1>

    <!-- Tabela de Alunos -->
    <div id="alunosContainer" class="table-responsive" >
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Média Final</th>
                    <th>Carga Horária</th>
                    <th>Aprovado ou pendente</th>
                    <th>Está no novo classroom?<th>
                </tr>
            </thead>
            <tbody id="alunosTableBody">
                <!-- Linhas dinâmicas dos alunos serão inseridas aqui -->
            </tbody>
        </table>

        <button id="finalizarEtapa" class="btn btn-success mt-3">Finalizar Etapa</button>
    </div>
</div>
</body>
</html>