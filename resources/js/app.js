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

const CASE_POLL_INTERVAL_MS = 3000;

/**
 * Poll API khi đang xem ngày hôm nay: thêm row mới vào bảng không cần reload.
 */
function initCaseLivePoll() {
    const root = document.getElementById('case-live-root');

    if (!root || root.dataset.casePoll !== '1') {
        return;
    }

    const pollUrl = root.dataset.casePollUrl;
    const date = root.dataset.caseDate;

    if (!pollUrl || !date) {
        return;
    }

    const tbody = document.getElementById('case-records-tbody');
    const emptyState = document.getElementById('case-empty-state');
    const tablePanel = document.getElementById('case-table-panel');
    const countEl = document.getElementById('case-record-count');
    const spinner = document.getElementById('case-live-spinner');
    const spinnerText = document.getElementById('case-live-spinner-text');
    const loadingRowText = document.getElementById('case-loading-row-text');

    if (!tbody || !emptyState || !tablePanel || !countEl) {
        return;
    }

    let afterId = Number.parseInt(root.dataset.caseAfterId ?? '0', 10);
    if (Number.isNaN(afterId)) {
        afterId = 0;
    }

    let timerId = null;

    function syncAfterIdFromDom() {
        const rows = tbody.querySelectorAll('tr[data-record-id]');
        let max = afterId;
        rows.forEach((row) => {
            const id = Number.parseInt(row.getAttribute('data-record-id') ?? '0', 10);
            if (!Number.isNaN(id) && id > max) {
                max = id;
            }
        });
        afterId = max;
        root.dataset.caseAfterId = String(afterId);
    }

    syncAfterIdFromDom();

    /** Cuộn main xuống tận cùng (hàng loading row). */
    function scrollToBottom() {
        const main = document.querySelector('main');
        if (main) {
            main.scrollTop = main.scrollHeight;
        }
    }

    function buildRow(record) {
        const isDead = Number(record.dead_flg) === 1;
        const tr = document.createElement('tr');
        tr.dataset.recordId = String(record.id);
        tr.className = isDead
            ? 'bg-rose-50/70 hover:bg-rose-100/60 transition'
            : 'bg-emerald-50/80 hover:bg-emerald-100/70 transition';

        const tdCount = document.createElement('td');
        tdCount.className = `px-4 py-2.5 text-right font-semibold tabular-nums ${isDead ? 'text-rose-900' : 'text-zinc-900'}`;
        tdCount.textContent = record.count === null || record.count === undefined ? '—' : String(record.count);

        const tdBusted = document.createElement('td');
        tdBusted.className = `px-4 py-2.5 text-right tabular-nums ${isDead ? 'text-rose-700' : 'text-zinc-700'}`;
        tdBusted.textContent = String(record.busted);

        const tdDead = document.createElement('td');
        tdDead.className = 'px-4 py-2.5 text-center';

        if (record.dead_flg === null || record.dead_flg === undefined) {
            const span = document.createElement('span');
            span.className = 'text-zinc-300';
            span.textContent = '—';
            tdDead.appendChild(span);
        } else if (isDead) {
            const span = document.createElement('span');
            span.className =
                'inline-flex items-center gap-0.5 rounded-full bg-rose-200/80 px-2 py-0.5 text-xs font-bold text-rose-800 ring-1 ring-rose-300/60';
            span.title = 'dead_flg = 1';
            span.textContent = '★';
            tdDead.appendChild(span);
        } else {
            const span = document.createElement('span');
            span.className = 'inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500';
            span.textContent = '0';
            tdDead.appendChild(span);
        }

        tr.appendChild(tdCount);
        tr.appendChild(tdBusted);
        tr.appendChild(tdDead);

        // Highlight bản ghi mới bằng nền xanh lá một lúc, rồi fade về bình thường.
        if (!isDead) {
            window.setTimeout(() => {
                if (!tr.isConnected) {
                    return;
                }
                tr.className = 'hover:bg-amber-50/40 transition';
            }, 6000);
        }

        return tr;
    }

    function updateCount(delta) {
        const match = countEl.textContent.match(/^(\d+)/);
        const current = match ? Number.parseInt(match[1], 10) : 0;
        const next = Math.max(0, current + delta);
        countEl.textContent = `${next} bản ghi`;
    }

    async function tick() {
        if (document.visibilityState !== 'visible') {
            return;
        }

        try {
            if (spinner) {
                spinner.classList.remove('opacity-40');
                spinner.classList.add('opacity-100');
            }
            if (spinnerText) {
                spinnerText.textContent = 'Đang tải';
            }
            if (loadingRowText) {
                loadingRowText.textContent = 'Đang tải dữ liệu mới...';
            }

            const { data } = await window.axios.get(pollUrl, {
                params: { date, after_id: afterId },
                headers: { Accept: 'application/json' },
            });

            const records = Array.isArray(data?.records) ? data.records : [];

            if (records.length === 0) {
                return;
            }

            if (tablePanel.classList.contains('hidden')) {
                emptyState.classList.add('hidden');
                tablePanel.classList.remove('hidden');
            }

            records.forEach((rec) => {
                tbody.appendChild(buildRow(rec));
            });

            updateCount(records.length);
            syncAfterIdFromDom();
            scrollToBottom();
            if (spinnerText) {
                spinnerText.textContent = 'Đã sync';
            }
            if (loadingRowText) {
                loadingRowText.textContent = records.length > 0 ? `Đã thêm ${records.length} bản ghi mới` : 'Đã sync';
            }
        } catch {
            /* bỏ qua lỗi mạng, lần poll sau thử lại */
            if (spinnerText) {
                spinnerText.textContent = 'Lỗi mạng';
            }
            if (loadingRowText) {
                loadingRowText.textContent = 'Lỗi mạng, sẽ thử lại...';
            }
        } finally {
            if (spinner) {
                spinner.classList.add('opacity-40');
                spinner.classList.remove('opacity-100');
            }
        }
    }

    function start() {
        if (timerId !== null) {
            return;
        }
        timerId = window.setInterval(tick, CASE_POLL_INTERVAL_MS);
    }

    function stop() {
        if (timerId === null) {
            return;
        }
        window.clearInterval(timerId);
        timerId = null;
    }

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            start();
            void tick();
        } else {
            stop();
        }
    });

    // Scroll xuống cuối ngay khi vào trang hôm nay.
    scrollToBottom();

    start();
    void tick();
}

