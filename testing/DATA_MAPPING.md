# Data Mapping: GeoJSON + CSV

## Sumber Data

### 1. **GeoJSON** (Dari Shapefile - PETA)
**File**: `datala/Wil{X}.geojson`

Ini adalah data geografis dari shapefile yang menggambarkan wilayah/blok di peta. Setiap feature adalah satu bidang area.

**Properties di GeoJSON:**
- `Id` - ID unik dari shapefile
- `Lokasi` - Kode lokasi (contoh: "554A3") - **INI YANG DIGUNAKAN UNTUK MERGE**
- `Wilayah` - Nomor wilayah (contoh: 23)
- `Area` - Nama area (contoh: "PG3")
- `Bruto` - Ukuran area bruto dalam hektar (contoh: "16.79")
- `Netto` - Ukuran area netto dalam hektar (contoh: "13.20")
- `Status` - Status lahan (contoh: "Nanas")

**Juga memiliki:**
- `geometry` - Koordinat polygon (garis tepi area)
- `coordinates` - Dikonversi dari UTM ke WGS84 (latitude/longitude)

---

### 2. **CSV Data** (Dari Upload/Import - STATUS GULMA)
**Disimpan di**: `DataGulma` table di database

Ini adalah data status gulma (kesadaran tanaman) yang di-upload dari CSV setiap periode.

**Fields dari CSV:**
- `seksi` - Kode seksi (contoh: "554A3") - **INI YANG DICOCOKKAN DENGAN GeoJSON Lokasi**
- `pg` - Nomor PG
- `fm` - Nomor FM
- `neto` - Netto (nilai panen bersih)
- `hasil` - Hasil panen
- `umur` - Umur tanaman
- `tnm_sts` - Status tanaman
- `activitas` - Aktivitas
- `penanggungjawab` - Penanggung jawab
- `kode_aktf` - Kode aktivitas
- **`kategori`** - **KATEGORI GULMA (Bersih/Ringan/Sedang/Berat)** ⭐ INI YANG DITAMPILKAN
- `tk_ha` - Tingkat kesadaran per hektar
- `total_tk` - Total tingkat kesadaran
- `tanggal` - Tanggal data

---

## Proses Merge

### Flow:
```
GeoJSON Features (112 features untuk Wil23)
         ↓
    Baca semua CSV records
         ↓
    Cocokkan by Lokasi (GeoJSON) == seksi (CSV)
    Normalisasi: lowercase + trim
         ↓
    Jika match → Tambahkan CSV data ke properties
    Jika tidak → Properties kosong
         ↓
    Return GeoJSON dengan CSV data merged
```

### Contoh Merge untuk 1 Feature:

**GeoJSON Feature Original:**
```json
{
  "properties": {
    "Id": 0,
    "Lokasi": "554A3",
    "Wilayah": 23,
    "Area": "PG3",
    "Bruto": "16.79",
    "Netto": "13.20",
    "Status": "Nanas"
  },
  "geometry": { ... }
}
```

**CSV Record yang Match (seksi = "554A3"):**
```
seksi: 554A3
pg: 1
fm: 5
neto: 50
hasil: Good
umur: 2
tnm_sts: Normal
activitas: Aktif
penanggungjawab: Budi
kode_aktf: 1
kategori: Ringan    ← INI PENTING!
tk_ha: 15
total_tk: 100
tanggal: 2026-01-20
```

**Hasil Setelah Merge:**
```json
{
  "properties": {
    "Id": 0,
    "Lokasi": "554A3",
    "Wilayah": 23,
    "Area": "PG3",
    "Bruto": "16.79",
    "Netto": "13.20",
    "Status": "Nanas",
    
    // ← DITAMBAHKAN DARI CSV:
    "seksi": "554A3",
    "pg": "1",
    "fm": "5",
    "neto": "50",
    "hasil": "Good",
    "umur": "2",
    "tnm_sts": "Normal",
    "activitas": "Aktif",
    "penanggungjawab": "Budi",
    "kode_aktf": "1",
    "kategori": "Ringan",  ← INILAH YANG DITAMPILKAN DI MAP
    "tk_ha": "15",
    "total_tk": "100",
    "tanggal": "2026-01-20"
  },
  "geometry": { ... }
}
```

---

## Data Usage

### Di Frontend (Map Display):
- **Warna**: Berdasarkan `kategori` (Bersih=Hijau, Ringan=Kuning, Sedang=Orange, Berat=Merah)
- **Popup Info**: Menampilkan CSV data (seksi, kategori, tk_ha, tanggal, dll)
- **Geometry**: GeoJSON polygon untuk menggambar area di peta

### Di Card/Stats:
- **Hitung jumlah**: Berapa banyak features dengan kategori = "Bersih", "Ringan", dll
- **Total TK**: Sum dari `total_tk` CSV records
- **Total Neto**: Sum dari `neto` CSV records

---

## Status Percocokkan (Matching)

### Wilayah 23, Period 2031/1/1:
- **GeoJSON Features**: 112 features (dari shapefile)
- **CSV Records**: Beberapa records
- **Matched**: 11 records yang berhasil dicocokkan
- **Unmatched**: Records yang seksi-nya tidak ada di GeoJSON

### Kategori Distribution (yang ditampilkan di map):
```
Bersih:  4 features
Ringan:  7 features
Sedang:  0 features
Berat:   0 features
Total:   11 features (ter-merge)
```

---

## Kesimpulan

| Aspek | GeoJSON (Shapefile) | CSV (Upload) |
|-------|-------------------|------------|
| **Sumber** | Shapefile peta | Upload CSV setiap periode |
| **Apa isinya** | Batas area fisik di peta | Status kesehatan tanaman |
| **Key field** | `Lokasi` | `seksi` |
| **Jumlah data** | Fixed (112 untuk Wil23) | Bervariasi per upload |
| **Merge key** | Lokasi (normalized) = seksi (normalized) | |
| **Output** | GeoJSON dengan CSV data merged | Ditampilkan di map & stats |

**Yang ditampilkan di map**: GeoJSON geometry + CSV kategori (Bersih/Ringan/Sedang/Berat)
