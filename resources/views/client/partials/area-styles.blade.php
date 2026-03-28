<style>
    /* Личный кабинет — те же приёмы, что на витрине (Услуги, запись) */
    .client-page-header.page-header {
        padding: 150px 0 60px;
        background-color: var(--chocolate);
        color: var(--white);
        text-align: center;
    }

    .client-page-header.page-header h1 {
        font-size: 48px;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .client-page-header .client-page-subtitle {
        font-size: 18px;
        opacity: 0.9;
        font-weight: 300;
        max-width: 640px;
        margin: 0 auto;
    }

    .client-area-main {
        margin: -40px auto 60px;
        position: relative;
        z-index: 2;
    }

    .client-panel {
        background: var(--white);
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        padding: 40px;
    }

    .client-panel-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: var(--chocolate);
        margin-bottom: 28px;
        padding-bottom: 12px;
        border-bottom: 3px solid var(--gold);
    }

    .client-booking-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        background: var(--cream);
        border-left: 4px solid var(--gold);
        border-radius: 2px;
        padding: 24px;
        margin-bottom: 20px;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .client-booking-row:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }

    .client-booking-row h4 {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        color: var(--chocolate);
        margin-bottom: 12px;
    }

    .client-booking-row p {
        font-size: 14px;
        color: var(--text-light);
        margin: 6px 0;
    }

    .client-booking-row strong {
        color: var(--text-dark);
    }

    .client-booking-side {
        text-align: right;
        min-width: 140px;
        flex-shrink: 0;
    }

    .client-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .client-btn {
        display: block;
        width: 100%;
        margin-top: 12px;
        padding: 10px 18px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 2px;
        border: none;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        box-sizing: border-box;
    }

    .client-btn--danger {
        background: #c62828;
        color: var(--white);
    }

    .client-btn--danger:hover {
        background: #b71c1c;
        transform: translateY(-2px);
    }

    .client-btn--gold {
        background: var(--gold);
        color: var(--chocolate);
    }

    .client-btn--gold:hover {
        background: var(--gold-light);
        transform: translateY(-2px);
    }

    .client-pagination {
        margin-top: 32px;
        text-align: center;
    }

    .client-empty {
        text-align: center;
        padding: 48px 24px 24px;
        color: var(--text-light);
    }

    .client-empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        line-height: 1;
    }

    .client-empty h3 {
        font-family: 'Playfair Display', serif;
        color: var(--chocolate);
        margin-bottom: 12px;
        font-size: 24px;
    }

    .client-empty .client-btn--gold {
        margin-top: 20px;
        width: auto;
        display: inline-block;
        padding: 14px 32px;
    }

    @media (max-width: 768px) {
        .client-page-header.page-header {
            padding: 120px 16px 48px;
        }

        .client-page-header.page-header h1 {
            font-size: 28px;
        }

        .client-page-header .client-page-subtitle {
            font-size: 16px;
        }

        .client-area-main {
            margin: -28px 0 48px;
            padding: 0 12px;
        }

        .client-panel {
            padding: 22px 16px;
        }

        .client-panel-title {
            font-size: 22px;
        }

        .client-booking-row {
            flex-direction: column;
            padding: 20px 16px;
        }

        .client-booking-side {
            text-align: left;
            width: 100%;
            min-width: 0;
        }
    }

    @media (max-width: 480px) {
        .client-page-header.page-header h1 {
            font-size: 24px;
        }
    }
</style>
