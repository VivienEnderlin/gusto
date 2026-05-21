<?php
require_once __DIR__ . '/BaseModel.php';

class Commande extends BaseModel {

    // Récupérer toutes les tables d'un restaurant
    public function getByEtablissementGroupedByTicket($id_etablissement) {

        $stmt = $this->personnalSelect(
            "commande",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];

        foreach ($rows as $row) {

            $ticket = $row['id_ticket'];

            // Initialisation du ticket
            if (!isset($grouped[$ticket])) {
                $grouped[$ticket] = [
                    'id_ticket' => $ticket,
                    'id_table' => $row['id_table'],
                    'id_etablissement' => $row['id_etablissement'],
                    'devise' => $row['devise'],
                    'montant_total' => 0,
                    'date_enreg' => $row['date_enreg'],
                    'commandes' => []
                ];
            }
            else {
                // ✅ garder la date la plus récente du ticket
                 if (strtotime($row['date_enreg']) > strtotime($grouped[$ticket]['date_enreg'])) {
                    $grouped[$ticket]['date_enreg'] = $row['date_enreg'];
                }
            }

            // Sécuriser JSON
            $items = json_decode($row['commande'], true) ?? [];

            foreach ($items as $item) {

                // Sécurisation des valeurs
                $prix = (float)($item['prix'] ?? 0);
                $quantite = (int)($item['quantite'] ?? 0);
                $total = round((float)($item['total'] ?? ($prix * $quantite)), 2);

                $grouped[$ticket]['commandes'][] = [
                    'id' => $item['id'] ?? null,
                    'libelle' => $item['libelle'] ?? '',
                    'prix' => $prix,
                    'quantite' => $quantite,
                    'total' => $total,
                    'etat' => $row['etat']
                ];

                // ✅ TOTAL FIABLE (sans SQL)
                $grouped[$ticket]['montant_total'] += $total;
            }

            // arrondi final du ticket
            $grouped[$ticket]['montant_total'] = round($grouped[$ticket]['montant_total'], 2);
        }

        usort($grouped, function ($a, $b) {
            return strtotime($b['date_enreg']) - strtotime($a['date_enreg']);
        });

        return array_values($grouped);
    }

    public function getstatByEtablissement($id_etablissement)
    {
        return $this->db->personnalSelect(
            'commande',
            '
            YEAR(date_enreg) AS annee,
            MONTH(date_enreg) AS mois,
            SUM(montant_total) AS total
            ',
            '
            WHERE id_etablissement = ?
            GROUP BY YEAR(date_enreg), MONTH(date_enreg)
            ORDER BY annee ASC, mois ASC
            ',
            [$id_etablissement]
        );
    }

    // Récupérer par ID
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "commande",
            "*",
            "WHERE id_commande = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer par ID et restaurant (sécurisé)
    public function getByIdAndEtablissement($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "commande",
            "*",
            "WHERE id_commande = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isTableActive($id_table, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "service",
            "*",
            "WHERE id_table = ? AND id_etablissement = ? AND date_heure_fermeture IS NULL ORDER BY id_service DESC LIMIT 1",
            [$id_table, $id_etablissement]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Créer
    public function create($data, $id_etablissement) {
        $this->insert(
            "commande",
            ["id_ticket",'id_table','id_etablissement','commande','montant_total','devise','date_enreg','etat'],
            [   
                $data['id_ticket'],
                $data['id_table'],
                $id_etablissement,
                json_encode($data['commande']),
                $data['montant_total'],
                $data['devise'],
                date("Y-m-d H:i:s", time() + 3600),
                "En attente"
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // Mettre à jour une table
    public function update($id, $id_etablissement, $data) {
        return $this->set(
            "commande",
            ['id_table','commande','montant_total','etat'],
            [
                $data['id_table'],
                json_encode($data['commande']),
                $data['montant_total'],
                $data['etat'],
            ],
            "WHERE id_commande = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

    // delete

   public function deleteByTicket($id_ticket, $id_etablissement) {

        return $this->personalDelete(
            "commande",
            "WHERE id_ticket = ? AND id_etablissement = ? AND etat NOT IN (?, ?)",
            [$id_ticket, $id_etablissement, "Payé", "Servi"]
        );
    }

    /*
    public function delete($id, $id_etablissement){
        return $this->personalDelete(
            "commande",
            "WHERE id_commande = ? AND id_etablissement = ? AND etat IN (?, ?)",
            [$id, $id_etablissement, 'Servi', 'Payé']
        );
    }
    */


   public function getByServiceRange($id_etablissement, $debut, $fin){

        $stmt = $this->personnalSelect(
            "commande",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];

        foreach ($rows as $row) {

            $ticket = $row['id_ticket'];
            $dateTicket = $row['date_enreg'];

            // Initialisation du ticket
            if (!isset($grouped[$ticket])) {
                $grouped[$ticket] = [
                    'id_ticket' => $ticket,
                    'id_table' => $row['id_table'],
                    'id_etablissement' => $row['id_etablissement'],
                    'montant_total' => 0,
                    'date_enreg' => $dateTicket,
                    'commandes' => []
                ];
            }

            // garder la date la plus récente du ticket
            if (strtotime($dateTicket) > strtotime($grouped[$ticket]['date_enreg'])) {
                $grouped[$ticket]['date_enreg'] = $dateTicket;
            }

            // commandes JSON
            $items = json_decode($row['commande'], true) ?? [];

            foreach ($items as $item) {

                $prix = (float)($item['prix'] ?? 0);
                $quantite = (int)($item['quantite'] ?? 0);
                $total = round((float)($item['total'] ?? ($prix * $quantite)), 2);

                $grouped[$ticket]['commandes'][] = [
                    'id' => $item['id'] ?? null,
                    'libelle' => $item['libelle'] ?? '',
                    'prix' => $prix,
                    'quantite' => $quantite,
                    'total' => $total,
                    'etat' => $row['etat']
                ];

                $grouped[$ticket]['montant_total'] += $total;
            }

            $grouped[$ticket]['montant_total'] =
                round($grouped[$ticket]['montant_total'], 2);
        }

        // 🧠 FILTRE SUR LES TICKETS (IMPORTANT)
        foreach ($grouped as $key => $ticket) {

            if (
                $ticket['date_enreg'] < $debut ||
                $ticket['date_enreg'] > $fin
            ) {
                unset($grouped[$key]);
            }
        }

        // tri du plus récent au plus ancien
        usort($grouped, function ($a, $b) {
            return strtotime($b['date_enreg']) - strtotime($a['date_enreg']);
        });

        return array_values($grouped);
    }

    public function getByTicketAndEtablissement($id_ticket, $id_etablissement) {

        $stmt = $this->personnalSelect(
            "commande",
            "*",
            "WHERE id_ticket = ? AND id_etablissement = ?",
            [$id_ticket, $id_etablissement]
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateEtatById($id_commande, $id_etablissement, $etat) {

        return $this->set(
            "commande",
            ["etat"],
            [$etat],
            "WHERE id_commande = ? AND id_etablissement = ?",
            [$id_commande, $id_etablissement]
        );
    }

}
?>