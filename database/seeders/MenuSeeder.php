<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # DASHBOARD
        $menus = [
            'parent_id' => null,
            'title' => 'Home',
            'icon' => null,
            'url' => null,
            'order_menu' => 1,
            'type' => 'section'
        ];

        $section = Menu::updateOrCreate([
            'title' => $menus['title'],
            'type' => $menus['type'],
        ], $menus);

        $menus = [
            'parent_id' => $section->id,
            'title' => 'Dashboard',
            'icon' => 'mdi:home-outline',
            'url' => null,
            'order_menu' => 1,
            'type' => 'collapse'
        ];

        $dashbaord = Menu::updateOrCreate([
            'title' => $menus['title'],
        ], $menus);

        $parents = [
            [
                'parent_id' => $dashbaord->id,
                'title' => 'Dashboard',
                'url' => '/dashboards/crm',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:view-dashboard',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'dashboard.view',
                    ]
                ]
            ]
        ];

        foreach ($parents as $value) {
            $menu = Menu::updateOrCreate([
                'parent_id' => $value['parent_id'],
                'title' => $value['title'],
            ], \Arr::except($value, ['permissions']));

            foreach ($value['permissions'] as $row) {
                $menu->permissions()->updateOrCreate(
                    [
                        'name' => $row['name'],
                        'action' => $row['action'],
                    ],
                    $row
                );
            }
        }

        # REFERENSI
        $menus = [
            'parent_id' => null,
            'title' => 'Management Data',
            'icon' => null,
            'url' => null,
            'order_menu' => 3,
            'type' => 'section'
        ];

        $section = Menu::updateOrCreate([
            'title' => $menus['title'],
            'type' => $menus['type'],
        ], $menus);

        $menus = [
            'parent_id' => $section->id,
            'title' => 'Referensi',
            'icon' => 'mdi:view-list',
            'url' => null,
            'order_menu' => 1,
            'type' => 'collapse',
        ];

        $referensi = Menu::updateOrCreate([
            'title' => $menus['title'],
        ], $menus);

        $parents = [
            [
                'parent_id' => $referensi->id,
                'title' => 'Lokasi',
                'url' => '/referensi/lokasi',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:map-marker-multiple',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'referensi.lokasi.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'referensi.lokasi.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'referensi.lokasi.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'referensi.lokasi.delete',
                    ]
                ]
            ], [
                'parent_id' => $referensi->id,
                'title' => 'Kementrian Lembaga / PEMDA',
                'url' => '/referensi/klp',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:text-box-edit-outline',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'referensi.klp.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'referensi.klp.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'referensi.klp.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'referensi.klp.delete',
                    ]
                ]
            ], [
                'parent_id' => $referensi->id,
                'title' => 'Jenis Sasaran',
                'url' => '/referensi/jenis-sasaran',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:target-account',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'referensi.jenis_sasaran.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'referensi.jenis_sasaran.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'referensi.jenis_sasaran.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'referensi.jenis_sasaran.delete',
                    ]
                ]
            ], [
                'parent_id' => $referensi->id,
                'title' => 'Kategori Risiko',
                'url' => '/referensi/kategori-risiko',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:format-list-bulleted-square',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'referensi.kategori_risiko.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'referensi.kategori_risiko.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'referensi.kategori_risiko.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'referensi.kategori_risiko.delete',
                    ]
                ]
            ], [
                'parent_id' => $referensi->id,
                'title' => 'Jenis KK Lead SPIP',
                'url' => '/referensi/jenis-kk-lead-spip',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:format-list-bulleted-square',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'referensi.jenis_kk_lead_spip.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'referensi.jenis_kk_lead_spip.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'referensi.jenis_kk_lead_spip.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'referensi.jenis_kk_lead_spip.delete',
                    ]
                ]
            ], [
                'parent_id' => $referensi->id,
                'title' => 'KK Lead SPIP',
                'url' => '/referensi/kk-lead-spip',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:playlist-edit',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'referensi.kk_lead_spip.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'referensi.kk_lead_spip.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'referensi.kk_lead_spip.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'referensi.kk_lead_spip.delete',
                    ]
                ]
            ],
        ];

        foreach ($parents as $value) {
            $menu = Menu::updateOrCreate([
                'parent_id' => $value['parent_id'],
                'title' => $value['title'],
            ], \Arr::except($value, ['permissions']));

            foreach ($value['permissions'] as $row) {
                $menu->permissions()->updateOrCreate(
                    [
                        'name' => $row['name'],
                        'action' => $row['action'],
                    ],
                    $row
                );
            }
        }

        #DATA USER

        $menus = [
            'parent_id' => $section->id,
            'title' => 'Data User',
            'icon' => 'mdi:account-group',
            'url' => null,
            'order_menu' => 1,
            'type' => 'collapse',
        ];

        $dataUser = Menu::updateOrCreate([
            'title' => $menus['title'],
        ], $menus);

        # DATA USER
        $menus = [
            'parent_id' => $dataUser->id,
            'title' => 'Data Pengguna',
            'icon' => 'mdi:account-file',
            'url' => null,
            'order_menu' => 1,
            'type' => 'collapse',
        ];

        $referensi = Menu::updateOrCreate([
            'title' => $menus['title'],
        ], $menus);

        $parents = [
            [
                'parent_id' => $referensi->id,
                'title' => 'Persetujuan',
                'url' => '/users/approval',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:checkbox-marked-circle-outline',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'users.approval.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'users.approval.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'users.approval.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'users.approval.delete',
                    ]
                ]
                    ],
                    [
                        'parent_id' => $referensi->id,
                        'title' => 'Semua Pengguna',
                        'url' => '/users/all',
                        'type' => 'item',
                        'order_menu' => 1,
                        'icon' => 'mdi:users',
                        'permissions' => [
                            [
                                'name' => 'View',
                                'action' => 'users.view',
                            ], [
                                'name' => 'Create',
                                'action' => 'users.create',
                            ], [
                                'name' => 'Edit',
                                'action' => 'users.edit',
                            ], [
                                'name' => 'Delete',
                                'action' => 'users.delete',
                            ]
                        ]
                    ]
        ];

        foreach ($parents as $value) {
            $menu = Menu::updateOrCreate([
                'parent_id' => $value['parent_id'],
                'title' => $value['title'],
            ], \Arr::except($value, ['permissions']));

            foreach ($value['permissions'] as $row) {
                $menu->permissions()->updateOrCreate(
                    [
                        'name' => $row['name'],
                        'action' => $row['action'],
                    ],
                    $row
                );
            }
        }


        # DATA OPD
        $menus = [
            'parent_id' => $dataUser->id,
            'title' => 'Data OPD',
            'icon' => 'mdi:shield-account-variant',
            'url' => null,
            'order_menu' => 1,
            'type' => 'collapse',
        ];

        $referensi = Menu::updateOrCreate([
            'title' => $menus['title'],
        ], $menus);

        $parents = [
            [
                'parent_id' => $referensi->id,
                'title' => 'Daftar OPD & User Management',
                'url' => '/opd/manage',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:checkbox-marked-circle-outline',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'opd.manage.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'opd.manage.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'opd.manage.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'opd.opd.delete',
                    ]
                ]
                    ],
                    [
                        'parent_id' => $referensi->id,
                        'title' => 'Permintaan Akun OPD',
                        'url' => '/opd/reqAkun',
                        'type' => 'item',
                        'order_menu' => 1,
                        'icon' => 'mdi:users',
                        'permissions' => [
                            [
                                'name' => 'View',
                                'action' => 'opd.reqAkun.view',
                            ], [
                                'name' => 'Create',
                                'action' => 'opd.reqAkun.create',
                            ], [
                                'name' => 'Edit',
                                'action' => 'opd.reqAkun.edit',
                            ], [
                                'name' => 'Delete',
                                'action' => 'opd.reqAkun.delete',
                            ]
                        ]
                    ]
        ];

        foreach ($parents as $value) {
            $menu = Menu::updateOrCreate([
                'parent_id' => $value['parent_id'],
                'title' => $value['title'],
            ], \Arr::except($value, ['permissions']));

            foreach ($value['permissions'] as $row) {
                $menu->permissions()->updateOrCreate(
                    [
                        'name' => $row['name'],
                        'action' => $row['action'],
                    ],
                    $row
                );
            }
        }


        # DATA Pemda
        $menus = [
            'parent_id' => $dataUser->id,
            'title' => 'Data Pemda',
            'icon' => 'mdi:shield-account',
            'url' => null,
            'order_menu' => 1,
            'type' => 'collapse',
        ];

        $referensi = Menu::updateOrCreate([
            'title' => $menus['title'],
        ], $menus);

        $parents = [
            [
                'parent_id' => $referensi->id,
                'title' => 'Daftar Pemda & User Management',
                'url' => '/pemda/manage',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:checkbox-marked-circle-outline',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'pemda.manage.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'pemda.manage.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'pemda.manage.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'pemda.pemda.delete',
                    ]
                ]
                    ],
                    [
                        'parent_id' => $referensi->id,
                        'title' => 'Permintaan Akun Pemda',
                        'url' => '/pemda/reqAkun',
                        'type' => 'item',
                        'order_menu' => 1,
                        'icon' => 'mdi:users',
                        'permissions' => [
                            [
                                'name' => 'View',
                                'action' => 'pemda.reqAkun.view',
                            ], [
                                'name' => 'Create',
                                'action' => 'pemda.reqAkun.create',
                            ], [
                                'name' => 'Edit',
                                'action' => 'pemda.reqAkun.edit',
                            ], [
                                'name' => 'Delete',
                                'action' => 'pemda.reqAkun.delete',
                            ]
                        ]
                    ]
        ];

        foreach ($parents as $value) {
            $menu = Menu::updateOrCreate([
                'parent_id' => $value['parent_id'],
                'title' => $value['title'],
            ], \Arr::except($value, ['permissions']));

            foreach ($value['permissions'] as $row) {
                $menu->permissions()->updateOrCreate(
                    [
                        'name' => $row['name'],
                        'action' => $row['action'],
                    ],
                    $row
                );
            }
        }

        # DATA Admin Perwakilan
        $menus = [
           'parent_id' => $dataUser->id,
           'title' => 'Data Perwakilan',
           'icon' => 'mdi:account-multiple-check',
           'url' => null,
           'order_menu' => 1,
           'type' => 'collapse',
        ];

        $referensi = Menu::updateOrCreate([
            'title' => $menus['title'],
        ], $menus);

        $parents = [
            [
                'parent_id' => $referensi->id,
                'title' => 'User Management',
                'url' => '/perwakilan/manage',
                'type' => 'item',
                'order_menu' => 1,
                'icon' => 'mdi:checkbox-marked-circle-outline',
                'permissions' => [
                    [
                        'name' => 'View',
                        'action' => 'perwakilan.manage.view',
                    ], [
                        'name' => 'Create',
                        'action' => 'perwakilan.manage.create',
                    ], [
                        'name' => 'Edit',
                        'action' => 'perwakilan.manage.edit',
                    ], [
                        'name' => 'Delete',
                        'action' => 'perwakilan.manage.delete',
                    ]
                ]
                    ]
        ];

        foreach ($parents as $value) {
            $menu = Menu::updateOrCreate([
                'parent_id' => $value['parent_id'],
                'title' => $value['title'],
            ], \Arr::except($value, ['permissions']));

            foreach ($value['permissions'] as $row) {
                $menu->permissions()->updateOrCreate(
                    [
                        'name' => $row['name'],
                        'action' => $row['action'],
                    ],
                    $row
                );
            }
        }

        # SETTING
        $menus = [
            'parent_id' => null,
            'title' => 'Settings',
            'icon' => null,
            'url' => null,
            'order_menu' => 99,
            'type' => 'section'
        ];

        $section = Menu::updateOrCreate([
            'title' => $menus['title'],
            'type' => $menus['type'],
        ], $menus);

        $menus = [
            'parent_id' => $section->id,
            'title' => 'Roles',
            'icon' => 'mdi:shield-outline',
            'url' => '/settings/roles',
            'order_menu' => 99,
            'type' => 'item',
            'permissions' => [
                [
                    'name' => 'View',
                    'action' => 'settings.roles.view',
                ], [
                    'name' => 'Create',
                    'action' => 'settings.roles.create',
                ], [
                    'name' => 'Edit',
                    'action' => 'settings.roles.edit',
                ], [
                    'name' => 'Delete',
                    'action' => 'settings.roles.delete',
                ]
            ]
        ];

        $setting = Menu::updateOrCreate([
            'title' => $menus['title'],
            'type' => $menus['type'],
        ], \Arr::except($menus, ['permissions']));

        foreach ($menus['permissions'] as $row) {
            $setting->permissions()->updateOrCreate(
                [
                    'name' => $row['name'],
                    'action' => $row['action'],
                ],
                $row
            );
        }

        # TRANSACTION
        $menus = [
            'parent_id' => null,
            'title' => 'Transaksi',
            'icon' => null,
            'url' => null,
            'order_menu' => 2,
            'type' => 'section'
        ];

        $section = Menu::updateOrCreate([
            'title' => $menus['title'],
            'type' => $menus['type'],
        ], $menus);

        $menus = [
            'parent_id' => $section->id,
            'title' => 'Penilaian Mandiri',
            'icon' => 'mdi:playlist-check',
            'url' => '/transaction/penilaian',
            'order_menu' => 1,
            'type' => 'item',
            'permissions' => [
                [
                    'name' => 'View',
                    'action' => 'transaction.penilaian.view',
                ], [
                    'name' => 'Create',
                    'action' => 'transaction.penilaian.create',
                ], [
                    'name' => 'Edit',
                    'action' => 'transaction.penilaian.edit',
                ], [
                    'name' => 'Delete',
                    'action' => 'transaction.penilaian.delete',
                ], [
                    'name' => 'Show',
                    'action' => 'transaction.sasaran.show',
                ], [
                    'name' => 'Create',
                    'action' => 'transaction.sasaran.create',
                ], [
                    'name' => 'Edit',
                    'action' => 'transaction.sasaran.edit',
                ], [
                    'name' => 'Delete',
                    'action' => 'transaction.sasaran.delete',
                ], [
                    'name' => 'List By Parent',
                    'action' => 'transaction.sasaran.listByParent',
                ], [
                    'name' => 'List By Penilaian',
                    'action' => 'transaction.sasaran.listByPenilaian',
                ], [
                    'name' => 'View',
                    'action' => 'transaction.sasaranindikator.view',
                ], [
                    'name' => 'Show',
                    'action' => 'transaction.sasaranindikator.show',
                ], [
                    'name' => 'Create',
                    'action' => 'transaction.sasaranindikator.create',
                ], [
                    'name' => 'Edit',
                    'action' => 'transaction.sasaranindikator.edit',
                ], [
                    'name' => 'Delete',
                    'action' => 'transaction.sasaranindikator.delete',
                ], [
                    'name' => 'Edit Visi',
                    'action' => 'transaction.penilaian.visi',
                ], [
                    'name' => 'Edit Misi',
                    'action' => 'transaction.penilaian.misi',
                ], [
                    'name' => 'View',
                    'action' => 'transaction.data_opini.view',
                ], [
                    'name' => 'Create',
                    'action' => 'transaction.data_opini.create',
                ], [
                    'name' => 'Edit',
                    'action' => 'transaction.data_opini.edit',
                ], [
                    'name' => 'Delete',
                    'action' => 'transaction.data_opini.delete',
                ], [
                    'name' => 'View',
                    'action' => 'transaction.penilaian_temuan.view',
                ], [
                    'name' => 'Create',
                    'action' => 'transaction.penilaian_temuan.create',
                ], [
                    'name' => 'Edit',
                    'action' => 'transaction.penilaian_temuan.edit',
                ], [
                    'name' => 'Delete',
                    'action' => 'transaction.penilaian_temuan.delete',
                ],
            ]
        ];

        $setting = Menu::updateOrCreate([
            'title' => $menus['title'],
            'type' => $menus['type'],
        ], \Arr::except($menus, ['permissions']));

        foreach ($menus['permissions'] as $row) {
            $setting->permissions()->updateOrCreate(
                [
                    'name' => $row['name'],
                    'action' => $row['action'],
                ],
                $row
            );
        }
    }
}
