<?php

use App\Livewire\Adm\Dashboard as AdmDashboard;
use App\Livewire\Adm\UserLevelManagement;
use App\Livewire\Evento\Cestas;
use App\Livewire\Evento\Dashboard;
use App\Livewire\Evento\Entregas;
use App\Livewire\Evento\Instituicoes;
use App\Livewire\Evento\Terreiros;
use App\Livewire\Universal\Banners;
use App\Livewire\Universal\Blocos;
use App\Livewire\Universal\Categorias;
use App\Livewire\Universal\Dashboard as UniversalDashboard;
use App\Livewire\Universal\Igrejas;
use App\Livewire\Universal\Pastores;
use App\Livewire\Universal\Pessoas;
use App\Livewire\Universal\Regiaos;
use App\Livewire\Unp\Grupos;
use App\Livewire\Unp\Cargos;
use App\Livewire\Unp\Cursos;
use App\Livewire\Unp\Dashboard as UnpDashboard;
use App\Livewire\Unp\Formaturas;
use App\Livewire\Unp\Instrutores;
use App\Livewire\Unp\Presidios;
use App\Livewire\Unp\Reeducandos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/download-pdf/{filename}', function ($filename) {
    $path = public_path('storage/temp/' . $filename);
    if (File::exists($path)) {
        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }
    \Log::error('PDF não encontrado', ['path' => $path]);
    abort(404);
})->where('filename', 'relatorio-cestas-[0-9]+\.pdf');

Route::get('/test-session', function (Request $request) {
    $request->session()->put('test', 'Session working');
    return [
        'session_test' => $request->session()->get('test'),
        'session_id' => $request->session()->getId(),
    ];
});

// Rota padrão (não requer autenticação)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Grupo de rotas protegidas por autenticação
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Rota de dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rota de documentação
    Route::get('/documentacao', function () {
        return view('components.documentacao');
    })->middleware('permission:view documentacao')->name('documentacao');

    // Rota de gestão de níveis de usuário (apenas superadmin)
    Route::get('/adm/user-levels', UserLevelManagement::class)->middleware('permission:superadmin')->name('user-levels');

    // Rotas Livewire
    Route::get('/universal/categorias', Categorias::class)->middleware('permission:view categorias')->name('categorias');
    Route::get('/universal/blocos', Blocos::class)->middleware('permission:view blocos')->name('blocos');
    Route::get('/universal/regiaos', Regiaos::class)->middleware('permission:view regiaos')->name('regiaos');
    Route::get('/universal/igrejas', Igrejas::class)->middleware('permission:view igrejas')->name('igrejas');
    Route::get('/universal/pessoas', Pessoas::class)->middleware('permission:view pessoas')->name('pessoas');
    Route::get('/universal/pastores', Pastores::class)->middleware('permission:view pastores')->name('pastores');
    Route::get('/universal/banners', Banners::class)->middleware('permission:view banners')->name('banners');
    Route::get('/unp/grupos', Grupos::class)->middleware('permission:view grupos')->name('grupos');
    Route::get('/unp/cargos', Cargos::class)->middleware('permission:view cargos')->name('cargos');
    Route::get('/unp/cursos', Cursos::class)->middleware('permission:view cursos')->name('cursos');
    Route::get('/unp/formaturas', Formaturas::class)->middleware('permission:view formaturas')->name('formaturas');
    Route::get('/unp/instrutores', Instrutores::class)->middleware('permission:view instrutores')->name('instrutores');
    Route::get('/unp/reeducandos', Reeducandos::class)->middleware('permission:view reeducandos')->name('reeducandos');
    Route::get('/unp/presidios', Presidios::class)->middleware('permission:view presidios')->name('presidios');
    Route::get('/evento/terreiros', Terreiros::class)->middleware('permission:view terreiros')->name('terreiros');
    Route::get('/evento/instituicoes', Instituicoes::class)->middleware('permission:view instituicoes')->name('instituicoes');
    Route::get('/evento/cestas', Cestas::class)->middleware('permission:view cestas')->name('cestas');
    Route::get('/evento/entregas', Entregas::class)->middleware('permission:view entregas')->name('entregas');
    Route::get('/adm/dashboard', AdmDashboard::class)->middleware('permission:view adm dashboard')->name('dashboard.adm');
    Route::get('/evento/dashboard', Dashboard::class)->middleware('permission:view evento dashboard')->name('dashboard.ev');
    Route::get('/unp/dashboard', UnpDashboard::class)->middleware('permission:view unp dashboard')->name('dashboard.unp');
    Route::get('/universal/dashboard', UniversalDashboard::class)->middleware('permission:view universal dashboard')->name('dashboard.uni');
});
