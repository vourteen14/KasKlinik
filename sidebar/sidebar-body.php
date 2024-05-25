<nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
	<ul class="app-menu list-unstyled accordion" id="menu-accordion">
		<li class="nav-item">
			<a class="nav-link <?php echo $isPage == 'dashboard' ? 'active' : ''; ?>" href="/">
				<span class="nav-icon">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door" fill="currentColor"
						xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd"
							d="M7.646 1.146a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5H9.5a.5.5 0 0 1-.5-.5v-4H7v4a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6zM2.5 7.707V14H6v-4a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v4h3.5V7.707L8 2.207l-5.5 5.5z" />
						<path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
					</svg>
				</span>
				<span class="nav-link-text">Dashboard</span>
			</a>
		</li>
		<li class="nav-item has-submenu">
			<a class="nav-link submenu-toggle <?php echo $isPage == 'data-pasien' || $isPage == 'data-tindakan' ? 'active' : ''; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-1"
				aria-expanded="false" aria-controls="submenu-1">
				<span class="nav-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid-1x2" viewBox="0 0 16 16">
  					<path d="M6 1H1v14h5zm9 0h-5v5h5zm0 9v5h-5v-5zM0 1a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm9 0a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1zm1 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1z"/>
					</svg>
				</span>
				<span class="nav-link-text">Master</span>
				<span class="submenu-arrow">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor"
						xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd"
							d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
					</svg>
				</span>
			</a>
			<div id="submenu-1" class="collapse submenu submenu-1" data-bs-parent="#menu-accordion">
				<ul class="submenu-list list-unstyled">
					<li class="submenu-item"><a class="submenu-link <?php echo $isPage == 'data-pasien' ? 'active' : ''; ?>" href="/data-pasien.php">Data Pasien</a></li>
					<li class="submenu-item"><a class="submenu-link <?php echo $isPage == 'data-tindakan' ? 'active' : ''; ?>" href="/data-tindakan.php">Data Tindakan</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item has-submenu">
			<a class="nav-link submenu-toggle <?php echo $isPage == 'penerimaan-kas' || $isPage == 'pengeluaran-kas' ? 'active' : ''; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-2"
				aria-expanded="false" aria-controls="submenu-2">
				<span class="nav-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-stack" viewBox="0 0 16 16">
						<path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
						<path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z"/>
					</svg>
				</span>
				<span class="nav-link-text">Transaksi</span>
				<span class="submenu-arrow">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor"
						xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd"
							d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
					</svg>
				</span>
			</a>
			<div id="submenu-2" class="collapse submenu submenu-2" data-bs-parent="#menu-accordion">
				<ul class="submenu-list list-unstyled">
					<li class="submenu-item"><a class="submenu-link <?php echo $isPage == 'penerimaan-kas' ? 'active' : ''; ?>" href="/penerimaan-kas.php">Penerimaan Kas</a></li>
					<li class="submenu-item"><a class="submenu-link <?php echo $isPage == 'pengeluaran-kas' ? 'active' : ''; ?>" href="/pengeluaran-kas.php">Pengeluaran Kas</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php echo $isPage == 'laporan' ? 'active' : ''; ?>" href="/laporan.php">
				<span class="nav-icon">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bar-chart-line" fill="currentColor"
						xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd"
							d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z" />
					</svg>
				</span>
				<span class="nav-link-text">Laporan</span>
			</a>
		</li>
	</ul>
</nav>