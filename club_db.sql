-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 16, 2025 lúc 08:12 AM
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
-- Cơ sở dữ liệu: `duy`
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
(1, 'Em là sinh viên năm nhất, cho em hỏi nên bắt đầu học ngôn ngữ lập trình nào đầu tiên ạ? Em đang phân vân giữa C++ và Python. Mong các anh chị đi trước cho em lời khuyên.', 1, '2025-11-20', NULL, 'mien', 0),
(2, 'Tổng hợp các hàm Excel thường dùng nhất cho dân văn phòng và sinh viên. Bài viết này sẽ giúp các bạn xử lý số liệu nhanh hơn rất nhiều, bao gồm các hàm VLOOKUP, HLOOKUP, IF, SUMIF,...', 2, '2025-11-19', NULL, 'duy', 0),
(3, 'Mọi người ơi, làm sao để căn giữa một thẻ div trong CSS ạ? Em đã thử dùng margin: 0 auto nhưng không được. Đây là đoạn code của em...', 4, '2025-11-21', NULL, 'mien', 0),
(4, 'Chia sẻ tài liệu tự học SQL từ cơ bản đến nâng cao. Bộ tài liệu bao gồm slide bài giảng, bài tập thực hành và link các trang web luyện tập SQL online miễn phí.', 5, '2025-11-18', NULL, 'duy', 0),
(5, 'Lỗi \"Undefined index\" trong PHP là gì và cách khắc phục? Đây là một lỗi rất phổ biến khi mới học PHP. Bài viết sẽ giải thích nguyên nhân và đưa ra các cách xử lý hiệu quả.', 4, '2025-11-17', NULL, 'mien', 0),
(6, 'Mình đang muốn xây một case máy tính để bàn để học lập trình và chơi game nhẹ, tầm giá 15 triệu. Nhờ mọi người tư vấn giúp mình cấu hình với ạ. Cảm ơn nhiều!', 6, '2025-11-22', NULL, 'duy', 0),
(7, 'Hướng dẫn sử dụng hàm `printf` và `scanf` để nhập xuất dữ liệu trong C++. Đây là hai hàm cơ bản nhưng cực kỳ quan trọng mà bất kỳ ai học C/C++ cũng phải nắm vững.', 3, '2025-11-16', NULL, 'mien', 0);

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
  `Trangthai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblbinhluan`
--

INSERT INTO `tblbinhluan` (`Mabinhluan`, `Noidung`, `Mabaiviet`, `Username`, `Ngaytao`, `Trangthai`) VALUES
(1, 'Theo mình thì năm nhất bạn nên học C++ trước để nắm vững tư duy lập trình và cấu trúc dữ liệu nhé. Sau này học Python sẽ dễ hơn nhiều.', 1, 'duy', '2025-11-20', 1),
(2, 'Bạn thử thêm `display: flex; justify-content: center; align-items: center;` cho thẻ cha của div đó xem sao nhé.', 3, 'duy', '2025-11-21', 1),
(3, 'Bài viết về Excel rất hay và chi tiết, cảm ơn bạn đã chia sẻ!', 2, 'mien', '2025-11-19', 1),
(4, 'Với 15 triệu bạn có thể build cấu hình Ryzen 5 5600G, RAM 16GB là ổn áp cho cả code và game nhẹ rồi đó bạn.', 6, 'mien', '2025-11-22', 1),
(10, 'Intel Core I5', 6, 'admin1', '2025-11-16', 0);

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

--
-- Đang đổ dữ liệu cho bảng `tblcontact`
--

INSERT INTO `tblcontact` (`id`, `Tennguoigui`, `Noidung`, `Email`, `Ngaygui`, `Trangthai`) VALUES
(1, 'mien', 'cần sửa giao diện lại cho đẹp', 'phuocmien@my.tvu.edu.vn', '2024-10-22', 0),
(2, 'mien', 'OK', 'phuocmien@my.tvu.edu.vn', '2024-10-22', 0),
(3, 'phuocmien', 'Tôi cần mua 50 sẩn phẩm abc', 'antonio86doan@gmail.com', '2024-10-22', 0);

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
('admin3', 'e10adc3949ba59abbe56e057f20f883e', 'Kiều Gia Thịnh', 0, 'giathinh.admin@tvu.edu.vn', '', 1, 1),
('camtu', 'e10adc3949ba59abbe56e057f20f883e', 'Phạm Thị Cẩm Tú', 0, 'camtu.pham@tvu.edu.vn', '', 0, 1),
('duy', '5dc6da3adfe8ccf1287a98c0a8f74496', 'le ha duy', 0, 'lehaduy2004@gmail.com', '175906369_800292980906077_319272073812101204_n.jpg', 1, 0),
('duytin', '6afd9643f3e1a07bb92faa4bb403ba32', 'Nguyen Duy Tin', 0, 'duytin@gmail.com', 'z4242815564484_63596ac735d7ce4d2fe59c3739962378.jpg', 0, 0),
('mien', '08d6cd99e919459b34b3c6777ba7f4ce', 'Doan Phuoc Mien', 0, 'phuocmien@tvu.edu.vn', '070216a.jpg', 1, 0),
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
  MODIFY `Mabinhluan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
