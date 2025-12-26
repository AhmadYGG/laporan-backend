CREATE TABLE `users` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `nik` varchar(50) UNIQUE NOT NULL,
  `email_phone` varchar(100) UNIQUE NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `reports` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `user_id` integer NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `photo` varchar(255) COMMENT 'filepath or URL',
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `notifications` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `user_id` integer NOT NULL,
  `report_id` integer NOT NULL,
  `message` text NOT NULL,
  `status` boolean DEFAULT false,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `report_logs` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `report_id` integer NOT NULL,
  `changed_by` integer NOT NULL,
  `new_status` varchar(50) NOT NULL,
  `notes` text,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

ALTER TABLE `reports` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `notifications` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `notifications` ADD FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`);
ALTER TABLE `report_logs` ADD FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`);
ALTER TABLE `report_logs` ADD FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`);
