<?php
namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Aluno;
use Google\Client;
use Google\Service\Classroom as GoogleClassroom;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ClassroomController extends Controller
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
    }


    public function listarCursosUserAutenticado()

    {
        $token = Cookie::get('google_login_token');
        $refreshToken = Cookie::get('google_login_refresh_token');

        $this->client->setAccessToken($token);

        if ($this->client->isAccessTokenExpired()) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

            if (isset($newToken[1])) {
                $expiresIn = 60;
                $this->client->setAccessToken($newToken[1]);
            } else {
                echo "<script>console.log('cant renew token')</script>";
            }
        }    

        $this->service = new GoogleClassroom($this->client);

        $courses = $this->service->courses->listCourses();

        return $courses;
    }


    public function adicionarCursoBanco($id_curso, $id_dono, $nome)
    {
        Classroom::create([
            'id_classroom' => $id_curso,
            'id_dono' => $id_dono,
            'nome' => $nome,
            'ativo' => true,
        ]);

        return redirect('/admin/alunosadmin');
    }


    public function pegarAlunosCurso($id_curso)
    {
        $token = Cookie::get('google_login_token');
        $refreshToken = Cookie::get('google_login_refresh_token');

        $this->client->setAccessToken($token);

        if ($this->client->isAccessTokenExpired()) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

            if (isset($newToken['access_token'])) {
                $expiresIn = 60;
                $this->client->setAccessToken($newToken['access_token']);
            } else {
                echo "<script>console.log('cannot renew token')</script>";
            }
        }

        $this->service = new GoogleClassroom($this->client);

        $students = $this->service->courses_students->listCoursesStudents($id_curso);
        $studentData = [];

        foreach ($students->getStudents() as $student) {
            $profile = $student->getProfile();

            $studentData[] = [
                'name' => $profile->getName()->getFullName(),
                'email' => $profile->getEmailAddress() ?? 'Email não disponível',
            ];
        }

        return $studentData;
    }


    public function pegarClassrooms()
    {
        $classrooms = Classroom::all();
        return $classrooms;
    }
    


    public function pegarAtividadesCursoAluno($id_curso, $email_aluno)
    {
        $user = Auth::user();
        $this->client->setAccessToken($user->token);
        $this->service = new GoogleClassroom($this->client);

        $courseWork = $this->service->courses_courseWork->listCoursesCourseWork($id_curso);

        $assignments = [];
        foreach ($courseWork->getCourseWork() as $work) {
            $assignments[] = [
                'title' => $work->title,
                'dueDate' => $work->dueDate,
                'state' => $work->state,
                'grade' => $work->assignedGrade,
            ];
        }

        return $assignments;
    }


    public function sincronizarAtividades($idClassroom)
    {
        $classroom = Classroom::findOrFail($idClassroom);
        $this->client->setAccessToken(Cookie::get('google_login_token'));
        $this->service = new GoogleClassroom($this->client);

        $courseWork = $this->service->courses_courseWork->listCoursesCourseWork($idClassroom);

        foreach ($courseWork->getCourseWork() as $work) {
            Atividade::updateOrCreate(
                ['titulo' => $work->title, 'id_classroom' => $idClassroom],
                [
                    'data_criacao' => now(),
                    'data_entrega' => $work->dueDate,
                    'nota' => 0,
                    'entregue' => false,
                    'titulo' => $work->title,
                ]
            );
        }

        return "Atividades sincronizadas com sucesso!";
    }


    public function verificarClassroomAtivo($idClassroom)
    {
        $this->client->setAccessToken(Cookie::get('google_login_token'));
        $this->service = new GoogleClassroom($this->client);

        try {
            $this->service->courses->get($idClassroom);
        } catch (\Exception $e) {
            $classroom = Classroom::findOrFail($idClassroom);
            $classroom->ativo = false;
            $classroom->save();

            return "Classroom desativado com sucesso!";
        }

        return "Classroom ainda está ativo.";
    }

    public function verificarAlunosClassroom($idClassroom)
    {
        #verificar se os alunos a mudarem de classrom, estão no classroom (pela api, vendo pelo email)
    }



}
