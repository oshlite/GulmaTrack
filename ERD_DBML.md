// Use DBML to define your database structure
// Docs: https://dbml.dbdiagram.io/docs
// GulmaTrack Database Schema

Table users {
  id bigint [primary key]
  name varchar
  email varchar [unique]
  email_verified_at timestamp [null]
  password varchar
  role enum [note: "'guest', 'admin'"]
  is_active boolean [default: true]
  remember_token varchar [null]
  created_at timestamp
  updated_at timestamp
}

Table import_logs {
  id bigint [primary key]
  nama_file varchar
  wilayah_id varchar [note: "Comma-separated: '16,17,18'"]
  tahun integer [null]
  bulan integer [null]
  minggu integer [null]
  jumlah_records integer [default: 0]
  jumlah_berhasil integer [default: 0]
  jumlah_gagal integer [default: 0]
  status enum [note: "'pending', 'success', 'partial', 'failed'"]
  error_log text [null]
  user_id bigint [not null]
  created_at timestamp
  updated_at timestamp
}

Table wilayah {
  id bigint [primary key]
  wilayah_id integer [unique, note: "Range 16-23"]
  nama_wilayah varchar
  deskripsi text [null]
  created_at timestamp
  updated_at timestamp
}

Table data_gulma {
  id bigint [primary key]
  wilayah_id bigint [not null]
  id_feature varchar [note: "SEKSI dari CSV"]
  status_gulma enum [null, note: "'Bersih', 'Ringan', 'Sedang', 'Berat'"]
  persentase integer [null]
  tanggal date [not null]
  import_log_id bigint [null]
  pg varchar [null]
  fm varchar [null]
  seksi varchar [null, note: "Duplicate dari id_feature"]
  neto decimal(10,2) [null]
  hasil decimal(10,2) [null]
  umur_tanaman integer [null]
  penanggungjawab varchar [null]
  kode_aktf varchar [null]
  activitas varchar [null]
  kategori varchar [null, note: "PENTING untuk warna peta"]
  tk_ha decimal(10,2) [null]
  total_tk integer [null]
  created_at timestamp
  updated_at timestamp
}

Table map_publications {
  id bigint [primary key]
  status varchar [default: "'draft'", note: "'draft', 'published'"]
  published_at timestamp [null]
  published_by bigint [null]
  periode varchar [null]
  notes text [null]
  created_at timestamp
  updated_at timestamp
}

Table gulma_photos {
  id bigint [primary key]
  kategori enum [note: "'bersih', 'ringan', 'sedang', 'berat'"]
  foto_path varchar
  deskripsi text [null]
  uploaded_by bigint [not null]
  file_size bigint [null]
  mime_type varchar(100) [null]
  is_primary boolean [default: false]
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp [null, note: "Soft delete"]
}

Table personal_access_tokens {
  id bigint [primary key]
  tokenable_type varchar
  tokenable_id bigint
  name varchar
  token varchar [unique]
  abilities text [null]
  last_used_at timestamp [null]
  expires_at timestamp [null]
  created_at timestamp
  updated_at timestamp
}

Table drones {
  id bigint [primary key]
  judul varchar
  lokasi varchar
  tanggal_perencanaan date
  pdf_path varchar
  user_id bigint [null]
  created_at timestamp [null]
  updated_at timestamp [null]
  persen_gulma numeric [null]
}

// Relationships
Ref import_logs_user: import_logs.user_id > users.id [delete: cascade]

Ref data_gulma_wilayah: data_gulma.wilayah_id > wilayah.id [delete: cascade]

Ref data_gulma_import_log: data_gulma.import_log_id > import_logs.id [delete: set null]

Ref map_publications_user: map_publications.published_by > users.id [delete: set null]

Ref gulma_photos_user: gulma_photos.uploaded_by > users.id [delete: cascade]

Ref personal_access_tokens: personal_access_tokens.tokenable_id > users.id [delete: cascade]

Ref drones_user: drones.user_id > users.id [delete: set null]