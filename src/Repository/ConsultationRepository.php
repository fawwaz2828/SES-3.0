<?php

declare(strict_types=1);

namespace Ses\Repository;

use Ses\Database;
use Ses\Dto\ConsultationRequest;
use Ses\Enum\ConsultationStatus;
use Ses\Model\Consultation;
use Ses\Model\ConsultationWithRequester;

/** Consultation requests (table `consultation_form`). */
final readonly class ConsultationRepository
{
    public function __construct(private Database $db) {}

    /**
     * @return list<Consultation>
     */
    public function forUser(int $userId): array
    {
        $rows = $this->db->all('SELECT * FROM consultation_form WHERE id_user = ? ORDER BY created_at DESC', [$userId]);

        return array_map(Consultation::fromRow(...), $rows);
    }

    /** Only returns the consultation when it belongs to the given user. */
    public function findForUser(int $id, int $userId): ?Consultation
    {
        $row = $this->db->one('SELECT * FROM consultation_form WHERE id = ? AND id_user = ?', [$id, $userId]);

        return $row === null ? null : Consultation::fromRow($row);
    }

    /**
     * @return list<Consultation>
     */
    public function latest(int $limit): array
    {
        $rows = $this->db->all('SELECT * FROM consultation_form ORDER BY created_at DESC LIMIT ?', [$limit]);

        return array_map(Consultation::fromRow(...), $rows);
    }

    /**
     * Admin list, with the name of the requesting user.
     *
     * @return list<ConsultationWithRequester>
     */
    public function allWithRequester(?ConsultationStatus $status = null): array
    {
        $sql = 'SELECT c.*, u.name, u.last_name FROM consultation_form c JOIN user u ON u.id_user = c.id_user';
        $params = [];
        if ($status !== null) {
            $sql .= ' WHERE c.status = ?';
            $params[] = $status->value;
        }

        return array_map(ConsultationWithRequester::fromRow(...), $this->db->all($sql . ' ORDER BY c.created_at DESC', $params));
    }

    public function countByStatus(ConsultationStatus $status): int
    {
        return $this->db->int('SELECT COUNT(*) FROM consultation_form WHERE status = ?', [$status->value]);
    }

    public function create(ConsultationRequest $request, int $userId): void
    {
        $this->db->execute(
            'INSERT INTO consultation_form
                (company_name, company_field, company_size, company_address, current_lighting, problem_detail,
                 goals, min_budget, max_budget, privacy_policy, updates_promotions, preferredDate, id_user)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->companyName,
                $request->companyField,
                $request->companySize,
                $request->companyAddress,
                $request->currentLighting,
                $request->problemDetail,
                $request->goals,
                $request->minBudget,
                $request->maxBudget,
                (int) $request->acceptsPrivacyPolicy,
                (int) $request->wantsUpdates,
                $request->preferredDate?->format('Y-m-d'),
                $userId,
            ],
        );
    }

    public function markFinished(int $id): void
    {
        $this->db->execute('UPDATE consultation_form SET status = ? WHERE id = ?', [ConsultationStatus::Finished->value, $id]);
    }
}
