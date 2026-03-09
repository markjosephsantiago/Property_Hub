<?php
class Guest {
    private $conn;
    private $table = "tbl_guest";

    public $guest_id;
    public $user_id; // Added user_id link
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact;
    public $address;
    public $id_type;
    public $id_number;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ✅ Create Guest
    public function create() {
        $sql = "INSERT INTO {$this->table} 
                (user_id, first_name, middle_name, last_name, email, contact, address, id_type, id_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "issssssss",
            $this->user_id,
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->email,
            $this->contact,
            $this->address,
            $this->id_type,
            $this->id_number
        );
        return $stmt->execute();
    }

    // ✅ Read All Guests
    public function readAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY guest_id DESC";
        return $this->conn->query($sql);
    }

    // ✅ Read One Guest
    public function readOne($id) {
        $sql = "SELECT * FROM {$this->table} WHERE guest_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ Update Guest
    public function update() {
        $sql = "UPDATE {$this->table} 
                SET user_id=?, first_name=?, middle_name=?, last_name=?, email=?, contact=?, address=?, id_type=?, id_number=? 
                WHERE guest_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "issssssssi",
            $this->user_id,
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->email,
            $this->contact,
            $this->address,
            $this->id_type,
            $this->id_number,
            $this->guest_id
        );
        return $stmt->execute();
    }

    // ✅ Delete Guest
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE guest_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
