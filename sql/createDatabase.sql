CREATE DATABASE IF NOT EXISTS `math` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `math`;

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `steps` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` varchar(1000) NOT NULL,
  `wrong_answer` varchar(1000) NOT NULL,
  `help` text NOT NULL,
  `task_id` int(11) NOT NULL,
  `visible` tinyint(4) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `visible` tinyint(4) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','paid','registered') NOT NULL DEFAULT 'registered',
  `code` varchar(10) NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users_steps` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `steps_id` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `steps_id` (`steps_id`) USING BTREE;


ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `steps`
  ADD CONSTRAINT `steps_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`);

ALTER TABLE `users_steps`
  ADD CONSTRAINT `users_steps_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_steps_ibfk_2` FOREIGN KEY (`steps_id`) REFERENCES `steps` (`id`);
