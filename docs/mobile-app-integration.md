# Mobile App Integration Guide for Laravel Backend

This guide explains how to link your mobile app frontend with the Laravel backend API using token-based authentication and consume the available API endpoints.

---

## 1. Authentication

To authenticate users in your mobile app:

- Make a POST request to the login endpoint:

```
POST /api/auth/login
Content-Type: application/json

{
  "username": "user123",
  "password": "userpassword"
}
```

- Successful response returns a JSON object including an access token:

```json
{
  "success": true,
  "message": "Login successful",
  "access_token": "your-generated-token-here",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "username": "user123",
    "email": "user@example.com"
  }
}
```

- Save the `access_token` securely in your mobile app for subsequent requests.

---

## 2. Using the Access Token for Authorized Requests

For all subsequent API requests requiring authentication, include the token in the Authorization header:

```
Authorization: Bearer your-generated-token-here
```

Example using `fetch` in JavaScript:

```javascript
fetch('https://your-backend-url/api/challenges', {
  method: 'GET',
  headers: {
    'Authorization': 'Bearer your-generated-token-here',
    'Content-Type': 'application/json'
  }
}).then(response => response.json())
  .then(data => {
    // handle the returned data
  });
```

---

## 3. Available API Endpoints

### Challenges

- `GET /api/challenges` - List all challenges.
- `GET /api/challenges/{id}` - Get details of a specific challenge.
- `POST /api/challenges/{id}/join` - Join a challenge.

### Payments

- `GET /api/payments/user/{user_id}` - Get payments for a user.
- `POST /api/payments` - Create a new payment.
- `GET /api/payments/challenge/{challenge_id}` - Get payments for a challenge.

### Materials

- `GET /api/materials` - List all materials.
- `GET /api/materials/{id}` - Get details of a specific material.

### Penalties

- `GET /api/penalties/user/{user_id}` - List penalties for a user.
- `POST /api/penalties/appeal` - Appeal a penalty.

---

## 4. Error Handling

- API responses include a `success` boolean and optional `message`.
- Handle HTTP status codes properly (e.g., 401 Unauthorized for invalid token).
- Implement token expiration and logout handling in the mobile app.

---

## 5. Example Login Request (cURL)

```bash
curl -X POST https://your-backend-url/api/auth/login \
-H "Content-Type: application/json" \
-d '{"username":"user123", "password":"userpassword"}'
```

---

## 6. Notes

- Replace `https://your-backend-url` with your actual backend URL.
- Ensure HTTPS is used for secure communication.
- Follow any additional business logic or API documentation specific to your backend.

---

This guide provides the basic integration steps. For any further customization or additional API endpoints, please consult the backend team.
