<?php

require 'connection.php';

$connection = new Connection();

//$connection->query("INSERT INTO users (name, email) values ('ultimo teste', 'teste@email')");
//$connection->query("insert into user_colors (user_id,color_id) values (3,1)");

$users = $connection->query("SELECT u.id AS id, u.name AS name, u.email AS email,
                        CASE 
                            WHEN EXISTS (SELECT 1 FROM user_colors uc WHERE uc.user_id = u.id AND uc.color_id = 1) THEN 'true'
                            ELSE 'false'
                        END AS blue,
                        CASE 
                            WHEN EXISTS (SELECT 1 FROM user_colors uc WHERE uc.user_id = u.id AND uc.color_id = 2) THEN 'true'
                            ELSE 'false'
                        END AS red,
                        CASE 
                            WHEN EXISTS (SELECT 1 FROM user_colors uc WHERE uc.user_id = u.id AND uc.color_id = 3) THEN 'true'
                            ELSE 'false'
                        END AS yellow,
                        CASE 
                            WHEN EXISTS (SELECT 1 FROM user_colors uc WHERE uc.user_id = u.id AND uc.color_id = 4) THEN 'true'
                            ELSE 'false'
                        END AS green
                    FROM users u ORDER BY u.id;");
//echo $users;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <link rel="stylesheet" href="css/style.css">
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
                            <td class='td-color' data-id='%s' data-name='%s' data-blue='%s' data-green='%s' data-yellow='%s' data-red='%s'>
                                <div id='color_blue' class='colors' data-isset='%s' style='display:%s;'></div>
                                <div id='color_green' class='colors' data-isset='%s' style='display:%s;'></div>
                                <div id='color_yellow' class='colors' data-isset='%s'style='display:%s;'></div>
                                <div id='color_red' class='colors' data-isset='%s' style='display:%s;'></div>
                            </td>
                            <td>
                                <a href='#' class='btn-edit' data-id='%s' data-name='%s' data-email='%s'>Editar</a>
                                <a href='#' class='btn-delete' data-id='%s' data-name='%s' data-email='%s'>Deletar</a>
                            </td>
                        </tr>",
                        $user->id, $user->name, $user->email,
                        $user->id,$user->name,
                        $user->blue,
                        $user->green,
                        $user->yellow,
                        $user->red,

                        // filter_var($user->blue, FILTER_VALIDATE_BOOLEAN),
                        // filter_var($user->green, FILTER_VALIDATE_BOOLEAN),
                        // filter_var($user->yellow, FILTER_VALIDATE_BOOLEAN),
                        // filter_var($user->red, FILTER_VALIDATE_BOOLEAN),
                        $user->blue, filter_var($user->blue, FILTER_VALIDATE_BOOLEAN) ? "block" : "none",
                        $user->green, filter_var($user->green, FILTER_VALIDATE_BOOLEAN) ? "block" : "none",
                        $user->yellow, filter_var($user->yellow, FILTER_VALIDATE_BOOLEAN) ? "block" : "none",
                        $user->red, filter_var($user->red, FILTER_VALIDATE_BOOLEAN) ? "block" : "none",
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
            <form action="update_color_user.php" method="post" class="form">
                <h2>Cores do Usuário</h2>
                <input type="hidden" id="color-user-id" name="id">
                <input type="hidden" id="hidden-blue" name="blue">
                <input type="hidden" id="hidden-green" name="green">
                <input type="hidden" id="hidden-yellow" name="yellow">
                <input type="hidden" id="hidden-red" name="red">
                <p id="color-message"></p>
                <div>
                    <button type="button" class="color-button" id="blue-button" data-state="false"></button>
                    <button type="button" class="color-button" id="green-button" data-state="false"></button>
                    <button type="button" class="color-button" id="yellow-button" data-state="false"></button>
                    <button type="button" class="color-button" id="red-button" data-state="false"></button>
                </div>
                <div>
                    <button type="submit" id="envio" >Salvar</button>
                    <button type="button" class="cancel" id="cancel-color-button">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ação do pop up de cores -->
    <script>
        function updateButtonStyle(button) {
            const isTrue = button.dataset.state === "true";
            button.classList.toggle("true", isTrue);
            button.classList.toggle("false", !isTrue);
        }
        
        document.querySelectorAll('.td-color').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const userName = button.dataset.name;
                document.getElementById('color-message').innerText = `Cores do usuário: ${userName}`;
                document.getElementById('color-user-id').value = button.dataset.id;

                const blueButton = document.getElementById('blue-button');
                const greenButton = document.getElementById('green-button');
                const yellowButton = document.getElementById('yellow-button');
                const redButton = document.getElementById('red-button');

                blueButton.dataset.state = button.dataset.blue === "true" ? "true" : "false";
                greenButton.dataset.state = button.dataset.green === "true" ? "true" : "false";
                yellowButton.dataset.state = button.dataset.yellow === "true" ? "true" : "false";
                redButton.dataset.state = button.dataset.red === "true" ? "true" : "false";

                updateButtonStyle(blueButton);
                updateButtonStyle(greenButton);
                updateButtonStyle(yellowButton);
                updateButtonStyle(redButton);

                document.getElementById('popup-colors').style.display = 'flex';
            });
        });

        document.querySelector('.form').addEventListener('submit', (event) => {
            
            document.getElementById('hidden-blue').value = document.getElementById('blue-button').dataset.state;
            document.getElementById('hidden-green').value = document.getElementById('green-button').dataset.state;
            document.getElementById('hidden-yellow').value = document.getElementById('yellow-button').dataset.state;
            document.getElementById('hidden-red').value = document.getElementById('red-button').dataset.state;

            // document.getElementById('hidden-red').value = true;

            console.log("Campos ocultos atualizados:");
            console.log("hidden-blue:", document.getElementById('hidden-blue').value);
            console.log("hidden-green:", document.getElementById('hidden-green').value);
            console.log("hidden-yellow:", document.getElementById('hidden-yellow').value);
            console.log("hidden-red:", document.getElementById('hidden-red').value);

        });

        document.getElementById('cancel-color-button').addEventListener('click', () => {
            document.getElementById('popup-colors').style.display = 'none';
        });

        document.querySelectorAll('.color-button').forEach(button => {
            updateButtonStyle(button);

            button.addEventListener('click', () => {
                const isTrue = button.dataset.state === "true";
                button.dataset.state = isTrue ? "false" : "true";
                console.log(`Button ${button.id} state is now:`, button.dataset.state);
                updateButtonStyle(button);
            });
        });
    </script>
</body>
</html>

