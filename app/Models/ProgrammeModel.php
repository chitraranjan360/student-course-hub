<?php
namespace App\Models;

    /**
     * @method array getAssignedStaff(int $programmeId)
     * @method void assignModule(int $programmeId, int $moduleId, int $year)
     * @method void unassignModule(int $programmeId, int $moduleId)
 */

class ProgrammeModel
{
    public function __construct(private \PDO $pdo) {}

    public function getAllPublished(?string $level = null, ?string $search = null): array
    {
        $sql = 'SELECT * FROM programmes WHERE is_published = 1';
        $params = [];
        if ($level) { $sql .= ' AND level = ?'; $params[] = $level; }
        if ($search) { $sql .= ' AND title LIKE ?'; $params[] = '%' . $search . '%'; }
        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        return $this->pdo->query('SELECT * FROM programmes ORDER BY created_at DESC')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM programmes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getModules(int $programmeId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT m.*, pm.year_of_study
             FROM modules m
             JOIN programme_modules pm ON pm.module_id = m.id
             WHERE pm.programme_id = ?
             ORDER BY pm.year_of_study, m.title'
        );
        $stmt->execute([$programmeId]);
        $grouped = [];
        foreach ($stmt->fetchAll() as $module) {
            $year = (int) ($module['year_of_study'] ?? 1);
            $grouped[$year][] = $module;
        }

        ksort($grouped);

        return $grouped;
    }

    public function getAssignedStaff(int $programmeId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT s.* FROM staff s
             JOIN staff_programmes sp ON sp.staff_id = s.id
             WHERE sp.programme_id = ?
             ORDER BY s.full_name ASC'
        );
        $stmt->execute([$programmeId]);
        return $stmt->fetchAll();
    }

    public function assignModule(int $programmeId, int $moduleId, int $year): void
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM programme_modules WHERE programme_id = ? AND module_id = ?'
        );
        $stmt->execute([$programmeId, $moduleId]);
        $existing = $stmt->fetch();
        if ($existing) {
            $update = $this->pdo->prepare(
                'UPDATE programme_modules SET year_of_study = ? WHERE programme_id = ? AND module_id = ?'
            );
            $update->execute([$year, $programmeId, $moduleId]);
            return;
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO programme_modules (programme_id, module_id, year_of_study) VALUES (?, ?, ?)'
        );
        $stmt->execute([$programmeId, $moduleId, $year]);
    }

    public function unassignModule(int $programmeId, int $moduleId): void
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM programme_modules WHERE programme_id = ? AND module_id = ?'
        );
        $stmt->execute([$programmeId, $moduleId]);
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO programmes (title, level, description, image_url, is_published)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['title'], $data['level'], $data['description'],
            $data['image_url'] ?? null, $data['is_published'] ?? 0,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE programmes SET title=?, level=?, description=?, image_url=?, is_published=? WHERE id=?'
        );
        $stmt->execute([
            $data['title'], $data['level'], $data['description'],
            $data['image_url'] ?? null, $data['is_published'] ?? 0, $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM programmes WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function togglePublish(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE programmes SET is_published = 1 - is_published WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM programmes')->fetchColumn();
    }

    /**
     * Return all programmes with a student count (from interest_registrations).
     * Each row will include 'students' => int
     */
    public function getAllWithCounts(): array
    {
        $sql = <<<'SQL'
SELECT p.*, COALESCE(cnt.c, 0) AS students
FROM programmes p
LEFT JOIN (
  SELECT programme_id, COUNT(*) AS c FROM interest_registrations GROUP BY programme_id
) cnt ON cnt.programme_id = p.id
ORDER BY p.created_at DESC
SQL;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
