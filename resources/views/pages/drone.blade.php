@extends('layouts.app')

@section('title', 'Drone')

@section('content')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fa-solid fa-paper-plane"></i> Drone Pengendalian Gulma</h1>
        <p>
            Dokumen perencanaan penggunaan drone untuk pengendalian gulma di berbagai wilayah. Download dan pelajari strategi perencanaan terbaru.
        </p>
    </div>

<style>
    :root {
        --primary-color: #128241;
        --secondary-color: #D6DF20;
        --accent-color: #FBA919;
        --light-bg: #f8f9fa;
        --text-color: #333;
        --border-color: #e0e0e0;
        --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    body {
        font-family: 'Poppins';
        padding-top: 70px;
    }

    /* Page Header */
    .page-header {
        margin-top: 0;
        padding-top: 40px;
    }

    /* Main Content */
    .main-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Container Wrapper */
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Cards Grid */
    .drone-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-top: 30px;
    }

    .drone-card {
        background: white;
        border-radius: 15px;
        overflow: visible;
        box-shadow: var(--shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        /* border: 2px solid transparent; */
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .drone-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-color);
    }

    .card-year {
        position: absolute;
        top: -12px;
        left: 20px;
        background: var(--accent-color);
        color: white;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12px;
    }

    .card-header {
        background: white;
        padding: 30px 20px 15px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }

    .card-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        margin-bottom: 15px;
        border-radius: 15px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-color);
    }

    .card-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 10px;
    }

    .badge {
        display: inline-block;
        background-color: var(--secondary-color);
        color: var(--text-color);
        padding: 5px 12px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-location {
        background-color: var(--primary-color);
        color: white;
    }

    .badge-gulma {
        background-color: var(--primary-color);
        color: white;
    }

    .card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .drone-info {
        margin-bottom: 12px;
    }

    .info-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 13px;
        color: var(--text-color);
        margin-top: 4px;
        font-weight: 500;
    }

    .card-footer {
        padding: 15px;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 8px;
    }

    .btn {
        /* flex: 1; */
        border: none;
        border-radius: 120px;
        font-weight: 600;
        font-family: 'Poppins';
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 12px;
    }

    .btn-download {
        background-color: var(--primary-color);
        color: #333;
    }

    .btn-download:hover {
        background-color: #c5ce1a;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-view {
        background-color: #c5ce1a   !important;
        color: white !important;
        padding: 10px 16px;
    }

    .btn-view:hover {
        background-color: #FBA919 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state-icon {
        font-size: 60px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .empty-state-text {
        font-size: 14px;
        color: #666;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Filter/Controls Section */
    .drone-controls {
        background: linear-gradient(135deg, #ffffff 0%, #f7faf8 100%);
        padding: 32px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(18, 130, 65, 0.1);
        border-left: 4px solid #128241;
        font-family: 'Poppins';
        position: relative;
        overflow: visible;
        z-index: 1;
    }

    .drone-controls::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background-size: 200% 100%;
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0%, 100% { background-position: 0% 0%; }
        50% { background-position: 100% 0%; }
    }

    .controls-wrapper {
        max-width: 100%;
        font-family: 'Poppins';
        overflow: visible;
        position: static;
        z-index: 1;
    }

    .controls-row {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
        overflow: visible;
        position: static;
    }

    .control-item {
        display: flex;
        flex-direction: column;
        font-family: 'Poppins';
        position: relative;
        flex: 1;
        min-width: 200px;
        z-index: 1000;
        overflow: visible;
        min-height: 0;
    }

    .control-item.compact {
        flex: 1;
        min-width: 200px;
    }

    .controls-buttons-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        z-index: 1;
        flex-wrap: wrap;
    }

    .control-label {
        font-size: 11px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-family: 'Poppins';
        transition: color 0.3s ease;
        white-space: nowrap;
    }

    .control-item:hover .control-label {
        color: #128241;
    }

    .control-label i {
        font-size: 13px;
        color: #FBA919;
        width: 16px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .control-item:hover .control-label i {
        transform: scale(1.15);
    }

    /* Button Grid Trigger */
    .button-grid-trigger {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 14px;
        border: 2px solid #e3eae8;
        border-radius: 10px;
        background-color: white;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: 'Poppins';
        font-size: 13px;
        font-weight: 500;
        color: #2c3e50;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        user-select: none;
        white-space: nowrap;
    }

    .button-grid-trigger:hover {
        border-color: #128241;
        box-shadow: 0 6px 16px rgba(18, 130, 65, 0.12), 0 2px 6px rgba(0, 0, 0, 0.05);
        background-color: #fafdfb;
    }

    .button-grid-trigger.active {
        border-color: #128241;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        background-color: #fafdfb;
        z-index: 10000;
        position: relative;
    }

    .button-grid-trigger.active ~ .button-grid {
        z-index: 10001 !important;
    }

    .grid-selected-text {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .grid-arrow {
        font-size: 11px;
        color: #128241;
        transition: transform 0.3s ease;
        margin-left: 8px;
        flex-shrink: 0;
    }

    .button-grid-trigger.active .grid-arrow {
        transform: rotate(180deg);
    }

    /* Lokasi Search Input */
    .lokasi-search-input {
        width: 100%;
        padding: 11px 14px;
        border: 2px solid #e3eae8;
        border-radius: 10px;
        background-color: white;
        font-family: 'Poppins';
        font-size: 13px;
        font-weight: 500;
        color: #2c3e50;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .lokasi-search-input:focus {
        outline: none;
        border-color: #128241;
        box-shadow: 0 6px 16px rgba(18, 130, 65, 0.12), 0 2px 6px rgba(0, 0, 0, 0.05);
        background-color: #fafdfb;
    }

    .lokasi-search-input::placeholder {
        color: #999;
    }

    .control-item:has(.lokasi-search-input) {
        z-index: 1000;
    }

    .control-item:has(.button-grid-trigger.active) {
        z-index: 10000 !important;
    }

    /* Button Grid */
    .button-grid {
        position: fixed;
        top: auto;
        bottom: auto;
        left: auto;
        right: auto;
        background: white;
        border: 2px solid #128241;
        border-radius: 10px;
        padding: 12px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        z-index: 10001 !important;
        box-shadow: 0 10px 30px rgba(18, 130, 65, 0.2);
        animation: slideDown 0.3s ease;
        max-height: 400px;
        overflow-y: auto;
        min-width: max-content;
        width: auto;
        pointer-events: auto;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Khusus untuk Tahun - hanya 3 kolom */
    #tahunGrid.button-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    /* Khusus untuk Bulan - 6 kolom */
    #bulanGrid.button-grid {
        grid-template-columns: repeat(6, 1fr);
    }

    .grid-btn {
        padding: 13px 12px;
        border: 2px solid #e3eae8;
        border-radius: 8px;
        background: white;
        color: #2c3e50;
        font-family: 'Poppins';
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        white-space: normal;
        min-width: 80px;
        line-height: 1.3;
        position: relative;
    }

    .grid-btn:hover {
        border-color: #128241;
        background: #f0f8f5;
        color: #128241;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.15);
    }

    .grid-btn.selected {
        background: linear-gradient(135deg, #128241 0%, #0d5c2e 100%);
        color: white;
        border-color: #128241;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.3);
    }

    .grid-btn.selected:hover {
        background: linear-gradient(135deg, #0d5c2e 0%, #0a4a26 100%);
        transform: translateY(-2px);
    }

    /* BASE BUTTON */
    .btn-primary,
    .btn-secondary {
        flex: 1;
        padding: 13px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        letter-spacing: 0.6px;
        font-family: 'Poppins';
        position: relative;
        overflow: hidden;
        white-space: nowrap;
        transition: background 0.2s ease, color 0.2s ease, border 0.2s ease;
    }

    .btn-primary {
        background: white;
        border: 2px solid #d8e1dd;
        color: #128241;
        box-shadow: none;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background: #128241;
        border-color: #128241;
        color: white;
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.2);
        outline: none;
    }

    .btn-primary::before {
        display: none;
    }

    .btn-secondary {
        background: white;
        border: 2px solid #d8e1dd;
        color: #FBA919;
        box-shadow: none;
    }

    .btn-secondary:hover,
    .btn-secondary:focus,
    .btn-secondary:active {
        background: #FBA919;
        border-color: #FBA919;
        color: white;
        box-shadow: 0 4px 12px rgba(251, 169, 25, 0.2);
        outline: none;
    }

    .btn-secondary::before {
        display: none;
    }

    /* Reset Icon */
    .reset-icon {
        font-size: 20px;
        color: var(--accent-color);
        cursor: pointer;
        transition: all 0.3s ease;
        align-self: flex-end;
        margin-bottom: 4px;
        padding: 8px;
    }

    .reset-icon:hover {
        color: var(--primary-color);
        text-shadow: 0 0 10px rgba(60, 231, 66, 0.6), 0 0 20px rgba(60, 231, 66, 0.4);
    }

    /* Old Filter Section */
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-section select {
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-family: 'Poppins';
        cursor: pointer;
        background-color: white;
        font-size: 13px;
    }

    .filter-section select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 100000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        width: 95%;
        max-width: 1000px;
        height: 85vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-lg);
        z-index: 100001;
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        padding: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 0;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .modal-close-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        flex: 1;
        overflow: auto;
        padding: 15px;
    }

    .pdf-viewer {
        width: 100%;
        height: 100%;
        border: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .main-content {
            padding: 20px;
        }

        .drone-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .controls-row {
            gap: 10px;
            margin-bottom: 12px;
        }

        .control-item {
            min-width: 150px;
            flex: 0 1 calc(50% - 6px);
        }

        .control-item.compact {
            min-width: 150px;
            flex: 0 1 calc(50% - 6px);
        }

        .controls-buttons-row {
            gap: 10px;
        }

        .button-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        #tahunGrid.button-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        #bulanGrid.button-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .card-header {
            padding: 25px 15px 12px;
        }

        .card-image {
            height: 160px;
        }

        .card-title {
            font-size: 15px;
        }

        .card-content {
            padding: 15px;
        }

        .card-footer {
            padding: 12px;
            gap: 6px;
        }

        .btn {
            padding: 9px 14px;
            font-size: 11px;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 15px;
        }

        .drone-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .page-header {
            padding: 20px 15px;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 22px;
        }

        .page-header p {
            font-size: 12px;
        }

        .drone-controls {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            gap: 8px;
        }

        .controls-wrapper {
            overflow: visible;
        }

        .controls-row {
            gap: 8px;
            margin-bottom: 10px;
            flex-direction: column;
            align-items: stretch;
        }

        .control-item {
            min-width: 100%;
            flex: 1 1 100%;
            width: 100%;
        }

        .control-item.compact {
            min-width: 100%;
            flex: 1 1 100%;
            width: 100%;
        }

        .control-label {
            font-size: 10px;
            margin-bottom: 6px;
        }

        .button-grid-trigger {
            padding: 10px 12px;
            font-size: 12px;
            width: 100%;
        }

        .grid-selected-text {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .controls-buttons-row {
            gap: 8px;
            flex-direction: column;
            align-items: stretch;
            margin-top: 12px;
        }

        .button-grid {
            grid-template-columns: repeat(3, 1fr) !important;
            padding: 10px;
            gap: 8px;
            max-height: 300px;
        }

        #tahunGrid.button-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }

        #bulanGrid.button-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }

        .grid-btn {
            padding: 10px 8px;
            font-size: 12px;
            min-width: 60px;
        }

        .filter-section {
            padding: 15px;
            margin-bottom: 20px;
        }

        .filter-section select {
            padding: 8px 10px;
            font-size: 12px;
            width: 100%;
        }

        .drone-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .drone-controls {
            padding: 15px;
            margin-bottom: 15px;
        }

        .controls-row {
            gap: 6px;
            margin-bottom: 8px;
        }

        .control-item {
            min-width: 100%;
            flex: 1 1 100%;
        }

        .control-label {
            font-size: 9px;
            margin-bottom: 4px;
            gap: 4px;
        }

        .control-label i {
            font-size: 12px;
            width: 14px;
        }

        .button-grid-trigger {
            padding: 8px 10px;
            font-size: 11px;
            width: 100%;
            min-height: 36px;
        }

        .grid-arrow {
            font-size: 10px;
            margin-left: 6px;
        }

        .controls-buttons-row {
            gap: 6px;
            margin-top: 10px;
        }

        .button-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            padding: 8px;
            gap: 6px;
            max-height: 250px;
            font-size: 10px;
        }

        #tahunGrid.button-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }

        #bulanGrid.button-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }

        .grid-btn {
            padding: 8px 6px;
            font-size: 10px;
            min-width: 50px;
        }

        .card-header {
            padding: 20px 15px 10px;
        }

        .card-image {
            height: 150px;
        }

        .card-year {
            left: 15px;
            padding: 6px 12px;
            font-size: 11px;
        }

        .card-title {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .badge {
            padding: 4px 10px;
            font-size: 10px;
        }

        .card-content {
            padding: 12px;
        }

        .drone-info {
            margin-bottom: 8px;
        }

        .info-label {
            font-size: 10px;
        }

        .info-value {
            font-size: 12px;
        }

        .card-footer {
            padding: 10px;
        }

        .btn {
            padding: 8px 12px;
            font-size: 11px;
        }

        .empty-state {
            padding: 50px 15px;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .empty-state-title {
            font-size: 18px;
        }

        .empty-state-text {
            font-size: 13px;
        }

        .modal-content {
            width: 95%;
            height: 80vh;
        }

        .modal-header h2 {
            font-size: 16px;
        }

        .modal-body {
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 15px 10px;
        }

        .page-header {
            padding: 15px 10px;
            margin-bottom: 15px;
        }

        .page-header h1 {
            font-size: 18px;
        }

        .page-header p {
            font-size: 11px;
        }

        .filter-section {
            padding: 12px;
            margin-bottom: 15px;
            flex-direction: column;
        }

        .filter-section select {
            width: 100%;
            padding: 8px;
            font-size: 11px;
        }

        .drone-grid {
            gap: 15px;
        }

        .card-header {
            padding: 18px 12px 8px;
        }

        .card-image {
            height: 120px;
            margin-bottom: 10px;
        }

        .card-year {
            left: 12px;
            top: -10px;
            padding: 5px 10px;
            font-size: 10px;
        }

        .card-title {
            font-size: 13px;
        }

        .badge {
            padding: 3px 8px;
            font-size: 9px;
        }

        .card-content {
            padding: 10px;
        }

        .drone-info {
            margin-bottom: 6px;
        }

        .info-label {
            font-size: 9px;
        }

        .info-value {
            font-size: 11px;
        }

        .card-footer {
            padding: 8px;
            gap: 5px;
        }

        .btn {
            padding: 7px 10px;
            font-size: 10px;
        }

        .empty-state {
            padding: 40px 12px;
        }

        .empty-state-icon {
            font-size: 40px;
        }

        .empty-state-title {
            font-size: 16px;
        }

        .empty-state-text {
            font-size: 12px;
        }

        .modal-content {
            width: 100%;
            height: 90vh;
            border-radius: 4px;
        }

        .modal-header {
            padding: 12px;
        }

        .modal-header h2 {
            font-size: 14px;
        }

        .modal-close-btn {
            width: 30px;
            height: 30px;
        }

        .modal-body {
            padding: 8px;
        }
    }

    .info-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 14px;
        color: var(--text-color);
        margin-top: 5px;
        font-weight: 500;
    }

    .drone-location {
        display: none;
    }

    .drone-location-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
    }

    .drone-location-value {
        font-size: 14px;
        color: var(--primary-color);
        font-weight: 600;
        margin-top: 3px;
    }

    .drone-date-badge {
        display: inline-block;
        background-color: rgba(212, 223, 32, 0.2);
        color: #5f7a1a;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .drone-percentage {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .percentage-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--primary-color);
        min-width: 50px;
    }

    .percentage-bar {
        flex: 1;
        height: 8px;
        background-color: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }

    .percentage-fill {
        height: 100%;
        width: var(--percentage, 0%);
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .card-footer {
        padding: 20px;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 10px;
    }

    /* Removed duplicate .btn styles - using the ones defined earlier */

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .empty-state-text {
        font-size: 15px;
        color: #666;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Footer */
    /* Using partials/footer.blade.php */

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-section select {
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-family: 'Poppins';
        cursor: pointer;
        background-color: white;
    }

    .filter-section select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 100000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 95%;
        max-width: 1200px;
        height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-lg);
        z-index: 100001;
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: background-color 0.3s;
    }

    .modal-close-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        flex: 1;
        overflow: auto;
        padding: 20px;
    }

    .pdf-viewer {
        width: 100%;
        height: 100%;
        border: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 20px 15px;
        }

        .page-title {
            font-size: 28px;
        }

        .drone-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .modal-content {
            width: 95%;
            height: 90vh;
        }
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <!-- Filter Section -->
    @if ($drones->count() > 0)
        <div class="drone-controls">
            <div class="controls-wrapper">
                <!-- Baris 1: Filter Fields -->
                <div class="controls-row">
                    <div class="control-item compact">
                        <label class="control-label">
                            <i class="fas fa-calendar"></i> Tahun <span style="font-size: 11px; font-weight: 400; color: #e74c3c;">*</span>
                        </label>
                        <input type="hidden" id="tahunSelect" value="">
                        <div class="button-grid-trigger" onclick="toggleButtonGrid('tahun')">
                            <span id="tahunSelected" class="grid-selected-text">Pilih Tahun...</span>
                            <i class="fas fa-chevron-down grid-arrow"></i>
                        </div>
                        <div id="tahunGrid" class="button-grid" style="display: none;">
                            <!-- Will be populated by JS -->
                        </div>
                    </div>

                    <div class="control-item compact">
                        <label class="control-label">
                            <i class="fas fa-calendar-alt"></i> Bulan <span style="font-size: 11px; font-weight: 400; color: #e74c3c;">*</span>
                        </label>
                        <input type="hidden" id="bulanSelect" value="">
                        <div class="button-grid-trigger" onclick="toggleButtonGrid('bulan')">
                            <span id="bulanSelected" class="grid-selected-text">Pilih Bulan...</span>
                            <i class="fas fa-chevron-down grid-arrow"></i>
                        </div>
                        <div id="bulanGrid" class="button-grid" style="display: none;">
                            <button class="grid-btn" data-value="1" onclick="selectGridOption('bulan', '1', 'Januari')">Januari</button>
                            <button class="grid-btn" data-value="2" onclick="selectGridOption('bulan', '2', 'Februari')">Februari</button>
                            <button class="grid-btn" data-value="3" onclick="selectGridOption('bulan', '3', 'Maret')">Maret</button>
                            <button class="grid-btn" data-value="4" onclick="selectGridOption('bulan', '4', 'April')">April</button>
                            <button class="grid-btn" data-value="5" onclick="selectGridOption('bulan', '5', 'Mei')">Mei</button>
                            <button class="grid-btn" data-value="6" onclick="selectGridOption('bulan', '6', 'Juni')">Juni</button>
                            <button class="grid-btn" data-value="7" onclick="selectGridOption('bulan', '7', 'Juli')">Juli</button>
                            <button class="grid-btn" data-value="8" onclick="selectGridOption('bulan', '8', 'Agustus')">Agustus</button>
                            <button class="grid-btn" data-value="9" onclick="selectGridOption('bulan', '9', 'September')">September</button>
                            <button class="grid-btn" data-value="10" onclick="selectGridOption('bulan', '10', 'Oktober')">Oktober</button>
                            <button class="grid-btn" data-value="11" onclick="selectGridOption('bulan', '11', 'November')">November</button>
                            <button class="grid-btn" data-value="12" onclick="selectGridOption('bulan', '12', 'Desember')">Desember</button>
                        </div>
                    </div>

                    <i class="fas fa-undo reset-icon" onclick="resetFilters()" title="Klik untuk Reset Filter"></i>

                    <div class="control-item compact">
                        <label class="control-label">
                            <i class="fas fa-search"></i> Cari File Drone <span style="font-size: 11px; font-weight: 400; color: #999;">(Opsional)</span>
                        </label>
                        <input type="text" id="lokasiSearch" placeholder="Cari wilayah, lokasi, gulma, tanggal..." class="lokasi-search-input" oninput="filterDroneByDateTime()">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cards Grid -->
    @if ($drones->count() > 0)
        <div class="drone-grid" id="droneGrid">
            @foreach ($drones as $drone)
                <div class="drone-card" data-location="{{ $drone->lokasi }}">
                    <div class="card-year">{{ $drone->tanggal_perencanaan->year }}</div>
                    
                    <div class="card-header">
                       <img src="{{ route('drone.thumbnail', $drone->id) }}" alt="Thumbnail {{ $drone->judul }}" class="card-image" loading="lazy">
                        <h2 class="card-title">{{ Str::limit($drone->judul, 30) }}</h2>
                        <div class="card-badges">
                            <span class="badge badge-location">Lokasi {{ $drone->lokasi }}</span>
                            @if ($drone->persen_gulma !== null)
                                <span class="badge badge-gulma">{{ number_format($drone->persen_gulma, 1) }}% Gulma</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="drone-info">
                            <div class="info-label"><i class="fas fa-calendar"></i> Tanggal Perencanaan Penerbangan</div>
                            <div class="info-value">{{ $drone->tanggal_perencanaan->translatedFormat('d F Y') }}</div>
                        </div>

                        <div class="drone-info">
                            <div class="info-label"><i class="fas fa-clock"></i> Tanggal Upload File Drone</div>
                            <div class="info-value">{{ $drone->created_at->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('drone.download', $drone->id) }}" class="btn btn-download">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button class="btn btn-view" data-pdf-url="{{ route('drone.view', $drone->id) }}" data-pdf-title="{{ $drone->judul }}" onclick="openPdfModal(this)">
                            <i class="fas fa-file-pdf"></i> Detail
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
            <h2 class="empty-state-title">Belum ada dokumen drone</h2>
            <p class="empty-state-text">
                Dokumen perencanaan penggunaan drone akan ditampilkan di sini. Silakan kembali lagi nanti untuk melihat dokumen terbaru.
            </p>
        </div>
    @endif