/**
 * User menu: dropdown toggle + modal dialogs cho Sửa hồ sơ / Đổi mật khẩu.
 */
function initUserMenu() {
    const trigger = document.getElementById('user-menu-trigger');
    const dropdown = document.getElementById('user-menu-dropdown');
    const chevron = document.getElementById('user-menu-chevron');

    function setDropdownOpen(open) {
        if (!trigger || !dropdown) {
            return;
        }

        dropdown.classList.toggle('hidden', !open);
        trigger.setAttribute('aria-expanded', open ? 'true' : 'false');

        if (chevron) {
            chevron.style.transform = open ? 'rotate(180deg)' : '';
        }
    }

    if (trigger && dropdown) {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = trigger.getAttribute('aria-expanded') === 'true';
            setDropdownOpen(!isOpen);
        });

        document.addEventListener('click', () => {
            setDropdownOpen(false);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                setDropdownOpen(false);
            }
        });
    }

    // Mở modal khi click vào item trong dropdown
    document.querySelectorAll('[data-open-modal]').forEach((btn) => {
        btn.addEventListener('click', () => {
            setDropdownOpen(false);
            const modalId = btn.getAttribute('data-open-modal');
            const modal = document.getElementById(modalId);
            modal?.showModal();
        });
    });

    // Đóng modal khi click nút close
    document.querySelectorAll('[data-close-modal]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-close-modal');
            document.getElementById(modalId)?.close();
        });
    });

    // Đóng modal khi click vào backdrop
    document.querySelectorAll('dialog').forEach((dialog) => {
        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) {
                dialog.close();
            }
        });
    });
}

/**
 * Scroll buttons: back-to-top và scroll-to-bottom.
 * Hiện / ẩn tùy vị trí cuộn trong <main>.
 */
function initScrollButtons() {
    const btnTop = document.getElementById('back-to-top');
    const btnBottom = document.getElementById('scroll-to-bottom');
    const main = document.querySelector('main');

    if (!main) {
        return;
    }

    function setVisible(el, visible) {
        if (!el) {
            return;
        }

        el.classList.toggle('opacity-0', !visible);
        el.classList.toggle('pointer-events-none', !visible);
    }

    function onScroll() {
        const scrollTop = main.scrollTop;
        const scrollable = main.scrollHeight - main.clientHeight;
        const nearBottom = scrollable > 0 && scrollable - scrollTop < 50;

        setVisible(btnTop, scrollTop > 300);
        setVisible(btnBottom, scrollable > 300 && !nearBottom);
    }

    main.addEventListener('scroll', onScroll, { passive: true });

    btnTop?.addEventListener('click', () => {
        main.scrollTo({ top: 0, behavior: 'smooth' });
    });

    btnBottom?.addEventListener('click', () => {
        main.scrollTo({ top: main.scrollHeight, behavior: 'smooth' });
    });

    // Kiểm tra lần đầu (trang có thể đã scroll sẵn)
    onScroll();
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminDrawer();
    initAdminSidebarCollapse();
    initCaseLivePoll();
    initUserMenu();
    initScrollButtons();
});
