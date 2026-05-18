<?php
namespace App\Models;

class StaffModel
{
    public function __construct(private \PDO $pdo) {}

    /**
     * Get all staff members
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM staff ORDER BY full_name ASC');
        return $stmt->fetchAll();
    }

    /**
     * Get staff by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM staff WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Find staff by username
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM staff WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Verify staff login
     */
    public function verifyLogin(string $username, string $password): ?array
    {
        $staff = $this->findByUsername($username);
        if (!$staff || !$staff['is_active']) {
            return null;
        }
        if (!password_verify($password, $staff['password_hash'])) {
            return null;
        }
        return $staff;
    }

    /**
     * Create new staff member
     */
    public function create(array $data, int $createdBy): int
    {
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            'INSERT INTO staff (username, password_hash, email, full_name, role, is_active, created_by)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['username'],
            $passwordHash,
            $data['email'],
            $data['full_name'],
            $data['role'] ?? 'instructor',
            $data['is_active'] ?? 1,
            $createdBy,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update staff member
     */
    public function update(int $id, array $data): void
    {
        $fields = [];
        $params = [];
        
        if (isset($data['email'])) { $fields[] = 'email = ?'; $params[] = $data['email']; }
        if (isset($data['full_name'])) { $fields[] = 'full_name = ?'; $params[] = $data['full_name']; }
        if (isset($data['role'])) { $fields[] = 'role = ?'; $params[] = $data['role']; }
        if (isset($data['is_active'])) { $fields[] = 'is_active = ?'; $params[] = $data['is_active']; }
        if (isset($data['password'])) { 
            $fields[] = 'password_hash = ?'; 
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT); 
        }
        
        if (empty($fields)) {
            return;
        }
        
        $params[] = $id;
        $sql = 'UPDATE staff SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Delete staff member
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM staff WHERE id = ?');
        $stmt->execute([$id]);
    }

    /**
     * Get modules assigned to staff
     */
    public function getAssignedModules(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT m.* FROM modules m
             JOIN staff_modules sm ON sm.module_id = m.id
             WHERE sm.staff_id = ?
             ORDER BY m.title'
        );
        $stmt->execute([$staffId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all modules
     */
    public function getAllModules(): array
    {
        return $this->pdo->query('SELECT * FROM modules ORDER BY title')->fetchAll();
    }

    /**
     * Get programmes assigned to staff
     */
    public function getAssignedProgrammes(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.* FROM programmes p
             JOIN staff_programmes sp ON sp.programme_id = p.id
             WHERE sp.staff_id = ?
             ORDER BY p.title'
        );
        $stmt->execute([$staffId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all programmes
     */
    public function getAllProgrammes(): array
    {
        return $this->pdo->query('SELECT * FROM programmes ORDER BY title')->fetchAll();
    }

    /**
     * Assign module to staff
     */
    public function assignModule(int $staffId, int $moduleId): void
    {
        // Check if already assigned
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM staff_modules WHERE staff_id = ? AND module_id = ?'
        );
        $stmt->execute([$staffId, $moduleId]);
        if ($stmt->fetch()) {
            return; // Already assigned
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO staff_modules (staff_id, module_id) VALUES (?, ?)'
        );
        $stmt->execute([$staffId, $moduleId]);
    }

    /**
     * Assign programme to staff
     */
    public function assignProgramme(int $staffId, int $programmeId): void
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM staff_programmes WHERE staff_id = ? AND programme_id = ?'
        );
        $stmt->execute([$staffId, $programmeId]);
        if ($stmt->fetch()) {
            return;
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO staff_programmes (staff_id, programme_id) VALUES (?, ?)'
        );
        $stmt->execute([$staffId, $programmeId]);
    }

    /**
     * Unassign module from staff
     */
    public function unassignModule(int $staffId, int $moduleId): void
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM staff_modules WHERE staff_id = ? AND module_id = ?'
        );
        $stmt->execute([$staffId, $moduleId]);
    }

    /**
     * Unassign programme from staff
     */
    public function unassignProgramme(int $staffId, int $programmeId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM staff_programmes WHERE staff_id = ? AND programme_id = ?');
        $stmt->execute([$staffId, $programmeId]);
    }

    /**
     * Clear all modules for a staff member
     */
    public function clearModules(int $staffId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM staff_modules WHERE staff_id = ?');
        $stmt->execute([$staffId]);
    }

    /**
     * Clear all programmes for a staff member
     */
    public function clearProgrammes(int $staffId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM staff_programmes WHERE staff_id = ?');
        $stmt->execute([$staffId]);
    }

    /**
     * Get modules NOT assigned to any staff (globally)
     */
    public function getUnassignedModules(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT m.* FROM modules m
             WHERE m.id NOT IN (
                SELECT module_id FROM staff_modules
             )
             ORDER BY m.title'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get programmes NOT assigned to any staff (globally)
     */
    public function getUnassignedProgrammes(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.* FROM programmes p
             WHERE p.id NOT IN (
                SELECT programme_id FROM staff_programmes
             )
             ORDER BY p.title'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Count total staff members
     */
    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) as cnt FROM staff')->fetch()['cnt'];
    }
}
