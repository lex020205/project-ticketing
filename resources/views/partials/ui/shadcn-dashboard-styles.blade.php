<style>
    .dashboard-shell {
        --bg: #f8fafc;
        --surface: #ffffff;
        --surface-soft: #f8fafc;
        --border: #e2e8f0;
        --border-strong: #cbd5e1;
        --text: #0f172a;
        --muted: #64748b;
        --primary: #0f172a;
        --accent: #2563eb;
        color: var(--text);
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .dashboard-shell .page-hero,
    .dashboard-shell .card.card-modern,
    .dashboard-shell .stat-card,
    .dashboard-shell .table-shell,
    .dashboard-shell .surface-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .dashboard-shell .page-hero {
        padding: 1.25rem 1.35rem;
        margin-bottom: 1rem;
        background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
    }

    .dashboard-shell .page-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.32rem 0.7rem;
        border-radius: 999px;
        background: #f1f5f9;
        color: var(--text);
        font-size: 0.88rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .dashboard-shell .page-title {
        margin: 0.85rem 0 0.35rem;
        font-size: clamp(1.45rem, 2vw, 2.1rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1.15;
    }

    .dashboard-shell .page-subtitle {
        margin: 0;
        color: var(--muted);
        font-size: 1rem;
        line-height: 1.6;
    }

    .dashboard-shell .stat-card {
        padding: 1rem 1.05rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-shell .stat-card:hover {
        border-color: var(--border-strong);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }

    .dashboard-shell .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.1rem;
        flex: 0 0 auto;
    }

    .dashboard-shell .stat-icon-blue { background: #2563eb; }
    .dashboard-shell .stat-icon-green { background: #16a34a; }
    .dashboard-shell .stat-icon-orange { background: #d97706; }
    .dashboard-shell .stat-icon-purple { background: #7c3aed; }
    .dashboard-shell .stat-icon-red { background: #dc2626; }

    .dashboard-shell .stat-value {
        margin: 0;
        font-size: 1.55rem;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .dashboard-shell .stat-label {
        margin: 0.15rem 0 0;
        color: var(--muted);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .dashboard-shell .card.card-modern .card-header,
    .dashboard-shell .table-shell .card-header {
        padding: 1rem 1.1rem;
        background: #ffffff;
        border-bottom: 1px solid var(--border);
    }

    .dashboard-shell .card.card-modern .card-body,
    .dashboard-shell .table-shell .card-body {
        padding: 0;
    }

    .dashboard-shell .table {
        margin-bottom: 0;
    }

    .dashboard-shell .table thead th {
        padding: 0.85rem 1rem;
        background: var(--surface-soft);
        color: #334155;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .dashboard-shell .table tbody td {
        padding: 0.95rem 1rem;
        vertical-align: middle;
        border-color: #edf2f7;
        color: var(--text);
        font-size: 0.96rem;
    }

    .dashboard-shell .table tbody tr:hover {
        background: #fafafa;
    }

    .dashboard-shell .badge {
        border-radius: 999px;
        padding: 0.35rem 0.65rem;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .dashboard-shell .btn {
        border-radius: 999px;
        padding: 0.38rem 0.75rem;
        font-weight: 600;
        border: 1px solid var(--border);
    }

    .dashboard-shell .btn:hover {
        background: var(--surface-soft);
        border-color: var(--border-strong);
    }

    .dashboard-shell .btn-outline-primary {
        background: #ffffff;
        color: #0f172a;
        border-color: var(--border);
    }

    .dashboard-shell .btn-primary,
    .dashboard-shell .btn-warning,
    .dashboard-shell .btn-danger {
        background: #ffffff;
        color: #0f172a;
    }

    .dashboard-shell .btn-danger:hover {
        background: #fef2f2;
        color: #991b1b;
    }

    .dashboard-shell .panel-note {
        color: var(--muted);
        font-size: 0.88rem;
    }

    @media (max-width: 768px) {
        .dashboard-shell .page-hero,
        .dashboard-shell .card.card-modern .card-header,
        .dashboard-shell .table-shell .card-header {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .dashboard-shell .page-kicker {
            font-size: 0.95rem;
            padding: 0.4rem 0.8rem;
            line-height: 1.2;
        }

        .dashboard-shell .page-title {
            font-size: 1.55rem;
            line-height: 1.2;
        }

        .dashboard-shell .page-subtitle {
            font-size: 1.02rem;
            line-height: 1.65;
        }

        .dashboard-shell .stat-card {
            padding: 1rem;
        }

        .dashboard-shell .stat-value {
            font-size: 1.7rem;
        }

        .dashboard-shell .stat-label {
            font-size: 0.95rem;
        }

        .dashboard-shell .btn {
            min-height: 44px;
            padding: 0.55rem 0.95rem;
            font-size: 0.95rem;
        }

        .dashboard-shell .table thead th {
            font-size: 0.8rem;
            padding: 0.9rem 0.85rem;
        }

        .dashboard-shell .table tbody td {
            font-size: 1rem;
            padding: 1rem 0.85rem;
        }
    }
</style>