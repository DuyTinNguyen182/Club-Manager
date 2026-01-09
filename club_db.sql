-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 09, 2026 lúc 08:44 AM
-- Phiên bản máy phục vụ: 8.0.43
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
  `ma_bai_viet` int NOT NULL,
  `noi_dung` text COLLATE utf8mb4_general_ci NOT NULL,
  `ma_chu_de` int NOT NULL,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `tep_tin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `trang_thai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblbaiviet`
--

INSERT INTO `tblbaiviet` (`ma_bai_viet`, `noi_dung`, `ma_chu_de`, `ngay_tao`, `tep_tin`, `username`, `trang_thai`) VALUES
(8, 'Tết dương lịch nghỉ mấy ngày vậy mọi người', 1, '2025-12-29 06:50:00', '', 'minhanh', 1),
(9, '[EXCEL] TỔNG HỢP 5 PHÍM TẮT GIÚP BẠN \"MÚA\" PHÍM CỰC NHANH...', 2, '2026-01-09 11:26:32', '', 'giathinh', 1),
(10, 'NGỪNG NGAY VIỆC GÕ DẤU CHẤM (...) THỦ CÔNG KHI LÀM MỤC LỤC!...', 2, '2026-01-09 11:33:28', '', 'vanbao', 1),
(13, 'C++, Java hay Python - NÊN CHỌN NGÔN NGỮ NÀO ĐỂ BẮT ĐẦU?...', 3, '2026-01-09 11:43:43', '1767933823_camtu.png', 'camtu', 1),
(14, 'HƯỚNG ĐỐI TƯỢNG (OOP) TRONG JAVA: CLASS & OBJECT KHÁC NHAU NHƯ THẾ NÀO?...', 3, '2026-01-09 11:47:21', '', 'duytin', 1),
(15, 'TẠI SAO PYTHON LẠI LÀ \"VUA\" CỦA NGƯỜI MỚI BẮT ĐẦU?...', 3, '2026-01-09 11:49:58', '', 'phuochiep', 1),
(16, 'PHÂN BIỆT INTER JOIN, LEFT JOIN & RIGHT JOIN DỄ HIỂU NHẤT...', 5, '2026-01-09 11:53:23', '', 'minhanh', 1),
(17, 'SQL & NoSQL - KHI NÀO CHỌN CÁI NÀO?...', 5, '2026-01-09 11:56:08', '', 'quocviet', 1),
(18, 'RAM DUAL CHANNEL LÀ GÌ? TẠI SAO 2  THANH 8GB LẠI MẠNH HƠN 1 THANH 16GB?...', 6, '2026-01-09 12:01:20', '', 'thuthao', 1),
(19, 'WIFI 2.4GHz & 5GHz - KHI NÀO NÊN DÙNG CÁI NÀO?...', 6, '2026-01-09 12:03:10', '', 'giathinh', 1),
(20, 'Trong giới thiết kế không chuyên, luôn có một cuộc tranh luận không hồi kết...', 7, '2026-01-09 12:09:34', '', 'huynhgiao', 1),
(21, 'Bạn code xong giao diện web, bố cục rất ổn nhưng nhìn màu sắc cứ bị \"quê\"...', 7, '2026-01-09 12:11:55', '', 'khanhduy', 1),
(22, 'CSS GRID & FLEXBOX: ĐÂU LÀ CHÂN ÁI CHO GIAO DIỆN WEB?...', 4, '2026-01-09 12:16:20', '', 'kieuthu', 1),
(23, 'PHÂN BIỆT LOCALSTORAGE, SESSIONSTORAGE & COOKIE TRONG 5 PHÚT...', 4, '2026-01-09 12:22:02', '', 'camtu', 1),
(24, 'Vừa sạc vừa dùng Laptop: Có bị chai pin không ạ?', 1, '2026-01-09 12:24:31', '', 'phuochiep', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblbinhluan`
--

