<?php
namespace App\Models;

class ModuleModel
{
    public function __construct(private \PDO $pdo) {}

    public function getAll(): array
    {
        return $this->pdo->query('SELECT * FROM modules ORDER BY title')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM modules WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO modules (title, description, photo) VALUES (?, ?, ?)'
        );
        $stmt->execute([$data['title'], $data['description'], $data['photo'] ?? null]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE modules SET title=?, description=?, photo=? WHERE id=?'
        );
        $stmt->execute([$data['title'], $data['description'], $data['photo'] ?? null, $id]);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare('DELETE FROM modules WHERE id = ?')->execute([$id]);
    }

    public function getAllProgrammes(): array
    {
        return $this->pdo->query('SELECT id, title FROM programmes ORDER BY title')->fetchAll();
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM modules')->fetchColumn();
    }

    /**
     * Get modules not assigned to any staff (globally), including programme info (if any)
     */
    public function getUnassignedForStaff(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT m.*, p.id AS programme_id, p.title AS programme_title, p.level AS programme_level
             FROM modules m
             LEFT JOIN programme_modules pm ON pm.module_id = m.id
             LEFT JOIN programmes p ON p.id = pm.programme_id
             WHERE m.id NOT IN (SELECT module_id FROM staff_modules)
             ORDER BY p.title, m.title'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get the programme this module is assigned to (if any). Returns first match or null.
     */
    public function getAssignedProgramme(int $moduleId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.* FROM programmes p
             JOIN programme_modules pm ON pm.programme_id = p.id
             WHERE pm.module_id = ? LIMIT 1'
        );
        $stmt->execute([$moduleId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get staff assigned to a module
     */
    public function getAssignedStaff(int $moduleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT s.* FROM staff s
             JOIN staff_modules sm ON sm.staff_id = s.id
             WHERE sm.module_id = ?
             ORDER BY s.full_name ASC'
        );
        $stmt->execute([$moduleId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all programmes that this module is assigned to, with year_of_study.
     * Returns an array of programme rows each including 'year'.
     */
    public function getAssignedProgrammes(int $moduleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, pm.year_of_study AS year
             FROM programmes p
             JOIN programme_modules pm ON pm.programme_id = p.id
             WHERE pm.module_id = ?
             ORDER BY p.title'
        );
        $stmt->execute([$moduleId]);
        return $stmt->fetchAll();
    }
}
