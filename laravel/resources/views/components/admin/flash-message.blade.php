{{-- Flash messages fire as Toastify toasts (consistent with AJAX responses).
     Inline banners are replaced — all feedback is uniform top-right toasts. --}}
@if(session('success') || session('error') || session('warning') || session('info'))
<script>
    (function () {
        function tryToast() {
            if (typeof window.adminToast === 'function') {
                @if(session('success'))
                window.adminToast(@js(session('success')), 'success');
                @endif
                @if(session('error'))
                window.adminToast(@js(session('error')), 'error');
                @endif
                @if(session('warning'))
                window.adminToast(@js(session('warning')), 'warning');
                @endif
                @if(session('info'))
                window.adminToast(@js(session('info')), 'info');
                @endif
            } else {
                // adminToast not yet defined — retry after JS loads
                setTimeout(tryToast, 120);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', tryToast);
        } else {
            tryToast();
        }
    })();
</script>
@endif
