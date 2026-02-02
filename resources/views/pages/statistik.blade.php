@extends('layouts.app')

@section('title', 'Statistik')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-chart-bar"></i> Statistik perkebunan</h1>
    <p>Analisis mendalam tentang tren produksi dan perbandingan data antar wilayah</p>
</div>

<div class="container">
    <style>
        .stats-controls {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .stats-controls input,
        .stats-controls select {
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 13px;
            font-family: 'Poppins';
            position: relative;
            z-index: 1;
            background: white;
        }

        .stats-controls button {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 13px;
            white-space: nowrap;
        }

        .stats-controls button:hover {
            background-color: var(--secondary-color);
        }

        .stat-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            overflow-x: auto;
        overflow-y: visible !important;
    }

        .stat-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .stat-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .stat-table th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .stat-table td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .stat-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .stat-table tbody tr:hover {
            background-color: var(--light-color);
        }

        .stat-table tbody tr:last-child td {
            border-bottom: none;
        }

        .stat-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Responsive - Tablet */
        @media (max-width: 1024px) {
            .stat-table {
                font-size: 12px;
            }

            .stat-table th,
            .stat-table td {
                padding: 11px 12px;
                font-size: 12px;
            }

            .stat-table th {
                font-size: 11px;
            }
        }

        /* Responsive - Mobile */
        @media (max-width: 768px) {
            .stat-table {
                font-size: 11px;
            }

            .stat-table th,
            .stat-table td {
                padding: 10px 10px;
                font-size: 11px;
            }

            .stat-table th {
                font-size: 10px;
            }
        }

        .bar-chart {
            margin: 20px 0;
        }

        .bar-item {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
            gap: 12px;
        }

        .bar-label {
            min-width: 150px;
            font-weight: 600;
            color: var(--text-color);
            font-size: 12px;
        }

        .bar-container {
            flex: 1;
            background-color: var(--light-color);
            height: 26px;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 8px;
            color: white;
            font-weight: 600;
            font-size: 11px;
        }

        .bar-value {
            min-width: 60px;
            text-align: right;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 12px;
        }

        .comparison-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 18px;
            margin: 20px 0;
        }

        .comparison-card {
            background: linear-gradient(135deg, var(--light-color), #fff);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 18px;
            transition: all 0.3s ease;
        }

        .comparison-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow);
        }

        .comparison-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 12px;
            font-size: 14px;
        }

        .comparison-stat {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
            font-size: 12px;
        }

        .comparison-stat:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .comparison-label {
            color: #666;
        }

        .comparison-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        .trend-indicator {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            margin-left: 6px;
        }

        .trend-up {
            background-color: #d4edda;
            color: #155724;
        }

        .trend-down {
            background-color: #f8d7da;
            color: #721c24;
        }

        .export-btn {
            background-color: var(--secondary-color);
            color: #333;
            border: none;
            padding: 9px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .export-btn:hover {
            background-color: #c5ce1a;
            transform: translateY(-2px);
        }

        .year-comparison {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .year-item {
            background: white;
            padding: 18px;
            border-radius: 8px;
            border-left: 5px solid var(--primary-color);
            text-align: center;
        }

        .year-item .year {
            font-size: 11px;
            color: #999;
            margin-bottom: 5px;
        }

        .year-item .value {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .year-item .label {
            font-size: 11px;
            color: #666;
            margin-top: 8px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-controls {
                padding: 18px;
                gap: 10px;
            }

            .stats-controls input,
            .stats-controls select,
            .stats-controls button {
                font-size: 12px;
                padding: 9px 12px;
            }

            .stat-table th,
            .stat-table td {
                padding: 10px;
            }

            .bar-label {
                min-width: 120px;
                font-size: 11px;
            }

            .bar-item {
                margin-bottom: 15px;
            }

            .comparison-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 15px;
            }

            .year-comparison {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .stats-controls {
                padding: 15px;
                flex-direction: column;
                gap: 10px;
                overflow: visible !important;
            }

            .stats-controls input,
            .stats-controls select,
            .stats-controls button {
                width: 100%;
                padding: 10px 12px;
                font-size: 12px;
            }

            .stat-section {
                padding: 15px;
                margin-bottom: 20px;
                font-size: 12px;
                overflow: visible !important;
            }

            .stat-section h3 {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .stat-table {
                font-size: 11px;
            }

            .stat-table th {
                padding: 8px;
            }

            .stat-table td {
                padding: 8px;
            }

            .bar-label {
                min-width: 100px;
                font-size: 10px;
            }

            .bar-item {
                margin-bottom: 12px;
                gap: 8px;
            }

            .bar-container {
                height: 22px;
            }

            .bar-value {
                min-width: 50px;
                font-size: 11px;
            }

            .comparison-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .comparison-card {
                padding: 14px;
            }

            .year-comparison {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .year-item {
                padding: 14px;
            }

            .year-item .value {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .stats-controls {
                padding: 12px;
                flex-direction: column;
                gap: 8px;
            }

            .stats-controls input,
            .stats-controls select,
            .stats-controls button {
                width: 100%;
                padding: 9px 10px;
                font-size: 11px;
            }

            .stat-section {
                padding: 12px;
                margin-bottom: 15px;
            }

            .stat-section h3 {
                font-size: 14px;
                margin-bottom: 12px;
            }

            .stat-table {
                font-size: 10px;
            }

            .stat-table th {
                padding: 6px;
            }

            .stat-table td {
                padding: 6px;
            }

            .bar-label {
                min-width: 80px;
                font-size: 9px;
            }

            .bar-item {
                margin-bottom: 10px;
                gap: 6px;
                flex-wrap: wrap;
            }

            .bar-container {
                flex: 1;
                min-width: 100px;
                height: 20px;
            }

            .bar-value {
                min-width: 40px;
                font-size: 10px;
            }

            .comparison-grid {
                gap: 10px;
            }

            .comparison-card {
                padding: 12px;
            }

            .comparison-title {
                font-size: 13px;
            }

            .comparison-stat {
                margin-bottom: 8px;
                padding-bottom: 8px;
                font-size: 11px;
            }

            .year-comparison {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .year-item {
                padding: 12px;
            }

            .year-item .value {
                font-size: 18px;
            }

            .export-btn {
                width: 100%;
                padding: 8px 12px;
            }
        }
    </style>

    <style>
        .wilayah-controls {
            background: linear-gradient(135deg, #ffffff 0%, #f7faf8 100%);
            padding: 32px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(18, 130, 65, 0.1);
            border-left: 4px solid #128241;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: visible !important;
            z-index: 1;
        }

        .wilayah-controls::before {
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
            font-family: 'Poppins', sans-serif;
            overflow: visible !important;
            position: relative;
            z-index: 1;
        }

        .controls-row {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: nowrap;
            margin-bottom: 16px;
            overflow: visible !important;
            position: relative;
        }

        .control-item {
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
            position: relative;
            flex: 1;
            min-width: 0;
            z-index: 100;
            overflow: visible !important;
        }

        .control-item.compact {
            flex: 1;
            min-width: 0;
        }

        .controls-buttons-row {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            z-index: 1;
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
            font-family: 'Poppins', sans-serif;
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
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: #2c3e50;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            user-select: none;
            white-space: nowrap;
            pointer-events: auto;
            position: relative;
        }

        .button-grid-trigger:hover {
            border-color: #128241;
            box-shadow: 0 6px 16px rgba(18, 130, 65, 0.12), 0 2px 6px rgba(0, 0, 0, 0.05);
            background-color: #fafdfb;
        }

        .button-grid-trigger.active {
            border-color: #128241;
            background-color: #fafdfb;
            z-index: 99998 !important;
            position: relative;
        }

        .button-grid-trigger.active ~ .button-grid {
            z-index: 99999 !important;
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

        .button-grid {
            position: fixed !important;
            background: white;
            border: 2px solid #128241;
            border-radius: 10px;
            padding: 10px 8px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            z-index: 999999 !important;
            pointer-events: auto !important;
            box-shadow: 0 10px 30px rgba(18, 130, 65, 0.2);
            animation: slideDown 0.3s ease;
            max-height: 400px;
            overflow-y: auto;
            width: auto;
            max-width: 320px;
        }

        #tahunGrid.button-grid {
            grid-template-columns: repeat(3, 1fr);
            max-width: 280px;
        }

        #bulanGrid.button-grid {
            grid-template-columns: repeat(6, 1fr);
            max-width: 600px;
        }

        #mingguGrid.button-grid {
            grid-template-columns: repeat(4, 1fr);
            max-width: 300px;
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

        .grid-btn {
            padding: 10px 8px;
            border: 2px solid #e3eae8;
            border-radius: 6px;
            background: white;
            color: #2c3e50;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            white-space: normal;
            line-height: 1.2;
            position: relative;
            pointer-events: auto !important;
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

        .grid-btn[disabled] {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f5f5f5;
            color: #999;
            border-color: #ddd;
        }

        .grid-btn[disabled]:hover {
            border-color: #ddd;
            background: #f5f5f5;
            color: #999;
            transform: none;
            box-shadow: none;
        }

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
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            transition: all 0.2s ease;
            background: white;
            border: 2px solid #d8e1dd;
            color: #FBA919;
            box-shadow: none;
        }

        .btn-secondary:hover {
            background: #FBA919;
            border-color: #FBA919;
            color: white;
            box-shadow: 0 4px 12px rgba(251, 169, 25, 0.2);
        }

        @media (max-width: 1024px) {
            .controls-row {
                flex-wrap: wrap;
            }

            .control-item.compact {
                flex: 1 1 calc(50% - 6px);
                min-width: 0;
            }

            .wilayah-controls {
                padding: 24px;
            }
        }

        @media (max-width: 768px) {
            .wilayah-controls {
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 20px;
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

            .btn-secondary {
                flex: 1 1 100% !important;
                width: 100%;
                padding: 10px 12px;
                font-size: 12px;
                gap: 6px;
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

            #mingguGrid.button-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .grid-btn {
                padding: 10px 8px;
                font-size: 12px;
                min-width: 60px;
            }
        }

        @media (max-width: 480px) {
            .wilayah-controls {
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

            .btn-secondary {
                flex: 1 1 100%;
                width: 100%;
                padding: 8px 10px;
                font-size: 11px;
                gap: 4px;
            }

            .btn-secondary i {
                font-size: 12px;
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

            #mingguGrid.button-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .grid-btn {
                padding: 8px 6px;
                font-size: 10px;
                min-width: 50px;
            }
        }
    </style>

    <!-- Filter & Kontrol -->
    <div class="wilayah-controls">
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
                        <!-- Will be populated by JS -->
                    </div>
                </div>

                <div class="control-item compact">
                    <label class="control-label">
                        <i class="fas fa-calendar-week"></i> Minggu <span style="font-size: 11px; font-weight: 400; color: #e74c3c;">*</span>
                    </label>
                    <input type="hidden" id="mingguSelect" value="">
                    <div class="button-grid-trigger" onclick="toggleButtonGrid('minggu')">
                        <span id="mingguSelected" class="grid-selected-text">Pilih Minggu ke-...</span>
                        <i class="fas fa-chevron-down grid-arrow"></i>
                    </div>
                    <div id="mingguGrid" class="button-grid" style="display: none;">
                        <!-- Will be populated by JS -->
                    </div>
                </div>
            </div>

            <!-- Baris 2: Action Button -->
            <div class="controls-buttons-row">
                <button onclick="updateStats()" class="btn-secondary" title="Tampilkan statistik berdasarkan periode yang dipilih">
                    <i class="fas fa-refresh"></i> Update Statistik
                </button>
            </div>
        </div>
    </div>

    <!-- Status Info (Data Terpilih) -->
    <div id="periodInfo" style="background: #f4f6f8; padding: 12px 20px; border-radius: 8px; margin-bottom: 18px; border-left: 4px solid #128241; display: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="display: flex; align-items: center; font-size: 18px; color: #e67e22; flex-shrink: 0; gap: 6px;">
                <i class="fas fa-info-circle"></i>
                <span style="font-size: 18px;">⭐</span>
            </div>
            <div>
                <span id="periodInfoText" style="font-size: 14px; color: #e67e22; font-weight: 600;">
                    Data Terpilih - <strong>Memuat...</strong> • records tersedia
                </span>
            </div>
        </div>
    </div>

     <!-- Perbandingan Produksi -->
    <div class="stat-section">
        <h3><i class="fas fa-chart-bar"></i> Perbandingan Produksi Komoditas</h3>
        <div class="comparison-grid" id="comparisonGrid">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>


    <!-- Ranking Wilayah -->
    <div class="stat-section">
        <h3><i class="fas fa-trophy"></i> Ranking Wilayah Berdasarkan Gulma</h3>
        <div class="bar-chart">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>

    <!-- Tabel Statistik Detail -->
    <div class="stat-section">
    <h3><i class="fas fa-list"></i> Tabel Statistik Detail Per Wilayah</h3>
        <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 6px;">
        <table class="stat-table">
            <thead>
                <tr>
                    <th style="text-align: center; width: 50px;">No.</th>
                    <th style="min-width: 120px;">Wilayah</th>
                    <th style="text-align: center; min-width: 140px;">Luas Rencana (Ha)</th>
                    <th style="text-align: center; min-width: 140px;">Total Neto (Ha)</th>
                    <th style="text-align: center; min-width: 140px;">Tenaga Kerja (Orang)</th>
                    <th style="text-align: center; width: 80px;">Tahun</th>
                </tr>
            </thead>
            <tbody id="detailStatsTable">
                <!-- Will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<script>
/* ===============================
   STATISTIK PAGE - WITH SMART DYNAMIC FILTERS
================================ */

let currentPeriod = {
    tahun: null,
    bulan: null,
    minggu: null
};

let availablePeriods = {
    periods: [],
    tahun_list: [],
    latest_period: null
};

const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

/* ===============================
   INIT
================================ */
document.addEventListener('DOMContentLoaded', async () => {
    console.log('📊 Statistik page loaded');
    await loadAvailablePeriods();
});

/* ===============================
   LOAD AVAILABLE PERIODS FROM API
================================ */
async function loadAvailablePeriods() {
    try {
        console.log('🔍 Loading available periods...');
        
        const response = await fetch('/api/wilayah/periods');
        
        if (!response.ok) {
            throw new Error(`Failed to fetch periods: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('📅 Periods API response:', data);
        
        if (data.success) {
            availablePeriods = {
                periods: data.periods || [],
                tahun_list: data.tahun_list || [],
                latest_period: data.latest_period
            };
            
            console.log('✅ Available years:', availablePeriods.tahun_list);
            console.log('✅ Latest period:', availablePeriods.latest_period);
            
            populateTahunDropdown();
            populateBulanAndMingguGrids();
            
            if (availablePeriods.latest_period) {
                const latest = availablePeriods.latest_period;
                const tahun = latest.tahun;
                const bulan = latest.bulan;
                const minggu = latest.minggu;
                
                console.log('🎯 Setting default to latest period:', {tahun, bulan, minggu});
                
                // Set hidden selectors
                document.getElementById('tahunSelect').value = tahun;
                document.getElementById('bulanSelect').value = bulan;
                document.getElementById('mingguSelect').value = minggu;
                
                // Update display text
                const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                document.getElementById('tahunSelected').textContent = tahun;
                document.getElementById('bulanSelected').textContent = bulanNames[parseInt(bulan)];
                document.getElementById('mingguSelected').textContent = 'Minggu ke-' + minggu;
                
                // Mark selected buttons with .selected class
                const tahunGrid = document.getElementById('tahunGrid');
                const bulanGrid = document.getElementById('bulanGrid');
                const mingguGrid = document.getElementById('mingguGrid');
                
                tahunGrid.querySelector(`[data-value="${tahun}"]`)?.classList.add('selected');
                bulanGrid.querySelector(`[data-value="${bulan}"]`)?.classList.add('selected');
                mingguGrid.querySelector(`[data-value="${minggu}"]`)?.classList.add('selected');
                
                // Initialize dependent dropdowns
                updateBulanDropdown(tahun);
                updateMingguDropdown(tahun, bulan);
                
                currentPeriod = latest;
                updatePeriodInfoDisplay();
                
                console.log('📍 Period defaults set to latest:', latest);
            }
            
            await loadAllStatistics();
            
        } else {
            throw new Error('API returned success: false');
        }
        
    } catch (error) {
        console.error('❌ Error loading periods:', error);
        
        const currentYear = new Date().getFullYear();
        availablePeriods.tahun_list = [currentYear];
        populateTahunDropdown();
        
        await loadAllStatistics();
    }
}

/* ===============================
   POPULATE FILTERS WITH SMART FILTERING
================================ */
function populateTahunDropdown() {
    const grid = document.getElementById('tahunGrid');
    if (!grid) return;
    
    grid.innerHTML = '';
    
    // Get only years from availablePeriods (yang sudah di-upload admin)
    const activeYears = availablePeriods.tahun_list || [];
    
    // Sort years in descending order (terbaru dulu)
    const sortedYears = [...activeYears].sort((a, b) => b - a);
    
    sortedYears.forEach(year => {
        const btn = document.createElement('button');
        btn.className = 'grid-btn';
        btn.setAttribute('data-value', year);
        btn.textContent = year;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
        btn.onclick = (e) => {
            e.preventDefault();
            selectGridOption('tahun', year, year);
        };
        
        grid.appendChild(btn);
    });
    
    console.log('✅ Tahun grid populated with available years:', sortedYears);
}

/* ===============================
   UPDATE BULAN DROPDOWN - SMART FILTERING
================================ */
function updateBulanDropdown(tahun) {
    const bulanGrid = document.getElementById('bulanGrid');
    const availableBulan = getAvailableBulanForTahun(tahun);
    const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    // Mark buttons based on availability
    const buttons = bulanGrid.querySelectorAll('.grid-btn');
    buttons.forEach(btn => {
        const bulanValue = parseInt(btn.getAttribute('data-value'));
        if (availableBulan.includes(bulanValue)) {
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
            btn.disabled = false;
            btn.title = `${bulanNames[bulanValue]} tersedia`;
        } else {
            btn.style.opacity = '0.4';
            btn.style.cursor = 'not-allowed';
            btn.disabled = true;
            btn.title = `${bulanNames[bulanValue]} tidak ada data`;
        }
    });
    
    console.log(`📊 Bulan tersedia untuk tahun ${tahun}:`, availableBulan);
}

/* ===============================
   UPDATE MINGGU DROPDOWN - SMART FILTERING
================================ */
function updateMingguDropdown(tahun, bulan) {
    const mingguGrid = document.getElementById('mingguGrid');
    const availableMinggu = getAvailableMingguForTahunBulan(tahun, bulan);
    
    // Mark buttons based on availability
    const buttons = mingguGrid.querySelectorAll('.grid-btn');
    buttons.forEach(btn => {
        const mingguValue = parseInt(btn.getAttribute('data-value'));
        if (availableMinggu.includes(mingguValue)) {
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
            btn.disabled = false;
            btn.title = `Minggu ke-${mingguValue} tersedia`;
        } else {
            btn.style.opacity = '0.4';
            btn.style.cursor = 'not-allowed';
            btn.disabled = true;
            btn.title = `Minggu ke-${mingguValue} tidak ada data`;
        }
    });
    
    console.log(`📊 Minggu tersedia untuk ${tahun}/${bulan}:`, availableMinggu);
}

/* ===============================
   POPULATE BULAN & MINGGU GRIDS - DYNAMIC
================================ */
function populateBulanAndMingguGrids() {
    const bulanGrid = document.getElementById('bulanGrid');
    const mingguGrid = document.getElementById('mingguGrid');
    const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    // Get all unique bulan and minggu from availablePeriods
    const allBulan = new Set();
    const allMinggu = new Set();
    
    if (availablePeriods.periods && availablePeriods.periods.length > 0) {
        availablePeriods.periods.forEach(period => {
            allBulan.add(period.bulan);
            allMinggu.add(period.minggu);
        });
    }
    
    // Clear existing buttons
    bulanGrid.innerHTML = '';
    mingguGrid.innerHTML = '';
    
    // Populate Bulan Grid - All 12 months, disabled if no data
    for (let bulan = 1; bulan <= 12; bulan++) {
        const btn = document.createElement('button');
        btn.className = 'grid-btn';
        btn.setAttribute('data-value', bulan);
        btn.textContent = bulanNames[bulan];
        
        const isActive = allBulan.has(bulan);
        
        if (!isActive) {
            btn.setAttribute('disabled', 'disabled');
            btn.style.opacity = '0.4';
            btn.style.cursor = 'not-allowed';
            btn.title = `${bulanNames[bulan]} tidak ada data`;
        } else {
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
            btn.title = `${bulanNames[bulan]} tersedia`;
            btn.onclick = (e) => {
                e.preventDefault();
                selectGridOption('bulan', bulan, bulanNames[bulan]);
            };
        }
        
        bulanGrid.appendChild(btn);
    }
    
    // Populate Minggu Grid - All 4 weeks, disabled if no data
    for (let minggu = 1; minggu <= 4; minggu++) {
        const btn = document.createElement('button');
        btn.className = 'grid-btn';
        btn.setAttribute('data-value', minggu);
        btn.textContent = `Ke-${minggu}`;
        
        const isActive = allMinggu.has(minggu);
        
        if (!isActive) {
            btn.setAttribute('disabled', 'disabled');
            btn.style.opacity = '0.4';
            btn.style.cursor = 'not-allowed';
            btn.title = `Minggu ke-${minggu} tidak ada data`;
        } else {
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
            btn.title = `Minggu ke-${minggu} tersedia`;
            btn.onclick = (e) => {
                e.preventDefault();
                selectGridOption('minggu', minggu, `Minggu ke-${minggu}`);
            };
        }
        
        mingguGrid.appendChild(btn);
    }
    
    console.log('✅ Bulan dan Minggu grids populated');
}

/* ===============================
   GET AVAILABLE BULAN FOR TAHUN
================================ */
function getAvailableBulanForTahun(tahun) {
    if (!availablePeriods.periods || availablePeriods.periods.length === 0) {
        return [];
    }
    
    const bulanSet = new Set();
    availablePeriods.periods.forEach(period => {
        if (period.tahun == tahun) {
            bulanSet.add(period.bulan);
        }
    });
    
    return Array.from(bulanSet).sort((a, b) => a - b);
}

/* ===============================
   GET AVAILABLE MINGGU FOR TAHUN & BULAN
================================ */
function getAvailableMingguForTahunBulan(tahun, bulan) {
    if (!availablePeriods.periods || availablePeriods.periods.length === 0) {
        return [];
    }
    
    const mingguSet = new Set();
    availablePeriods.periods.forEach(period => {
        if (period.tahun == tahun && period.bulan == bulan) {
            mingguSet.add(period.minggu);
        }
    });
    
    return Array.from(mingguSet).sort((a, b) => a - b);
}

/* ===============================
   TOGGLE BUTTON GRID (DROPDOWN)
================================ */
function toggleButtonGrid(type) {
    let gridId = type + 'Grid';
    const grid = document.getElementById(gridId);
    const trigger = grid.previousElementSibling;

    if (grid.style.display === 'none' || grid.style.display === '') {
        document.querySelectorAll('.button-grid').forEach(g => {
            if (g.id !== gridId) {
                g.style.display = 'none';
                g.style.pointerEvents = 'none';
            }
        });
        document.querySelectorAll('.button-grid-trigger').forEach(t => {
            if (t !== trigger) {
                t.classList.remove('active');
            }
        });

        grid.style.position = 'fixed';
        grid.style.display = 'grid';
        grid.style.pointerEvents = 'auto';
        trigger.classList.add('active');

        const triggerRect = trigger.getBoundingClientRect();

        let top = triggerRect.bottom + 8;
        let left = triggerRect.left;

        requestAnimationFrame(() => {
            const gridHeight = grid.offsetHeight;
            const gridWidth = grid.offsetWidth;
            
            if (top + gridHeight > window.innerHeight - 10) {
                top = triggerRect.top - gridHeight - 8;
            }

            if (left + gridWidth > window.innerWidth - 10) {
                left = Math.max(10, window.innerWidth - gridWidth - 10);
            }

            if (left < 10) {
                left = 10;
            }

            if (top < 10) {
                top = 10;
            }

            grid.style.top = top + 'px';
            grid.style.left = left + 'px';
            grid.style.zIndex = '999999';
        });
    } else {
        grid.style.display = 'none';
        grid.style.pointerEvents = 'none';
        trigger.classList.remove('active');
    }
}

/* ===============================
   SELECT GRID OPTION
================================ */
function selectGridOption(type, value, label) {
    // Update hidden input
    document.getElementById(type + 'Select').value = value;
    
    // Update display text
    document.getElementById(type + 'Selected').textContent = label;
    
    // Update selected button
    let gridId = type + 'Grid';
    const grid = document.getElementById(gridId);
    
    // Remove selected class from all buttons in this grid
    grid.querySelectorAll('.grid-btn').forEach(btn => {
        btn.classList.remove('selected');
    });
    
    // Add selected class to the clicked button
    if (event && event.target && event.target.classList) {
        event.target.classList.add('selected');
    } else {
        // If called programmatically, find the button by data-value
        const btn = grid.querySelector(`[data-value="${value}"]`);
        if (btn) btn.classList.add('selected');
    }
    
    // CUSTOM LOGIC: Handle period-related dropdown updates
    if (type === 'tahun') {
        // Reset bulan & minggu selections
        document.getElementById('bulanSelect').value = '';
        document.getElementById('mingguSelect').value = '';
        document.getElementById('bulanSelected').textContent = 'Pilih Bulan...';
        document.getElementById('mingguSelected').textContent = 'Pilih Minggu ke-...';
        
        // Update bulan options
        updateBulanDropdown(value);
        
        console.log(`✅ Tahun dipilih: ${value}`);
        
    } else if (type === 'bulan') {
        const tahun = document.getElementById('tahunSelect').value;
        
        // Reset minggu selection
        document.getElementById('mingguSelect').value = '';
        document.getElementById('mingguSelected').textContent = 'Pilih Minggu ke-...';
        
        // Update minggu options
        updateMingguDropdown(tahun, value);
        
        console.log(`✅ Bulan dipilih: ${value}, tahun: ${tahun}`);
        
    } else if (type === 'minggu') {
        console.log(`✅ Minggu dipilih: ${value}`);
        
        // Auto-load statistics when all periods are selected
        const tahun = document.getElementById('tahunSelect').value;
        const bulan = document.getElementById('bulanSelect').value;
        const minggu = document.getElementById('mingguSelect').value;
        
        if (tahun && bulan && minggu) {
            console.log(`🔄 Semua periode dipilih - memuat statistik...`);
            
            // Update display and load stats
            setTimeout(() => {
                updateStats();
            }, 200);
        }
    }
    
    // Close grid
    grid.style.display = 'none';
    grid.previousElementSibling.classList.remove('active');
}

/* ===============================
   CLOSE GRIDS WHEN CLICKING OUTSIDE
================================ */
document.addEventListener('click', (e) => {
    // Ignore clicks on control items
    if (!e.target.closest('.control-item')) {
        document.querySelectorAll('.button-grid').forEach(grid => {
            grid.style.display = 'none';
            grid.style.pointerEvents = 'none';
        });
        document.querySelectorAll('.button-grid-trigger').forEach(trigger => {
            trigger.classList.remove('active');
        });
    }
});

/* ===============================
   UPDATE PERIOD INFO DISPLAY
================================ */
function updatePeriodInfoDisplay() {
    const display = document.getElementById('periodInfo');
    const textEl = document.getElementById('periodInfoText');
    
    if (!display || !textEl) return;
    
    if (!currentPeriod.tahun) {
        display.style.display = 'none';
        return;
    }
    
    const tahun = currentPeriod.tahun;
    const bulan = monthNames[currentPeriod.bulan] || 'Bulan ' + currentPeriod.bulan;
    const minggu = currentPeriod.minggu ? `Minggu ke-${currentPeriod.minggu}` : 'Minggu';
    const recordCount = document.getElementById('recordCount')?.textContent || '0';
    
    textEl.innerHTML = `Data Terpilih - <strong>Tahun ${tahun}, ${bulan}, ${minggu}</strong> • records tersedia`;
    display.style.display = 'block';
    
    console.log('📍 Period display updated:', currentPeriod);
}

/* ===============================
   UPDATE RECORD COUNT
================================ */
function updateRecordCount(count) {
    const recordCountEl = document.getElementById('recordCount');
    if (recordCountEl) {
        recordCountEl.textContent = count;
        updatePeriodInfoDisplay();
    }
}

/* ===============================
   LOAD ALL STATISTICS
================================ */
async function loadAllStatistics() {
    showLoading();

    try {
        console.log('🚀 Loading statistics with latest published data...');
        
        const tahun = document.getElementById('tahunSelect')?.value;
        const bulan = document.getElementById('bulanSelect')?.value;
        const minggu = document.getElementById('mingguSelect')?.value;
        
        let queryParams = new URLSearchParams();
        if (tahun) queryParams.append('tahun', tahun);
        if (bulan) queryParams.append('bulan', bulan);
        if (minggu) queryParams.append('minggu', minggu);
        
        const queryString = queryParams.toString() ? '?' + queryParams.toString() : '';
        
        console.log('📡 Query:', queryString || '(using latest published)');
        
        const [summaryRes, rankingRes] = await Promise.all([
            fetch(`/api/statistik/summary${queryString}`),
            fetch(`/api/statistik/ranking${queryString}`)
        ]);

        if (!summaryRes.ok) throw new Error(`Summary API: ${summaryRes.status}`);
        if (!rankingRes.ok) throw new Error(`Ranking API: ${rankingRes.status}`);

        const summary = await summaryRes.json();
        const ranking = await rankingRes.json();

        console.log('📊 Summary response:', summary);
        console.log('🏆 Ranking response:', ranking);

        if (summary.success) {
            renderDetailStats(summary.data);
            renderComparison(summary.data);
            
            if (summary.import_log_id) {
                console.log(`✅ Using import_log_id: ${summary.import_log_id}`);
            }
        } else {
            showEmptyState('detailStatsTable', summary.message || 'Tidak ada data statistik');
        }
        
        if (ranking.success) {
            renderRanking(ranking.data);
        } else {
            showEmptyState('bar-chart', ranking.message || 'Tidak ada data ranking');
        }

        hideLoading();
        console.log('✅ Statistics loaded successfully!');

    } catch (err) {
        console.error('❌ Error loading statistics:', err);
        alert('Gagal memuat statistik: ' + err.message);
        hideLoading();
    }
}

/* ===============================
   UPDATE STATS
================================ */
async function updateStats() {
    const tahun = document.getElementById('tahunSelect')?.value;
    const bulan = document.getElementById('bulanSelect')?.value;
    const minggu = document.getElementById('mingguSelect')?.value;

    console.log('🔍 Filter:', { tahun, bulan, minggu });
    currentPeriod = { tahun, bulan, minggu };
    
    updatePeriodInfoDisplay();
    await loadAllStatistics();
}

/* ===============================
   RENDER FUNCTIONS
================================ */
function renderDetailStats(data) {
    const tbody = document.getElementById('detailStatsTable');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    updateRecordCount(data.length);

    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center; padding: 50px 20px;">
                    <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 15px;"></i>
                    <strong style="display: block; font-size: 14px; margin-bottom: 5px;">Tidak ada data</strong>
                    <small style="display: block; font-size: 12px; color: #999;">Pilih periode lain atau upload CSV di Admin</small>
                </td>
            </tr>
        `;
        return;
    }

    data.forEach((item, i) => {
        const row = tbody.insertRow();
        
        const luasRencana = parseFloat(item.total_hasil || 0).toFixed(2);
        const totalNeto = parseFloat(item.total_neto || 0).toFixed(2);
        const totalTk = Math.round(parseFloat(item.total_tenaga_kerja || 0));
        
        row.innerHTML = `
            <td style="text-align: center; font-weight: 600; color: #999;"><strong>${i + 1}</strong></td>
            <td style="font-weight: 600; color: var(--primary-color);">Wilayah ${item.wilayah_id}</td>
            <td class="stat-value" style="text-align: center;">${luasRencana}</td>
            <td class="stat-value" style="text-align: center;">${totalNeto}</td>
            <td class="stat-value" style="text-align: center;"><strong>${totalTk.toLocaleString('id-ID')}</strong></td>
            <td style="text-align: center; font-weight: 600;"><strong>${currentPeriod.tahun || new Date().getFullYear()}</strong></td>
        `;
        
        if (item.raw_count) {
            row.cells[0].title = `Raw: ${item.raw_count} → Dedup: ${item.total_features}`;
        }
    });
    
    console.log('✅ Detail stats rendered:', data.length, 'wilayah');
}

function renderComparison(data) {
    const container = document.getElementById('comparisonGrid');
    if (!container) return;
    
    container.innerHTML = '';

    if (!data || data.length === 0) {
        container.innerHTML = `
            <div style="text-align:center; padding: 40px; width: 100%; color: #999;">
                <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i><br>
                <strong>Tidak ada data</strong>
            </div>
        `;
        return;
    }

    // IMPORTANT: total_hasil = Luas Rencana (from CSV "hasil" column)
    const totalLuasRencana = data.reduce((sum, item) => sum + (parseFloat(item.total_hasil) || 0), 0);
    const totalTenagaKerja = data.reduce((sum, item) => sum + (parseFloat(item.total_tenaga_kerja) || 0), 0);
    
    // DEBUG: Log raw values
    console.log('🔍 RENDER COMPARISON - Raw Values:', {
        dataLength: data.length,
        firstItem: data[0],
        totalTenagaKerjaRaw: totalTenagaKerja,
        totalTenagaKerjaRounded: Math.round(totalTenagaKerja)
    });

    const card = document.createElement('div');
    card.className = 'comparison-card';
    card.innerHTML = `
        <div class="comparison-title"><i class="fa-solid fa-jar-wheat" style="color: #FBA919;"></i> Nanas</div>
        
        <div class="comparison-stat">
            <span class="comparison-label">Luas Rencana Wilayah:</span>
            <span class="comparison-value">${totalLuasRencana.toFixed(2)} Ha</span>
        </div>
        <div class="comparison-stat">
            <span class="comparison-label">Tenaga Kerja:</span>
            <span class="comparison-value">${Math.round(totalTenagaKerja).toLocaleString('id-ID')} Orang</span>
        </div>
    `;
    container.appendChild(card);
    
    console.log('✅ Comparison rendered:', {
        totalLuasRencana: totalLuasRencana.toFixed(2),
        totalTenagaKerja: totalTenagaKerja
    });
}

function renderRanking(data) {
    const container = document.querySelector('.bar-chart');
    if (!container) return;
    
    container.innerHTML = '';

    if (!data || data.length === 0) {
        container.innerHTML = `
            <p style="text-align:center; padding: 40px;">
                <i class="fas fa-chart-bar" style="font-size: 48px; opacity: 0.3;"></i><br>
                <strong>Tidak ada data ranking</strong>
            </p>
        `;
        return;
    }

    const max = Math.max(...data.map(d => parseFloat(d.total_hasil) || 0));

    data.forEach((item, index) => {
        const hasil = parseFloat(item.total_hasil) || 0;
        const percent = max > 0 ? (hasil / max) * 100 : 0;
        
        // Medal icons for top 3
        let medalIcon = '';
        if (index === 0) medalIcon = '🥇';
        else if (index === 1) medalIcon = '🥈';
        else if (index === 2) medalIcon = '🥉';

        const barItem = document.createElement('div');
        barItem.className = 'bar-item';
        barItem.innerHTML = `
            <div class="bar-label">${medalIcon} Wilayah ${item.wilayah_id}</div>
            <div class="bar-container">
                <div class="bar-fill" style="width:${percent}%">${hasil.toFixed(2)} Ha</div>
            </div>
            <div class="bar-value">${hasil.toFixed(2)} Ha</div>
        `;
        container.appendChild(barItem);
    });
    
    console.log('✅ Ranking rendered:', data.length, 'wilayah');
}

function showEmptyState(elementId, message) {
    const element = document.getElementById(elementId) || document.querySelector(`.${elementId}`);
    if (!element) return;
    
    element.innerHTML = `
        <div style="text-align:center; padding: 40px; color: #999;">
            <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i><br>
            <strong>${message}</strong>
        </div>
    `;
}

/* ===============================
   LOADING HELPERS
================================ */
function showLoading() {
    document.body.style.cursor = 'wait';
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.remove();
    
    const div = document.createElement('div');
    div.id = 'loadingOverlay';
    div.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.9); z-index: 9999; display: flex; align-items: center; justify-content: center;';
    div.innerHTML = `
        <div style="text-align: center;">
            <div style="border: 4px solid #f3f3f3; border-top: 4px solid var(--primary-color); border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
            <p style="color: var(--primary-color); font-weight: 600;">Memuat statistik...</p>
        </div>
        <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
    `;
    document.body.appendChild(div);
}

function hideLoading() {
    document.body.style.cursor = 'default';
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.remove();
}

/* ===============================
   CSV DATA LOADING
================================ */
async function loadCsvDataForStatistik() {
    try {
        const response = await fetch('/api/csv/data?per_page=100');
        const result = await response.json();
        
        if (!result.success || !result.data) {
            showEmptyState('csvDataTableBody', 'Tidak ada data CSV');
            return;
        }
        
        const tbody = document.getElementById('csvDataTableBody');
        let html = '';
        let no = 1;
        
        result.data.forEach(row => {
            const kategoriColor = getKategoriColorStatistik(row.kategori);
            html += `
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #666;">${no++}</td>
                    <td style="text-align: center; font-size: 13px;">${row.pg || '-'}</td>
                    <td style="text-align: center; font-size: 13px;">${row.fm || '-'}</td>
                    <td style="text-align: center; font-weight: 500; color: #128241;">${row.wilayah_id || '-'}</td>
                    <td style="text-align: left; font-size: 13px;">${row.seksi || '-'}</td>
                    <td style="text-align: right; font-size: 13px;">${row.neto ? parseFloat(row.neto).toFixed(2) : '-'}</td>
                    <td style="text-align: right; font-size: 13px;">${row.hasil ? parseFloat(row.hasil).toFixed(2) : '-'}</td>
                    <td style="text-align: center; font-size: 13px;">${row.umur ? parseInt(row.umur) : '-'}</td>
                    <td style="text-align: center; font-size: 12px; color: #666;">${row.tnm_sts || '-'}</td>
                    <td style="text-align: center; font-size: 12px;">${row.activitas || '-'}</td>
                    <td style="text-align: center;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; color: white; background-color: ${kategoriColor};">
                            ${row.kategori || 'N/A'}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 12px;">${row.tanggal ? new Date(row.tanggal).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' }) : '-'}</td>
                    <td style="text-align: right; font-size: 13px;">${row.total_tk ? parseInt(row.total_tk) : '-'}</td>
                    <td style="text-align: right; font-size: 12px;">${row.tk_ha ? parseFloat(row.tk_ha).toFixed(2) : '-'}</td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        console.log('✅ CSV data loaded:', result.data.length, 'records');
        
    } catch (error) {
        console.error('Error loading CSV data:', error);
        showEmptyState('csvDataTableBody', 'Gagal memuat data CSV');
    }
}

function getKategoriColorStatistik(kategori) {
    const colors = {
        'Bersih': '#3498db',
        'Ringan': '#27ae60',
        'Sedang': '#f39c12',
        'Berat': '#e74c3c',
        'Weeding': '#9b59b6'
    };
    return colors[kategori] || '#95a5a6';
}

function exportStatistikToCsv() {
    window.location.href = '/api/csv/export';
}

// Load CSV data after page load
window.addEventListener('load', function() {
    setTimeout(loadCsvDataForStatistik, 1000);
});

</script>

@endsection