CREATE TABLE `tblbinhluan` (
  `ma_binh_luan` int NOT NULL,
  `noi_dung` text COLLATE utf8mb4_general_ci NOT NULL,
  `ma_bai_viet` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `trang_thai` int NOT NULL,
  `ma_binh_luan_cha` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblbinhluan`
--

INSERT INTO `tblbinhluan` (`ma_binh_luan`, `noi_dung`, `ma_bai_viet`, `username`, `ngay_tao`, `trang_thai`, `ma_binh_luan_cha`) VALUES
(16, '1 ngày 1/1 hay sao ấy', 8, 'giathinh', '2025-12-29 00:00:00', 1, 0),
(17, '4 ngày từ ngày 1/1 đến hết CN á', 8, 'vanbao', '2025-12-29 00:00:00', 1, 0),
(18, 'phải k cha', 8, 'vanbao', '2025-12-29 00:00:00', 1, 16),
(19, '@vanbao 1/1 đúng rồi', 8, 'quocviet', '2025-12-29 00:00:00', 1, 16);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblchude`
--

CREATE TABLE `tblchude` (
  `ma_chu_de` int NOT NULL,
  `ten_chu_de` text COLLATE utf8mb4_general_ci NOT NULL,
  `trang_thai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblchude`
--

INSERT INTO `tblchude` (`ma_chu_de`, `ten_chu_de`, `trang_thai`) VALUES
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
  `ma_lien_he` int NOT NULL,
  `ten_nguoi_gui` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `noi_dung` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ngay_gui` datetime NOT NULL,
  `trang_thai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblcontact`
--

INSERT INTO `tblcontact` (`ma_lien_he`, `ten_nguoi_gui`, `noi_dung`, `email`, `ngay_gui`, `trang_thai`) VALUES
(4, 'Nguyễn Thị Lan Anh', 'Cho em tham gia clb với', 'lananh@gmail.com', '2025-12-29 00:00:00', 1),
(5, 'Hoàng Quốc Việt', 'Clb khi nào bầu ban chủ nhiệm mới vậy?', 'quocviet.hoang@tvu.edu.vn', '2025-12-29 14:47:26', 0),
(6, 'Hoàng Quốc Việt', 'Khi nào clb giải thể vậy?', 'quocviet.hoang@tvu.edu.vn', '2025-12-29 20:49:29', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbldangkyhoatdong`
--

CREATE TABLE `tbldangkyhoatdong` (
  `ma_dang_ky` int NOT NULL,
  `ma_hoat_dong` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ngay_dang_ky` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `minh_chung` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Đã đăng ký, 1: Đã tham gia, 2: Vắng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbldangkyhoatdong`
--

INSERT INTO `tbldangkyhoatdong` (`ma_dang_ky`, `ma_hoat_dong`, `username`, `ngay_dang_ky`, `minh_chung`, `trang_thai`) VALUES
(3, 1, 'camtu', '2025-11-10 08:30:00', NULL, 0),
(4, 1, 'duytin', '2025-11-05 09:15:00', NULL, 0),
(5, 1, 'giathinh', '2025-10-28 14:00:00', NULL, 0),
(6, 1, 'minhanh', '2025-11-18 10:45:00', NULL, 0),
(7, 1, 'quocviet', '2025-10-20 16:20:00', NULL, 0),
(8, 1, 'vanbao', '2025-11-02 07:50:00', NULL, 0),
(9, 2, 'camtu', '2025-11-12 09:00:00', NULL, 2),
(10, 2, 'duytin', '2025-10-30 15:10:00', NULL, 2),
(11, 2, 'giathinh', '2025-11-08 08:40:00', NULL, 1),
(12, 2, 'minhanh', '2025-11-20 11:30:00', NULL, 2),
(13, 2, 'quocviet', '2025-10-25 13:55:00', NULL, 1),
(14, 2, 'vanbao', '2025-11-15 17:05:00', NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblhoatdong`
--

CREATE TABLE `tblhoatdong` (
  `ma_hoat_dong` int NOT NULL,
  `ten_hoat_dong` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `mo_ta_hoat_dong` text COLLATE utf8mb4_general_ci NOT NULL,
  `ngay_bat_dau` datetime NOT NULL,
  `ngay_ket_thuc` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dia_diem` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trang_thai` int NOT NULL DEFAULT '0' COMMENT '0: Sắp diễn ra, 1: Đã kết thúc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblhoatdong`
--

INSERT INTO `tblhoatdong` (`ma_hoat_dong`, `ten_hoat_dong`, `mo_ta_hoat_dong`, `ngay_bat_dau`, `ngay_ket_thuc`, `dia_diem`, `trang_thai`) VALUES
(1, 'Workshop Lập trình Web cơ bản cho người mới bắt đầu', 'Buổi workshop hướng dẫn các bạn sinh viên làm quen với HTML, CSS và Javascript.', '2025-11-25 08:30:00', '2026-01-04 13:11:32', 'Phòng máy A1.201, Khu 1, ĐH Trà Vinh', 1),
(2, 'Cuộc thi ', 'Cuộc thi giải thuật toán hàng tháng dành cho tất cả thành viên CLB. Giải thưởng hấp dẫn đang chờ đón!', '2025-12-05 07:00:00', '2026-01-04 13:11:32', 'Trực tuyến trên nền tảng HackerRank', 1),
(3, 'Buổi sinh hoạt CLB cuối năm', 'Tổng kết hoạt động năm 2025 và định hướng cho năm 2026. Có tiệc trà và giao lưu văn nghệ.', '2025-12-29 18:00:00', '2026-01-04 13:11:32', 'Hội trường B5.101', 1),
(4, 'Cuộc thi thiết kế Banner/Poster \"Tech & Life\"', 'Mục đích: Phát triển mảng Đồ họa & Thiết kế.\r\n\r\nNội dung: Thi thiết kế ấn phẩm truyền thông cho CLB bằng Canva hoặc Photoshop.\r\n\r\nĐịa điểm: Nộp bài Online.', '2026-01-12 12:00:00', '2026-01-19 12:00:00', 'Online', 0),
(5, 'Design Contest: Thiết kế Áo CLB Tin học 2026', 'Cuộc thi thiết kế mẫu áo mới cho thành viên CLB. Giải nhất sẽ được chọn làm mẫu in chính thức + Tiền thưởng 500k.', '2026-01-19 12:00:00', '2026-01-26 12:00:00', 'Nộp bài Online qua Drive', 0),
(6, 'Seminar chuyên đề \"Cấu trúc dữ liệu & Giải thuật\"', 'Mục đích: Nâng cao tư duy logic, chuẩn bị cho các kỳ thi Olympic Tin học hoặc phỏng vấn xin việc (Coding Interview).\r\n\r\nĐối tượng: Những bạn muốn đi sâu vào thuật toán tối ưu.', '2026-01-15 08:00:00', '2026-01-15 11:00:00', 'B21.201', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblslideshow`
--

CREATE TABLE `tblslideshow` (
  `ma_slide` int NOT NULL,
  `tieu_de` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_general_ci NOT NULL,
  `hinh_anh` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `trang_thai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblslideshow`
--

INSERT INTO `tblslideshow` (`ma_slide`, `tieu_de`, `mo_ta`, `hinh_anh`, `trang_thai`) VALUES
(9, 'CLB Tin học', '', 'images/750banner.jpg', 1),
(10, 'CLB Tin học', '', 'images/240banner1.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbluser`
--

CREATE TABLE `tbluser` (
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ho_va_ten` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ma_lop` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `ma_sinh_vien` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `gioi_tinh` int NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `anh_dai_dien` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `quyen` int NOT NULL,
  `trang_thai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbluser`
--

INSERT INTO `tbluser` (`username`, `password`, `ho_va_ten`, `ma_lop`, `ma_sinh_vien`, `gioi_tinh`, `email`, `anh_dai_dien`, `quyen`, `trang_thai`) VALUES
('admin1', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Duy Tín', 'DA22TTA', '110122182', 0, 'duytin.admin@tvu.edu.vn', '', 1, 1),
('admin2', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Phước Hiệp', 'DA22TTA', '110122005', 0, 'phuochiep.admin@tvu.edu.vn', 'avatar_admin2_1767885338.jpg', 1, 1),
('admin3', 'e10adc3949ba59abbe56e057f20f883e', 'Gia Thịnh', 'DA22TTC', '110122167', 0, 'giathinh.admin@tvu.edu.vn', 'avatar_admin3_1767929234.jpg', 1, 1),
('camtu', 'e10adc3949ba59abbe56e057f20f883e', 'Phạm Thị Cẩm Tú', '', '', 0, 'camtu.pham@tvu.edu.vn', '', 0, 1),
('duytin', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyen Duy Tin', '', '', 0, 'duytin@gmail.com', 'z4242815564484_63596ac735d7ce4d2fe59c3739962378.jpg', 0, 1),
('giathinh', 'e10adc3949ba59abbe56e057f20f883e', 'Gia Thịnh', 'DA22TTC', '110122167', 0, 'giathinh@gmail.com', 'avatar_giathinh_1767929464.jpeg', 0, 1),
('huynhgiao', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Thị Huỳnh Giao', '', '', 0, 'huynhgiao@tvu.edu.vn', '0', 0, 1),
('khanhduy', 'e10adc3949ba59abbe56e057f20f883e', 'Huỳnh Khánh Duy', '', '', 0, 'khanhduy@tvu.edu.vn', 'avatar_khanhduy_1767935606.jpeg', 0, 1),
('kieuthu', 'e10adc3949ba59abbe56e057f20f883e', 'Huỳnh Thị Kiều Thư', '', '', 0, 'kieuthu@tvu.edu.vn', 'avatar_kieuthu_1767935988.png', 0, 1),
('minhanh', 'e10adc3949ba59abbe56e057f20f883e', 'Lê Minh Anh', 'DA22TTA', '110122111', 1, 'minhanh.le@tvu.edu.vn', 'avatar_minhanh_1766281524.jpg', 0, 1),
('phuochiep', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Phước Hiệp', '', '', 0, 'phuochiep@tvu.edu.vn', 'avatar_phuochiep_1767934230.jpg', 0, 1),
('quocviet', 'e10adc3949ba59abbe56e057f20f883e', 'Hoàng Quốc Việt', '', '', 0, 'quocviet.hoang@tvu.edu.vn', '', 0, 1),
('thuthao', 'e10adc3949ba59abbe56e057f20f883e', 'Trần Thị Thu Thảo', '', '', 0, 'thuthao@tvu.edu.vn', '0', 0, 1),
('vanbao', 'e10adc3949ba59abbe56e057f20f883e', 'Trần Văn Bảo', '', '', 0, 'vanbao.tran@tvu.edu.vn', '', 0, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  ADD PRIMARY KEY (`ma_bai_viet`),
  ADD KEY `username` (`username`),
  ADD KEY `ma_chu_de` (`ma_chu_de`);

--
-- Chỉ mục cho bảng `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  ADD PRIMARY KEY (`ma_binh_luan`),
  ADD KEY `ma_bai_viet` (`ma_bai_viet`),
  ADD KEY `username` (`username`);

--
-- Chỉ mục cho bảng `tblchude`
--
ALTER TABLE `tblchude`
  ADD PRIMARY KEY (`ma_chu_de`);

--
-- Chỉ mục cho bảng `tblcontact`
--
ALTER TABLE `tblcontact`
  ADD PRIMARY KEY (`ma_lien_he`);

--
-- Chỉ mục cho bảng `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  ADD PRIMARY KEY (`ma_dang_ky`),
  ADD UNIQUE KEY `unique_user_hoatdong` (`ma_hoat_dong`,`username`),
  ADD KEY `fk_dangky_user` (`username`);

--
-- Chỉ mục cho bảng `tblhoatdong`
--
ALTER TABLE `tblhoatdong`
  ADD PRIMARY KEY (`ma_hoat_dong`);

--
-- Chỉ mục cho bảng `tblslideshow`
--
ALTER TABLE `tblslideshow`
  ADD PRIMARY KEY (`ma_slide`);

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
  MODIFY `ma_bai_viet` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  MODIFY `ma_binh_luan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `tblchude`
--
ALTER TABLE `tblchude`
  MODIFY `ma_chu_de` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `tblcontact`
--
ALTER TABLE `tblcontact`
  MODIFY `ma_lien_he` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  MODIFY `ma_dang_ky` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `tblhoatdong`
--
ALTER TABLE `tblhoatdong`
  MODIFY `ma_hoat_dong` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tblslideshow`
--
ALTER TABLE `tblslideshow`
  MODIFY `ma_slide` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  ADD CONSTRAINT `tblbaiviet_ibfk_1` FOREIGN KEY (`username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbaiviet_ibfk_2` FOREIGN KEY (`ma_chu_de`) REFERENCES `tblchude` (`ma_chu_de`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  ADD CONSTRAINT `tblbinhluan_ibfk_1` FOREIGN KEY (`ma_bai_viet`) REFERENCES `tblbaiviet` (`ma_bai_viet`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbinhluan_ibfk_2` FOREIGN KEY (`username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  ADD CONSTRAINT `fk_dangky_hoatdong` FOREIGN KEY (`ma_hoat_dong`) REFERENCES `tblhoatdong` (`ma_hoat_dong`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dangky_user` FOREIGN KEY (`username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
