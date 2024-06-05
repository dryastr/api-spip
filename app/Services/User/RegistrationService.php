<?php

namespace App\Services\User;

use App\Exceptions\ServiceErrorException;
use App\Mail\Notification;
use App\Repositories\Eloquent\User\RegistrationRepository;
use App\Repositories\Eloquent\User\UserRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Exception;

class RegistrationService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param UserRepository $repositoryUser
     * @param RegistrationRepository $repositoryRegistration
     */
    public function __construct(
        protected UserRepository $repositoryUser = new UserRepository(),
        protected RegistrationRepository $repositoryRegistration = new RegistrationRepository(),
    ) {
    }

    public function saveAttachment(array $attachmentData)
    {
        return DB::table('trans_attachment')->insert($attachmentData);
    }

    /**
     * Registration
     *
     * @throws ServiceErrorException
     * @throws Exception
     */
    public function registration($data)
    {
        try {
            $checkUserActive = $this->repositoryUser->findBy(
                key: [
                    'email' => $data['email'],
                ]
            );

            if ($checkUserActive && $checkUserActive->status === 'active') {
                throw new ServiceErrorException('Data Sudah Aktif', 422);
            }

            # Upload File Surat Permohonan
            $file = $data['file_surat_permohonan']->getContent();
            $file_extension = $data['file_surat_permohonan']->extension();
            $date = now()->toDateString();

            $file = file_helper()->upload(
                    fileContent: $file,
                    ext: $file_extension,
                    path: 'registration/' . $date
                );

            # Upload File Surat Permohonan
            $file2 = $data['file_sk_penunjukan_admin']->getContent();
            $file_extension2 = $data['file_sk_penunjukan_admin']->extension();
            $date2 = now()->toDateString();

            $file2 = file_helper()->upload(
                fileContent: $file2,
                ext: $file_extension2,
                path: 'registration/' . $date2
            );


            $data['email_verify_key'] = \Str::random(20);

            $result = DB::transaction(function () use ($data, $file, $file2) {

                $user = $this->repositoryUser->updateOrCreate(
                    key: [
                        'email' => $data['email'],
                    ],
                    attributes: [
                        'fullname' => $data['fullname'],
                        'username' => $data['email'],
                        'email_verify_key' => $data['email_verify_key'],
                        'password' => \Hash::make($data['password']),
                        'status' => 'inactive',
                        'created_by_name' => $data['fullname'],
                        'updated_by_name' => $data['fullname'],
                    ]
                );

                $dataRegistration = \Arr::except($data, [
                    'email',
                    'fullname',
                    'password',
                    'file_surat_permohonan',
                    'file_sk_penunjukan_admin',
                    'email_verify_key'
                ]);

                if (!isset($dataRegistration['ref_klp_id'])) {
                    $dataRegistration['ref_klp_id'] = null;
                }
                if ($file) {
                    $dataRegistration['file_surat_permohonan'] = $file['filename'];
                }

                if ($user->registration()->first()) {
                    $user->registration()->update($dataRegistration);
                } else {
                    $user->registration()->create($dataRegistration);
                }

                $userRegistration = $user->registration()->first();

                // Simpan ke Attachment, dibuat secara manual/dibuat masing masing upload file 1
                $this->saveAttachment([
                    'parent_table' => 'registrations',
                    'table_id' => $userRegistration->id,
                    'path' => $file['filename'],
                    'name' => $data['file_surat_permohonan']->getClientOriginalName(),
                    'size' => $data['file_surat_permohonan']->getSize(),
                    'extension' => $data['file_surat_permohonan']->extension(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                ]);

                $this->saveAttachment([
                    'parent_table' => 'registrations',
                    'table_id' => $userRegistration->id,
                    'path' => $file2['filename'],
                    'name' => $data['file_sk_penunjukan_admin']->getClientOriginalName(),
                    'size' => $data['file_sk_penunjukan_admin']->getSize(),
                    'extension' => $data['file_sk_penunjukan_admin']->extension(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                ]);
                
                return $user;
            }, config('database.deadlock_retry', 3));


            if ($result) {
                $content = 'Klik disini untuk verifikasi akun anda : ' . config('app.url') . 'api/v1/registration/email-verification?key_code=' . security()->encrypt($result['email_verify_key']);
                \Mail::to($data['email'])->send(new Notification('Verifikasi Email', $content));
            }

            if (!$result) {
                // Delete uploaded files in case of failure
                file_helper()->delete($file['filename']);
                file_helper()->delete($file2['filename']);
            }

            return $result;
        } catch (\Exception $e) {
            // Handle exceptions or log errors
            throw $e;
        }
    }

    /**
     * Registration Email Verirication
     *
     * @throws ServiceErrorException
     * @throws Exception
     */
    public function registrationEmailVerification($data)
    {
        $checkUserActive = $this->repositoryUser->findBy(
            key: [
                'email_verify_key' => security()->decrypt($data['key_code']),
            ]
        );

        if (! $checkUserActive) {
            throw new ServiceErrorException('Data tidak ditemukan', 422);
        }

        return DB::transaction(function () use ($checkUserActive) {
            return $checkUserActive->update([
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);
        }, config('database.deadlock_retry', 3));
    }
}
