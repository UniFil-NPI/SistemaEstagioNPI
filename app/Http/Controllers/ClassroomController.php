<?php
namespace App\Http\Controllers;

use App\Models\Classroom;
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
        #$this->client->setApplicationName('Sistema de Estágio');    
        #$this->client->setScopes([
            #GoogleClassroom::CLASSROOM_COURSES_READONLY, 
            #GoogleClassroom::CLASSROOM_ROSTERS_READONLY, 
            #GoogleClassroom::CLASSROOM_COURSEWORK_ME_READONLY
        #]);
        #$this->client->setAccessType('offline');

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
                #Cookie::queue(Cookie::make('google_login_token', $newToken, $expiresIn , null, null, true, true));
                $this->client->setAccessToken($newToken[1]);
            } else {
                echo "<script>console.log('cant renew token')</script>";
            }
        }    

        $this->service = new GoogleClassroom($this->client);

        $courses = $this->service->courses->listCourses();

        return $courses;
    }

    public function pegarClassrooms()
    {
        $classrooms = Classroom::all();

        return $classrooms;
    }

    public function adicionarCursoBanco($id_curso,$id_dono,$nome)
    {

        Classroom::create([
            'id_classroom' => $id_curso,
            'id_dono' => $id_dono,
            'nome' => $nome,
            'ativo' => TRUE,
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
                #Cookie::queue(Cookie::make('google_login_token', $newToken['access_token'], $expiresIn, null, null, true, true));
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

            #dd($profile);

            $studentData[] = [
                'name' => $profile->getName()->getFullName(),
                'email' => $profile->getEmailAddress() ?? 'Email não disponível', // Se o email for nulo, usar uma string padrão
            ];
        }

        return $studentData;
    }


    public function pegarAtividadesAluno($email_aluno)
    {
        $user = Auth::user();
        $this->client->setAccessToken($user->token);
        $this->service = new GoogleClassroom($this->client);

        $assignments = [];
        $courses = $this->service->courses->listCourses();

        foreach ($courses->getCourses() as $course) {
            $courseWork = $this->service->courses_courseWork->listCoursesCourseWork($course->id, [
                'userId' => $email_aluno
            ]);

            foreach ($courseWork->getCourseWork() as $work) {
                $assignments[] = [
                    'course' => $course->name,
                    'title' => $work->title,
                    'dueDate' => $work->dueDate,
                    'state' => $work->state,
                    'grade' => $work->assignedGrade,
                ];
            }
        }

        return response()->json($assignments, 200);
    }
}
