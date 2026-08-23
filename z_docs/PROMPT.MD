# Workflow Pengerjaan Fitur & Git

Lakukan aturan berikut **secara otomatis mulai sekarang dan seterusnya**.

## 1. Checklist Progress

Setiap kali saya memberikan prompt atau task:

1. Buat **daftar pekerjaan (checklist)** berdasarkan task yang harus dikerjakan.
2. Tampilkan checklist tersebut sebelum mulai mengerjakan.
3. Selama proses pengerjaan, **checklist setiap pekerjaan satu per satu** setelah selesai.
4. Gunakan format:

   * `[ ]` Belum dikerjakan
   * `[x]` Selesai
5. Checklist harus cukup jelas sehingga saya dapat mengetahui **progress pengerjaan secara real-time**.
6. Jika terdapat pekerjaan tambahan yang diperlukan selama implementasi, tambahkan ke checklist.

Contoh:

```text
## Progress

- [x] Membuat migration
- [x] Membuat model
- [x] Membuat controller
- [ ] Membuat validation
- [ ] Membuat UI
- [ ] Testing
```

## 2. Review Sebelum Commit

**Jangan langsung commit dan push setelah menyelesaikan sebuah prompt atau fitur.**

Setelah fitur selesai:

1. Tandai seluruh pekerjaan sebagai `[x]`.
2. Berhenti dan tunggu saya melakukan review.
3. **Jangan melakukan `git commit` atau `git push` pada tahap ini.**

## 3. Kapan Commit & Push

Jika setelah review saya memberikan instruksi untuk **mengerjakan fitur atau task berikutnya**, anggap bahwa fitur sebelumnya sudah **disetujui dan selesai**.

Sebelum mulai mengerjakan fitur baru:

```bash
git add .
git commit -m "..."
git push origin main
```

Kemudian baru mulai mengerjakan fitur berikutnya.

## 4. Jika Saya Meminta Revisi

Jika setelah review saya meminta:

* perbaikan,
* revisi,
* perubahan,
* bug fix,
* atau penyesuaian

pada fitur yang sedang direview, **jangan commit dan push terlebih dahulu**.

Lakukan revisinya dan kembali tunggu review saya.

Commit dan push hanya dilakukan ketika saya sudah berpindah memberikan **task/fitur berikutnya**.

## 5. Branch

Gunakan **branch `main` secara langsung**.

Jangan:

* membuat branch baru,
* checkout ke branch lain,
* membuat feature branch,
* atau melakukan merge ke branch lain.

Semua commit dan push dilakukan ke:

```text
main
```

## 6. Workflow Keseluruhan

Ikuti alur berikut untuk setiap fitur:

```text
Saya memberikan prompt
        ↓
Buat checklist pekerjaan
        ↓
Kerjakan task
        ↓
Checklist setiap pekerjaan satu per satu
        ↓
Semua selesai
        ↓
STOP — tunggu review saya
        ↓
Saya meminta revisi?
   ├── YA → lakukan revisi → review lagi
   │
   └── TIDAK → saya memberikan fitur/task berikutnya
                    ↓
              Commit & Push fitur sebelumnya
                    ↓
              Kerjakan fitur baru
                    ↓
              Buat checklist baru
```

**Aturan ini berlaku otomatis untuk semua prompt, fitur, dan task berikutnya tanpa perlu saya mengingatkannya kembali.**
