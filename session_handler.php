<?php

class VercelSessionHandler implements SessionHandlerInterface {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function open($path, $name): bool { return true; }
    public function close(): bool { return true; }

    public function read($id): string {
        $stmt = $this->db->prepare("SELECT data FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row['data'] : '';
    }

    public function write($id, $data): bool {
        $stmt = $this->db->prepare(
            "REPLACE INTO sessions (id, data, timestamp) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$id, $data, time()]);
    }

    public function destroy($id): bool {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    }

    public function gc($lifetime): int|false {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE timestamp < ?");
        $stmt->execute([time() - $lifetime]);
        return $stmt->rowCount();
    }
}

require_once 'db.php';
$handler = new VercelSessionHandler($db);
session_set_save_handler($handler, true);
session_start();