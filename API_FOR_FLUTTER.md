# REST API untuk Konsumsi di Flutter

Panduan ringkas untuk mengakses REST API backend dari aplikasi Flutter.

**Base URL**: `https://your-api.example.com/api/`

**Header umum**:
- `Accept: application/json`
- `Content-Type: application/json` (untuk body JSON)
- `Authorization: Bearer <token>` (untuk route yang dilindungi)

---

**Autentikasi**

- POST `auth/register`
  - Body JSON:
    ```json
    {
      "name": "User Name",
      "email": "user@example.com",
      "password": "secret",
      "password_confirmation": "secret"
    }
    ```
  - Response (201):
    ```json
    {
      "message": "Registered successfully",
      "data": { "id": 1, "email": "user@example.com" }
    }
    ```

- POST `auth/login`
  - Body JSON:
    ```json
    { "email": "user@example.com", "password": "secret" }
    ```
  - Response (200):
    ```json
    {
      "access_token": "<jwt-token>",
      "token_type": "Bearer",
      "expires_in": 3600,
      "user": { "id": 1, "name": "User Name", "email": "user@example.com" }
    }
    ```

Gunakan `access_token` pada header `Authorization` untuk endpoint yang dilindungi.

---

**Kategori**

- GET `categories/`
  - List kategori (pagination mungkin diterapkan)
- GET `categories/{id}`
  - Detail kategori
- GET `categories/{id}/products`
  - Produk pada kategori

Contoh respon singkat `GET categories/`:
```json
[{"id":1,"name":"Elektronik"},{"id":2,"name":"Fashion"}]
```

---

**Produk**

- GET `products/` — list produk
- GET `products/search?query=...` — pencarian
- GET `products/popular`
- GET `products/deals`
- GET `products/{id}` — detail produk
- GET `products/{id}/reviews` — review produk

Contoh respon `GET products/{id}`:
```json
{
  "id": 12,
  "name": "Headphone ABC",
  "price": 250000,
  "description": "...",
  "images": ["/storage/.."],
  "category": {"id":3,"name":"Audio"}
}
```

---

**Banner**

- GET `banners` — list banner untuk homepage

---

**Cart (memerlukan otentikasi)**

- GET `cart/` — lihat cart
- POST `cart/items` — tambah item
  - Body contoh:
    ```json
    { "product_id": 12, "quantity": 2 }
    ```
- PUT `cart/items/{id}` — update quantity
- DELETE `cart/items/{id}` — hapus item
- DELETE `cart/` — kosongkan cart

Contoh response `GET cart/`:
```json
{
  "items": [ { "id": 5, "product": {"id":12,"name":"..."}, "quantity":2 } ],
  "subtotal": 500000
}
```

---

**Alamat (Addresses) — memerlukan otentikasi**

- GET `addresses/` — list alamat
- GET `addresses/{id}` — detail
- POST `addresses/` — tambah alamat
  - Body contoh:
    ```json
    { "label": "Rumah", "street": "Jl. Contoh", "city": "Jakarta", "postal_code": "12345" }
    ```
- PUT `addresses/{id}` — update
- DELETE `addresses/{id}` — hapus
- PUT `addresses/{id}/default` — set default

---

**Order (memerlukan otentikasi)**

- GET `orders/` — list pesanan
- GET `orders/{id}` — detail pesanan
- POST `orders/` — buat pesanan (checkout)
  - Body ringkas contoh:
    ```json
    { "address_id": 3, "payment_method": "credit_card", "notes": "..." }
    ```
- PUT `orders/{id}/cancel` — batalkan pesanan
- GET `orders/{id}/track` — tracking
- POST `orders/{orderId}/reviews` — kirim review pada order/produk

---

**Review**

- PUT `reviews/{id}` — update review (auth)
- DELETE `reviews/{id}` — hapus review (auth)

---

**Favorites**

- GET `favorites/`
- POST `favorites/` — body `{ "product_id": 12 }`
- DELETE `favorites/{productId}`

---

**Notifications**

- GET `notifications/`
- PUT `notifications/{id}/read`
- PUT `notifications/read-all`
- DELETE `notifications/{id}`

---

**Payments**

- POST `payments/process` — proses pembayaran (auth)
- GET `payments/{id}/status` — cek status
- POST `payments/webhook` — webhook publik dari payment gateway

---

**Format error umum**

Gagal validasi biasanya mengembalikan 422:
```json
{
  "message": "The given data was invalid.",
  "errors": { "email": ["The email field is required."] }
}
```

Kesalahan otorisasi (401) contoh:
```json
{ "message": "Unauthenticated." }
```

---

**Contoh penggunaan di Flutter (http package)**

1) Login dan simpan token

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

final baseUrl = 'https://your-api.example.com/api/';

Future<String?> login(String email, String password) async {
  final res = await http.post(
    Uri.parse(baseUrl + 'auth/login'),
    headers: { 'Content-Type': 'application/json' },
    body: jsonEncode({ 'email': email, 'password': password }),
  );
  if (res.statusCode == 200) {
    final json = jsonDecode(res.body);
    return json['access_token'];
  }
  return null;
}
```

2) Ambil daftar produk menggunakan token

```dart
Future<List<dynamic>> fetchProducts(String token) async {
  final res = await http.get(
    Uri.parse(baseUrl + 'products'),
    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer $token' },
  );
  if (res.statusCode == 200) return jsonDecode(res.body);
  throw Exception('Failed to load products');
}
```

3) Tambah item ke cart

```dart
Future<bool> addToCart(String token, int productId, int qty) async {
  final res = await http.post(
    Uri.parse(baseUrl + 'cart/items'),
    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer $token' },
    body: jsonEncode({ 'product_id': productId, 'quantity': qty }),
  );
  return res.statusCode == 201 || res.statusCode == 200;
}
```

4) Tips: simpan token aman menggunakan `flutter_secure_storage`.

---

Catatan cepat:
- Pastikan base URL disesuaikan dan HTTPS digunakan.
- Periksa paginasi (header/format) pada list endpoint.
- Untuk file upload (profile image), gunakan multipart/form-data.

File ini dibuat berdasarkan daftar route pada aplikasi. Jika Anda mau, saya bisa menambahkan contoh response penuh untuk setiap endpoint, DTO/Model Dart yang disarankan, atau contoh penggunaan `dio` dengan interceptor token.
