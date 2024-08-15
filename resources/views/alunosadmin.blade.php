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
            background-color: #f8f9fa;
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

        /* Estilo da lista de tarefas */
        .task-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .task-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #e1e1e1;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .task-list-item img {
            max-width: 50px;
            margin-right: 15px;
            border-radius: 50%;
        }

        .task-list-item .task-info {
            flex-grow: 1;
            display: flex;
            align-items: center;
        }

        .task-list-item .task-info p {
            margin: 0 15px;
            font-size: 0.875em;
            color: #777;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-size: 0.875em;
            font-weight: bold;
        }

        .status-normal {
            background-color: #28a745; /* Verde */
        }

        .status-pendente {
            background-color: #dc3545; /* Vermelho */
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
            background: rgba(24, 24, 16, 0.2);
            border-radius: 2em;
            backdrop-filter: blur(25px);
            border: 2px solid rgba(255, 255, 255, 0.05);
            background-clip: padding-box;
            box-shadow: 10px 10px 10px rgba(46, 54, 68, 0.03);
            width: 130%;
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

    <section class="vh-100 gradient-custom-2">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-12 col-xl-10">

        <div class="card mask-custom">
          <div class="card-body p-4 text-white">

            <table class="table text-white mb-0">
              <thead>
                <tr>
                  <th scope="col">Nome</th>
                  <th scope="col">Matrícula</th>
                  <th scope="col">Bimestre</th>
                  <th scope="col">Email</th>
                  <th scope="col">Status</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody>
                @foreach($alunos as $aluno)
                <tr class="fw-normal">
                  <th>
                    <img src="{{ $aluno['avatar'] }}" alt="" style="width: 45px; height: auto;">
                    <span class="ms-2">{{ $aluno->nome }}</span>
                  </th>
                  <td class="align-middle">
                    <span>{{ $aluno->matricula }}</span>
                  </td>
                  <td class="align-middle">
                    <span>{{ $aluno->bimestre }}</span>
                  </td>
                  <td class="align-middle">
                    <span>{{ $aluno->email_aluno }}</span>
                  </td>
                  <td class="align-middle">
                    <h6 class="mb-0"><span class="badge {{ $aluno->ativo ? 'bg-success' : 'bg-danger' }}">{{ $aluno->ativo ? 'Normal' : 'Pendente' }}</span></h6>
                  </td>
                  <td class="align-middle">
                    <a href="#" class="btn btn-primary mb-1" onclick="openBimestreModal('{{ $aluno['id'] }}', '{{ $aluno['etapa'] }}')">Alterar Bimestre</a>
                    <a href="#" class="btn btn-primary mb-1" onclick="openProfessorModal('{{ $aluno['id'] }}')">Alterar Professor</a>
                    <a href="/admin/alunosadmin/desativar/{{ $aluno['email'] }}" class="btn btn-danger mb-1">Desativar</a>
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
</section>


                <!-- Modal para Adicionar Alunos -->
                <div class="modal fade" id="adicionarAlunosModal" tabindex="-1" aria-labelledby="adicionarAlunosModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="adicionarAlunosModalLabel">Adicionar Alunos</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="adicionarAlunosForm" action="/admin/alunosadmin/adicionar" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="dropzone" id="dropzone">
                                        <p>Arraste e solte o arquivo CSV aqui ou</p>
                                        <input type="file" id="fileInput" name="csv_file" accept=".csv" required>
                                        <button type="button" class="btn btn-primary mt-2" onclick="document.getElementById('fileInput').click()">Selecionar Arquivo</button>
                                    </div>
                                    <div class="mb-3">
                                        <label for="courseSelect" class="form-label">Selecionar Curso</label>
                                        <select class="form-select" id="courseSelect" name="course_id" required>
                                            <option value="1">Opcao 1</option>
                                            <option value="2">Opcao 2</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        <button type="submit" class="btn btn-primary" id="continueBtn" disabled>Continuar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Alterar Bimestre -->
                <div class="modal fade" id="bimestreModal" tabindex="-1" aria-labelledby="bimestreModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bimestreModalLabel">Alterar Bimestre</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="/admin/alunosadmin/alterarbimestre" method="POST">
                                    @csrf
                                    <input type="hidden" id="bimestreAlunoId" name="aluno_id" value="">
                                    <div class="mb-3">
                                        <label for="bimestreSelect" class="form-label">Selecionar Etapa</label>
                                        <select class="form-select" id="bimestreSelect" name="bimestre">
                                            <option value="1">Estágio I: Planejamento</option>
                                            <option value="2">Estágio I: Desenvolvimento</option>
                                            <option value="3">Estágio II: Desenvolvimento</option>
                                            <option value="4">Estágio II: Final</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Alterar Professor -->
                <div class="modal fade" id="professorModal" tabindex="-1" aria-labelledby="professorModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="professorModalLabel">Alterar Professor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="/admin/alunosadmin/alterarprofessor" method="POST">
                                    @csrf
                                    <input type="hidden" id="professorAlunoId" name="aluno_id" value="">
                                    <div class="mb-3">
                                        <label for="professorSelect" class="form-label">Selecionar Professor</label>
                                        <select class="form-select" id="professorSelect" name="professor_id">
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

    <!-- Scripts para abertura dos modais -->
    <script>
        function openBimestreModal(alunoId, etapaAtual) {
            document.getElementById('bimestreAlunoId').value = alunoId;
            document.getElementById('bimestreSelect').value = etapaAtual;
            var myModal = new bootstrap.Modal(document.getElementById('bimestreModal'));
            myModal.show();
        }

        function openProfessorModal(alunoId) {
            document.getElementById('professorAlunoId').value = alunoId;
            var myModal = new bootstrap.Modal(document.getElementById('professorModal'));
            myModal.show();
        }

        document.getElementById('fileInput').addEventListener('change', validateFileAndCourse);
        document.getElementById('courseSelect').addEventListener('change', validateFileAndCourse);

        function validateFileAndCourse() {
            const fileInput = document.getElementById('fileInput');
            const courseSelect = document.getElementById('courseSelect');
            const continueBtn = document.getElementById('continueBtn');

            if (fileInput.files.length > 0 && courseSelect.value) {
                continueBtn.disabled = false;
            } else {
                continueBtn.disabled = true;
            }
        }

        function openAdicionarAlunosModal() {
            var myModal = new bootstrap.Modal(document.getElementById('adicionarAlunosModal'));
            myModal.show();
        }
    </script>

</body>
</html>
