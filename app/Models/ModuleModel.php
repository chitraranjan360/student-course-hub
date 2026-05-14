<?php
namespace App\Models;

class ModuleModel
{
    public function __construct(private \PDO $pdo) {}

    public function getAll(): array
    {
        return $this->pdo->query('SELECT * FROM modules ORDER BY year_of_study, title')->fetchAll();
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
            'INSERT INTO modules (title, description, year_of_study) VALUES (?, ?, ?)'
        );
        $stmt->execute([$data['title'], $data['description'], $data['year_of_study']]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE modules SET title=?, description=?, year_of_study=? WHERE id=?'
        );
        $stmt->execute([$data['title'], $data['description'], $data['year_of_study'], $id]);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare('DELETE FROM modules WHERE id = ?')->execute([$id]);
    }

    public function getAllProgrammes(): array
    {
        return $this->pdo->query('SELECT id, title FROM programmes ORDER BY title')->fetchAll();
    }
}
