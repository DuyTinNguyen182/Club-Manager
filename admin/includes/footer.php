<script>
	document.addEventListener("DOMContentLoaded", function () {
		// Lấy các phần tử cần thiết
		const sidebarToggle = document.getElementById('sidebarToggle');
		const sidebar = document.getElementById('sidebar');
		const sidebarOverlay = document.getElementById('sidebarOverlay');

		// Hàm bật/tắt menu
		function toggleSidebar() {
			sidebar.classList.toggle('active'); // Thêm/bỏ class active cho sidebar
			sidebarOverlay.classList.toggle('active'); // Thêm/bỏ class active cho lớp phủ
		}

		// Sự kiện khi bấm nút menu
		if (sidebarToggle) {
			sidebarToggle.addEventListener('click', function (e) {
				e.stopPropagation(); // Ngăn chặn sự kiện nổi bọt
				toggleSidebar();
			});
		}

		// Sự kiện khi bấm vào lớp phủ (vùng tối bên ngoài) thì đóng menu
		if (sidebarOverlay) {
			sidebarOverlay.addEventListener('click', function () {
				sidebar.classList.remove('active');
				sidebarOverlay.classList.remove('active');
			});
		}
	});
</script>
</body>

</html>