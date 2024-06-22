<?php 
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToDo Lista</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="favicon.png" type="favicon.png">
</head>
<body>
    <div class="main">
        <img src="logo.png" alt="logo" class="csize">
       <div class="add">
          <form action="app/add.php" method="POST">
              <input type="text" name="title" placeholder="Wpisz tytuł zadania" />
              <button type="submit">Dodaj do listy zadań </button>
          </form>
       </div>
       <div class="speak">
            <button id="speak-tasks">Wysłuchaj listy zadań</button>
            <?php
            $countQuery = $conn->query("SELECT COUNT(*) AS total FROM todos");
            $countResult = $countQuery->fetch(PDO::FETCH_ASSOC);
            $totalCount = $countResult['total'];
            ?>
            <h2 style="text-align: center; font-size: 15px; color: #888; font-weight: 500;">Zadań w liście: <?php echo $totalCount; ?></h2>
       </div>
       <?php 
          $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
       ?>
       <div class="show-todo-section">
            <?php if($todos->rowCount() <= 0){ ?>
                <div class="todo-item">
                    <div class="empty">
                        <h2>Lista jest pusta</h2>
                    </div>
                </div>
            <?php } ?>

            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <button id="<?php echo $todo['id']; ?>" class="remove-to-do">Usuń</button>
                    <?php if($todo['checked']){ ?> 
                        <input type="checkbox" class="check-box" data-todo-id ="<?php echo $todo['id']; ?>" checked />
                        <h2 class="checked"><?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <?php }else { ?>
                        <input type="checkbox" data-todo-id ="<?php echo $todo['id']; ?>" class="check-box" />
                        <h2><?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <button class="speak-todo" data-title="<?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8'); ?>"></button>
                    <?php } ?>
                    <br>
                    <small>Stworzono: <?php echo $todo['date_time'] ?></small> 
                </div>
            <?php } ?>
       </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/speech.js"></script>

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');
                
                $.post("app/remove.php", 
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');
                
                $.post('app/check.php', 
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('checked');
                              }else {
                                  h2.addClass('checked');
                              }
                          }
                      }
                );
            });

            $("#speak-tasks").click(function(){
                $('.todo-item').each(function(){
                    const isChecked = $(this).find('.check-box').prop('checked');
                    if (!isChecked) {
                        const title = $(this).find('.speak-todo').attr('data-title');
                        speak(title);
                    }
                });
            });

            $(".speak-todo").click(function(){
                const isChecked = $(this).prev().prop('checked');
                if (!isChecked) {
                    const title = $(this).attr('data-title');
                    speak(title);
                }
            });
        });
    </script>
</body>
</html>
