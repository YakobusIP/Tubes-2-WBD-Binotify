# TUGAS BESAR 1 IF3110 Pengembangan Aplikasi Berbasis Web
## _Milestone 1 -  Monolithic PHP & Vanilla Web Application_

### **Deskripsi**
**Binotify** adalah sebuah aplikasi musik berbasis web pada BNMO yang dibuat untuk Indra agar ponselnya tidak dipinjam terus oleh BNMO dan Indra untuk memutar musik dari Toktok. Aplikasi musik berbasis web ini menggunakan DBMS PostgreSQL dan menggunakan PHP murni serta HTML, CSS dan Javascript vanilla.

Program ini dibuat oleh :
- 13520077 / [Rava Naufal Attar](https://github.com/sivaren)
- 13520104 / [Yakobus Iryanto Prasethio](https://github.com/YakobusIP)
- 13520164 / [Hilda Carissa Widelia](https://github.com/hcarissa)

### **Daftar Requirement**
1. Docker-compose 3.9
2. Docker

### **Cara Instalasi**
1. Install Docker dan docker-compose pada device anda, dapat melalui [link berikut](https://docs.docker.com/compose/install/)
2. Clone repository dan masuk ke folder repository
```
git clone https://gitlab.informatika.org/if3110-2022-k02-01-36/tubes-1-wbd-papah.git
cd tubes-1-wbd-papah
```
3. Jalankan script berikut
```
sudo chmod +x -R ./scripts
./scripts/run-build.sh

# open http://localhost:8080/
```
4. Jalankan website pada browser dengan default port 8080

### **Cara Menjalankan Server**
1. Buka terminal dan masuk ke folder tubes-1-wbd-papah
2. Jalankan script 
```
./scripts/run.sh
```
3. Jalankan webiste pada browser dengan default port 8080

### **Screenshot Tampilan Aplikasi**
Berikut beberapa screenshot tampilan dari aplikasi

Login
![Login](/src/uploads/img/login.jpg)

Register
![Register](/src/uploads/img/register.jpg)

Home User
![Home](/src/uploads/img/homeuser.jpg)

Home Admin
![Login](/src/uploads/img/homeadmin.jpg)

Daftar Album
![Login](/src/uploads/img/albumlist.jpg)

Search, Sort, Filter
![Search, Sort, Filter](/src/uploads/img/search.jpg)

Detail Lagu
![Detail Lagu](/src/uploads/img/songdetail.jpg)

Tambah Lagu
![Tambah Lagu](/src/uploads/img/addsong.jpg)

Edit Lagu
![Edit Lagu](/src/uploads/img/editsong.jpg)

Detail Album
![Detial Album](/src/uploads/img/albumdetail.jpg)

Tambah Ablum
![Tambah Album](/src/uploads/img/addalbum.jpg)

Edit Album
![Edit Album](/src/uploads/img/editalbum.jpg)

Daftar User
![Daftar User](/src/uploads/img/userlist.jpg)


### **Pembagian Tugas**

Server Side
| Fitur | NIM 1 | NIM 2 | NIM 3 |
| :---: | :---: | :---: | :---: |
| Login | 13520164 | 13520077 | 13520104 |
| Register | 13520164 | 13520077 | 13520104 |
| Home | 13520164 |
| Daftar Album | 13520077 | 13520104 | 
| Search, Sort, Filter | 13520104 | 13520077 | 
| Detail Lagu | 13520104 |
| Tambah Lagu | 13520104 | 13520077 |
| Detail Album | 13520077 |
| Tambah Album | 13520077| 13520104 |
| Daftar User | 13520104 | 

Client Side
| Fitur | NIM 1 | NIM 2 | NIM 3 |
| :---: | :---: | :---: | :---: |
| Login | 13520164 |
| Register | 13520164 | 
| Home | 13520164 | 13520104 | 13520077 |
| Daftar Album | 13520077 | 13520104 | 
| Search, Sort, Filter | 13520104 | 13520077 | 
| Detail Lagu | 13520104 | 13520077 | 13520164 |
| Tambah Lagu | 13520104 | 13520077 |
| Detail Album | 13520077 | 
| Tambah Album | 13520077| 13520104 |
| Daftar User | 13520104 | 