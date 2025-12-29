-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 29, 2025 lúc 03:04 PM
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
-- Cơ sở dữ liệu: `club_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblbaiviet`
--

CREATE TABLE `tblbaiviet` (
  `Mabaiviet` int(11) NOT NULL,
  `Noidung` text NOT NULL,
  `Machude` int(11) NOT NULL,
  `Ngaytao` date NOT NULL,
  `Teptin` varchar(255) DEFAULT NULL,
  `Username` varchar(50) NOT NULL,
  `Trangthai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblbaiviet`
--

INSERT INTO `tblbaiviet` (`Mabaiviet`, `Noidung`, `Machude`, `Ngaytao`, `Teptin`, `Username`, `Trangthai`) VALUES
(3, 'Noi dung 1', 2, '2025-11-18', '1765242242_Ảnh chụp màn hình 2024-11-18 091528.png', 'giathinh', 1),
(8, 'Tết dương lịch nghỉ mấy ngày vậy mọi người', 1, '2025-12-29', '', 'minhanh', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblbinhluan`
--

CREATE TABLE `tblbinhluan` (
  `Mabinhluan` int(11) NOT NULL,
  `Noidung` text NOT NULL,
  `Mabaiviet` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Ngaytao` date NOT NULL,
  `Trangthai` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblbinhluan`
--

INSERT INTO `tblbinhluan` (`Mabinhluan`, `Noidung`, `Mabaiviet`, `Username`, `Ngaytao`, `Trangthai`, `parent_id`) VALUES
(12, 'haha', 3, 'admin1', '2025-12-09', 1, 0),
(13, 'haha', 3, 'admin1', '2025-12-09', 0, 0),
(15, 'hihi', 3, 'admin1', '2025-12-21', 1, 12),
(16, '1 ngày 1/1 hay sao ấy', 8, 'giathinh', '2025-12-29', 1, 0),
(17, '4 ngày từ ngày 1/1 đến hết CN á', 8, 'vanbao', '2025-12-29', 1, 0),
(18, 'phải k cha', 8, 'vanbao', '2025-12-29', 1, 16),
(19, '@vanbao 1/1 đúng rồi', 8, 'quocviet', '2025-12-29', 1, 16);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblchude`
--

CREATE TABLE `tblchude` (
  `Machude` int(11) NOT NULL,
  `Tenchude` text NOT NULL,
  `Trangthai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblchude`
--

INSERT INTO `tblchude` (`Machude`, `Tenchude`, `Trangthai`) VALUES
(1, 'Hỏi đáp - Thắc mắc', 1),
(2, 'Tin học Văn phòng (Word, Excel, PP)', 1),
(3, 'Lập trình Căn bản (C/C++, Python, Java)', 1),
(4, 'Lập trình Web (HTML, CSS, JS, PHP)', 1),
(5, 'Cơ sở dữ liệu (SQL)', 1),
(6, 'Phần cứng & Mạng máy tính', 1),
(7, 'Đồ họa & Thiết kế', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblcontact`
--

CREATE TABLE `tblcontact` (
  `id` int(11) NOT NULL,
  `Tennguoigui` varchar(100) NOT NULL,
  `Noidung` text NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Ngaygui` datetime NOT NULL,
  `Trangthai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblcontact`
--

INSERT INTO `tblcontact` (`id`, `Tennguoigui`, `Noidung`, `Email`, `Ngaygui`, `Trangthai`) VALUES
(4, 'Nguyễn Thị Lan Anh', 'Cho em tham gia clb với', 'lananh@gmail.com', '2025-12-29 00:00:00', 1),
(5, 'Hoàng Quốc Việt', 'Clb khi nào bầu ban chủ nhiệm mới vậy?', 'quocviet.hoang@tvu.edu.vn', '2025-12-29 14:47:26', 0),
(6, 'Hoàng Quốc Việt', 'Khi nào clb giải thể vậy?', 'quocviet.hoang@tvu.edu.vn', '2025-12-29 20:49:29', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbldangkyhoatdong`
--

CREATE TABLE `tbldangkyhoatdong` (
  `dangky_id` int(11) NOT NULL,
  `hoatdong_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ngay_dangky` datetime NOT NULL DEFAULT current_timestamp(),
  `trang_thai` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Đã đăng ký, 1: Đã tham gia, 2: Vắng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbldangkyhoatdong`
--

INSERT INTO `tbldangkyhoatdong` (`dangky_id`, `hoatdong_id`, `username`, `ngay_dangky`, `trang_thai`) VALUES
(3, 1, 'camtu', '2025-11-10 08:30:00', 0),
(4, 1, 'duytin', '2025-11-05 09:15:00', 0),
(5, 1, 'giathinh', '2025-10-28 14:00:00', 0),
(6, 1, 'minhanh', '2025-11-18 10:45:00', 0),
(7, 1, 'quocviet', '2025-10-20 16:20:00', 0),
(8, 1, 'vanbao', '2025-11-02 07:50:00', 0),
(9, 2, 'camtu', '2025-11-12 09:00:00', 2),
(10, 2, 'duytin', '2025-10-30 15:10:00', 2),
(11, 2, 'giathinh', '2025-11-08 08:40:00', 1),
(12, 2, 'minhanh', '2025-11-20 11:30:00', 2),
(13, 2, 'quocviet', '2025-10-25 13:55:00', 1),
(14, 2, 'vanbao', '2025-11-15 17:05:00', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblhoatdong`
--

CREATE TABLE `tblhoatdong` (
  `hoatdong_id` int(11) NOT NULL,
  `ten_hoat_dong` varchar(200) NOT NULL,
  `mo_ta_hoat_dong` text NOT NULL,
  `ngay_bat_dau` datetime NOT NULL,
  `dia_diem` varchar(255) DEFAULT NULL,
  `trang_thai` int(11) NOT NULL DEFAULT 0 COMMENT '0: Sắp diễn ra, 1: Đã kết thúc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblhoatdong`
--

INSERT INTO `tblhoatdong` (`hoatdong_id`, `ten_hoat_dong`, `mo_ta_hoat_dong`, `ngay_bat_dau`, `dia_diem`, `trang_thai`) VALUES
(1, 'Workshop Lập trình Web cơ bản cho người mới bắt đầu', 'Buổi workshop hướng dẫn các bạn sinh viên làm quen với HTML, CSS và Javascript.', '2025-11-25 08:30:00', 'Phòng máy A1.201, Khu 1, ĐH Trà Vinh', 1),
(2, 'Cuộc thi ', 'Cuộc thi giải thuật toán hàng tháng dành cho tất cả thành viên CLB. Giải thưởng hấp dẫn đang chờ đón!', '2025-12-05 07:00:00', 'Trực tuyến trên nền tảng HackerRank', 1),
(3, 'Buổi sinh hoạt CLB cuối năm', 'Tổng kết hoạt động năm 2025 và định hướng cho năm 2026. Có tiệc trà và giao lưu văn nghệ.', '2025-12-29 18:00:00', 'Hội trường B5.101', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblslideshow`
--

CREATE TABLE `tblslideshow` (
  `Id` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `ImageUrl` varchar(255) NOT NULL,
  `Status` int(11) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblslideshow`
--

INSERT INTO `tblslideshow` (`Id`, `Title`, `Description`, `ImageUrl`, `Status`, `username`) VALUES
(9, 'CLB Tin học', '', 'images/750banner.jpg', 1, ''),
(10, 'CLB Tin học', '', 'images/240banner1.jpg', 1, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbluser`
--

CREATE TABLE `tbluser` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `gender` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbluser`
--

INSERT INTO `tbluser` (`username`, `password`, `fullname`, `gender`, `email`, `avatar`, `role`, `status`) VALUES
('admin1', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Duy Tín', 0, 'duytin.admin@tvu.edu.vn', '', 1, 1),
('admin2', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Phước Hiệp', 0, 'phuochiep.admin@tvu.edu.vn', '', 1, 1),
('admin3', 'e10adc3949ba59abbe56e057f20f883e', 'Gia Thịnh', 0, 'giathinh.admin@tvu.edu.vn', '', 1, 1),
('camtu', 'e10adc3949ba59abbe56e057f20f883e', 'Phạm Thị Cẩm Tú', 0, 'camtu.pham@tvu.edu.vn', '', 0, 1),
('duytin', '6afd9643f3e1a07bb92faa4bb403ba32', 'Nguyen Duy Tin', 0, 'duytin@gmail.com', 'z4242815564484_63596ac735d7ce4d2fe59c3739962378.jpg', 0, 0),
('giathinh', 'e10adc3949ba59abbe56e057f20f883e', 'Gia Thịnh', 0, 'giathinh@gmail.com', 'avatar_giathinh_1765264719.png', 0, 1),
('minhanh', 'e10adc3949ba59abbe56e057f20f883e', 'Lê Minh Anh', 1, 'minhanh.le@tvu.edu.vn', 'avatar_minhanh_1766281524.jpg', 0, 1),
('quocviet', 'e10adc3949ba59abbe56e057f20f883e', 'Hoàng Quốc Việt', 0, 'quocviet.hoang@tvu.edu.vn', '', 0, 1),
('vanbao', 'e10adc3949ba59abbe56e057f20f883e', 'Trần Văn Bảo', 0, 'vanbao.tran@tvu.edu.vn', '', 0, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  ADD PRIMARY KEY (`Mabaiviet`),
  ADD KEY `Username` (`Username`),
  ADD KEY `Machude` (`Machude`);

--
-- Chỉ mục cho bảng `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  ADD PRIMARY KEY (`Mabinhluan`),
  ADD KEY `Mabaiviet` (`Mabaiviet`),
  ADD KEY `Username` (`Username`);

--
-- Chỉ mục cho bảng `tblchude`
--
ALTER TABLE `tblchude`
  ADD PRIMARY KEY (`Machude`);

--
-- Chỉ mục cho bảng `tblcontact`
--
ALTER TABLE `tblcontact`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  ADD PRIMARY KEY (`dangky_id`),
  ADD UNIQUE KEY `unique_user_hoatdong` (`hoatdong_id`,`username`),
  ADD KEY `fk_dangky_user` (`username`);

--
-- Chỉ mục cho bảng `tblhoatdong`
--
ALTER TABLE `tblhoatdong`
  ADD PRIMARY KEY (`hoatdong_id`);

--
-- Chỉ mục cho bảng `tblslideshow`
--
ALTER TABLE `tblslideshow`
  ADD PRIMARY KEY (`Id`);

--
-- Chỉ mục cho bảng `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  MODIFY `Mabaiviet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  MODIFY `Mabinhluan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `tblchude`
--
ALTER TABLE `tblchude`
  MODIFY `Machude` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `tblcontact`
--
ALTER TABLE `tblcontact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  MODIFY `dangky_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `tblhoatdong`
--
ALTER TABLE `tblhoatdong`
  MODIFY `hoatdong_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tblslideshow`
--
ALTER TABLE `tblslideshow`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  ADD CONSTRAINT `tblbaiviet_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbaiviet_ibfk_2` FOREIGN KEY (`Machude`) REFERENCES `tblchude` (`Machude`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  ADD CONSTRAINT `tblbinhluan_ibfk_1` FOREIGN KEY (`Mabaiviet`) REFERENCES `tblbaiviet` (`Mabaiviet`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbinhluan_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  ADD CONSTRAINT `fk_dangky_hoatdong` FOREIGN KEY (`hoatdong_id`) REFERENCES `tblhoatdong` (`hoatdong_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dangky_user` FOREIGN KEY (`username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
