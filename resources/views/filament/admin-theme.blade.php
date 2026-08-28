<style>
    :root {
        color-scheme: light;
        --mihbw-admin-bg: #f8f7f2;
        --mihbw-admin-surface: #ffffff;
        --mihbw-admin-surface-soft: #fbfaf6;
        --mihbw-admin-border: #e7e3d8;
        --mihbw-admin-text: #1c1917;
        --mihbw-admin-muted: #78716c;
        --mihbw-admin-emerald: #065f46;
        --mihbw-admin-emerald-dark: #064e3b;
        --mihbw-admin-sidebar-start: #ffffff;
        --mihbw-admin-sidebar-mid: #fbfaf6;
        --mihbw-admin-sidebar-end: #f1f5f0;
        --mihbw-admin-sidebar-text: #292524;
        --mihbw-admin-sidebar-muted: #57534e;
        --mihbw-admin-sidebar-hover-bg: #ecfdf5;
        --mihbw-admin-sidebar-active-bg: #d1fae5;
        --mihbw-admin-sidebar-active-text: #065f46;
        --mihbw-admin-gold: #f5c542;
        --mihbw-admin-shadow: rgba(68, 64, 60, 0.06);
        --mihbw-admin-topbar: rgba(255, 255, 255, 0.9);
        --mihbw-admin-login-end: #f8f7f2;
        --mihbw-admin-input-bg: #ffffff;
    }

    :root.dark {
        color-scheme: dark;
        --mihbw-admin-bg: #0d1311;
        --mihbw-admin-surface: #151c18;
        --mihbw-admin-surface-soft: #101713;
        --mihbw-admin-border: rgba(217, 249, 157, 0.1);
        --mihbw-admin-text: #f5f5f4;
        --mihbw-admin-muted: #a8a29e;
        --mihbw-admin-emerald: #34d399;
        --mihbw-admin-emerald-dark: #022c22;
        --mihbw-admin-sidebar-start: #022c22;
        --mihbw-admin-sidebar-mid: #052e26;
        --mihbw-admin-sidebar-end: #111c18;
        --mihbw-admin-sidebar-text: #ffffff;
        --mihbw-admin-sidebar-muted: rgba(255, 255, 255, 0.72);
        --mihbw-admin-sidebar-hover-bg: rgba(255, 255, 255, 0.075);
        --mihbw-admin-sidebar-active-bg: rgba(248, 216, 106, 0.14);
        --mihbw-admin-sidebar-active-text: #ffffff;
        --mihbw-admin-gold: #f8d86a;
        --mihbw-admin-shadow: rgba(0, 0, 0, 0.28);
        --mihbw-admin-topbar: rgba(21, 28, 24, 0.86);
        --mihbw-admin-login-end: #0d1311;
        --mihbw-admin-input-bg: #101713;
    }

    .fi-body {
        background:
            linear-gradient(180deg, rgba(6, 95, 70, 0.045), transparent 320px),
            var(--mihbw-admin-bg);
        color: var(--mihbw-admin-text);
    }

    :root.dark .fi-body {
        background:
            linear-gradient(180deg, rgba(52, 211, 153, 0.06), transparent 340px),
            radial-gradient(circle at top right, rgba(245, 197, 66, 0.08), transparent 26rem),
            var(--mihbw-admin-bg);
    }

    .fi-sidebar {
        background:
            linear-gradient(
                180deg,
                var(--mihbw-admin-sidebar-start),
                var(--mihbw-admin-sidebar-mid) 72%,
                var(--mihbw-admin-sidebar-end)
        );
        border-inline-end: 1px solid var(--mihbw-admin-border);
        box-shadow: 12px 0 30px rgba(68, 64, 60, 0.08);
    }

    :root.dark .fi-sidebar {
        border-inline-end: 0;
        box-shadow: 14px 0 35px rgba(0, 0, 0, 0.34);
    }

    .fi-sidebar .fi-logo,
    .fi-sidebar .fi-sidebar-header,
    .fi-sidebar .fi-sidebar-nav-groups,
    .fi-sidebar .fi-sidebar-nav {
        color: var(--mihbw-admin-sidebar-text);
    }

    .fi-sidebar .fi-logo {
        letter-spacing: 0;
        font-weight: 800;
        object-fit: contain;
    }

    .fi-sidebar .fi-sidebar-nav-item a,
    .fi-sidebar .fi-sidebar-nav-group-label {
        color: var(--mihbw-admin-sidebar-muted);
    }

    .fi-sidebar .fi-sidebar-nav-item a .fi-icon,
    .fi-sidebar .fi-sidebar-nav-item-label,
    .fi-sidebar .fi-sidebar-nav-group-label {
        color: inherit;
    }

    .fi-sidebar .fi-sidebar-nav-item a:hover {
        background: var(--mihbw-admin-sidebar-hover-bg);
        color: var(--mihbw-admin-sidebar-active-text);
    }

    .fi-sidebar .fi-sidebar-nav-item.fi-active a,
    .fi-sidebar .fi-sidebar-nav-item [aria-current='page'] {
        background: var(--mihbw-admin-sidebar-active-bg);
        color: var(--mihbw-admin-sidebar-active-text);
        box-shadow: inset 3px 0 0 var(--mihbw-admin-gold);
    }

    .fi-sidebar .fi-sidebar-nav-item.fi-active a .fi-icon,
    .fi-sidebar .fi-sidebar-nav-item [aria-current='page'] .fi-icon,
    .fi-sidebar .fi-sidebar-nav-item.fi-active .fi-sidebar-nav-item-label,
    .fi-sidebar .fi-sidebar-nav-item [aria-current='page'] .fi-sidebar-nav-item-label {
        color: var(--mihbw-admin-sidebar-active-text);
    }

    .fi-topbar {
        background: var(--mihbw-admin-topbar);
        border-bottom: 1px solid var(--mihbw-admin-border);
        backdrop-filter: blur(14px);
    }

    .fi-main {
        background: transparent;
    }

    .fi-section,
    .fi-wi,
    .fi-ta-ctn,
    .fi-fo-tabs,
    .fi-form {
        border-color: var(--mihbw-admin-border);
        box-shadow: 0 12px 30px var(--mihbw-admin-shadow);
    }

    :root.dark .fi-section,
    :root.dark .fi-wi,
    :root.dark .fi-ta-ctn,
    :root.dark .fi-fo-tabs,
    :root.dark .fi-form {
        background-color: var(--mihbw-admin-surface);
    }

    .fi-btn {
        border-radius: 0.45rem;
    }

    .fi-btn-color-primary {
        box-shadow: 0 10px 20px rgba(6, 95, 70, 0.18);
    }

    :root.dark .fi-btn-color-primary {
        box-shadow: 0 10px 22px rgba(52, 211, 153, 0.14);
    }

    .fi-input,
    .fi-select-input,
    .fi-textarea {
        background-color: var(--mihbw-admin-input-bg);
        border-radius: 0.45rem;
    }

    :root.dark .fi-input,
    :root.dark .fi-select-input,
    :root.dark .fi-textarea {
        border-color: var(--mihbw-admin-border);
    }

    .fi-badge {
        border-radius: 0.4rem;
    }

    .fi-dropdown-panel,
    .fi-modal-window {
        border-color: var(--mihbw-admin-border);
    }

    :root.dark .fi-dropdown-panel,
    :root.dark .fi-modal-window {
        background-color: var(--mihbw-admin-surface);
    }

    .fi-simple-layout {
        background:
            radial-gradient(circle at top left, rgba(245, 197, 66, 0.22), transparent 28rem),
            linear-gradient(135deg, var(--mihbw-admin-sidebar-start), var(--mihbw-admin-sidebar-mid) 48%, var(--mihbw-admin-login-end) 48%);
    }

    :root.dark .fi-simple-layout {
        background:
            radial-gradient(circle at top left, rgba(248, 216, 106, 0.14), transparent 25rem),
            linear-gradient(135deg, #022c22, #061f1a 52%, var(--mihbw-admin-login-end) 52%);
    }

    .fi-simple-main {
        border: 1px solid rgba(231, 227, 216, 0.9);
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.2);
    }

    :root.dark .fi-simple-main {
        background-color: var(--mihbw-admin-surface);
        border-color: var(--mihbw-admin-border);
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.42);
    }

    .fi-simple-header .fi-logo {
        color: var(--mihbw-admin-emerald-dark);
        font-weight: 800;
    }

    :root.dark .fi-simple-header .fi-logo {
        color: #ecfdf5;
    }

    .fi-theme-switcher {
        background-color: color-mix(in srgb, var(--mihbw-admin-surface) 86%, transparent);
        border: 1px solid var(--mihbw-admin-border);
        border-radius: 0.55rem;
        padding: 0.125rem;
    }

    @media (max-width: 1023px) {
        .fi-sidebar {
            box-shadow: none;
        }

        .fi-simple-layout {
            background: linear-gradient(
                180deg,
                var(--mihbw-admin-sidebar-start) 0,
                var(--mihbw-admin-sidebar-start) 11rem,
                var(--mihbw-admin-login-end) 11rem
            );
        }
    }
</style>
