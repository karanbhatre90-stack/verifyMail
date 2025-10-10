# ✉️ Email Verification API (PHP)

A simple and lightweight REST API built in **PHP** to verify if an email address is valid and deliverable by checking its **MX records** and performing a basic **SMTP verification**.

---

## 🚀 Features

- ✅ Validates email format  
- 🔍 Checks MX records (mail exchange server)  
- 📬 Verifies deliverability via SMTP handshake  
- 🧾 Returns structured JSON response  
- ⚡ Fast and lightweight — no external dependencies  

---

## 🧩 API Endpoint

```
GET /verify.php?email={email}&index={index}
```

| Parameter | Type | Required | Description |
|------------|------|-----------|-------------|
| `email` | string | ✅ Yes | The email address to verify |
| `index` | integer | ❌ No | Optional numeric index (default = 1) for batch requests |

---

## ⚙️ Example Usage

### Using cURL

```bash
curl "https://yourdomain.com/verify.php?email=test@example.com"
```

### Using JavaScript (Fetch)

```javascript
fetch("https://yourdomain.com/verify.php?email=test@example.com")
  .then(res => res.json())
  .then(data => console.log(data));
```

### Using PHP

```php
$response = file_get_contents("https://yourdomain.com/verify.php?email=test@example.com");
$data = json_decode($response, true);
print_r($data);
```

---

## 📤 Example Successful Response

```json
[
  {
    "index": 1,
    "verify": "test@example.com",
    "response": {
      "safetosend": "Yes",
      "status": "Valid",
      "type": "",
      "v_response": "Deliverable",
      "mx_server": "mx.example.com",
      "mx_found": true,
      "mx_response": "250 2.1.5 OK"
    },
    "code": "200"
  }
]
```

---

## ⚠️ Example Invalid Email Response

```json
[
  {
    "index": 1,
    "verify": "invalid_email",
    "response": {
      "safetosend": "No",
      "status": "Invalid",
      "type": "",
      "v_response": "Invalid email format",
      "mx_server": "",
      "mx_found": false,
      "mx_response": ""
    },
    "code": "400"
  }
]
```

---

## 📡 Example Undeliverable Email Response

```json
[
  {
    "index": 1,
    "verify": "unknown@nonexistentdomain.xyz",
    "response": {
      "safetosend": "No",
      "status": "Invalid",
      "type": "",
      "v_response": "MX server connection failed",
      "mx_server": "",
      "mx_found": false,
      "mx_response": ""
    },
    "code": "200"
  }
]
```

---

## 🛠️ Installation

1. Upload the `verify.php` file to your web server (e.g., `public_html` folder).  
2. Make sure PHP is enabled (version 7.4 or higher recommended).  
3. Access it via URL:
   ```
   https://yourdomain.com/verify.php?email=example@example.com
   ```

---

## 📋 Response Parameters Explained

| Key | Description |
|------|-------------|
| `safetosend` | “Yes” if deliverable, otherwise “No” |
| `status` | Overall verification status (`Valid` / `Invalid`) |
| `v_response` | Descriptive text of the result |
| `mx_server` | Mail exchange server hostname |
| `mx_found` | Boolean – whether MX record was found |
| `mx_response` | Raw SMTP response (if available) |

---

## ⚠️ Notes

- Some mail servers **block SMTP verification** for privacy; such addresses may return `Undeliverable` even if valid.  
- Always use this API for **pre-validation**, not guaranteed inbox delivery.  
- Avoid excessive requests to the same domain to prevent blacklisting.

---

## 🧑‍💻 Author

**Muhammad Usama**  
📧 Email: *uxeerorg@gmail.com*  
🌐 GitHub: [github.com/yourusername](https://github.com/uxeerorg)

---

## 📝 License

This project is licensed under the **MIT License** — feel free to use, modify, and distribute it.

---

## 🏷️ Tags

`email-verification` , `php-api` , `smtp-checker` , `mx-verifier` , `email-validator` , `deliverability` , `mail-validation` , `api`
