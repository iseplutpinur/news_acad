START TRANSACTION;

CREATE TABLE `artikel_kategori_detail` (
  `id` int(11) NOT NULL,

  `artikel_id` int,
  `artikel_kategori_id` int,

  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 Tidak Aktif | 1 Aktif',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `artikel_kategori_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artikel_id` (`artikel_id`),
  ADD KEY `artikel_kategori_id` (`artikel_kategori_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `deleted_by` (`deleted_by`);

ALTER TABLE `artikel_kategori_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `artikel_kategori_detail`
  ADD CONSTRAINT `artikel_kategori_detail_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `artikel_kategori_detail_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `artikel_kategori_detail_ibfk_3` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `artikel_kategori_detail_ibfk_4` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `artikel_kategori_detail_ibfk_5` FOREIGN KEY (`artikel_kategori_id`) REFERENCES `artikel_kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
