<?php
class Employee {
    private $conn;
    private $table = "tbl_employee";

    public $employee_id;
    public $employee_code;
    public $user_id;
    public $position;
    public $department;
    public $employment_status;
    public $date_hired;
    public $date_resigned;
    public $salary;
    public $is_active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 🔹 Generate Employee Code
    private function generateEmployeeCode() {
        $sql = "SELECT employee_code FROM {$this->table} ORDER BY employee_id DESC LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastCode = $row['employee_code'];
            $num = intval(substr($lastCode, 3)) + 1;
            return "EMP" . str_pad($num, 4, "0", STR_PAD_LEFT);
        } else {
            return "EMP0001";
        }
    }

    // 🔹 CREATE Employee
    public function create() {
        $this->employee_code = $this->generateEmployeeCode();

        $sql = "INSERT INTO {$this->table} 
                (employee_code, user_id, position, department, employment_status, date_hired, date_resigned, salary, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sisssssdi",
            $this->employee_code,
            $this->user_id,
            $this->position,
            $this->department,
            $this->employment_status,
            $this->date_hired,
            $this->date_resigned,
            $this->salary,
            $this->is_active
        );

        return $stmt->execute();
    }

    // 🔹 READ ALL Employees
    public function readAll() {
        $sql = "SELECT e.*, u.firstName, u.lastName, u.email, u.username 
                FROM {$this->table} e
                LEFT JOIN tbl_user u ON e.user_id = u.user_id
                ORDER BY e.employee_id DESC";
        return $this->conn->query($sql);
    }

    // 🔹 READ SINGLE Employee by ID
    public function readOne($id) {
        $sql = "SELECT 
                    e.employee_id, e.employee_code, e.user_id, 
                    e.position, e.department, e.employment_status, 
                    e.date_hired, e.date_resigned, e.salary, e.is_active,
                    u.user_id AS user_id, u.firstName, u.middleName, u.lastName, 
                    u.email, u.contact, u.username
                FROM {$this->table} e
                LEFT JOIN tbl_user u ON e.user_id = u.user_id
                WHERE e.employee_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    // 🔹 UPDATE Employee
    public function update() {
        $sql = "UPDATE {$this->table} 
                SET position = ?, department = ?, employment_status = ?, 
                    date_hired = ?, date_resigned = ?, salary = ?, is_active = ?
                WHERE employee_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssdii",
            $this->position,
            $this->department,
            $this->employment_status,
            $this->date_hired,
            $this->date_resigned,
            $this->salary,
            $this->is_active,
            $this->employee_id
        );

        return $stmt->execute();
    }

    // 🔹 DELETE Employee
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE employee_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
