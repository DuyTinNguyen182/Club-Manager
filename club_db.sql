-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 05:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `club_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblbaiviet`
--

CREATE TABLE `tblbaiviet` (
  `ma_bai_viet` int(11) NOT NULL,
  `noi_dung` text NOT NULL,
  `ma_chu_de` int(11) NOT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `tep_tin` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `trang_thai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbaiviet`
--

INSERT INTO `tblbaiviet` (`ma_bai_viet`, `noi_dung`, `ma_chu_de`, `ngay_tao`, `tep_tin`, `username`, `trang_thai`) VALUES
(8, 'Tết dương lịch nghỉ mấy ngày vậy mọi người', 1, '2025-12-29 06:50:00', '', 'minhanh', 1),
(9, '[EXCEL] TỔNG HỢP 5 PHÍM TẮT GIÚP BẠN \"MÚA\" PHÍM CỰC NHANH\r\nDân văn phòng hay sinh viên làm đồ án mà không biết mấy phím này thì phí cả thanh xuân ngồi click chuột nhé! Lưu lại ngay nào:\r\n\r\n1️⃣ Alt + = (AutoSum)\r\nTự động tính tổng cả cột/hàng trong 1 nốt nhạc. Không cần gõ =SUM() thủ công nữa.\r\n\r\n2️⃣ Ctrl + Shift + L (Filter)\r\nBật/Tắt bộ lọc dữ liệu ngay lập tức. Cực tiện khi cần tra cứu nhanh trong bảng tính lớn.\r\n\r\n3️⃣ Ctrl + ; (Insert Date)\r\nĐiền ngày hiện tại vào ô. Mẹo: Dùng thêm Ctrl + Shift + ; để điền giờ hiện tại.\r\n\r\n4️⃣ Ctrl + E (Flash Fill) - Cực xịn ????\r\nTự động điền dữ liệu theo mẫu.\r\nVí dụ: Cột A có \"Nguyễn Văn A\". Bạn gõ sang cột B chữ \"A\", rồi bấm Ctrl + E. Excel sẽ tự tách tên cho toàn bộ danh sách.\r\n\r\n5️⃣ Ctrl + PgUp / Ctrl + PgDn\r\nChuyển đổi qua lại giữa các Sheet (Tab) mà không cần dùng chuột.\r\n\r\nAnh em còn phím nào hay ho comment bên dưới chia sẻ nhé! ????\r\n#ExcelTips #TinHocVanPhong #PhimTat', 2, '2026-01-07 11:26:32', '', 'giathinh', 1),
(10, 'NGỪNG NGAY VIỆC GÕ DẤU CHẤM (...) THỦ CÔNG KHI LÀM MỤC LỤC!\r\nLàm báo cáo thực tập hay tiểu luận mà ngồi gõ từng dấu chấm \"................\" cho thẳng hàng thì bao giờ mới xong? Chưa kể chỉnh sửa là bị lệch ngay.\r\n\r\n✅ Cách làm chuẩn chỉnh (Tab Leader):\r\n1. Bôi đen các dòng cần tạo dòng chấm.\r\n2. Nhấp đúp chuột trái vào thanh thước kẻ (Ruler) ở vị trí bạn muốn dòng chấm kết thúc (ví dụ số 16cm).\r\n3. Hộp thoại Tabs hiện ra:\r\n   - Mục Alignment chọn \"Right\".\r\n   - Mục Leader chọn số \"2 ......\"\r\n4. Nhấn Set > OK.\r\n5. Bây giờ chỉ cần đặt trỏ chuột cuối chữ và bấm phím TAB. Dòng chấm sẽ tự chạy thẳng tắp!\r\n\r\nAi chưa làm được thì inbox hoặc comment mình chỉ chi tiết nha!\r\n#WordTips #BaoCao #MucLuc', 2, '2026-01-07 15:33:28', '', 'vanbao', 1),
(13, 'C++, Java hay Python - NÊN CHỌN NGÔN NGỮ NÀO ĐỂ BẮT ĐẦU?\r\nCâu hỏi triệu đô của mọi tân sinh viên IT. Câu trả lời là: Tùy vào mục đích của bạn!\r\n\r\n1. Chọn C/C++ nếu: Bạn muốn hiểu sâu về cách máy tính hoạt động, quản lý bộ nhớ, cấu trúc dữ liệu. Đây là nền tảng vững chắc nhất để học mọi ngôn ngữ khác sau này. Các trường đại học thường dạy món này đầu tiên là có lý do cả đấy.\r\n\r\n2. Chọn Python nếu: Bạn thích làm về Dữ liệu (Data), Trí tuệ nhân tạo (AI) hoặc muốn viết code chạy được ngay ra kết quả mà không muốn đau đầu vì lỗi cú pháp.\r\n\r\n3. Chọn Java nếu: Bạn hướng tới làm việc tại các công ty lớn (Ngân hàng, Viễn thông), làm hệ thống Backend doanh nghiệp hoặc lập trình ứng dụng Android.\r\n\r\nKết luận: Ngôn ngữ chỉ là công cụ. Tư duy giải quyết vấn đề (Algorithm) mới là thứ đi theo bạn suốt đời. Đừng quá quan trọng ngôn ngữ, hãy bắt đầu với một cái và học thật sâu! #CareerPath #TuVan #ITStudent', 3, '2026-01-08 08:51:43', '1767933823_camtu.png', 'camtu', 1),
(14, 'HƯỚNG ĐỐI TƯỢNG (OOP) TRONG JAVA: CLASS & OBJECT KHÁC NHAU NHƯ THẾ NÀO?\r\nHọc Java mà không hiểu OOP (Lập trình hướng đối tượng) thì coi như chưa học. Hai khái niệm cơ bản nhất là Class (Lớp) và Object (Đối tượng), nhưng nhiều bạn vẫn nhầm lẫn.\r\n\r\nHãy tưởng tượng về việc xây nhà:\r\n\r\n1. Class (Bản vẽ thiết kế): Nó là bản vẽ trên giấy. Nó quy định ngôi nhà sẽ có 2 cửa sổ, 1 cửa chính, sơn màu gì. Bản vẽ này KHÔNG Ở ĐƯỢC. -> Trong code: public class House { ... }\r\n\r\n2. Object (Ngôi nhà thực tế): Từ 1 bản vẽ (Class), thợ xây có thể xây ra hàng nghìn ngôi nhà thực tế (Object). Ngôi nhà này mới là thứ tồn tại chiếm diện tích đất (bộ nhớ). -> Trong code: House myHouse = new House();\r\n\r\nTóm lại: Class là khuôn mẫu, Object là sản phẩm tạo ra từ khuôn mẫu đó. Hiểu được cái này là bạn đã nắm được 50% linh hồn của Java rồi đấy! \r\n#Java #OOP #HuongDoiTuong', 3, '2026-01-09 18:26:21', '', 'duytin', 1),
(15, 'TẠI SAO PYTHON LẠI LÀ \"VUA\" CỦA NGƯỜI MỚI BẮT ĐẦU?\r\nNếu C++ bắt bạn viết 10 dòng để in ra màn hình và xử lý chuỗi, thì Python chỉ cần 1 dòng. Đó là lý do Python đang thống trị các bảng xếp hạng ngôn ngữ lập trình.\r\n\r\nƯu điểm tuyệt đối:\r\n\r\n1. Cú pháp như tiếng Anh: Đọc code Python cảm giác như đang đọc một đoạn văn tiếng Anh đơn giản, không rườm rà dấu chấm phẩy (;).\r\n\r\n2. Thư viện khổng lồ: Bạn muốn làm AI? Có TensorFlow. Muốn làm Web? Có Django. Muốn phân tích dữ liệu? Có Pandas.\r\n\r\n3. Cộng đồng cực lớn: Gặp lỗi gì, chỉ cần copy lỗi lên Google là có ngay câu trả lời.\r\n\r\nVí dụ vui: Để đảo ngược một chuỗi \"Hello\" trong C++, bạn cần vòng lặp for. Trong Python: \"Hello\"[::-1] -> Xong!\r\n\r\nTuy nhiên, đừng vì Python dễ mà bỏ qua C++. Học C++ giúp bạn hiểu sâu về tư duy máy tính, còn Python giúp bạn làm việc nhanh hơn. \r\n#Python #Programming #Beginner', 3, '2026-01-08 11:49:58', '', 'phuochiep', 1),
(16, 'PHÂN BIỆT INTER JOIN, LEFT JOIN & RIGHT JOIN DỄ HIỂU NHẤT\r\nNếu bạn vẫn đang mơ hồ mỗi khi viết câu lệnh JOIN các bảng với nhau, thì đây là bài viết dành cho bạn. Đừng học vẹt, hãy tưởng tượng nó như sơ đồ tập hợp (Venn):\r\n\r\n1. INNER JOIN (Giao nhau): Chỉ lấy những dòng dữ liệu có mặt ở CẢ HAI bảng. Ví dụ: Tìm những Sinh viên CÓ đăng ký tham gia Hoạt động. (Sinh viên nào không đăng ký sẽ không hiện ra).\r\n\r\n2. LEFT JOIN (Ưu tiên bảng bên Trái): Lấy TẤT CẢ dòng ở bảng bên trái (bảng A), và chỉ lấy những dòng khớp ở bảng bên phải (bảng B). Nếu bảng B không có dữ liệu khớp, nó sẽ để là NULL. Ví dụ: Liệt kê TẤT CẢ Sinh viên và hoạt động họ tham gia. (Sinh viên nào không tham gia thì cột hoạt động sẽ là NULL, nhưng tên sinh viên vẫn hiện).\r\n\r\n3. RIGHT JOIN (Ưu tiên bảng bên Phải): Ngược lại với Left Join. Lấy tất cả bảng bên phải và phần khớp của bảng bên trái.\r\n\r\nMẹo: Trong thực tế đi làm, 90% chúng ta dùng Inner Join và Left Join. Right Join rất ít khi dùng vì nó làm luồng suy nghĩ bị ngược. \r\n#SQL #Database #Join', 5, '2026-01-07 14:36:23', '', 'minhanh', 1),
(17, 'SQL & NoSQL - KHI NÀO CHỌN CÁI NÀO?\r\nThế giới dữ liệu chia làm 2 phe: SQL (MySQL, SQL Server) và NoSQL (MongoDB, Firebase). Sinh viên nên chọn học cái nào?\r\n\r\n1. SQL (Cơ sở dữ liệu quan hệ):\r\n- Dữ liệu có cấu trúc rõ ràng (bảng, cột, dòng).\r\n- Dữ liệu có sự liên kết chặt chẽ (Ràng buộc khóa ngoại).\r\n- Phù hợp cho: Hệ thống ngân hàng, Quản lý sinh viên, Thương mại điện tử (cần độ chính xác tuyệt đối).\r\n\r\n2. NoSQL (Phi quan hệ):\r\n- Dữ liệu lưu dạng văn bản (JSON), linh hoạt, không cần cấu trúc cố định.\r\n- Tốc độ đọc ghi cực nhanh, dễ mở rộng.\r\n- Phù hợp cho: Mạng xã hội (Facebook, TikTok), Game, Big Data, Chat realtime.\r\n\r\nKết luận: SQL vẫn là nền tảng bắt buộc phải biết. Sau khi vững SQL, hãy học thêm MongoDB để trở thành Fullstack Developer nhé! #SQL #NoSQL #CareerPath', 5, '2026-01-07 19:56:08', '', 'quocviet', 1),
(18, 'RAM DUAL CHANNEL LÀ GÌ? TẠI SAO 2  THANH 8GB LẠI MẠNH HƠN 1 THANH 16GB?\r\nKhi build PC hoặc nâng cấp Laptop, rất nhiều bạn thắc mắc: \"Nên mua 1 thanh RAM 16GB để sau này dễ nâng cấp, hay mua 2 thanh 8GB?\"\r\n\r\nCâu trả lời cho hiệu năng tốt nhất luôn là: 2 thanh 8GB (Chạy Dual Channel).\r\n\r\n1. Hãy tưởng tượng RAM như một con đường để dữ liệu đi từ CPU ra ngoài.\r\n- Single Channel (1 thanh RAM): Giống như đường 1 chiều. Xe cộ phải chen chúc nhau, dễ gây tắc nghẽn.\r\n- Dual Channel (2 thanh RAM): Giống như mở rộng thành đường 2 chiều. Lưu lượng dữ liệu đi qua tăng gấp đôi băng thông.\r\n\r\n2. Lợi ích thực tế:\r\n- FPS khi chơi game ổn định hơn, ít bị giật lag (drop fps).\r\n- Tốc độ render video hoặc chạy máy ảo nhanh hơn đáng kể.\r\n\r\nLưu ý: Để kích hoạt Dual Channel, bạn cần cắm 2 thanh RAM vào các khe so le nhau (thường là khe 2 và 4 trên Mainboard có 4 khe). \r\n#Hardware #RAM #DualChannel #PCBuilding', 6, '2026-01-08 12:18:20', '', 'thuthao', 1),
(19, 'WIFI 2.4GHz & 5GHz - KHI NÀO NÊN DÙNG CÁI NÀO?\r\nHầu hết các Router wifi hiện nay đều phát 2 băng tần. Nhưng không phải lúc nào sóng 5GHz cũng tốt hơn đâu nhé. Hãy chọn đúng để mạng không bị lag:\r\n\r\n1. Băng tần 2.4GHz:\r\n- Ưu điểm: Khả năng xuyên tường cực tốt, phủ sóng rộng.\r\n- Nhược điểm: Tốc độ thấp hơn, dễ bị nhiễu sóng (do lò vi sóng, loa bluetooth cũng dùng tần số này).\r\n- Khi nào dùng: Khi bạn ở xa cục Router, hoặc ngồi trong phòng kín cách Router 1-2 bức tường.\r\n\r\n2. Băng tần 5GHz:\r\n- Ưu điểm: Tốc độ cực nhanh, ít bị nhiễu, chơi game ping rất thấp.\r\n- Nhược điểm: Khả năng xuyên tường kém. Chỉ cần đóng cửa phòng là sóng yếu ngay.\r\n- Khi nào dùng: Khi bạn ngồi gần Router, chơi game online hoặc xem phim 4K.\r\n\r\nMẹo: Nếu ngồi chơi game cạnh cục modem mà thấy lag, hãy kiểm tra xem điện thoại/laptop đã kết nối đúng vào tên wifi có chữ \"5G\" chưa nhé. #Network #Wifi #Tips', 6, '2026-01-09 16:03:10', '', 'giathinh', 1),
(20, 'Trong giới thiết kế không chuyên, luôn có một cuộc tranh luận không hồi kết: Nên dùng Canva cho nhanh hay học Photoshop cho chuyên nghiệp? Câu trả lời nằm ở mục đích sử dụng của bạn.\r\n\r\n1. Canva - \"Mì ăn liền\" chất lượng cao\r\nƯu điểm lớn nhất của Canva là tốc độ và kho tài nguyên khổng lồ. Nếu bạn cần làm gấp một cái Poster tuyển thành viên, một slide thuyết trình hay ảnh bìa Facebook, Canva là lựa chọn số 1. Chỉ cần kéo thả, thay chữ là xong. Không cần cài đặt nặng máy, làm trực tiếp trên trình duyệt.\r\n\r\n2. Photoshop - \"Đại bác\" hạng nặng\r\nNếu bạn cần chỉnh sửa ảnh chi tiết, tách nền tóc phức tạp, hay thiết kế giao diện web (UI) có chiều sâu, Canva sẽ phải chào thua. Photoshop cho bạn quyền kiểm soát từng điểm ảnh (pixel). Học Photoshop giúp bạn hiểu sâu về layer, mask và blend mode - những tư duy cốt lõi của đồ họa.\r\n\r\nLời khuyên:\r\nĐừng chọn 1 trong 2, hãy dùng cả hai!\r\n- Dùng Canva khi cần tốc độ, làm slide, ấn phẩm truyền thông xã hội đơn giản.\r\n- Dùng Photoshop khi cần xử lý ảnh gốc, thiết kế logo hoặc banner khổ lớn để in ấn.\r\n\r\nKết hợp linh hoạt sẽ giúp bạn vừa tiết kiệm thời gian, vừa đảm bảo chất lượng sản phẩm.\r\n#DesignTools #Canva #Photoshop #GraphicDesign', 7, '2026-01-02 12:09:34', '', 'huynhgiao', 1),
(21, 'Bạn code xong giao diện web, bố cục rất ổn nhưng nhìn màu sắc cứ bị \"quê\" hoặc rối mắt? Đó là do bạn chưa biết đến quy tắc vàng trong phối màu: 60-30-10. Đây là công thức kinh điển giúp thiết kế của bạn hài hòa ngay lập tức.\r\n\r\nQuy tắc hoạt động như sau:\r\n1. 60% là Màu chủ đạo (Dominant Color)\r\nĐây thường là màu nền (background). Hãy chọn các màu trung tính như trắng, xám nhạt, hoặc màu be. Nó đóng vai trò là sân khấu để tôn các thành phần khác lên.\r\n2. 30% là Màu phụ (Secondary Color)\r\nMàu này dùng cho các khối nội dung, thanh menu, hoặc các tiêu đề phụ. Nó phải có độ tương phản vừa đủ với màu chủ đạo để tạo sự rõ ràng nhưng không quá chói.\r\n3. 10% là Màu nhấn (Accent Color)\r\nĐây là \"ngôi sao\" của thiết kế. Hãy dùng màu nổi bật nhất (như đỏ, cam, xanh neon) cho các nút bấm quan trọng (Call To Action), link liên kết hoặc thông báo. Nó dẫn dắt mắt người dùng bấm vào nơi bạn muốn.\r\nVí dụ thực tế:\r\nHãy nhìn giao diện Facebook cũ: 60% là màu trắng (nền), 30% là màu xanh dương (thanh header), 10% là màu đỏ (số thông báo).\r\n\r\nLần tới khi CSS cho web, hãy thử áp dụng tỉ lệ này nhé!\r\n#ColorTheory #UIUX #WebDesign #Tips', 7, '2026-01-03 16:11:55', '', 'khanhduy', 1),
(22, 'CSS GRID & FLEXBOX: ĐÂU LÀ CHÂN ÁI CHO GIAO DIỆN WEB?\r\n\r\nNội dung: Khi mới học CSS, chúng ta thường bị rối giữa Flexbox và Grid vì cả hai đều dùng để dàn trang. Vậy khi nào nên dùng cái nào?\r\n\r\n1. Flexbox (1 chiều): Hãy dùng Flexbox khi bạn muốn sắp xếp các phần tử theo MỘT chiều duy nhất: hoặc là hàng ngang (row), hoặc là hàng dọc (column). Ứng dụng tốt nhất: Thanh menu (Navbar), căn giữa nội dung trong một ô, chia danh sách sản phẩm đơn giản.\r\n\r\n2. CSS Grid (2 chiều): Grid sinh ra để xử lý bố cục HAI chiều: cả hàng và cột cùng lúc. Nó giống như bạn kẻ một cái lưới ô ly lên trang giấy rồi đặt nội dung vào. Ứng dụng tốt nhất: Chia layout tổng thể của cả trang web (Header, Sidebar, Main Content, Footer), thư viện ảnh phức tạp.\r\n\r\nMẹo cốt lõi: Dùng Grid để chia khung sườn cho ngôi nhà. Dùng Flexbox để sắp xếp nội thất bên trong từng phòng. Kết hợp cả hai là cách làm chuyên nghiệp nhất hiện nay! #CSS #Frontend #WebDesign', 4, '2026-01-09 15:16:20', '', 'kieuthu', 1),
(23, 'PHÂN BIỆT LOCALSTORAGE, SESSIONSTORAGE & COOKIE TRONG 5 PHÚT\r\n\r\nNội dung: Khi làm web, chúng ta thường cần lưu một số thông tin ở trình duyệt người dùng (như trạng thái đăng nhập, giỏ hàng, chế độ tối/sáng). Nhưng lưu vào đâu thì đúng?\r\n\r\n1. Cookie:\r\nĐặc điểm: Được gửi kèm lên Server mỗi khi load trang. Dung lượng nhỏ (4KB). Có hạn sử dụng.\r\nDùng khi: Lưu Token đăng nhập (để Server biết bạn là ai).\r\n\r\n2. LocalStorage:\r\nĐặc điểm: Lưu trữ vĩnh viễn trên trình duyệt (trừ khi người dùng tự xóa). Dung lượng lớn (5-10MB). Không gửi lên Server.\r\nDùng khi: Lưu cấu hình trang web (Dark mode, ngôn ngữ), lưu giỏ hàng cho khách chưa đăng nhập.\r\n\r\n3. SessionStorage:\r\nĐặc điểm: Chỉ sống trong một phiên làm việc. Tắt tab hoặc tắt trình duyệt là mất sạch.\r\nDùng khi: Lưu dữ liệu tạm thời của một form đang điền dở, hoặc thông tin nhạy cảm không muốn lưu lâu dài.\r\n\r\nHiểu rõ 3 cái này giúp website của bạn chạy nhanh hơn và bảo mật hơn đấy! #WebDev #Frontend #KienThuc', 4, '2026-01-05 12:58:02', '', 'camtu', 1),
(24, 'Vừa sạc vừa dùng Laptop: Có bị chai pin không ạ?', 1, '2026-01-09 12:24:31', '', 'phuochiep', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblbinhluan`
--

CREATE TABLE `tblbinhluan` (
  `ma_binh_luan` int(11) NOT NULL,
  `noi_dung` text NOT NULL,
  `ma_bai_viet` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `trang_thai` int(11) NOT NULL,
  `ma_binh_luan_cha` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbinhluan`
--

INSERT INTO `tblbinhluan` (`ma_binh_luan`, `noi_dung`, `ma_bai_viet`, `username`, `ngay_tao`, `trang_thai`, `ma_binh_luan_cha`) VALUES
(16, '1 ngày 1/1 hay sao ấy', 8, 'giathinh', '2025-12-29 00:00:00', 1, 0),
(17, '4 ngày từ ngày 1/1 đến hết CN á', 8, 'vanbao', '2025-12-29 00:00:00', 1, 0),
(18, 'phải k cha', 8, 'vanbao', '2025-12-29 00:00:00', 1, 16),
(19, '@vanbao 1/1 đúng rồi', 8, 'quocviet', '2025-12-29 00:00:00', 1, 16),
(23, 'Không bạn nhé', 24, 'giathinh', '2026-01-09 21:44:04', 1, 0),
(24, 'Hay á chứ. Vậy mà từ trước tới giờ tôi cứ gõ từng dấu thôi', 10, 'giathinh', '2026-01-09 21:44:56', 1, 0),
(25, 'haha giống nhau vậy', 10, 'duytin', '2026-01-09 21:46:03', 1, 24),
(27, 'Ctrl + Space – Chọn nguyên cột trong 1 nốt nhạc nữa nè', 9, 'camtu', '2026-01-09 21:51:30', 1, 0),
(28, 'Python dễ vậy có phải học xong là làm được liền không hay vẫn cần nền tảng lập trình trước ạ?', 15, 'camtu', '2026-01-09 21:59:25', 1, 0),
(29, 'Python rất thân thiện cho người mới nha. Chỉ cần hiểu tư duy cơ bản (biến, điều kiện, vòng lặp) là đã làm được nhiều thứ rồi.', 15, 'phuochiep', '2026-01-09 22:00:45', 1, 28),
(30, 'Hay quá', 22, 'phuochiep', '2026-01-09 22:40:53', 1, 0),
(31, 'Thật không bạn', 24, 'kieuthu', '2026-01-09 22:42:39', 1, 23),
(32, '@kieuthu Thật bạn nhé', 24, 'giathinh', '2026-01-09 22:42:59', 1, 23),
(34, 'Tuyệt vời', 22, 'giathinh', '2026-01-09 22:47:05', 1, 30),
(35, 'Shift + Space – Chọn nguyên hàng nữa nè', 9, 'quocviet', '2026-01-09 22:49:51', 1, 27),
(36, 'Ngôn ngữ nào dễ học nhất cho người mới bắt đầu ạ', 13, 'quocviet', '2026-01-09 22:50:53', 1, 0),
(37, 'Như vậy thì lưu cái nào sẽ an toàn hơn', 23, 'quocviet', '2026-01-09 22:51:43', 1, 0),
(38, 'Hay quá', 17, 'quocviet', '2026-01-09 22:53:25', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblchude`
--

CREATE TABLE `tblchude` (
  `ma_chu_de` int(11) NOT NULL,
  `ten_chu_de` text NOT NULL,
  `trang_thai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblchude`
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
-- Table structure for table `tblcontact`
--

CREATE TABLE `tblcontact` (
  `ma_lien_he` int(11) NOT NULL,
  `ten_nguoi_gui` varchar(100) NOT NULL,
  `noi_dung` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `ngay_gui` datetime NOT NULL,
  `trang_thai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcontact`
--

INSERT INTO `tblcontact` (`ma_lien_he`, `ten_nguoi_gui`, `noi_dung`, `email`, `ngay_gui`, `trang_thai`) VALUES
(4, 'Nguyễn Thị Lan Anh', 'Cho em tham gia clb với', 'lananh@gmail.com', '2025-12-29 00:00:00', 1),
(5, 'Hoàng Quốc Việt', 'Clb khi nào bầu ban chủ nhiệm mới vậy?', 'quocviet.hoang@tvu.edu.vn', '2025-12-29 14:47:26', 0),
(6, 'Hoàng Quốc Việt', 'Khi nào clb giải thể vậy?', 'quocviet.hoang@tvu.edu.vn', '2025-12-29 20:49:29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbldangkyhoatdong`
--

CREATE TABLE `tbldangkyhoatdong` (
  `ma_dang_ky` int(11) NOT NULL,
  `ma_hoat_dong` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ngay_dang_ky` datetime NOT NULL DEFAULT current_timestamp(),
  `minh_chung` varchar(50) DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Đã đăng ký, 1: Đã tham gia, 2: Vắng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldangkyhoatdong`
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
(14, 2, 'vanbao', '2025-11-15 17:05:00', NULL, 1),
(15, 4, 'phuochiep', '2026-01-09 22:41:32', NULL, 0),
(16, 6, 'phuochiep', '2026-01-09 22:41:46', NULL, 0),
(17, 5, 'phuochiep', '2026-01-09 22:41:52', NULL, 0),
(18, 4, 'kieuthu', '2026-01-09 22:42:11', NULL, 0),
(19, 5, 'kieuthu', '2026-01-09 22:42:17', NULL, 0),
(20, 4, 'quocviet', '2026-01-09 22:56:25', NULL, 0),
(21, 6, 'quocviet', '2026-01-09 22:56:35', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblhoatdong`
--

CREATE TABLE `tblhoatdong` (
  `ma_hoat_dong` int(11) NOT NULL,
  `ten_hoat_dong` varchar(200) NOT NULL,
  `mo_ta_hoat_dong` text NOT NULL,
  `ngay_bat_dau` datetime NOT NULL,
  `ngay_ket_thuc` datetime NOT NULL DEFAULT current_timestamp(),
  `dia_diem` varchar(255) DEFAULT NULL,
  `trang_thai` int(11) NOT NULL DEFAULT 0 COMMENT '0: Sắp diễn ra, 1: Đã kết thúc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhoatdong`
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
-- Table structure for table `tblslideshow`
--

CREATE TABLE `tblslideshow` (
  `ma_slide` int(11) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `mo_ta` text NOT NULL,
  `hinh_anh` varchar(255) NOT NULL,
  `trang_thai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblslideshow`
--

INSERT INTO `tblslideshow` (`ma_slide`, `tieu_de`, `mo_ta`, `hinh_anh`, `trang_thai`) VALUES
(9, 'CLB Tin học', '', 'images/750banner.jpg', 0),
(10, 'CLB Tin học', '', 'images/240banner1.jpg', 0),
(14, 'SEMINAR', '', 'images/1768060919_Screenshot 2026-01-10 223940.png', 1),
(15, 'TECH & LIFE 2026', '', 'images/1768060911_Screenshot 2026-01-10 223953.png', 1),
(16, ' ', '', 'images/1768060868_Screenshot 2026-01-10 224508.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `ho_va_ten` varchar(50) NOT NULL,
  `ma_lop` varchar(11) NOT NULL,
  `ma_sinh_vien` varchar(10) NOT NULL,
  `gioi_tinh` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `anh_dai_dien` varchar(255) NOT NULL,
  `quyen` int(11) NOT NULL,
  `trang_thai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
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
-- Indexes for dumped tables
--

--
-- Indexes for table `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  ADD PRIMARY KEY (`ma_bai_viet`),
  ADD KEY `username` (`username`),
  ADD KEY `ma_chu_de` (`ma_chu_de`);

--
-- Indexes for table `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  ADD PRIMARY KEY (`ma_binh_luan`),
  ADD KEY `ma_bai_viet` (`ma_bai_viet`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `tblchude`
--
ALTER TABLE `tblchude`
  ADD PRIMARY KEY (`ma_chu_de`);

--
-- Indexes for table `tblcontact`
--
ALTER TABLE `tblcontact`
  ADD PRIMARY KEY (`ma_lien_he`);

--
-- Indexes for table `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  ADD PRIMARY KEY (`ma_dang_ky`),
  ADD UNIQUE KEY `unique_user_hoatdong` (`ma_hoat_dong`,`username`),
  ADD KEY `fk_dangky_user` (`username`);

--
-- Indexes for table `tblhoatdong`
--
ALTER TABLE `tblhoatdong`
  ADD PRIMARY KEY (`ma_hoat_dong`);

--
-- Indexes for table `tblslideshow`
--
ALTER TABLE `tblslideshow`
  ADD PRIMARY KEY (`ma_slide`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  MODIFY `ma_bai_viet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  MODIFY `ma_binh_luan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tblchude`
--
ALTER TABLE `tblchude`
  MODIFY `ma_chu_de` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblcontact`
--
ALTER TABLE `tblcontact`
  MODIFY `ma_lien_he` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  MODIFY `ma_dang_ky` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tblhoatdong`
--
ALTER TABLE `tblhoatdong`
  MODIFY `ma_hoat_dong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblslideshow`
--
ALTER TABLE `tblslideshow`
  MODIFY `ma_slide` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblbaiviet`
--
ALTER TABLE `tblbaiviet`
  ADD CONSTRAINT `tblbaiviet_ibfk_1` FOREIGN KEY (`username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbaiviet_ibfk_2` FOREIGN KEY (`ma_chu_de`) REFERENCES `tblchude` (`ma_chu_de`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblbinhluan`
--
ALTER TABLE `tblbinhluan`
  ADD CONSTRAINT `tblbinhluan_ibfk_1` FOREIGN KEY (`ma_bai_viet`) REFERENCES `tblbaiviet` (`ma_bai_viet`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbinhluan_ibfk_2` FOREIGN KEY (`username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbldangkyhoatdong`
--
ALTER TABLE `tbldangkyhoatdong`
  ADD CONSTRAINT `fk_dangky_hoatdong` FOREIGN KEY (`ma_hoat_dong`) REFERENCES `tblhoatdong` (`ma_hoat_dong`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dangky_user` FOREIGN KEY (`username`) REFERENCES `tbluser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
