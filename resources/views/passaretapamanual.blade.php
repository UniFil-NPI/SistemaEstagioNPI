<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Alunos por Etapa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Avaliar Alunos da Etapa {{ $etapa }}</h1>

    <!-- Tabela de Alunos -->
    <div id="alunosContainer" class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Média Final</th>
                    <th>Carga Horária</th>
                    <th>Status</th>
                    <th>Está no Novo Classroom?</th>
                    <th>Aprovar</th> <!-- Adiciona coluna para marcar aprovação -->
                </tr>
            </thead>
            <tbody id="alunosTableBody">
                @foreach ($alunosAvaliados as $aluno)
                    <tr>
                        <td>{{ $aluno['nome'] }}</td>
                        <td>{{ $aluno['email'] }}</td>
                        <td>{{ $aluno['media'] }}</td>
                        <td>{{ $aluno['cargaHoraria'] }} orientações</td>
                        <td>{{ $aluno['status'] }}</td>
                        <td>{{ $aluno['novoClassroom'] }}</td>
                        <td>
                            <input type="checkbox" name="aprovados[]" value="{{ $aluno['email'] }}" 
                                   @if ($aluno['status'] == 'Aprovado') checked @endif>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form method="POST" action="/admin/alunosadmin/passaretapamanual/finalizar">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $novoClassroomID }}">
            <input type="hidden" name="classroom_atual" value="{{ $classroomAtual }}">
            <input type="hidden" name="etapa" value="{{ $etapa }}">
            <button type="submit" class="btn btn-success mt-3">Finalizar Etapa</button>
            <a href="{{ url()->previous() }}" class="btn btn-danger mt-3">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
