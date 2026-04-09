import './bootstrap';

/**
 * Menu admin: mobile drawer + overlay (lg+ luôn hiện sidebar).
 */
function initAdminDrawer() {
    const openBtn = document.getElementById('admin-drawer-open');
    const overlay = document.getElementById('admin-overlay');
    const sidebar = document.getElementById('admin-sidebar');
    const closeBtn = document.getElementById('admin-drawer-close');

    if (!openBtn || !overlay || !sidebar) {
        return;
    }

    const mqLarge = window.matchMedia('(min-width: 64rem)');

    function isLargeScreen() {
        return mqLarge.matches;
    }

    function setDrawerOpen(open) {
        if (isLargeScreen()) {
            document.body.classList.remove('admin-drawer-is-open');
            document.body.style.overflow = '';
            openBtn.setAttribute('aria-expanded', 'false');

            return;
        }

        document.body.classList.toggle('admin-drawer-is-open', open);
        document.body.style.overflow = open ? 'hidden' : '';
        openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    openBtn.addEventListener('click', () => {
        setDrawerOpen(true);
    });

    overlay.addEventListener('click', () => {
        setDrawerOpen(false);
    });

    closeBtn?.addEventListener('click', () => {
        setDrawerOpen(false);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            setDrawerOpen(false);
        }
    });

    mqLarge.addEventListener('change', () => {
        if (isLargeScreen()) {
            setDrawerOpen(false);
        }
    });

    sidebar.querySelectorAll('a[href]').forEach((anchor) => {
        anchor.addEventListener('click', () => {
            setDrawerOpen(false);
        });
    });
}

const ADMIN_SIDEBAR_COLLAPSED_KEY = 'admin-sidebar-collapsed';

/**
 * Thu gọn / mở rộng sidebar trên lg+ (lưu localStorage).
 */
function initAdminSidebarCollapse() {
    const toggle = document.getElementById('admin-sidebar-toggle');

    if (!toggle) {
        return;
    }

    const mqLarge = window.matchMedia('(min-width: 64rem)');

    function setCollapsed(collapsed) {
        if (!mqLarge.matches) {
            document.documentElement.classList.remove('admin-sidebar-collapsed');

            return;
        }

        document.documentElement.classList.toggle('admin-sidebar-collapsed', collapsed);
        try {
            localStorage.setItem(ADMIN_SIDEBAR_COLLAPSED_KEY, collapsed ? '1' : '0');
        } catch {
            /* ignore quota / private mode */
        }
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        toggle.setAttribute('aria-label', collapsed ? 'Mở rộng sidebar' : 'Thu gọn sidebar');
    }

    function isCollapsedPreferred() {
        try {
            return localStorage.getItem(ADMIN_SIDEBAR_COLLAPSED_KEY) === '1';
        } catch {
            return false;
        }
    }

    function sync() {
        if (mqLarge.matches) {
            setCollapsed(isCollapsedPreferred());
        } else {
            document.documentElement.classList.remove('admin-sidebar-collapsed');
        }
    }

    toggle.addEventListener('click', () => {
        if (!mqLarge.matches) {
            return;
        }

        const next = !document.documentElement.classList.contains('admin-sidebar-collapsed');
        setCollapsed(next);
    });

    mqLarge.addEventListener('change', sync);

    sync();
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminDrawer();
    initAdminSidebarCollapse();
});
