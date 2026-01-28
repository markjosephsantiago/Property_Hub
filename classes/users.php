<?php
class User {
    private $conn;
    private $table = "tbl_user";

    public function __construct($db) {
        $this->conn = $db;
    }

    // 🔹 Generate Next Employee Code
    private function generateEmployeeCode() {
        $sql = "SELECT employee_code FROM {$this->table} ORDER BY user_id DESC LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastCode = $row['employee_code'];
            $num = (int) str_replace("EMP-", "", $lastCode);
            return "EMP-" . str_pad($num + 1, 4, "0", STR_PAD_LEFT);
        } else {
            return "EMP-0001";
        }
    }

    // ADD USER (general)
    public function addUser($data) {
        // 🔹 Check if email already exists
        $checkQuery = "SELECT user_id FROM " . $this->table . " WHERE email = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $data['email']);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Email already exists
            return false;
        }

        // 🔹 Proceed with insert
        $query = "INSERT INTO " . $this->table . " 
                (firstName, middleName, lastName, img, contact, email, username, password, role_id, created_at, is_verified)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

        $stmt = $this->conn->prepare($query);

        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $img = !empty($data['img']) ? $data['img'] : null;
        $role_id = $data['role'] ?? 3; 
        $is_verified = $data['is_verified'] ?? 1;

        $stmt->bind_param(
            "ssssssssii",
            $data['firstName'],
            $data['middleName'],
            $data['lastName'],
            $img,
            $data['contact'],
            $data['email'],
            $data['username'],
            $password,
            $role_id,
            $is_verified
        );

        if ($stmt->execute()) {
            return $this->conn->insert_id; // return user_id
        }
        return false;
    }


    // 🔹 ADD EMPLOYEE (auto employee_code + default role)
    public function addEmployee($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (firstName, middleName, lastName, contact, email, username, password, 
                   role_id, employee_code, position, department, employment_status, date_hired, salary, created_at, is_verified)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

        $stmt = $this->conn->prepare($query);

        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $role_id = 2; // Default: Employee
        $employee_code = $this->generateEmployeeCode();
        $is_verified = 1;

        $stmt->bind_param(
            "sssssssisssssi",
            $data['firstName'],
            $data['middleName'],
            $data['lastName'],
            $data['contact'],
            $data['email'],
            $data['username'],
            $password,
            $role_id,
            $employee_code,
            $data['position'],
            $data['department'],
            $data['employmentStatus'],
            $data['dateHired'],
            $data['salary'],
            $is_verified
        );

        return $stmt->execute();
    }

    // UPDATE USER
    public function updateUser($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET firstName = ?, middleName = ?, lastName = ?, img = ?, contact = ?, 
                      email = ?, username = ?, role_id = ?, is_verified = ?";

        if (!empty($data['password'])) {
            $query .= ", password = ?";
        }
        $query .= " WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        $img = !empty($data['img']) ? $data['img'] : null;
        $role_id = $data['role'] ?? $data['role_id'] ?? 3;
        $is_verified = $data['is_verified'] ?? 1;

        if (!empty($data['password'])) {
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bind_param(
                "sssssssissi",
                $data['firstName'],
                $data['middleName'],
                $data['lastName'],
                $img,
                $data['contact'],
                $data['email'],
                $data['username'],
                $role_id,
                $is_verified,
                $password,
                $id
            );
        } else {
            $stmt->bind_param(
                "sssssssiii",
                $data['firstName'],
                $data['middleName'],
                $data['lastName'],
                $img,
                $data['contact'],
                $data['email'],
                $data['username'],
                $role_id,
                $is_verified,
                $id
            );
        }

        return $stmt->execute();
    }

    // DELETE USER
    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // GET ALL USERS
    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    // GET USER BY ID
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
