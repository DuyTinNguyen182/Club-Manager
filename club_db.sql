-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 09, 2025 lúc 08:37 AM
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
(3, 'Noi dung 1', 2, '2025-11-18', '1765242242_Ảnh chụp màn hình 2024-11-18 091528.png', 'giathinh', 0);

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
(13, 'haha', 3, 'admin1', '2025-12-09', 0, 0);

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
(1, 'Hỏi đáp - Thắc mắc', 0),
(2, 'Tin học Văn phòng (Word, Excel, PP)', 0),
(3, 'Lập trình Căn bản (C/C++, Python, Java)', 0),
(4, 'Lập trình Web (HTML, CSS, JS, PHP)', 0),
(5, 'Cơ sở dữ liệu (SQL)', 0),
(6, 'Phần cứng & Mạng máy tính', 0),
(7, 'Đồ họa & Thiết kế', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblcontact`
--

CREATE TABLE `tblcontact` (
  `id` int(11) NOT NULL,
  `Tennguoigui` varchar(100) NOT NULL,
  `Noidung` text NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Ngaygui` date NOT NULL,
  `Trangthai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Workshop Lập trình Web cơ bản cho người mới bắt đầu', 'Buổi workshop hướng dẫn các bạn sinh viên làm quen với HTML, CSS và Javascript.', '2025-11-25 08:30:00', 'Phòng máy A1.201, Khu 1, ĐH Trà Vinh', 0),
(2, 'Cuộc thi \"Code Challenge\" tháng 11', 'Cuộc thi giải thuật toán hàng tháng dành cho tất cả thành viên CLB. Giải thưởng hấp dẫn đang chờ đón!', '2025-12-05 07:00:00', 'Trực tuyến trên nền tảng HackerRank', 0),
(3, 'Buổi sinh hoạt CLB cuối năm', 'Tổng kết hoạt động năm 2025 và định hướng cho năm 2026. Có tiệc trà và giao lưu văn nghệ.', '2025-12-20 18:00:00', 'Hội trường B5.101', 0);

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
(9, '', '', 'images/750banner.jpg', 0, ''),
(10, '', '', 'images/240banner1.jpg', 0, '');

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
('minhanh', 'e10adc3949ba59abbe56e057f20f883e', 'Lê Minh Anh', 0, 'minhanh.le@tvu.edu.vn', '', 0, 1),
('quocviet', 'e10adc3949ba59abbe56e057f20f883e', 'Hoàng Quốc Việt', 0, 'quocviet.hoang@tvu.edu.vn', '', 0, 1),
('vanbao', 'e10adc3949ba59abbe56e057f20f883e', 'Trần Văn Bảo', 0, 'vanbao.tran@tvu.edu.vn', '', 0, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  ADD PRIMARY KEY (`Mabaiviet`);

--
-- Chỉ mục cho bảng `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  ADD PRIMARY KEY (`Mabinhluan`);

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
  MODIFY `Mabaiviet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  MODIFY `Mabinhluan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `tblchude`
--
ALTER TABLE `tblchude`
  MODIFY `Machude` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `tblcontact`
--
ALTER TABLE `tblcontact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