</div><!-- End main-content -->

<!-- PDF Modal -->
<div id="pdfModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="pdfTitle">Detail PDF</h2>
            <button class="modal-close-btn" onclick="closePdfModal()">&times;</button>
        </div>
        <div class="modal-body">
            <iframe id="pdfViewer" class="pdf-viewer" src=""></iframe>
        </div>
    </div>
</div>

<script>
    // Get all unique years from drone cards
    function populateTahunGrid() {
        const cards = document.querySelectorAll('.drone-card');
        const years = new Set();
        
        cards.forEach(card => {
            const yearText = card.querySelector('.card-year')?.textContent;
            if (yearText) {
                years.add(yearText);
            }
        });
        
        const tahunGrid = document.getElementById('tahunGrid');
        tahunGrid.innerHTML = '';
        
        Array.from(years).sort((a, b) => b - a).forEach(year => {
            const btn = document.createElement('button');
            btn.className = 'grid-btn';
            btn.dataset.value = year;
            btn.textContent = year;
            btn.onclick = () => selectGridOption('tahun', year, year);
            tahunGrid.appendChild(btn);
        });
    }

    function toggleButtonGrid(type) {
        const trigger = document.querySelector(`#${type === 'tahun' ? 'tahunSelected' : 'bulanSelected'}`)?.closest('.button-grid-trigger');
        const gridId = type === 'tahun' ? 'tahunGrid' : 'bulanGrid';
        const grid = document.getElementById(gridId);
        
        if (!trigger || !grid) return;
        
        // Close other grids
        document.querySelectorAll('.button-grid-trigger').forEach(t => {
            if (t !== trigger) {
                t.classList.remove('active');
                t.nextElementSibling.style.display = 'none';
            }
        });
        
        // Toggle current grid
        trigger.classList.toggle('active');
        grid.style.display = trigger.classList.contains('active') ? 'grid' : 'none';

        if (trigger.classList.contains('active')) {
            positionGrid(grid, trigger);
        }
    }

    function positionGrid(grid, trigger) {
        const triggerRect = trigger.getBoundingClientRect();
        const gridHeight = grid.offsetHeight;
        const viewportHeight = window.innerHeight;
        
        let top, left;
        if (triggerRect.bottom + gridHeight < viewportHeight) {
            top = triggerRect.bottom + 5;
        } else {
            top = triggerRect.top - gridHeight - 5;
        }
        
        left = triggerRect.left;
        
        grid.style.position = 'fixed';
        grid.style.top = top + 'px';
        grid.style.left = left + 'px';
        grid.style.width = trigger.offsetWidth + 'px';
    }

    function selectGridOption(type, value, text) {
        const selectId = type === 'tahun' ? 'tahunSelect' : 'bulanSelect';
        const selectedId = type === 'tahun' ? 'tahunSelected' : 'bulanSelected';
        const gridId = type === 'tahun' ? 'tahunGrid' : 'bulanGrid';
        
        const hiddenInput = document.getElementById(selectId);
        const selectedText = document.getElementById(selectedId);
        const grid = document.getElementById(gridId);
        const trigger = selectedText.closest('.button-grid-trigger');
        
        hiddenInput.value = value;
        selectedText.textContent = text;
        
        // Update selected button styling
        grid.querySelectorAll('.grid-btn').forEach(btn => {
            btn.classList.toggle('selected', btn.dataset.value === value);
        });
        
        // Close grid
        trigger.classList.remove('active');
        grid.style.display = 'none';

        // Auto-apply filter immediately
        filterDroneByDateTime();
    }

    function filterDroneByDateTime() {
        const tahun = document.getElementById('tahunSelect').value;
        const bulan = document.getElementById('bulanSelect').value;
        const searchQuery = document.getElementById('lokasiSearch').value.toLowerCase();
        
        const cards = document.querySelectorAll('.drone-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const cardYear = card.querySelector('.card-year')?.textContent;
            const cardLokasi = card.dataset.location;
            
            // Get all text content from the card for comprehensive search
            const cardTitle = card.querySelector('.card-title')?.textContent || '';
            const infoValues = card.querySelectorAll('.info-value');
            let cardDateText = '';
            let cardUploadDate = '';
            let cardPersentase = '';
            
            if (infoValues.length > 0) {
                cardDateText = infoValues[0].textContent; // Tanggal Perencanaan
            }
            if (infoValues.length > 1) {
                cardUploadDate = infoValues[1].textContent; // Tanggal Upload
            }
            
            const gulmaText = card.querySelector('.badge-gulma')?.textContent || '';
            
            let show = true;

            // Filter by tahun
            if (tahun && cardYear != tahun) {
                show = false;
            }

            // Filter by bulan
            if (show && bulan) {
                const monthNames = {
                    'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4,
                    'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8,
                    'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
                };
                
                let cardMonth = null;
                for (const [monthName, monthNum] of Object.entries(monthNames)) {
                    if (cardDateText.includes(monthName)) {
                        cardMonth = monthNum;
                        break;
                    }
                }
                
                if (cardMonth && cardMonth != bulan) {
                    show = false;
                }
            }

            // Filter by search query (search in multiple fields like Ctrl+F)
            if (show && searchQuery) {
                const searchableText = [
                    cardTitle,
                    cardLokasi,
                    cardDateText,
                    cardUploadDate,
                    gulmaText
                ].join(' ').toLowerCase();
                
                if (!searchableText.includes(searchQuery)) {
                    show = false;
                }
            }

            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Show/hide empty state if needed
        const emptyState = document.querySelector('.empty-state');
        if (emptyState && visibleCount === 0 && (tahun || bulan || searchQuery)) {
            emptyState.style.display = 'none';
        }
    }

    function resetFilters() {
        // Reset tahun
        document.getElementById('tahunSelect').value = '';
        document.getElementById('tahunSelected').textContent = 'Pilih Tahun...';
        document.querySelectorAll('#tahunGrid .grid-btn').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Reset bulan
        document.getElementById('bulanSelect').value = '';
        document.getElementById('bulanSelected').textContent = 'Pilih Bulan...';
        document.querySelectorAll('#bulanGrid .grid-btn').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Reset search lokasi
        document.getElementById('lokasiSearch').value = '';

        // Apply filter (tampilkan semua)
        filterDroneByDateTime();
    }

    function openPdfModal(button) {
        const pdfUrl = button.getAttribute('data-pdf-url');
        const title = button.getAttribute('data-pdf-title');
        document.getElementById('pdfTitle').textContent = title;
        document.getElementById('pdfViewer').src = pdfUrl;
        document.getElementById('pdfModal').classList.add('show');
    }

    function closePdfModal() {
        document.getElementById('pdfModal').classList.remove('show');
        document.getElementById('pdfViewer').src = '';
    }

    // Tutup modal saat klik di luar konten modal
    window.onclick = function(event) {
        const modal = document.getElementById('pdfModal');
        if (event.target == modal) {
            closePdfModal();
        }
    }

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePdfModal();
        }
    });

    // Initialize grids on page load
    document.addEventListener('DOMContentLoaded', function() {
        populateTahunGrid();
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.control-item')) {
            document.querySelectorAll('.button-grid-trigger').forEach(trigger => {
                trigger.classList.remove('active');
                trigger.nextElementSibling.style.display = 'none';
            });
        }    
    });

    // Initialize saat DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        populateTahunGrid();
    });
</script>