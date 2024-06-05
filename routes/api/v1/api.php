<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Referensi\JenisLeadSpipController;
use App\Http\Controllers\Api\V1\Referensi\JenisSasaranController;
use App\Http\Controllers\Api\V1\Referensi\KategoriRisikoController;
use App\Http\Controllers\Api\V1\Referensi\KkLeadSpipController;
use App\Http\Controllers\Api\V1\Referensi\KlpController;
use App\Http\Controllers\Api\V1\Referensi\LokasiController;
use App\Http\Controllers\Api\V1\Transaction\DataOpiniController;
use App\Http\Controllers\Api\V1\Transaction\PenilaianController;
use App\Http\Controllers\Api\V1\Transaction\ProgramController;
use App\Http\Controllers\Api\V1\Transaction\SasaranController;
use App\Http\Controllers\Api\V1\Transaction\SasaranIndikatorController;
use App\Http\Controllers\Api\V1\Transaction\SasaranProgramController;
use App\Http\Controllers\Api\V1\Transaction\VisiMisiController;
use App\Http\Controllers\Api\V1\Transaction\PenilaianDaftarUserController;
use App\Http\Controllers\Api\V1\Transaction\PenilaianTemuanController;
use App\Http\Controllers\Api\V1\User\MenuController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use App\Http\Controllers\Api\V1\User\RegistrationController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

# REFERENSI
Route::prefix('ref')->group(function () {
    Route::prefix('klp')->controller(KlpController::class)->group(function () {
        # FOR REGISTRATION NO AUTH
        Route::get('/list', 'listKlp');

        # CRUD WITH AUTH
        Route::middleware(['auth:api'])->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/list-opd', 'listOPD');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
            Route::get('/user/{id}', 'listKlpWithUser');
        });
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::prefix('lokasi')->controller(LokasiController::class)->group(function () {
            Route::get('/list', 'list');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        Route::prefix('kategori-risiko')->controller(KategoriRisikoController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
            Route::get('/list-klp/{id}', 'listByKlp');
        });

        Route::prefix('kk-lead-spip')->controller(KkLeadSpipController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/list-parent', 'listParent');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        Route::prefix('jenis-sasaran')->controller(JenisSasaranController::class)->group(function () {
            Route::get('/list', 'list');
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        Route::prefix('jenis-leadspip')->controller(JenisLeadSpipController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/list', 'list');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });
});

# TRANSACTION
Route::prefix('trans')->group(function () {
    Route::prefix('penilaian')->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::prefix('sasaran')->controller(SasaranController::class)->group(function () {
                Route::get('/list', 'list');
                Route::get('/', 'index');
                Route::get('/{id}', 'show');
                Route::post('/', 'store');
                Route::put('/update-checklist/{id}', 'updateChecklist');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::prefix('sasaran-indikator')->controller(SasaranIndikatorController::class)->group(function () {
                Route::get('/list', 'list');
                Route::get('/', 'index');
                Route::get('/{id}', 'show');
                Route::post('/', 'store');
                Route::put('/update-checklist/{id}', 'updateChecklist');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::controller(ProgramController::class)->group(function () {
                Route::get('/program/list', 'list');
                Route::get('/program', 'index');
                Route::get('/program/{id}', 'show');
                Route::post('/program', 'store');
                Route::put('/program/{id}', 'update');
                Route::delete('/program/{id}', 'destroy');
            });

            Route::controller(SasaranProgramController::class)->group(function () {
                Route::middleware(['auth:api'])->group(function () {
                    Route::get('/sasaran-program', 'index');
                    Route::get('/sasaran-program/{id}', 'show');
                    Route::post('/sasaran-program', 'store');
                    Route::put('/sasaran-program/{id}', 'update');
                    Route::delete('/sasaran-program/{id}', 'destroy');
                    Route::get('/sasaran-program/list-penilaian/{id}', 'listByPenilaian');
                });
            });

            Route::controller(PenilaianDaftarUserController::class)->group(function () {
                Route::middleware(['auth:api'])->group(function () {
                    Route::get('/penilaian-daftar-user', 'index');
                    Route::get('/penilaian-daftar-user/search', 'autocompleteNama');
                    Route::get('/penilaian-daftar-user/{id}', 'show');
                    Route::post('/penilaian-daftar-user', 'store');
                    Route::put('/penilaian-daftar-user/{id}', 'update');
                    Route::delete('/penilaian-daftar-user/{id}', 'destroy');
                    Route::get('/penilaian-daftar-user/{id}/download-surat-tugas', 'downloadSuratTugas');
                });
            });

            Route::prefix('data-opini')->controller(DataOpiniController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/{id}', 'show');
                Route::post('/', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::prefix('penilaian-temuan')->controller(PenilaianTemuanController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/{id}', 'show');
                Route::post('/', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

        });

        Route::controller(PenilaianController::class)->group(function () {
            Route::get('list', 'listpenilaian');
            # CRUD WITH AUTH
            Route::middleware(['auth:api'])->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::put('/anggaran/{id}', 'updateAnggaran');
                Route::delete('/{id}', 'destroy');
                Route::get('/user/{id}', 'listKlpWithUser');
                Route::get('/{id}', 'show');
            });
        });

        Route::controller(VisiMisiController::class)->group(function () {
            Route::middleware(['auth:api'])->group(function () {
                Route::get('/{id}/visi-misi', 'show');
                Route::post('{id}/visi-misi', 'store');
            });
        });
    });
});

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
});

Route::middleware(['auth:api'])->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('user', 'me');
    });
    Route::controller(MenuController::class)->group(function () {
        Route::get('menu/me', 'me');
        Route::get('menu/me-new', 'me2');
    });
});

Route::controller(RegistrationController::class)->group(function () {
    Route::post('registration', 'registration');
    Route::get('registration/email-verification', 'registrationEmailVerification');
});

Route::prefix('approval')->controller(RegistrationController::class)->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/list-user', 'index');
        Route::get('/count', 'countApprovalStatus');
        Route::get('/{id}', 'show');
        Route::put('/{action}/{id}', 'approve');
    });
});

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/list-user', 'index');
        Route::get('/count', 'countActiveStatus');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});
