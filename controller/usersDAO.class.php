<?php
class UsersDAO_class
{
    public function readUser($id, $name, $email)
    {
        try {
            $connect = new Connection();
        } catch (Exception $e) {
            echo "Erro ao consultar banco de dados: " . $e->getMessage();
        }

        try {
            $sql = "SELECT * from users";
            //echo "passou1";
            $p_sql = $connect->query($sql);
            //echo "passou2";

            $p_sql->execute();
            //echo "passou4";
            $lista = $p_sql->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        } catch (Exception $e) {
            echo "Erro ao consultar Usuários: " . $e->getMessage();
        }
    }

    public function createUser($user)
    {
        $connect = new Connection();
        try{
            $sql = "insert into users (name, email) values (:name, :email)";
            $p_sql = $connect->query($sql);
            $p_sql -> bindValue(":name", $user->getName());
            $p_sql -> bindValue(":email", $user->getEmail());

            if ($p_sql->execute()){
                $response['message'] = 'Usuário cadastrado com sucesso';
            } else {
                $response['error'] = 'Erro ao cadastrar usuário';
            }
        } catch (Exception $e){
            $response['error'] = 'Erro ao cadastrar usuário: ' . $e->getMessage();
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($response);
    }
    

    
}




?>