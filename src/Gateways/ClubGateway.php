<?php
namespace Src\Gateways;

class ClubGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;    
    }

    public function findAll()
    {
        $statement = "
            SELECT
                id, clube, saldo_disponivel
            FROM
                Clubes;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO Clubes
                (clube, saldo_disponivel)
            VALUES
                (:clube, :saldo_disponivel);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'clube' => $input['clube'],
                'saldo_disponivel'  => $input['saldo_disponivel']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function useResource(Array $input)
    {
        $clubId = $input['clube_id'];
        $resourceId = $input['recurso_id'];
        $amount = $input['valor_consumo'];

        // verify that the club balance is sufficient
        $clubStatement = $this->db->prepare("
            SELECT saldo_disponivel, clube
            FROM Clubes
            WHERE id = :clubId;
        ");
        $clubStatement->bindParam(':clubId', $clubId);
        $clubStatement->execute();

        $clubResult = $clubStatement->fetch(\PDO::FETCH_ASSOC);

        $nameClub = $clubResult['clube'];
        $availableBalance = $clubResult['saldo_disponivel'];

        // return message that the club balance is insufficient
        if ($availableBalance < $amount) {
            $response = array(
                'status' => false,
                'body' => 'O saldo disponível do clube é insuficiente'
            );
    
            return $response;
        }

        // verify that the resource balance is sufficient
        $resourceStatement = $this->db->prepare("
            SELECT saldo_disponivel
            FROM Recursos
            WHERE id = :resourceId;
        ");
        $resourceStatement->bindParam(':resourceId', $resourceId);
        $resourceStatement->execute();

        $resourceResult = $resourceStatement->fetch(\PDO::FETCH_ASSOC);

        $resourceBalance = $resourceResult['saldo_disponivel'];

        // return message that the resource balance is insuficient
        if ($resourceBalance < $amount) {
            $response = array(
                'status' => false,
                'body' => 'O saldo disponível do recurso é insuficiente'
            );
    
            return $response;
        }

        //update balance
        $newClubBalance     = $availableBalance - $amount;
        $newResourceBalance = $resourceBalance  - $amount;

        $this->db->beginTransaction();

        try {
            // update club
            $clubUpdateStatement = $this->db->prepare("
                UPDATE Clubes
                SET saldo_disponivel = :newClubBalance
                WHERE id = :clubId;
            ");
            $clubUpdateStatement->bindParam(':newClubBalance', $newClubBalance);
            $clubUpdateStatement->bindParam(':clubId', $clubId);
            $clubUpdateStatement->execute();

            // update resource
            $resourceUpdateStatement = $this->db->prepare("
                UPDATE Recursos
                SET saldo_disponivel = :newResourceBalance
                WHERE id = :resourceId;
            ");
            $resourceUpdateStatement->bindParam(':newResourceBalance', $newResourceBalance);
            $resourceUpdateStatement->bindParam(':resourceId', $resourceId);
            $resourceUpdateStatement->execute();

            $this->db->commit();

            return array(
                'clube' => $nameClub,
                'saldo_anterior' => $availableBalance,
                'saldo_atual' => $newClubBalance,
            );
        } catch (\PDOException $e) {
            // if exception, undo the transtaction and rethrow exception
            $this->db->rollback();
            exit($e->getMessage());
        }
    }
}