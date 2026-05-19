<?php
namespace App\Models;

class InterestModel
{
    public function __construct(private \PDO $pdo) {}

    public function findOneWithProgramme(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ir.*, p.title AS programme_title
             FROM interest_registrations ir
             JOIN programmes p ON p.id = ir.programme_id
             WHERE ir.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function listEmailsByProgramme(int $programmeId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT email
             FROM interest_registrations
             WHERE programme_id = ?
             ORDER BY email ASC'
        );
        $stmt->execute([$programmeId]);
        return array_map(
            static fn (array $row): string => (string) $row['email'],
            $stmt->fetchAll()
        );
    }

    public function findAllWithProgramme(): array
    {
        $stmt = $this->pdo->query(
            'SELECT ir.*, p.title AS programme_title
             FROM interest_registrations ir
             JOIN programmes p ON p.id = ir.programme_id
             ORDER BY p.title ASC, ir.registered_at DESC'
        );
        return $stmt->fetchAll();
    }

    public function register(array $data): bool
    {
        // Prevent duplicate
        $stmt = $this->pdo->prepare(
            'SELECT id FROM interest_registrations WHERE email=? AND programme_id=?'
        );
        $stmt->execute([$data['email'], $data['programme_id']]);
        if ($stmt->fetch()) return false;

        $token = bin2hex(random_bytes(32));
        $stmt = $this->pdo->prepare(
            'INSERT INTO interest_registrations (first_name, last_name, email, programme_id, withdraw_token)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['first_name'], $data['last_name'],
            $data['email'], $data['programme_id'], $token,
        ]);
        return true;
    }

    public function findByProgramme(int $programmeId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM interest_registrations WHERE programme_id=? ORDER BY registered_at DESC'
        );
        $stmt->execute([$programmeId]);
        return $stmt->fetchAll();
    }

    public function withdraw(string $token): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM interest_registrations WHERE withdraw_token=?');
        $stmt->execute([$token]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare('DELETE FROM interest_registrations WHERE id=?')->execute([$id]);
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM interest_registrations')->fetchColumn();
    }

    public function countByProgramme(int $programmeId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM interest_registrations WHERE programme_id = ?');
        $stmt->execute([$programmeId]);
        return (int) $stmt->fetchColumn();
    }
}
