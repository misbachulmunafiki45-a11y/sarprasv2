<div class="floating-button-container d-flex" onclick="window.location.href = '{{ route('report.create') }}'">
    <button class="floating-button">
        <i class="fa-solid fa-square-plus"></i>
    </button>
</div>
<nav class="nav-mobile d-flex">
    <a href="{{ route ('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        Beranda
    </a>
    <a href="{{ route('report.myreport', ['status'=> 'delivered']) }}" class="">
        <i class="fa-solid fa-clipboard-list"></i>
        Laporanmu
    </a>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <a href="#" id="notifLink" class="position-relative">
        <i class="fa-solid fa-bell"></i>
        <span id="notifBadge" class="badge rounded-pill bg-danger position-absolute d-none" style="font-size:10px; line-height:1; top:-4px; right:-8px;">0</span>
        Notifikasi
    </a>
    @auth
    <a href="{{ route('profile') }}"class="">
        <i class="fa-solid fa-user"></i>
        Profile
    </a> 
    @else
    <a href="{{ route('register') }}"class="">
        <i class="fa-solid fa-right-to-bracket"></i>
        Daftar
    </a> 
    @endauth
</nav>

@auth
<script>
    (function() {
        const badge = document.getElementById('notifBadge');
        const notifLink = document.getElementById('notifLink');
        const audio = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');
        let lastCount = 0;

        async function fetchCount() {
            try {
                const res = await fetch('{{ route('notifications.count') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) return;
                const data = await res.json();
                const count = Number(data.count || 0);

                if (count > 0) {
                    badge.classList.remove('d-none');
                    badge.textContent = count > 99 ? '99+' : count;
                } else {
                    badge.classList.add('d-none');
                }

                if (count > lastCount) {
                    audio.currentTime = 0;
                    audio.play().catch(() => {});
                }
                lastCount = count;
            } catch (e) {
                // silent
            }
        }

        async function openLatest() {
            try {
                const res = await fetch('{{ route('notifications.latest') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) return;
                const data = await res.json();
                // Optimistically clear badge
                badge.classList.add('d-none');
                lastCount = 0;
                if (data && data.url) {
                    window.location.href = data.url;
                } else {
                    // Jika tidak ada notifikasi baru, arahkan ke daftar laporan terbaru (non-delivered)
                    window.location.href = '{{ route('report.myreport', ['status' => 'in_proses']) }}';
                }
            } catch (e) {
                // Fallback ke halaman laporanmu
                window.location.href = '{{ route('report.myreport', ['status' => 'in_proses']) }}';
            }
        }

        notifLink && notifLink.addEventListener('click', function(e) {
            e.preventDefault();
            openLatest();
        });

        fetchCount();
        setInterval(fetchCount, 10000);
    })();
</script>
@endauth
