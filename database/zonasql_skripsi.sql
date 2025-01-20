-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 27, 2024 at 05:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zonasql_skripsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `babs`
--

CREATE TABLE `babs` (
  `id` bigint UNSIGNED NOT NULL,
  `materi_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `babs`
--

INSERT INTO `babs` (`id`, `materi_id`, `judul`, `slug`, `isi`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Bab Materi DDL 1', 'bab-materi-ddl-1', '&emsp; **Basis Data** atau **Database** merupakan media utama yang harus tersedia untuk membangun sebuah basis data sehingga langkah paling awal adalah membuat basis data. Basis data difungsikan untuk menyimpan tabel beserta data di dalam tabel, untuk membuat basis data digunakan perintah sebagai berikut:\r\n~~~SQL:Membuat_Basis_Data\r\nCREATE DATABASE Nama_Basis_Data;\r\n~~~\r\nTerdapat beberapa aturan dalam pemberian nama pada basis data seperti, menggunakan nama yang deskriptif dan merepresentasikan isi dari basis data, nama basis data bersifat unik dan berbeda dengan nama basis data lainnya, menggunakan huruf kapital untuk memisahkan setiap kata. Berikut adalah pemberian nama pada basis data sesuai dengan aturan, sebagai berikut:\r\n ~~~SQL:Contoh\r\nCREATE DATABASE SMA_Negeri_1_Sumberrejo;\r\nQuery OK, 1 row affected (0.125 sec)\r\n~~~\r\nContoh di atas menampilkan perintah yang diberikan berhasil dieksekusi dengan ditampilkan pesan setelah baris perintah yang memuat informasi keberhasilan perintah, jumlah baris yang terpengaruh, dan waktu eksekusi perintah.\r\n~~~SQL:Contoh\r\nCREATE DATABASE SMA Negeri 1 Sumberrejo;\r\nERROR: Could not create database \'SMA Negeri 1 Sumberrejo\'. \r\nDatabase names cannot contain spaces.\r\n~~~\r\nContoh di atas menampilkan perintah yang diberikan gagal dieksekusi dengan ditampilkan pesan setelah baris perintah yang memuat informasi penyebab kegagalan dalam mengeksekusi perintah yang diberikan, dalam contoh tersebut gagal mengeksekusi perintah membuat basis data yang disebabkan nama basis data yang diberikan mengandung karakter spasi.', '2024-02-04 03:55:17', '2024-02-04 04:28:26', 0),
(2, 1, 'Bab Materi DDL 2', 'bab-materi-ddl-2', '&emsp; **Database Management System** atau **Sistem Manajemen Basis Data** pada umumnya dapat menyimpan lebih dari satu basis data dengan berbagai keperluan, untuk menampilkan basis data yang tersimpan di dalam sistem manajemen basis data dapat menggunakan perintah sebagai berikut:\r\n~~~SQL:Menampilkan_Basis_Data\r\nSHOW DATABASES;\r\n~~~\r\nKetika perintah tersebut dieksekusi, sistem manajemen basis data akan mengembalikan daftar basis data yang tersedia di dalam _environment_ yang sedang digunakan, hasil yang ditampilkan ketika perintah berhasil dieksekusi adalah sebagai berikut:\r\n~~~SQL:Contoh\r\nSHOW DATABASES;\r\n+--------------------+\r\n| Database           |\r\n+--------------------+\r\n| Database1          |\r\n| Database2          |\r\n| Database3          |\r\n| Database4          |\r\n+--------------------+\r\n4 rows in set (0.00 sec)\r\n~~~\r\nTerdapat beberapa informasi yang dapat diperoleh ketika menjalankan perintah untuk menampilkan daftar basis data adalah seperti, nama basis data, pemeriksaan ketersediaan basis data, serta menunjang proses administrasi dari basis data.', '2024-02-04 03:56:01', '2024-02-04 03:56:01', 0),
(3, 2, 'Bab Materi DML 1', 'bab-materi-dml-1', '&emsp; **Entry Data** atau memasukkan data dengan menggunakan kata kunci ``INSERT`` sebagai perintah standar dalam program yang menggunakan bahasa SQL untuk berinteraksi dengan basis data. Syarat untuk melakukan memasukkan data  ke dalam basis data adalah harus tersedia tabel di dalam basis data tersebut, untuk memasukkan data ke dalam tabel pada basis data menggunakan perintah sebagai berikut:\r\n~~~SQL:Perintah Memasukkan Data Ke Tabel\r\nINSERT INTO Nama_Tabel (Kolom1, ..., KolomN)\r\nVALUES (Nilai1, ..., NilaiN);\r\n~~~\r\n\r\nPerintah di atas merupakan perintah umum yang umum digunakan untuk memasukkan data ke tabel di dalam basis data, selain perintah di atas dapat juga menggunakan perintah dengan bentuk yang lebih singkat dengan langsung mencantumkan nilai setelah kata kunci ``VALUES`` yang dibungkus dengan tanda kurung seperti perintah berikut:\r\n~~~SQL: Perintah Singkat Memasukkan Data Ke Tabel\r\nINSERT INTO Nama_Tabel\r\nVALUES (Nilai1, ..., NilaiN);\r\n~~~\r\n\r\nPerintah untuk memasukkan data ke tabel juga dapat dilakukan secara bersama dengan memisahkan nilai tiap baris ke dalam tanda kurung dan di pisahkan oleh tanda koma seperti pada perintah berikut:\r\n~~~SQL:Perintah Memasukkan Lebih Dari Satu Data Ke Tabel \r\nINSERT INTO Nama_Tabel VALUES\r\n(Nilai1, ..., NilaiN), -- Baris 1\r\n(Nilai1, ..., NilaiN), -- Baris 2\r\n...\r\n(Nilai1, ..., NilaiN); -- Baris N\r\n~~~\r\n\r\nContoh menambahkan beberapa data ke tabel di dalam basis data dengan nama tabel ``Karyawan`` yang memiliki susunan kolom ``Nama_Depan``, ``Nama_Belakang``, ``Jabatan``, ``Gaji``, dapat dilakukan sebagai berikut:\r\n~~~SQL:Contoh\r\nINSERT INTO Karyawan\r\n(Nama_Depan, Nama_Belakang, Jabatan, Gaji)\r\nVALUES\r\n(\'John\', \'Doe\', \'Personalia\', 8000000),\r\n(\'Jane\', \'Doe\', \'Bendahara\', 6000000);\r\n~~~\r\nContoh di atas menunjukkan beberapa data berhasil dimasukkan ke tabel ``Karyawan`` dengan menampilkan pesan ``Query OK`` yang menujukan bahwa query berhasil di eksekusi, ``3 rows affected`` yang menujukan bahwa tiga baris terpengaruh dari perintah yang di eksekusi dan ``Records: 3`` yang menunjukkan tiga data berhasil di tambahkan.', '2024-02-04 03:57:17', '2024-02-04 03:57:41', 0),
(4, 2, 'Bab Materi DML 2', 'bab-materi-dml-2', '&emsp; **Sistem Manajemen Basis Data** mendukung perintah untuk menampilkan data-data yang tersimpan di basis data yang dapat dilakukan dengan menggunakan perintah sebagai berikut:\r\n~~~SQL:Perintah Menampilkan Data Pada Tabel\r\nSELECT * FROM Nama_Tabel;\r\n~~~\r\n\r\nPerintah di atas digunakan untuk menampilkan semua data atau *records* dari kolom-kolom yang tersimpan dalam sebuah tabel, tanda asterisk (*) dalam pada perintah di atas digunakan untuk mengambil semua kolom atau *fields* dari tabel yang disebutkan dalam query. Perintah di atas ketika berhasil di eksekusi akan mengembalikan semua kolom dan baris yang ada pada tabel yang disebutkan, untuk mempermudah memahami diberikan contoh sebagai berikut:\r\n~~~SQL:Contoh\r\nSELECT * FROM Karyawan;\r\n+---+-----------+---------------+-----------+--------+\r\n|Id	|Nama_Depan	|Nama_Belakang	|Jabatan    |Gaji	   |\r\n+---+-----------+---------------+-----------+--------+\r\n|1  |\'John\'		  |\'Doe\'			    |Personalia |8000000 |\r\n|2  |\'Jane\'		  |\'Doe\'			    |Bendahara 	|6000000 |\r\n+---+-----------+---------------+-----------+--------+\r\n2 rows in set (0.001 sec)\r\n~~~\r\n\r\nPerintah pada contoh di atas menunjukkan perintah berhasil di eksekusi dan mengembalikan kolom serta baris dari tabel ``Karyawan``, perintah tersebut juga mengembalikan pesan hasil query yaitu, ``2 rows in set`` yang menunjukkan jumlah baris atau rows yang berhasil diambil oleh query dari tabel ``Karyawan``.\r\nJika sebelumnya perintah dapat digunakan untuk menampilkan data dari semua kolom pada suatu tabel, perintah tersebut juga dapat digunakan untuk menampilkan data dari kolom-kolom tertentu pada suatu tabel dengan menggunakan perintah sebagai berikut:\r\n~~~SQL:Menampilkan Data Pada Tabel Dengan Kolom Tertentu\r\nSELECT Kolom1, ..., KolomN FROM Nama_Tabel;\r\n~~~\r\n\r\nDengan mengganti tanda asterisk (*) nama kolom yang dikehendaki secara otomatis query akan mengembalikan data dari kolom sesuai dengan nama kolom yang disebutkan dalam perintah, untuk mempermudah dalam memahami diberikan contoh sebagai berikut:\r\n~~~SQL:Contoh\r\nSELECT Nama_Depan, Jabatan FROM Karyawan;\r\n+-----------+-----------+\r\n|Nama_Depan	|Jabatan 	  |\r\n+-----------+-----------+\r\n|\'John\'		  |Personalia |\r\n|\'Jane\'		  |Bendahara  |\r\n+-----------+-----------+\r\n2 rows in set (0.001 sec)\r\n~~~\r\nContoh tersebut adalah perintah yang berhasil di eksekusi untuk menampilkan data dari kolom ``Nama_Depan`` dan ``Jabatan`` dari tabel ``Karyawan``.', '2024-02-04 03:58:30', '2024-02-04 04:00:03', 0),
(5, 2, 'Bab Materi DML 3', 'bab-materi-dml-3', '&emsp; **Sistem Manajemen Basis Data** mendukung pengambilan data dengan kondisi tertentu dari suatu tabel, dengan menambahkan kata kunci ``WHERE`` setelah nama tabel yang dikehendaki, perintah yang digunakan adalah sebagai berikut:\r\n~~~SQL:Perintah Menampilkan Data Dengan Kondisi\r\nSELECT * FROM Nama_Tabel WHERE Kondisi;\r\n~~~\r\n\r\nSetelah kata kunci ``WHERE`` di ikuti dengan kondisi tertentu sehingga query akan mengembalikan data sesuai dengan kondisi yang terpenuhi dari kondisi yang dijalankan, diberikan contoh untuk lebih mudah memahami, adalah sebagai berikut:\r\n~~~SQL:Contoh\r\nSELECT * FROM Karyawan WHERE Nama_Depan = \'John\';\r\n+---+-----------+---------------+-----------+--------+\r\n|Id	|Nama_Depan	|Nama_Belakang	|Jabatan    |Gaji	   |\r\n+---+-----------+---------------+-----------+--------+\r\n|1  |\'John\'		  |\'Doe\'			    |Personalia |8000000 |\r\n+---+-----------+---------------+-----------+--------+\r\n1 rows in set (0.001 sec) \r\n~~~\r\nContoh di atas adalah perintah untuk mengambil dan mengembalikan data dengan kondisi kolom ``Nama_Depan`` dengan kolom yang memiliki nilai ``‘John’``, perintah di atas berhasil di eksekusi dan mengembalikan data sesuai dengan kondisi yang diberikan dalam query.', '2024-02-04 03:59:04', '2024-02-04 03:59:04', 0),
(6, 3, 'Bab Materi Eloquent 1', 'bab-materi-eloquent-1', '&emsp; Perintah query pada SQL mendukung pengurutan data berdasarkan kolom atau fields tertentu dengan tipe data yang dimiliki pada suatu tabel, pengurutan dapat dilakukan secara naik atau *ascending* maupun secara menurun atau *descending*, untuk melakukan pengurutan dapat menggunakan perintah sebagai berikut:\r\n~~~SQL:Perintah Mengurutkan Data\r\nSELECT * FROM Nama_Tabel ORDER BY Nama_Kolom;\r\n~~~\r\n\r\nPerintah di atas secara default mengurutkan data secara naik, dari nilai terkecil hingga terbesar atau dari huruf a hingga z namun, jika diperlukan dapat diurutkan secara menurut dengan menggunakan perintah sebagai berikut:\r\n~~~SQL:Perintah Mengurutkan Data Menurun\r\nSELECT * FROM Nama_Tabel ORDER BY Nama_Kolom DESC;\r\n~~~\r\n\r\nDengan menambahkan data kunci ``DESC`` setalah nama kolom, perintah yang dijalankan akan mengembalikan data dengan urutan secara menurun, untuk lebih mudah memahami pengurutan data diberikan contoh sebagai berikut:\r\n~~~SQL:Contoh\r\nSELECT * FROM Karyawan WHERE Nama_Depan = \'John\';\r\n+---+-----------+---------------+-----------+--------+\r\n|Id	|Nama_Depan	|Nama_Belakang	|Jabatan    |Gaji	   |\r\n+---+-----------+---------------+-----------+--------+\r\n|4  |\'Dandy\'	  |\'Waluyo\'		    |HRD		    |4500000 |\r\n|3  |\'Cita\'	    |\'Putri\'		    |CFO		    |5000000 |\r\n|1  |\'Andi\'		  |\'Purwatan\'		  |CTO		    |6000000 |\r\n|5  |\'Emil\'		  |\'Danitama\'		  |Co-CEO		  |7000000 |\r\n|2  |\'Budi\'		  |\'Hartanto\'		  |CEO 		    |7500000 |\r\n+---+-----------+---------------+-----------+--------+\r\n5 rows in set (0.001 sec) \r\n~~~\r\nContoh di atas adalah perintah mengurutkan data pada tabel ``Karyawan`` berdasarkan kolom ``Gaji`` yang secara *default* mengurutkan baris mulai dari gaji terendah hingga gaji tertinggi .', '2024-02-04 04:00:48', '2024-02-04 04:00:48', 0),
(7, 3, 'Bab Materi Eloquent 2', 'bab-materi-eloquent-2', '&emsp; Perintah memberikan hak akses digunakan untuk memberikan hak akses kepada pengguna tertentu yang ada di dalam sistem basis data, dengan menggunakan perintah tersebut pengguna tertentu dalam sistem basis data dapat diberikan hak akses oleh administrator untuk menjalankan operasi tertentu pada tabel atau objek dalam sistem basis data, seperti ``SELECT``, ``INSERT``, ``UPDATE``, ``DELETE``, perintah yang digunakan untuk memberikan hak akses adalah sebagai berikut:\r\n\r\n~~~SQL:Perintah_Memberikan_Hak_Akses\r\nGRANT Operasi ON Nama_Tabel TO Nama_Pengguna;\r\n~~~\r\nKata kunci ``GRANT`` digunakan untuk memberikan hak akses dengan operasi tertentu pada tabel yang dipilih dan pada pengguna yang diberikan hak akses, untuk mempermudah dalam memahami pengguna perintah memberikan hak akses diberikan contoh sebagai berikut:\r\n\r\n~~~SQL:Contoh\r\nGRANT SELECT, INSERT, UPDATE, DELETE \r\nON Karyawan TO HumamResource_1;\r\n~~~\r\n\r\nContoh di atas merupakan perintah untuk memberikan hak akses kepada pengguna dengan nama pengguna atau *username* ``HumanResource_1``, dengan hak akses untuk menjalankan operasi ``SELECT``, ``INSERT``, ``UPDATE``, ``DELETE`` terhadap tabel ``Karyawan``.', '2024-02-04 04:01:40', '2024-02-04 04:01:40', 0),
(8, 3, 'Bab Materi Eloquent 3', 'bab-materi-eloquent-3', '&emsp; Perintah mencabut hak akses merupakan kebalikan dari perintah untuk memberikan hak akses kepada pengguna, hak yang sudah diberikan kepada pengguna dapat dicabut oleh administrator dari sistem basis data sehingga, pengguna yang telah dicabut hak aksesnya tidak dapat melakukan operasi yang sebelumnya diizinkan, untuk menjalankan perintah mencabut hak akses adalah sebabagai berikut:\r\n\r\n~~~SQL:Perintah_Mencabut_Hak_Akses\r\nREVOKE Operasi ON Nama_Tabel TO Nama_Pengguna;\r\n~~~\r\n\r\nUntuk mencabut hak akses digunakan kata kunci ``REVOKE`` pada awal perintah yang akan dijalankan, untuk mempermudah dalam memahami cara menggunakan perintah untuk mencabut hak akses dari pengguna diberikan contoh sebagai berikut:\r\n\r\n~~~SQL:Contoh\r\nREVOKE SELECT, INSERT, UPDATE, DELETE\r\nON Karyawan TO Staff_HR_1;\r\n~~~\r\n\r\nContoh yang diberikan di atas adalah untuk mencabut hak akses pada operasi ``SELECT``, ``INSERT``, ``UPDATE``, ``DELETE``yang sebelumnya diberikan pada pengguna dengan nama ``Staff_HR_1`` yang dapat melakukan operasi terhadap tabel ``Karyawan`` dalam sistem basis data.', '2024-02-04 04:02:14', '2024-02-04 04:02:14', 0),
(9, 3, 'Bab Materi Eloquent 4', 'bab-materi-eloquent-4', '&emsp; Fungsi agregasi ``COUNT()`` adalah fungsi yang digunakan untuk menghitung jumlah nilai dari kolom pada suatu tabel, fungsi ``COUNT()`` dapat digunakan terhadap tipe data *numeric* atau *string*, untuk menjalankan fungsi ``COUNT()`` dapat menggunakan perintah sebagai berikut:\r\n\r\n~~~SQL:Perintah_Dengan_Fungsi_COUNT()\r\nSELECT COUNT(Nama_Kolom) AS \'Nama_Kolom_Baru\' FROM Nama_Tabel;\r\n~~~\r\n\r\nFungsi tersebut akan menghitung jumlah baris pada kolom suatu tabel dan mengembalikan berupa nilai dalam bentuk *numeric* atau angka, untuk dapat lebih mudah memahami perintah dengan menggunakan fungsi ``COUNT()`` diberikan contoh sebagai berikut:\r\n\r\n~~~SQL:Contoh\r\n-- Tabel Pegawai\r\n+----+------------------+-------------+\r\n|Id	 |Nama				     |Id_Departemen	|\r\n+----+------------------+-------------+\r\n|	  1|Antonius Franz	 |102			      |\r\n|	  2|Budi Hartanto		 |102			      |\r\n|	  3|Clarisa Nadia		 |102			      |\r\n|	  4|Dandy Waranto		 |100			      |\r\n|	  5|Edo Gunawan		   |100			      |\r\n+----+------------------+-------------+\r\n\r\n-- Perintah Dengan Fungsi COUNT()\r\nSELECT COUNT(Id) AS \'Banyak_Pegawai\' FROM Pegawai\r\nWHERE Id_Departemen = 102;\r\n~~~\r\n\r\nPerintah di atas merupakan contoh perintah dengan menggunakan fungsi ``COUNT()`` yang digunakan untuk menghitung banyaknya pegawai dari tabel ``Pegawai``, ``SELECT COUNT(Id)`` menyatakan perintah untuk mengambil data pegawai dan menghitung banyaknya pegawai dengan menggunakan kolom ``Id``, ``AS \'Banyak_Pegawai\'`` menyatakan perintah untuk mengembalikan tabel dengan nama kolom ``Banyak_Pegawai``,``FROM Pegawai`` menyatakan perintah mengambil data dari tabel ``Pegawai``, dan ``WHERE Id_Departemen = 102`` mennyatakan perintah untuk menyaring data pegawai yang bekerja pada departemen dengan ``Id_Departemen`` adalah **102**.\r\n\r\n~~~SQL:Hasil_Dari_Perintah_Dengan_Fungsi_COUNT()\r\n+----------------+\r\n|Banyak_Pegawai  |\r\n+----------------+\r\n|3               |\r\n+----------------+\r\n~~~', '2024-02-04 04:03:28', '2024-02-04 04:03:28', 0),
(10, 4, 'Bab Materi API 1', 'bab-materi-api-1', '&emsp; Fungsi ``MIN()`` adalah singkatan dari *Minimum* yang sesuai dengan penamaannya fungsi tersebut digunakan untuk mengembalikan nilai terkecil dari sekumpulan nilai pada suatu kolom dalam tabel, nilai tersebut diperoleh dari suatu kolom dengan nilai dalam kolom tersebut adalah *numeric* dan akan mengembalikan nilai dalam bentuk yang sama, yaitu *numeric*, untuk menggunakan perintah dengan fungsi ``MIN()`` dapat menggunakan perintah sebagai berikut:\r\n\r\n~~~SQL:Perintah_Dengan_Fungsi_MIN()\r\nSELECT MIN(Nama_Kolom) AS \'Nama_Kolom_Baru\' FROM Nama_Tabel;\r\n~~~\r\n\r\nUntuk dapat lebih mudah memahami penggunaan perintah dengan fungsi ``MIN()`` dalam **Structured Query Language** diberikan contoh sebagai berikut:\r\n\r\n~~~SQL:Contoh\r\n-- Tabel Pegawai\r\n+----------------+---------------+\r\n|Nama_Pegawai    |Usia_Pegawai   |\r\n+----------------+---------------+\r\n|Antonius Franz  |28             |\r\n|Budi Hartanto   |29             |\r\n|Clarisa Nadia   |27             |\r\n|Dedi Anwar      |29             |\r\n+----------------+---------------+\r\n\r\n-- Perintah Dengan Fungsi MIN()\r\nSELECT MIN(Usia_Pegawai) AS \'Usia_Temuda\' FROM Pegawai;\r\n~~~\r\n\r\nContoh di atas adalah perintah dengan menggunakan fungsi ``MIN()`` yang digunakan untuk mengambil data pegawai dengan usia termuda, ``SELECT MIN(Usia_Pegawai)`` menyatakan perintah untuk mengambil data pegawai dengan usia termuda atau nilai terkecil dari kolom ``Usia_Pegawai``, ``AS \'Usia_Termuda\'`` menyatakan perintah untuk mengembalikan nilai dengan nama kolom ``Usia_Termuda``, dan ``FROM Pegawai`` menyatakan perintah untuk mengambil data dari tabel ``Pegawai``.\r\n\r\n~~~SQL:Hasil_Perintah_Dengan_Fungsi_MIN()\r\n+----------------+\r\n|Usia_Temuda     |\r\n+----------------+\r\n|27              |\r\n+----------------+\r\n~~~', '2024-02-04 04:04:05', '2024-02-04 04:04:05', 0),
(11, 4, 'Bab Materi API 2', 'bab-materi-api-2', '&emsp; Fungsi ``MAX()`` atau kependekan dari kata *Maximum* merupakan fungsi yang kebalikan dengan fungsi ``MIN()``, fungsi ``MAX()`` digunakan untuk mengambil nilai terbesar dari sekumpulan nilai dari suatu kolom pada sebuah tabel, ilai tersebut diperoleh dari suatu kolom dengan nilai dalam kolom tersebut adalah *numeric* dan akan mengembalikan nilai dalam bentuk yang sama, yaitu *numeric*, untuk menggunakan perintah dengan fungsi ``MIN()`` dapat menggunakan perintah sebagai berikut:\r\n\r\n~~~SQL:Perintah_Dengan_Fungsi_MAX()\r\nSELECT MAX(Nama_Kolom) AS \'Nama_Kolom_Baru\';\r\n~~~\r\n\r\nUntuk dapat lebih mudah memahami penggunaan perintah dengan fungsi ``MIN()`` dalam **Structured Query Language** diberikan contoh sebagai berikut:\r\n\r\n~~~SQL:Contoh\r\n-- Tabel Pegawai\r\n+----------------+---------------+\r\n|Nama_Pegawai    |Usia_Pegawai   |\r\n+----------------+---------------+\r\n|Antonius Franz  |28             |\r\n|Budi Hartanto   |29             |\r\n|Clarisa Nadia   |27             |\r\n|Dedi Anwar      |29             |\r\n+----------------+---------------+\r\n\r\n-- Perintah Dengan Fungsi MAX()\r\nSELECT MAX(Usia_Pegawai) AS \'Usia_Tertua\' FROM Pegawai;\r\n~~~\r\n\r\nContoh di atas adalah perintah dengan menggunakan fungsi ``MAX()`` yang digunakan untuk mengambil data pegawai dengan usia tertua, ``SELECT MAX(Usia_Pegawai)`` menyatakan perintah untuk mengambil data pegawai dengan usia tertua atau nilai terbesar dari kolom ``Usia_Pegawai``, ``AS \'Usia_Termuda\'`` menyatakan perintah untuk mengembalikan nilai dengan nama kolom ``Usia_Tertua``, dan ``FROM Pegawai`` menyatakan perintah untuk mengambil data dari tabel ``Pegawai``.\r\n\r\n~~~SQL:Hasil_Perintah_Dengan_Fungsi_MAX()\r\n+----------------+\r\n|Usia_Tertua     |\r\n+----------------+\r\n|29              |\r\n+----------------+\r\n~~~', '2024-02-04 04:04:51', '2024-02-04 04:04:51', 0),
(12, 5, 'Pre Test', 'pre-test', 'Ini adalah pre test sebelum menggunakan web edukasi teknologi ini', '2024-02-10 03:25:17', '2024-02-10 03:25:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `babs_attempt`
--

CREATE TABLE `babs_attempt` (
  `id` bigint UNSIGNED NOT NULL,
  `bab_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `babs_attempt`
--

INSERT INTO `babs_attempt` (`id`, `bab_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 3, 1, NULL, NULL),
(2, 7, 3, 1, NULL, NULL),
(3, 8, 3, 1, NULL, NULL),
(4, 9, 3, 1, NULL, NULL),
(7, 1, 3, 1, NULL, NULL),
(8, 2, 3, 1, NULL, NULL),
(12, 6, 2, 1, NULL, NULL),
(13, 7, 2, 1, NULL, NULL),
(14, 8, 2, 1, NULL, NULL),
(15, 1, 2, 1, NULL, NULL),
(17, 9, 2, 1, '2024-02-25 07:24:24', '2024-02-25 07:24:24'),
(18, 2, 2, 1, '2024-02-26 05:46:56', '2024-02-26 05:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jawabans`
--

CREATE TABLE `jawabans` (
  `id` bigint UNSIGNED NOT NULL,
  `soal_id` bigint UNSIGNED NOT NULL,
  `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jawabans`
--

INSERT INTO `jawabans` (`id`, `soal_id`, `answer`, `created_at`, `updated_at`) VALUES
(1, 1, 'jawaban dml 1', NULL, NULL),
(2, 2, 'jawaban dml 2', NULL, NULL),
(3, 3, 'jawaban dml 3', NULL, NULL),
(4, 4, 'jawaban ddl 1', NULL, NULL),
(5, 5, 'jawaban ddl 2', NULL, NULL),
(6, 6, 'jawaban eloquent 1', NULL, NULL),
(7, 7, 'jawaban eloquent 2', NULL, NULL),
(8, 8, 'jawaban eloquent 3', NULL, NULL),
(9, 9, 'jawaban eloquent 4', NULL, NULL),
(10, 10, 'jawaban api 2', NULL, NULL),
(11, 11, 'jawaban api 3', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `latihans`
--

CREATE TABLE `latihans` (
  `id` bigint UNSIGNED NOT NULL,
  `materi_id` bigint UNSIGNED NOT NULL,
  `judulLatihan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `latihans`
--

INSERT INTO `latihans` (`id`, `materi_id`, `judulLatihan`, `slug`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Latihan DDL 1', 'latihan-ddl-1', '2024-02-04 04:18:14', '2024-02-04 04:18:14', 0),
(2, 2, 'Latihan DML 2', 'latihan-dml-2', '2024-02-04 04:19:18', '2024-02-04 04:32:43', 0),
(3, 2, 'Latihan DML 1', 'latihan-dml-1', '2024-02-04 04:19:48', '2024-02-04 04:19:48', 0),
(4, 3, 'Latihan Eloquent Pertama', 'latihan-eloquent-pertama', '2024-02-04 04:33:16', '2024-02-04 04:33:34', 0),
(5, 3, 'Latihan Eloquent Kedua', 'latihan-eloquent-kedua', '2024-02-04 04:34:10', '2024-02-04 04:34:10', 0),
(6, 3, 'Latihan Eloquent Ketiga', 'latihan-eloquent-ketiga', '2024-02-04 04:37:17', '2024-02-04 04:37:17', 0),
(7, 3, 'Latihan Eloquent Keempat', 'latihan-eloquent-keempat', '2024-02-04 04:37:50', '2024-02-04 04:37:50', 0),
(8, 4, 'Latihan API Pertama', 'latihan-api-pertama', '2024-02-04 04:38:14', '2024-02-04 04:38:14', 0),
(9, 4, 'Latihan API Kedua', 'latihan-api-kedua', '2024-02-04 04:38:45', '2024-02-04 04:38:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `latihans_answer`
--

CREATE TABLE `latihans_answer` (
  `id` bigint UNSIGNED NOT NULL,
  `latihan_attempt_id` bigint UNSIGNED NOT NULL,
  `soal_id` bigint UNSIGNED NOT NULL,
  `typed_answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `latihans_answer`
--

INSERT INTO `latihans_answer` (`id`, `latihan_attempt_id`, `soal_id`, `typed_answer`, `created_at`, `updated_at`) VALUES
(36, 47, 6, 'Jawaban Eloquent 1', '2024-02-06 10:36:18', NULL),
(37, 47, 9, 'Jawaban Eloquent 1', '2024-02-06 10:36:18', NULL),
(38, 48, 6, 'Jawaban Eloquent 1', '2024-02-06 10:36:45', NULL),
(39, 48, 9, 'Jawaban Eloquent 4', '2024-02-06 10:36:45', NULL),
(40, 49, 6, 'tes kedua', '2024-02-06 14:08:15', NULL),
(41, 50, 6, 'Jawbaan', '2024-02-06 14:38:35', NULL),
(42, 51, 6, 'Jawaban Eloquent 1', '2024-02-06 14:38:52', NULL),
(49, 55, 4, 'dsfs', '2024-02-07 04:29:38', NULL),
(50, 55, 5, 'dfdfa', '2024-02-07 04:29:38', NULL),
(51, 55, 1, 'dsfsfs', '2024-02-07 04:29:38', NULL),
(52, 55, 2, 'fsgfs', '2024-02-07 04:29:38', NULL),
(53, 55, 3, 'ffdgf', '2024-02-07 04:29:38', NULL),
(54, 56, 4, 'Jawaban DDL 1', '2024-02-07 04:30:14', NULL),
(55, 56, 5, 'Jawaban DDL 2', '2024-02-07 04:30:14', NULL),
(56, 56, 1, 'Jawaban DML 1', '2024-02-07 04:30:14', NULL),
(57, 56, 2, 'Jawaban DML 2', '2024-02-07 04:30:14', NULL),
(58, 56, 3, 'Jawaban DML 3', '2024-02-07 04:30:14', NULL),
(62, 60, 6, 'jawaban eloquent 1', '2024-02-25 07:55:20', NULL),
(63, 60, 9, 'jawaban eloquent 4', '2024-02-25 07:55:20', NULL),
(64, 61, 6, 'jawaban eloquent 1', '2024-02-26 05:48:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `latihans_attempt`
--

CREATE TABLE `latihans_attempt` (
  `id` bigint UNSIGNED NOT NULL,
  `latihan_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `latihans_attempt`
--

INSERT INTO `latihans_attempt` (`id`, `latihan_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(47, 4, 3, 1, NULL, '2024-02-06 10:36:18'),
(48, 4, 3, 2, NULL, '2024-02-06 10:36:45'),
(49, 5, 3, 1, NULL, '2024-02-06 14:08:15'),
(50, 5, 3, 1, NULL, '2024-02-06 14:38:35'),
(51, 5, 3, 2, NULL, '2024-02-06 14:38:52'),
(55, 1, 3, 1, NULL, '2024-02-07 04:29:38'),
(56, 1, 3, 2, NULL, '2024-02-07 04:30:14'),
(60, 4, 2, 2, NULL, '2024-02-25 07:55:20'),
(61, 5, 2, 2, NULL, '2024-02-26 05:48:25');

-- --------------------------------------------------------

--
-- Table structure for table `materis`
--

CREATE TABLE `materis` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materis`
--

INSERT INTO `materis` (`id`, `judul`, `slug`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'DDL', 'ddl', 'Materi DDL', '2024-02-04 03:51:36', '2024-02-04 03:51:36'),
(2, 'DML', 'dml', 'Materi DML', '2024-02-04 03:52:00', '2024-02-04 03:52:00'),
(3, 'Eloquent', 'eloquent', 'Materi Eloquent', '2024-02-04 03:52:29', '2024-02-04 03:52:29'),
(4, 'API', 'api', 'Materi API', '2024-02-04 03:52:52', '2024-02-04 03:52:52'),
(5, 'Pre Test', 'pre-test', 'Ini adalah latihan untuk test pre test', '2024-02-10 03:02:48', '2024-02-10 03:16:21'),
(6, 'Post Test', 'post-test', 'Ini adalah latihan test untuk post test', '2024-02-10 03:13:43', '2024-02-10 03:13:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(7, '2023_11_16_195733_create_babs_table', 4),
(10, '2024_01_03_124504_create_soals_table', 7),
(16, '2014_10_12_000000_create_users_table', 8),
(17, '2014_10_12_100000_create_password_reset_tokens_table', 8),
(18, '2019_08_19_000000_create_failed_jobs_table', 8),
(19, '2019_12_14_000001_create_personal_access_tokens_table', 8),
(20, '2023_11_15_115923_add_details_to_users_table', 8),
(21, '2023_11_15_150904_create_materis_table', 8),
(22, '2023_11_16_200724_create_babs_table', 8),
(23, '2024_01_03_111622_create_latihans_table', 8),
(24, '2024_01_04_165647_create_soals_table', 8),
(25, '2024_01_04_165716_create_jawabans_table', 8),
(26, '2024_01_05_131738_create_tests_table', 8),
(27, '2024_01_14_172930_add_status_to_babs_table', 8),
(28, '2024_01_30_172254_add_status_to_latihans_table', 8),
(29, '2024_02_03_112333_create_babs_attempt_table', 9),
(30, '2024_02_04_103014_create_latihans_attempt_table', 9),
(31, '2024_02_05_135449_create_latihans_answer_table', 10),
(32, '2024_02_12_112327_create_pre_posts_table', 11),
(33, '2024_02_12_113927_create_pre_post_tests_table', 12),
(34, '2024_02_12_143542_create_pre_tests_attempt_table', 13),
(35, '2024_02_12_143558_create_pre_tests_answer_table', 13),
(36, '2024_02_13_122524_add_link_github_to_pre_tests_attempt_table', 14),
(37, '2024_02_24_102022_add_nilai_to_pre_tests_answer_table', 15),
(38, '2024_02_24_144739_create_post_tests_attempt_table', 16),
(39, '2024_02_24_144819_create_post_tests_answer_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 3, 'auth_token', 'f522bbbcc6ee345436f736675f0ed83610fd9d38826ed0f064173b8a80636dba', '[\"*\"]', NULL, NULL, '2024-02-05 13:07:59', '2024-02-05 13:07:59');

-- --------------------------------------------------------

--
-- Table structure for table `post_tests_answer`
--

CREATE TABLE `post_tests_answer` (
  `id` bigint UNSIGNED NOT NULL,
  `post_test_attempt_id` bigint UNSIGNED NOT NULL,
  `soal_id` bigint UNSIGNED NOT NULL,
  `typed_answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_tests_answer`
--

INSERT INTO `post_tests_answer` (`id`, `post_test_attempt_id`, `soal_id`, `typed_answer`, `nilai`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 'jawaban eloquent 1', 10, '2024-02-24 09:02:44', '2024-02-24 09:02:44'),
(2, 1, 7, 'jawaban eloquent 2', 10, '2024-02-24 09:02:44', '2024-02-24 09:02:44'),
(3, 1, 10, 'jawaban api 1', 0, '2024-02-24 09:02:44', NULL),
(4, 1, 11, 'jawaban api 2', 0, '2024-02-24 09:02:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_tests_attempt`
--

CREATE TABLE `post_tests_attempt` (
  `id` bigint UNSIGNED NOT NULL,
  `pre_post_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `link_github` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_tests_attempt`
--

INSERT INTO `post_tests_attempt` (`id`, `pre_post_id`, `user_id`, `status`, `link_github`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 1, 'sdhbjhdssdh', '2024-02-24 08:55:45', '2024-02-24 09:02:44'),
(2, 2, 3, 0, NULL, '2024-02-26 05:07:43', '2024-02-26 05:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `pre_posts`
--

CREATE TABLE `pre_posts` (
  `id` bigint UNSIGNED NOT NULL,
  `materi_id` bigint UNSIGNED NOT NULL,
  `judulPrePost` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_posts`
--

INSERT INTO `pre_posts` (`id`, `materi_id`, `judulPrePost`, `slug`, `created_at`, `updated_at`) VALUES
(1, 5, 'Pre Test', 'pre-test', '2024-02-12 05:37:39', '2024-02-12 05:48:14'),
(2, 6, 'Post Test', 'post-test', '2024-02-12 05:38:07', '2024-02-12 05:38:07');

-- --------------------------------------------------------

--
-- Table structure for table `pre_post_tests`
--

CREATE TABLE `pre_post_tests` (
  `id` bigint UNSIGNED NOT NULL,
  `pre_post_id` bigint UNSIGNED NOT NULL,
  `soal_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_post_tests`
--

INSERT INTO `pre_post_tests` (`id`, `pre_post_id`, `soal_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(6, 1, 6, NULL, NULL),
(7, 1, 7, NULL, NULL),
(8, 1, 8, NULL, NULL),
(9, 1, 9, NULL, NULL),
(10, 2, 6, NULL, NULL),
(11, 2, 7, NULL, NULL),
(12, 2, 10, NULL, NULL),
(13, 2, 11, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pre_tests_answer`
--

CREATE TABLE `pre_tests_answer` (
  `id` bigint UNSIGNED NOT NULL,
  `pre_test_attempt_id` bigint UNSIGNED NOT NULL,
  `soal_id` bigint UNSIGNED NOT NULL,
  `typed_answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nilai` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_tests_answer`
--

INSERT INTO `pre_tests_answer` (`id`, `pre_test_attempt_id`, `soal_id`, `typed_answer`, `created_at`, `updated_at`, `nilai`) VALUES
(106416, 31, 1, 'jhshj', '2024-02-24 07:22:54', NULL, 0),
(106417, 31, 2, 'ihidshih', '2024-02-24 07:22:54', NULL, 0),
(106418, 31, 3, 'hsidhiu', '2024-02-24 07:22:54', NULL, 0),
(106419, 31, 6, 'hhsdihi', '2024-02-24 07:22:54', NULL, 0),
(106420, 31, 7, 'hidshih', '2024-02-24 07:22:54', NULL, 0),
(106421, 31, 8, 'ohidshih', '2024-02-24 07:22:54', NULL, 0),
(106422, 31, 9, 'ohefoiho', '2024-02-24 07:22:54', NULL, 0),
(106430, 33, 1, 'sda', '2024-02-24 11:28:37', NULL, 0),
(106431, 33, 2, 'hihisdh', '2024-02-24 11:28:37', NULL, 0),
(106432, 33, 3, 'uhsdihi', '2024-02-24 11:28:37', NULL, 0),
(106433, 33, 6, 'ihdsih', '2024-02-24 11:28:37', NULL, 0),
(106434, 33, 7, 'hi', '2024-02-24 11:28:37', NULL, 0),
(106435, 33, 8, 'ihisdh', '2024-02-24 11:28:37', NULL, 0),
(106436, 33, 9, 'oefhfihewihew', '2024-02-24 11:28:37', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pre_tests_attempt`
--

CREATE TABLE `pre_tests_attempt` (
  `id` bigint UNSIGNED NOT NULL,
  `pre_post_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `link_github` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_tests_attempt`
--

INSERT INTO `pre_tests_attempt` (`id`, `pre_post_id`, `user_id`, `status`, `created_at`, `updated_at`, `link_github`) VALUES
(31, 1, 2, 1, '2024-02-24 07:21:03', '2024-02-24 07:22:54', 'jheihewiuqhuewif'),
(33, 1, 3, 1, '2024-02-24 07:35:26', '2024-02-24 11:28:37', 'idshidshidhs');

-- --------------------------------------------------------

--
-- Table structure for table `soals`
--

CREATE TABLE `soals` (
  `id` bigint UNSIGNED NOT NULL,
  `question` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `soals`
--

INSERT INTO `soals` (`id`, `question`, `created_at`, `updated_at`) VALUES
(1, 'Soal DML 1', NULL, NULL),
(2, 'Soal DML 2', NULL, NULL),
(3, 'Soal DML 3', NULL, NULL),
(4, 'Soal DDL 1', NULL, NULL),
(5, 'Soal DDL 2', NULL, NULL),
(6, 'Soal Eloquent 1', NULL, NULL),
(7, 'Soal Eloquent 2', NULL, NULL),
(8, 'Soal Eloquent 3', NULL, NULL),
(9, 'Soal Eloquent 4', NULL, NULL),
(10, 'Soal API 1', NULL, NULL),
(11, 'Soal API 2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` bigint UNSIGNED NOT NULL,
  `latihan_id` bigint UNSIGNED NOT NULL,
  `soal_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `latihan_id`, `soal_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, NULL),
(2, 1, 5, NULL, NULL),
(3, 1, 1, NULL, NULL),
(4, 1, 2, NULL, NULL),
(5, 1, 3, NULL, NULL),
(6, 4, 6, NULL, NULL),
(7, 4, 9, NULL, NULL),
(8, 5, 6, NULL, NULL),
(9, 6, 7, NULL, NULL),
(10, 6, 8, NULL, NULL),
(11, 7, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_as` tinyint NOT NULL DEFAULT '0' COMMENT '0=user, 1=guru, 2=admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_as`) VALUES
(1, 'alwayswrong', 'alwayswrong@gmail.com', NULL, '$2y$12$xjb8OmuiKu1vL.Xbyp3lU.Mbr9zARS60ucObH8OzfREqFAO6cDsXG', NULL, '2024-02-04 03:49:20', '2024-02-04 03:49:20', 1),
(2, 'TeddyFirman', 'teddyfirman902@gmail.com', NULL, '$2y$12$EMMhEwF3aN8P1Dm2d4aTROIcodSDgrrhIF0LTmDkZ2J5o6pXHkeJi', NULL, '2024-02-04 08:56:01', '2024-02-04 08:56:01', 0),
(3, 'alwaystrue', 'nilamsty@gmail.com', NULL, '$2y$12$lIl7JvN6RvN5OAMiuyWKF.73MHtVv7PCxT.Z/pf8UehxFR2L.jTpu', NULL, '2024-02-04 08:56:43', '2024-02-04 08:56:43', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `babs`
--
ALTER TABLE `babs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `babs_materi_id_foreign` (`materi_id`);

--
-- Indexes for table `babs_attempt`
--
ALTER TABLE `babs_attempt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `babs_attempt_bab_id_foreign` (`bab_id`),
  ADD KEY `babs_attempt_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jawabans`
--
ALTER TABLE `jawabans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jawabans_soal_id_foreign` (`soal_id`);

--
-- Indexes for table `latihans`
--
ALTER TABLE `latihans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `latihans_materi_id_foreign` (`materi_id`);

--
-- Indexes for table `latihans_answer`
--
ALTER TABLE `latihans_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `latihans_answer_latihan_attempt_id_foreign` (`latihan_attempt_id`),
  ADD KEY `latihans_answer_soal_id_foreign` (`soal_id`);

--
-- Indexes for table `latihans_attempt`
--
ALTER TABLE `latihans_attempt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `latihans_attempt_latihan_id_foreign` (`latihan_id`),
  ADD KEY `latihans_attempt_user_id_foreign` (`user_id`);

--
-- Indexes for table `materis`
--
ALTER TABLE `materis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `post_tests_answer`
--
ALTER TABLE `post_tests_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_tests_answer_post_test_attempt_id_foreign` (`post_test_attempt_id`),
  ADD KEY `post_tests_answer_soal_id_foreign` (`soal_id`);

--
-- Indexes for table `post_tests_attempt`
--
ALTER TABLE `post_tests_attempt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_tests_attempt_pre_post_id_foreign` (`pre_post_id`),
  ADD KEY `post_tests_attempt_user_id_foreign` (`user_id`);

--
-- Indexes for table `pre_posts`
--
ALTER TABLE `pre_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_posts_materi_id_foreign` (`materi_id`);

--
-- Indexes for table `pre_post_tests`
--
ALTER TABLE `pre_post_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_post_tests_pre_post_id_foreign` (`pre_post_id`),
  ADD KEY `pre_post_tests_soal_id_foreign` (`soal_id`);

--
-- Indexes for table `pre_tests_answer`
--
ALTER TABLE `pre_tests_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_tests_answer_pre_test_attempt_id_foreign` (`pre_test_attempt_id`),
  ADD KEY `pre_tests_answer_soal_id_foreign` (`soal_id`);

--
-- Indexes for table `pre_tests_attempt`
--
ALTER TABLE `pre_tests_attempt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_tests_attempt_pre_post_id_foreign` (`pre_post_id`),
  ADD KEY `pre_tests_attempt_user_id_foreign` (`user_id`);

--
-- Indexes for table `soals`
--
ALTER TABLE `soals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tests_latihan_id_foreign` (`latihan_id`),
  ADD KEY `tests_soal_id_foreign` (`soal_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `babs`
--
ALTER TABLE `babs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `babs_attempt`
--
ALTER TABLE `babs_attempt`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jawabans`
--
ALTER TABLE `jawabans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `latihans`
--
ALTER TABLE `latihans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `latihans_answer`
--
ALTER TABLE `latihans_answer`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `latihans_attempt`
--
ALTER TABLE `latihans_attempt`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `materis`
--
ALTER TABLE `materis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_tests_answer`
--
ALTER TABLE `post_tests_answer`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `post_tests_attempt`
--
ALTER TABLE `post_tests_attempt`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pre_posts`
--
ALTER TABLE `pre_posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pre_post_tests`
--
ALTER TABLE `pre_post_tests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pre_tests_answer`
--
ALTER TABLE `pre_tests_answer`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106437;

--
-- AUTO_INCREMENT for table `pre_tests_attempt`
--
ALTER TABLE `pre_tests_attempt`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `soals`
--
ALTER TABLE `soals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `babs`
--
ALTER TABLE `babs`
  ADD CONSTRAINT `babs_materi_id_foreign` FOREIGN KEY (`materi_id`) REFERENCES `materis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `babs_attempt`
--
ALTER TABLE `babs_attempt`
  ADD CONSTRAINT `babs_attempt_bab_id_foreign` FOREIGN KEY (`bab_id`) REFERENCES `babs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `babs_attempt_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jawabans`
--
ALTER TABLE `jawabans`
  ADD CONSTRAINT `jawabans_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `latihans`
--
ALTER TABLE `latihans`
  ADD CONSTRAINT `latihans_materi_id_foreign` FOREIGN KEY (`materi_id`) REFERENCES `materis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `latihans_answer`
--
ALTER TABLE `latihans_answer`
  ADD CONSTRAINT `latihans_answer_latihan_attempt_id_foreign` FOREIGN KEY (`latihan_attempt_id`) REFERENCES `latihans_attempt` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `latihans_answer_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `latihans_attempt`
--
ALTER TABLE `latihans_attempt`
  ADD CONSTRAINT `latihans_attempt_latihan_id_foreign` FOREIGN KEY (`latihan_id`) REFERENCES `latihans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `latihans_attempt_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_tests_answer`
--
ALTER TABLE `post_tests_answer`
  ADD CONSTRAINT `post_tests_answer_post_test_attempt_id_foreign` FOREIGN KEY (`post_test_attempt_id`) REFERENCES `post_tests_attempt` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tests_answer_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_tests_attempt`
--
ALTER TABLE `post_tests_attempt`
  ADD CONSTRAINT `post_tests_attempt_pre_post_id_foreign` FOREIGN KEY (`pre_post_id`) REFERENCES `pre_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tests_attempt_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_posts`
--
ALTER TABLE `pre_posts`
  ADD CONSTRAINT `pre_posts_materi_id_foreign` FOREIGN KEY (`materi_id`) REFERENCES `materis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_post_tests`
--
ALTER TABLE `pre_post_tests`
  ADD CONSTRAINT `pre_post_tests_pre_post_id_foreign` FOREIGN KEY (`pre_post_id`) REFERENCES `pre_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pre_post_tests_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_tests_answer`
--
ALTER TABLE `pre_tests_answer`
  ADD CONSTRAINT `pre_tests_answer_pre_test_attempt_id_foreign` FOREIGN KEY (`pre_test_attempt_id`) REFERENCES `pre_tests_attempt` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pre_tests_answer_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_tests_attempt`
--
ALTER TABLE `pre_tests_attempt`
  ADD CONSTRAINT `pre_tests_attempt_pre_post_id_foreign` FOREIGN KEY (`pre_post_id`) REFERENCES `pre_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pre_tests_attempt_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_latihan_id_foreign` FOREIGN KEY (`latihan_id`) REFERENCES `latihans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tests_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
