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
        <a href="/admin" class="btn-back">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{asset('/images/logounifil.png')}}" alt="Logo Unifil">
        </div>
        <div>
            <p style="color:red">ADMIN/COORDENADORES</p>
        </div>
    </nav>

    <!-- Seção principal com filtros e lista de alunos -->
    <section class="content-container container">
        
        <div class="container mb-4">

            <!-- Botão seleção opções -->
            <div class="float-button" data-bs-toggle="modal" data-bs-target="#mainMenuModal">
                <i class="fa fa-plus"></i>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <input type="text" id="searchName" class="form-control" placeholder="Pesquisar por Nome">
                </div>
                <div class="col-md-3">
                    <select id="filterEtapa" class="form-select">
                        <option value="">Filtrar por Etapa</option>
                        <option value="Estágio I: Planejamento">Estágio I: Planejamento</option>
                        <option value="Estágio I: Desenvolvimento">Estágio I: Desenvolvimento</option>
                        <option value="Estágio II: Desenvolvimento">Estágio II: Desenvolvimento</option>
                        <option value="Estágio II: Final">Estágio II: Final</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterStatus" class="form-select">
                        <option value="">Filtrar por Status</option>
                        <option value="0">Normal</option>
                        <option value="1">Pendente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="filterOrientador" class="form-control" placeholder="Filtrar por Orientador">
                </div>
            </div>
        </div>

        <!-- Tabela de alunos com fundo branco, borda arredondada e sombra -->
        <div class="table-container">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Matrícula</th>
                        <th scope="col">Etapa</th>
                        <th scope="col">Email</th>
                        <th scope="col">Orientador</th>
                        <th scope="col">Status</th>
                        <th scope="col">Classroom Atual</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alunos as $aluno)
                    <tr class="fw-normal">
                        <td class="align-middle">{{ $aluno->nome }}</td>
                        <td class="align-middle">{{ $aluno->matricula }}</td>
                        <td class="align-middle">
                            @if($aluno->etapa==1)
                            Estágio I: Planejamento
                            @elseif($aluno->etapa==2)
                            Estágio I: Desenvolvimento
                            @elseif($aluno->etapa==3)
                            Estágio II: Desenvolvimento
                            @elseif($aluno->etapa==4)
                            Estágio II: Final
                            @else
                            ERRO
                            @endif
                        </td>
                        <td class="align-middle">{{ $aluno->email_aluno }}</td>
                        <td class="align-middle">{{ $aluno->email_orientador }}</td>
                        <td class="align-middle">
                            <span class="badge {{ $aluno->pendente ? 'bg-danger' : 'bg-success' }}">{{ $aluno->pendente ? 'Pendente' : 'Normal' }}</span>
                        </td>
                        <td class="align-middle">Etapa I: Planejamento 2024</td>
                        <td class="align-middle">
                            <button class="btn btn-danger btn-sm desativar-aluno" data-email="{{ $aluno->email_aluno }}">Desativar</button>
                            <div class="btn-group dropend"> <!-- Adiciona dropend para posicionar o dropdown à direita -->
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end fs-6 p-3">
                                    <li><button class="dropdown-item alterar-bimestre" data-bs-toggle="modal" data-bs-target="#alterarEtapaModal" data-email="{{ $aluno->email_aluno }}">Alterar Etapa</button></li>
                                    <li><button class="dropdown-item alterar-professor" data-bs-toggle="modal" data-bs-target="#alterarProfessorModal" data-email="{{ $aluno->email_aluno }}">Alterar Professor</button></li>
                                    <li><button class="dropdown-item alterar-status" data-bs-toggle="modal" data-bs-target="#alterarStatusModal" data-email="{{ $aluno->email_aluno }}">Alterar Status</button></li>
                                </ul>
                            </div>
                        </td>



                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>


    <div class="modal fade" id="mainMenuModal" tabindex="-1" aria-labelledby="mainMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mainMenuModalLabel">Opções</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary w-100 mb-2" onclick="openAdicionarAlunosModal()">Adicionar Alunos</button>
                    <button class="btn btn-primary w-100 mb-2" onclick="openClassroomsModal()">Classrooms</button>
                    <button class="btn btn-primary w-100 mb-2" onclick="openFinalizarEtapaModal()">Finalizar Etapa</button>
                    <a class="btn btn-primary w-100 mb-2" href="/admin/alunosdesativados">Alunos Desativos</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" onclick="submitAlterarEtapa()">Salvar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para Adicionar Alunos -->
    <div class="modal fade" id="adicionarAlunosModal" tabindex="-1" aria-labelledby="adicionarAlunosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarAlunosModalLabel">Adicionar Alunos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="adicionarAlunosForm" action="/admin/adicionar-alunos" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="dropzone" onclick="document.getElementById('arquivoCSV').click()">
                            <input type="file" id="arquivoCSV" name="arquivoCSV" accept=".csv">
                            <p>Arraste e solte o arquivo CSV aqui ou clique para selecionar.</p>
                        </div>
                        <select class="form-select" id="idClassroom" name="classroom_user" required>
                            <p>Escolha um curso para coleta dos dados do classroom</p>
                            @foreach($classroomBanco as $classBD)
                            <option value="{{$classBD->id_classroom}}">{{$classBD->nome}}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" onclick="submitAdicionarAlunos()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Classroom -->
    <div class="modal fade" id="classroomModal" tabindex="-1" aria-labelledby="classroomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="classroomModalLabel">Classrooms</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="classroomForm" action="" method="POST">
                        @csrf
                        <input type="hidden" id="adicionarClassroom" name="email_aluno">
                        <div class="form-group">
                            <h3>Cursos que estão no Classroom</h3>
                            <select class="form-select" id="classroomUser" name="classroom_user" required>
                            @foreach($classroomUser as $class)
                                <option value='{"class_id":"{{$class["id"]}}","class_ownerId":"{{$class["ownerId"]}}","class_nome":"{{$class["nome"]}}"}'>{{$class["nome"]}}</option>
                            @endforeach
                            </select>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" onclick="submitCadastrarClassroom()">Cadastrar</button>
                    </div>
                </div>
                <hr>
                <div class="modal-body">
                    <h2>Cursos cadastrados no sistema</h2>
                        @foreach($classroomBanco as $classBD)
                        {{$classBD->nome}}
                        @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Alterar Etapa -->
    <div class="modal fade" id="alterarEtapaModal" tabindex="-1" aria-labelledby="alterarEtapaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alterarEtapaModalLabel">Alterar Etapa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="alterarEtapaForm" action="" method="POST">
                        @csrf
                        <input type="hidden" id="alterarEtapaEmail" name="email_aluno">
                        <div class="form-group">
                            <label for="novaEtapa">Nova Etapa</label>
                            <select class="form-select" id="novaEtapa" name="nova_etapa" required>
                                <option value="1">Estágio I: Planejamento</option>
                                <option value="2">Estágio I: Desenvolvimento</option>
                                <option value="3">Estágio II: Desenvolvimento</option>
                                <option value="4">Estágio II: Final</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" onclick="submitAlterarEtapa()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Alterar Professor -->
    <div class="modal fade" id="alterarProfessorModal" tabindex="-1" aria-labelledby="alterarProfessorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alterarProfessorModalLabel">Alterar Professor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="alterarProfessorForm" action="" method="POST">
                        @csrf
                        <input type="hidden" id="alterarProfessorEmail" name="email_aluno">
                        <div class="form-group">
                            <label for="novoProfessor">Novo Professor</label>
                            <select class="form-select" id="novoProfessor" name="novo_professor" required>
                                @foreach($users as $user)
                                <option value="{{ $user->email }}">{{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" onclick="submitAlterarProfessor()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Desativar Aluno -->
    <div class="modal fade" id="desativarAlunoModal" tabindex="-1" aria-labelledby="desativarAlunoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="desativarAlunoModalLabel">Desativar Aluno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja desativar este aluno?</p>
                    <form id="desativarAlunoForm" action="" method="POST">
                        @csrf
                        <input type="hidden" id="desativarAlunoEmail" name="email_aluno">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" onclick="submitDesativarAluno()">Desativar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para Alterar Status -->
    <div class="modal fade" id="alterarStatusModal" tabindex="-1" aria-labelledby="alterarStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alterarStatusModalLabel">Altear Status Aluno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja alterar o status do aluno?</p>
                    <form id="alterarStatusForm" action="" method="POST">
                        @csrf
                        <input type="hidden" id="alterarStatusEmail" name="email_aluno">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" onclick="submitAlterarStatus()">Alterar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para Finalizar Etapa -->
    <div class="modal fade" id="finalizarEtapaModal" tabindex="-1" aria-labelledby="finalizarEtapaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alterarEtapaModalLabel">Alterar Etapa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="etapa" class="form-label">Selecionar Etapa</label>
                            <form id="finalizarEtapaForm" action="" method="POST">
                                @csrf
                                <select class="form-select" id="etapa" name="etapa" required>
                                    <option value="1">Estágio I: Planejamento</option>
                                    <option value="2">Estágio I: Desenvolvimento</option>
                                    <option value="3">Estágio II: Desenvolvimento</option>
                                    <option value="4">Estágio II: Final</option>
                                </select>
                                <label for="classroomnovo" class="form-label">Selecionar Novo Classroom</label>
                                <select class="form-select" id="classroomnovo" name="classroomnovo" required>
                                    <option value="">Não mudar classroom</option>
                                    @foreach($classroomBanco as $classBD)
                                    <option value="{{ $classBD->id_classroom }}">{{ $classBD->nome }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" onclick="submitFinalizarEtapa()">Finalizar Etapa</button>
                </div>
            </div>
    </div>


    <script>
        // Função para abrir o modal de adicionar alunos
        function openAdicionarAlunosModal() {
            var adicionarAlunosModal = new bootstrap.Modal(document.getElementById('adicionarAlunosModal'));
            adicionarAlunosModal.show();
        }

        function openClassroomsModal() {
            var classroomModal = new bootstrap.Modal(document.getElementById('classroomModal'));
            classroomModal.show();
        }

        function openFinalizarEtapaModal() {
            var etapaModal = new bootstrap.Modal(document.getElementById('finalizarEtapaModal'));
            etapaModal.show();
        }

        // Evento para capturar o clique do botão "Alterar Etapa"
        document.querySelectorAll('.alterar-bimestre').forEach(button => {
            button.addEventListener('click', function() {
                const email = this.dataset.email;
                document.getElementById('alterarEtapaEmail').value = email;

                var alterarEtapaModal = new bootstrap.Modal(document.getElementById('alterarEtapaModal'));
                alterarEtapaModal.show();
            });
        });

        // Evento para capturar o clique do botão "Alterar Professor"
        document.querySelectorAll('.alterar-professor').forEach(button => {
            button.addEventListener('click', function() {
                const email = this.dataset.email;
                document.getElementById('alterarProfessorEmail').value = email;

                var alterarProfessorModal = new bootstrap.Modal(document.getElementById('alterarProfessorModal'));
                alterarProfessorModal.show();
            });
        });


        document.querySelectorAll('.alterar-status').forEach(button => {
            button.addEventListener('click', function() {
                const email = this.dataset.email;
                document.getElementById('alterarStatusEmail').value = email;

                var alterarStatusModal = new bootstrap.Modal(document.getElementById('alterarStatusModal'));
                alterarStatusModal.show();
            });
        });

        // Evento para capturar o clique do botão "Desativar Aluno"
        document.querySelectorAll('.desativar-aluno').forEach(button => {
            button.addEventListener('click', function() {
                const email = this.dataset.email;
                document.getElementById('desativarAlunoEmail').value = email;

                var desativarAlunoModal = new bootstrap.Modal(document.getElementById('desativarAlunoModal'));
                desativarAlunoModal.show();
            });
        });

        function submitFinalizarEtapa() {
            const classroom = document.getElementById('classroomnovo').value;
            const etapa = document.getElementById('etapa').value;
            const form = document.getElementById('finalizarEtapaForm');
            form.action = `/admin/alunosadmin/passaretapamanual/${etapa}/novoclassroom/${classroom}`;
            form.submit();
        }

        // Função para submeter o formulário de alterar etapa com a rota correta
        function submitAlterarEtapa() {
            const email = document.getElementById('alterarEtapaEmail').value;
            const etapa = document.getElementById('novaEtapa').value;
            const form = document.getElementById('alterarEtapaForm');
            form.action = `/admin/alunosadmin/alterarbimestre/${etapa}/aluno/${email}`;
            form.submit();
        }

        // Função para submeter o formulário de alterar professor com a rota correta
        function submitAlterarProfessor() {
            const alunoEmail = document.getElementById('alterarProfessorEmail').value;
            const professorEmail = document.getElementById('novoProfessor').value;
            const form = document.getElementById('alterarProfessorForm');
            form.action = `/admin/alunosadmin/alterarprofessor/${professorEmail}/aluno/${alunoEmail}`;
            form.submit();
        }

        // Função para submeter o formulário de desativar aluno com a rota correta
        function submitDesativarAluno() {
            const email = document.getElementById('desativarAlunoEmail').value;
            const form = document.getElementById('desativarAlunoForm');
            form.action = `/admin/alunosadmin/desativar/${email}`;
            form.submit();
        }

        function submitAlterarStatus() {
            const email = document.getElementById('alterarStatusEmail').value;
            const form = document.getElementById('alterarStatusForm');
            form.action = `/admin/alunosadmin/alterarstatus/${email}`;
            form.submit();
        }

        function submitCadastrarClassroom() {
            const selectedClass = document.getElementById('classroomUser').value;
            
            const classInfo = JSON.parse(selectedClass);

            const form = document.getElementById('classroomForm');
            form.action = `/admin/alunosadmin/cadastrarcurso/${classInfo.class_id}/owner/${classInfo.class_ownerId}/nome/${classInfo.class_nome}`;
            form.submit();
        }

        function submitAdicionarAlunos() {
            const arquivoCSV = document.getElementById('arquivoCSV').files[0];
            const idClassroom = document.getElementById('idClassroom').value;

            const form = document.getElementById('adicionarAlunosForm');
            form.action = `/admin/alunosadmin/adicionaralunos/classroom/${idClassroom}`;

            if (arquivoCSV && idClassroom) {
                form.submit();
            } else {
                alert('Por favor, selecione um arquivo CSV e um curso.');
            }
        }
    </script>

    <script>
        // Escutando mudanças nos campos de filtro
        document.getElementById('searchName').addEventListener('input', filterTable);
        document.getElementById('filterEtapa').addEventListener('change', filterTable);
        document.getElementById('filterStatus').addEventListener('change', filterTable);
        document.getElementById('filterOrientador').addEventListener('input', filterTable);

        function filterTable() {
            const searchName = document.getElementById('searchName').value.toLowerCase();
            const filterEtapa = document.getElementById('filterEtapa').value;
            const filterStatus = document.getElementById('filterStatus').value;
            const filterOrientador = document.getElementById('filterOrientador').value.toLowerCase();

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const nome = row.querySelector('td:nth-child(1)').innerText.toLowerCase();
                const etapa = row.querySelector('td:nth-child(3)').innerText;
                const status = row.querySelector('td:nth-child(6) .badge').classList.contains('bg-danger') ? '1' : '0';
                const orientador = row.querySelector('td:nth-child(5)').innerText.toLowerCase();

                const matchesName = nome.includes(searchName);
                const matchesEtapa = filterEtapa === "" || etapa.includes(`${filterEtapa}`);
                const matchesStatus = filterStatus === "" || status === filterStatus;
                const matchesOrientador = orientador.includes(filterOrientador);

                if (matchesName && matchesEtapa && matchesStatus && matchesOrientador) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>


</body>
</html>
