## API Documentation for Minesweeper

### Base URL
```
/api
```

---

### **Authentication**

Authentication is managed through session data. The user must be authenticated via the `$_SESSION['username']` variable. If not authenticated, the API returns a `404 Not Found` status.


---

### **Endpoints**

#### 1. **Create Save**
- **Endpoint:** `/game.php`
- **Method:** `POST`
- **Description:** Creates a new game save for the authenticated user.

##### Request
- **Headers:**
    - `Content-Type: application/json`
    - Authentication via session (`$_SESSION['username']`).
- **Body:**
  ```json
  {
      "save_name": "string",     // Name of the save
      "elapsed_time": "mixed",  // Elapsed time data
      "board": "mixed"          // Game board state
  }
  ```

##### Responses
- **Success:**
    - Status: `200 OK`
    - Body:
      ```json
      {
          "success": true,
          "message": "Save created successfully"
      }
      ```
- **Error:**
    - `400 Bad Request`: Missing required fields or invalid JSON.
    - `404 Not Found`: Authentication failed.

---

#### 2. **Retrieve Board Data**
- **Endpoint:** `/game.php`
- **Method:** `GET`
- **Description:** Retrieves board data for a specific game save.

##### Request
- **Headers:**
    - Authentication via session (`$_SESSION['username`).
- **Query Parameters:**
    - `id`: Game ID (integer).

##### Responses
- **Success:**
    - Status: `200 OK`
    - Body:
      ```json
      {
          "board_data": "array",
          "elapsed_time": {
              "hour": "integer",
              "minute": "integer",
              "second": "integer"
          }
      }
      ```
- **Error:**
    - `400 Bad Request`: Missing Game ID.
    - `404 Not Found`: Game not found or authentication failed.

---

#### 3. **Delete Save**
- **Endpoint:** `/game.php`
- **Method:** `DELETE`
- **Description:** Deletes a specific game save.

##### Request
- **Headers:**
    - Authentication via session (`$_SESSION['username`).
- **Body:**
  ```json
  {
      "saveId": "integer"  // ID of the save to delete
  }
  ```

##### Responses
- **Success:**
    - Status: `200 OK`
    - Body:
      ```json
      {
          "success": true,
          "message": "Save deleted successfully"
      }
      ```
- **Error:**
    - `400 Bad Request`: Invalid or missing save ID.
    - `404 Not Found`: Authentication failed.

---

#### 4. **List All Saves**
- **Endpoint:** `/games.php`
- **Method:** `GET`
- **Description:** Lists all saves for the authenticated user.

##### Request
- **Headers:**
    - Authentication via session (`$_SESSION['username`).

##### Responses
- **Success:**
    - Status: `200 OK`
    - Body:
      ```json
      [
          {
              "id": "integer",
              "name": "string"
          },
          ...
      ]
      ```
- **Error:**
    - `404 Not Found`: Authentication failed.

---

#### 5. **Delete All Saves**
- **Endpoint:** `/games.php`
- **Method:** `DELETE`
- **Description:** Deletes all game saves for the authenticated user.

##### Request
- **Headers:**
    - Authentication via session (`$_SESSION['username`).

##### Responses
- **Success:**
    - Status: `200 OK`
    - Body:
      ```json
      {
          "success": true,
          "message": "All saves deleted successfully"
      }
      ```
- **Error:**
    - `404 Not Found`: Authentication failed.

---

### **Common Errors**

| Status Code | Error Message                          | Cause                                                                 |
|-------------|----------------------------------------|----------------------------------------------------------------------|
| 400         | `Missing required fields`             | Required data in the request body or query is not provided.          |
| 400         | `Invalid JSON input`                  | The request body is not valid JSON.                                  |
| 404         | `User not authenticated`              | User is not logged in or session is expired.                        |
| 404         | `Game not found`                      | Requested game ID does not exist or does not belong to the user.     |
