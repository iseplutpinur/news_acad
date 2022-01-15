-- tambah tahun angkatan user
ALTER TABLE `users` ADD `thn_angkatan` YEAR NULL DEFAULT NULL AFTER `user_tanggal_lahir`;

-- tambah menu pengurus
INSERT INTO `menu` (`menu_id`, `menu_menu_id`, `menu_nama`, `menu_keterangan`, `menu_index`, `menu_icon`, `menu_url`, `menu_status`, `created_at`)
VALUES
('130', '0', 'Pengurus', '-', '2', 'fas fa-users', 'admin/pengurus', 'Aktif', '2021-12-16 15:03:43');

-- tambah ke level
INSERT INTO `role_aplikasi` (`rola_id`, `rola_menu_id`, `rola_lev_id`, `created_at`) VALUES (NULL, '130', '1', '2021-12-16 15:05:33');
