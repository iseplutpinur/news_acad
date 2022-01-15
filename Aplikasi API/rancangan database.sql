-- clear
CREATE TABLE `pengurus_periode` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `dari` year,
  `sampai` year,
  `nama` varchar(255),
  `keterangan` text,
  `slogan` text,
  `visi` text,
  `misi` text
);

-- clear
CREATE TABLE `users` (
  `user_id` int PRIMARY KEY AUTO_INCREMENT,
  `npp` varchar(255),
  `nama_belakang` varchar(255),
  `nama_depan` varchar(255),
  `nama_panggilan` varchar(255),
  `foto` varchar(255),
  `tanggal_lahir` date,
  `email` varchar(255),
  `kecamatan` int,
  `desa` int
);

-- clear
CREATE TABLE `pengurus_jabatan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `pengurus_periode_id` int,
  `parrent_id` int,
  `no_ururt` int,
  `nama` varchar(255),
  `keterangan` text,
);

-- clear
CREATE TABLE `pengurus_jabatan_detail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `pengurus_jabatan_id` int,
);

-- clear
CREATE TABLE `pengurus_periode_detail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `pengurus_periode_id` int
);

-- clear
CREATE TABLE `pengurus_kontak_tipe` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `keterangan` varchar(255)
);

-- clear
CREATE TABLE `pengurus_kontak` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `pengurus_kontak_tipe_id` int,
  `value1` varchar(255),
  `value2` varchar(255)
);

-- clear
CREATE TABLE `pengurus_pendidikan_jenis` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `keterangan` varchar(255)
);

-- clear
CREATE TABLE `pengurus_pendidikan` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `pengurus_pendidikan_jenis_id` int,
  `dari` year,
  `sampai` year,
  `nama` varchar(255),
  `keterangan` text
);


CREATE TABLE `pengurus_pengalaman_organisasi` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `nama` varchar(255),
  `dari` year,
  `sampai` year,
  `jabatan` varchar(255),
  `keterangan` text
);

-- clear
CREATE TABLE `pengurus_pengalaman_lain` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `nama` varchar(255),
  `keterangan` text
);

-- clear
CREATE TABLE `pengurus_hobi` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `nama` varchar(255),
  `keterangan` text
);

-- pengurus
CREATE TABLE `galeri` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `keterangan` text
);

-- clear
CREATE TABLE `galeri_detail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `galeri_id` int,
  `nama` varchar(255),
  `foto` varchar(255),
  `keterangan` text
);

-- clear
CREATE TABLE `galeri_detail_komentar` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `galeri_detail_id` int,
  `nama` varchar(255),
  `email` varchar(255),
  `komentar` text,
  `tanggal` date
);

-- clear
CREATE TABLE `galeri_tag_pengurus` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `galeri_id` int,
  `user_id` int,
  `keterangan` text
);

-- clear
CREATE TABLE `artikel` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `foto` varchar(255),
  `detail` text,
  `tanggal` date
);

-- clear
CREATE TABLE `artikel_kategori` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `keterangan` text
);

CREATE TABLE `artikel_kategori_detail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `artikel_id` int,
  `artikel_kategori_id` int
);

-- clear
CREATE TABLE `artikel_tag` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `keterangan` text
);

CREATE TABLE `artikel_tag_detail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `artikel_id` int,
  `artikel_tag_id` int
);

CREATE TABLE `artikel_komentar` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `artikel_id` int,
  `nama` varchar(255),
  `email` varchar(255),
  `komentar` text,
  `tanggal` date
);

CREATE TABLE `artikel_jabatans` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `artikel_id` int,
  `pengurus_jabatan_id` int
);

-- clear
CREATE TABLE `download` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(255),
  `file` text,
  `keterangan` text
);

ALTER TABLE `pengurus_periode` ADD FOREIGN KEY (`id`) REFERENCES `pengurus_jabatan` (`pengurus_periode_id`);

ALTER TABLE `pengurus_jabatan` ADD FOREIGN KEY (`id`) REFERENCES `pengurus_jabatan` (`parrent_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`user_id`) REFERENCES `pengurus_jabatan_detail` (`user_id`);

ALTER TABLE `pengurus_jabatan` ADD FOREIGN KEY (`id`) REFERENCES `pengurus_jabatan_detail` (`pengurus_jabatan_id`);

ALTER TABLE `pengurus_periode` ADD FOREIGN KEY (`id`) REFERENCES `pengurus_periode_detail` (`pengurus_periode_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`user_id`) REFERENCES `pengurus_periode_detail` (`user_id`);

ALTER TABLE `pengurus_kontak_tipe` ADD FOREIGN KEY (`id`) REFERENCES `pengurus_kontak` (`pengurus_kontak_tipe_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`user_id`) REFERENCES `pengurus_kontak` (`user_id`);

ALTER TABLE `pengurus_pendidikan_jenis` ADD FOREIGN KEY (`id`) REFERENCES `pengurus_pendidikan` (`pengurus_pendidikan_jenis_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`user_id`) REFERENCES `pengurus_pendidikan` (`user_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`user_id`) REFERENCES `pengurus_pengalaman_organisasi` (`user_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`user_id`) REFERENCES `pengurus_pengalaman_lain` (`user_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`user_id`) REFERENCES `pengurus_hobi` (`user_id`);

ALTER TABLE `galeri` ADD FOREIGN KEY (`id`) REFERENCES `galeri_detail` (`galeri_id`);

ALTER TABLE `galeri_detail` ADD FOREIGN KEY (`id`) REFERENCES `galeri_detail_komentar` (`galeri_detail_id`);

ALTER TABLE `galeri` ADD FOREIGN KEY (`id`) REFERENCES `galeri_tag_pengurus` (`galeri_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`user_id`) REFERENCES `galeri_tag_pengurus` (`user_id`);

ALTER TABLE `artikel` ADD FOREIGN KEY (`id`) REFERENCES `artikel_kategori_detail` (`artikel_id`);

ALTER TABLE `artikel_kategori` ADD FOREIGN KEY (`id`) REFERENCES `artikel_kategori_detail` (`artikel_kategori_id`);

ALTER TABLE `artikel` ADD FOREIGN KEY (`id`) REFERENCES `artikel_tag_detail` (`artikel_id`);

ALTER TABLE `artikel_tag` ADD FOREIGN KEY (`id`) REFERENCES `artikel_tag_detail` (`artikel_tag_id`);

ALTER TABLE `artikel` ADD FOREIGN KEY (`id`) REFERENCES `artikel_komentar` (`artikel_id`);

ALTER TABLE `artikel` ADD FOREIGN KEY (`id`) REFERENCES `artikel_jabatans` (`artikel_id`);

ALTER TABLE `pengurus_jabatan` ADD FOREIGN KEY (`id`) REFERENCES `artikel_jabatans` (`pengurus_jabatan_id`);
