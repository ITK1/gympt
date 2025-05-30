-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 30, 2025 lúc 05:35 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ptgym1`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `goal` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `difficulty` varchar(20) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `equipment` varchar(50) DEFAULT NULL,
  `intensity` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `exercises`
--

INSERT INTO `exercises` (`id`, `category`, `title`, `description`, `goal`, `image_url`, `video_url`, `difficulty`, `duration`, `equipment`, `intensity`) VALUES
(1, 'ngực', 'Đẩy ngực ngang', 'Bài tập phát triển ngực giữa hiệu quả.', NULL, 'images/bench_press.jpg', 'https://www.youtube.com/embed/vthMCtgVtFw', NULL, NULL, NULL, NULL),
(2, 'lưng', 'Kéo xà', 'Tăng sức mạnh và cơ lưng xô.', NULL, 'images/pull_up.jpg', 'https://www.youtube.com/embed/eGo4IYlbE5g', NULL, NULL, NULL, NULL),
(3, 'bụng', 'Gập bụng', 'Đốt mỡ bụng hiệu quả.', NULL, 'images/crunch.jpg', 'https://www.youtube.com/embed/Xyd_fa5zoEU', NULL, NULL, NULL, NULL),
(4, 'chân', 'Squat tạ tay', 'Phát triển cơ đùi và mông.', NULL, 'images/squat.jpg', 'https://www.youtube.com/embed/UXJrBgI2RxA', NULL, NULL, NULL, NULL),
(5, 'ngực', 'Đẩy ngực ngang', 'Bài tập phát triển ngực giữa hiệu quả.', NULL, 'images/bench_press.jpg', 'https://www.youtube.com/embed/vthMCtgVtFw', NULL, NULL, NULL, NULL),
(6, 'lưng', 'Kéo xà', 'Tăng sức mạnh và cơ lưng xô.', NULL, 'images/pull_up.jpg', 'https://www.youtube.com/embed/eGo4IYlbE5g', NULL, NULL, NULL, NULL),
(7, 'bụng', 'Gập bụng', 'Đốt mỡ bụng hiệu quả.', NULL, 'images/crunch.jpg', 'https://www.youtube.com/embed/Xyd_fa5zoEU', NULL, NULL, NULL, NULL),
(8, 'chân', 'Squat tạ tay', 'Phát triển cơ đùi và mông.', NULL, 'images/squat.jpg', 'https://www.youtube.com/embed/UXJrBgI2RxA', NULL, NULL, NULL, NULL),
(9, 'tay', 'Cuốn tạ trước tay', 'Tăng cơ tay trước bắp tay.', NULL, 'images/bicep_curl.jpg', 'https://www.youtube.com/embed/ykJmrZ5v0Oo', NULL, NULL, NULL, NULL),
(10, 'vai', 'Nâng tạ vai', 'Phát triển cơ vai tròn và vai trước.', NULL, 'images/shoulder_press.jpg', 'https://www.youtube.com/embed/B-aVuyhvLHU', NULL, NULL, NULL, NULL),
(11, 'toàn thân', 'Plank giữ thăng bằng', 'Tăng sức mạnh cơ core toàn thân.', NULL, 'images/plank.jpg', 'https://www.youtube.com/embed/pSHjTRCQxIw', NULL, NULL, NULL, NULL),
(12, 'ngực', 'Chống đẩy tay rộng', 'Tăng sức mạnh cơ ngực và tay sau.', NULL, 'images/wide_pushup.jpg', 'https://www.youtube.com/embed/IODxDxX7oi4', NULL, NULL, NULL, NULL),
(13, 'lưng', 'Deadlift', 'Bài tập nâng tạ giúp phát triển lưng, chân và cơ core.', NULL, 'images/deadlift.jpg', 'https://www.youtube.com/embed/ytGaGIn3SjE', NULL, NULL, NULL, NULL),
(14, 'bụng', 'Leg Raise', 'Bài tập giảm mỡ bụng dưới.', NULL, 'images/leg_raise.jpg', 'https://www.youtube.com/embed/JB2oyawG9KI', NULL, NULL, NULL, NULL),
(15, 'chân', 'Lunge', 'Phát triển cơ đùi và mông, tăng sự cân bằng.', NULL, 'images/lunge.jpg', 'https://www.youtube.com/embed/QOVaHwm-Q6U', NULL, NULL, NULL, NULL),
(16, 'tay', 'Dumbbell Triceps Kickback', 'Tăng sức mạnh cơ tay sau.', NULL, 'images/triceps_kickback.jpg', 'https://www.youtube.com/embed/6SSpN8jGCu0', NULL, NULL, NULL, NULL),
(17, 'vai', 'Lateral Raise', 'Phát triển cơ vai bên.', NULL, 'images/lateral_raise.jpg', 'https://www.youtube.com/embed/3VcKaXpzqRo', NULL, NULL, NULL, NULL),
(18, 'toàn thân', 'Burpees', 'Bài tập toàn thân giúp giảm mỡ và tăng sức bền.', NULL, 'images/burpees.jpg', 'https://www.youtube.com/embed/dZgVxmf6jkA', NULL, NULL, NULL, NULL),
(19, 'ngực', 'Đẩy ngực ngang', 'Bài tập phát triển ngực giữa hiệu quả.', NULL, 'images/bench_press.jpg', 'https://www.youtube.com/embed/vthMCtgVtFw', 'trung bình', 30, 'tạ đòn', 'cao'),
(20, 'lưng', 'Kéo xà', 'Tăng sức mạnh và cơ lưng xô.', NULL, 'images/pull_up.jpg', 'https://www.youtube.com/embed/eGo4IYlbE5g', 'khó', 20, 'xà đơn', 'cao'),
(21, 'bụng', 'Gập bụng', 'Đốt mỡ bụng hiệu quả.', NULL, 'images/crunch.jpg', 'https://www.youtube.com/embed/Xyd_fa5zoEU', 'dễ', 15, 'không dụng cụ', 'thấp'),
(22, 'chân', 'Squat tạ tay', 'Phát triển cơ đùi và mông.', NULL, 'images/squat.jpg', 'https://www.youtube.com/embed/UXJrBgI2RxA', 'trung bình', 25, 'tạ tay', 'trung bình'),
(23, 'tay', 'Cuốn tạ trước tay', 'Tăng cơ tay trước bắp tay.', NULL, 'images/bicep_curl.jpg', 'https://www.youtube.com/embed/ykJmrZ5v0Oo', 'dễ', 10, 'tạ tay', 'trung bình'),
(24, 'vai', 'Nâng tạ vai', 'Phát triển cơ vai tròn và vai trước.', NULL, 'images/shoulder_press.jpg', 'https://www.youtube.com/embed/B-aVuyhvLHU', 'trung bình', 20, 'tạ tay', 'trung bình'),
(25, 'toàn thân', 'Plank giữ thăng bằng', 'Tăng sức mạnh cơ core toàn thân.', NULL, 'images/plank.jpg', 'https://www.youtube.com/embed/pSHjTRCQxIw', 'dễ', 5, 'không dụng cụ', 'thấp'),
(26, 'ngực', 'Chống đẩy', 'Tăng sức mạnh tay và ngực.', NULL, 'images/push_up.jpg', 'https://www.youtube.com/embed/IODxDxX7oi4', 'dễ', 10, 'không dụng cụ', 'trung bình'),
(27, 'lưng', 'Deadlift', 'Tăng sức mạnh toàn thân, đặc biệt là lưng dưới.', NULL, 'images/deadlift.jpg', 'https://www.youtube.com/embed/op9kVnSso6Q', 'khó', 40, 'tạ đòn', 'cao'),
(28, 'chân', 'Leg press', 'Tăng cơ đùi và mông.', NULL, 'images/leg_press.jpg', 'https://www.youtube.com/embed/IZxyjW7MPJQ', 'trung bình', 30, 'máy tập chân', 'trung bình'),
(29, 'tay', 'French press', 'Tăng cơ tay sau.', NULL, 'images/french_press.jpg', 'https://www.youtube.com/embed/0326dy_-CzM', 'trung bình', 15, 'tạ đòn', 'trung bình'),
(30, 'vai', 'Nâng tạ vai bên', 'Phát triển vai giữa.', NULL, 'images/lateral_raise.jpg', 'https://www.youtube.com/embed/3VcKaXpzqRo', 'dễ', 15, 'tạ tay', 'thấp'),
(31, 'bụng', 'Crunch xoay người', 'Tăng cơ bụng chéo.', NULL, 'images/oblique_crunch.jpg', 'https://www.youtube.com/embed/oDdkytliOqE', 'trung bình', 20, 'không dụng cụ', 'trung bình'),
(32, 'toàn thân', 'Burpees', 'Bài tập toàn thân tăng cường sức bền và đốt mỡ.', NULL, 'images/burpees.jpg', 'https://www.youtube.com/embed/dZgVxmf6jkA', 'khó', 10, 'không dụng cụ', 'cao'),
(33, 'toàn thân', 'Chạy bộ tại chỗ', 'Tăng sức bền tim mạch.', NULL, 'images/running_in_place.jpg', 'https://www.youtube.com/embed/J5uGpBz7uG8', 'dễ', 15, 'không dụng cụ', 'trung bình');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
