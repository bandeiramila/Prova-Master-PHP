<?php

require 'connection.php';

$connection = new Connection();

//$connection->query("INSERT INTO users (name, email) values ('ultimo teste', 'teste@email')");

$users = $connection->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="crud-container">
        <nav>
            <h1>Usuários</h1>
        </nav>

        <!-- FORMULÁRIO -->
        <section>
            <?php 
                echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>    
                        <th>Nome</th>    
                        <th>Email</th>
                        <th>Cores</th>
                        <th>Ação</th>    
                    </tr>
                </thead>
                <tbody>";

                foreach ($users as $user) {
                    echo sprintf(
                        "<tr>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td class='td-color' data-id='%s' data-name='%s'>
                                <div id='color_blue' class='colors' data-isset=''></div>
                                <div id='color_green' class='colors'></div>
                                <div id='color_yellow' class='colors'></div>
                                <div id='color_red' class='colors'></div>
                            </td>
                            <td>
                                <a href='#' class='btn-edit' data-id='%s' data-name='%s' data-email='%s'>Editar</a>
                                <a href='#' class='btn-delete' data-id='%s' data-name='%s' data-email='%s'>Deletar</a>
                            </td>
                        </tr>",
                        $user->id, $user->name, $user->email,
                        $user->id,$user->name,
                        $user->id, $user->name, $user->email,
                        $user->id, $user->name, $user->email
                    );
                }
                echo "</tbody></table>";
            ?>
            
            <div class="form_new_row">
                <form action="" method="post">
                    <!-- <input class="new_user" type="text" id="new_user" name="new_user" required placeholder="Novo Usuário"> -->
                    <button value="send_new_user" class="btn-create">Cadastrar Novo Usuário</button>
                </form>
            </div>
        </section>
    </div>

    <!-- POP UP DE EDIÇÃO -->
    <div class="popup-overlay" id="popup-edit">
        <div class="popup-content">
            <form action="update_user.php" method="post">
                <h2>Editar Usuário</h2>
                <input type="hidden" id="edit-id" name="id">
                <input type="text" id="edit-name" name="name" placeholder="Nome">
                <input type="email" id="edit-email" name="email" placeholder="Email">
                <button type="submit">Salvar</button>
                <button type="button" class="cancel" id="cancel-edit-button">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Ação do pop up de edição -->
    <script>
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                document.getElementById('edit-id').value = button.dataset.id;
                document.getElementById('edit-name').value = button.dataset.name;
                document.getElementById('edit-email').value = button.dataset.email;

                document.getElementById('popup-edit').style.display = 'flex';
            });
        });

        document.getElementById('cancel-edit-button').addEventListener('click', () => {
            document.getElementById('popup-edit').style.display = 'none';
        });
    </script>

    <!-- POP UP DE DELETAR -->
    <div class="popup-overlay" id="popup-delete">
        <div class="popup-content">
            <form action="delete_user.php" method="post">
                <h2>Excluir Usuário</h2>
                <input type="hidden" id="delete-id" name="id">
                <p id="delete-message"></p>
                <button type="submit">Deletar</button>
                <button type="button" class="cancel" id="cancel-delete-button">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Ação do pop up de delete -->
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const userName = button.dataset.name;
                const userEmail = button.dataset.email;
                document.getElementById('delete-message').innerText = `Tem certeza que deseja excluir o usuário ${userName} (${userEmail})?`;
                document.getElementById('delete-id').value = button.dataset.id;
                document.getElementById('popup-delete').style.display = 'flex';
            });
        });
        document.getElementById('cancel-delete-button').addEventListener('click', () => {
            document.getElementById('popup-delete').style.display = 'none';
        });
    </script>

    <!-- POP UP DE CADASTRO -->
    <div class="popup-overlay" id="popup-create">
        <div class="popup-content">
            <form action="create_user.php" method="post">
                <h2>Cadastrar Novo Usuário</h2>
                <input type="text" id="create-name" name="name" placeholder="Nome">
                <input type="email" id="create-email" name="email" placeholder="Email">
                <button type="submit">Salvar</button>
                <button type="button" class="cancel" id="cancel-create-button">Cancelar</button>
            </form>
        </div>
    </div>
        
    <!-- Ação do pop up de criar novo usuário -->
    <script>
        document.querySelectorAll('.btn-create').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                document.getElementById('create-name');
                document.getElementById('create-email');

                document.getElementById('popup-create').style.display = 'flex';
            });
        });

        document.getElementById('cancel-create-button').addEventListener('click', () => {
            document.getElementById('popup-create').style.display = 'none';
        });
    </script>

    <!-- POP UP DE CORES -->
    <div class="popup-overlay" id="popup-colors">
        <div class="popup-content">
            <form action="update_user.php" method="post">
                <h2>Cores do Usuário</h2>
                <input type="hidden" id="color-user-id" name="id">
                <p id="color-message"></p>
                <!-- <input type="text" id="color-user-name" name="name" placeholder="Nome"> -->
                <div>
                    <button type="button" class="color-button" id="blue-button"></button>
                    <button type="button" class="color-button" id="green-button"></button>
                    <button type="button" class="color-button" id="yellow-button"></button>
                    <button type="button" class="color-button" id="red-button"></button>
                </div>
                <div>
                    <button type="submit">Salvar</button>
                    <button type="button" class="cancel" id="cancel-color-button">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ação do pop up de cores -->
    <script>
        document.querySelectorAll('.td-color').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const userName = button.dataset.name;
                document.getElementById('color-message').innerText = `Cores do usuário: ${userName}`;
                document.getElementById('color-user-id').value = button.dataset.id;

                document.getElementById('popup-colors').style.display = 'flex';
            });
        });

        document.getElementById('cancel-color-button').addEventListener('click', () => {
            document.getElementById('popup-colors').style.display = 'none';
        });
    </script>
</body>
</html>

