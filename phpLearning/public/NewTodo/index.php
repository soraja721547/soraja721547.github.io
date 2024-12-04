<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../framework/Supports/functions.php';
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo_List</title>
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" 
        crossorigin="anonymous"
    >
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" 
        crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<?php
session_start();
date_default_timezone_set("Asia/Taipei");

const CLEAR_SESSION = 'CAS';

$users = $_SESSION['users'] ?? [];
$logingUser = $_SESSION['logingUsername'] ?? '';
$now = date("Y-m-d-H-i-s");

if (!empty($logingUser)){

    $todoPath = base_path("/temp/$logingUser todos");
    $todos = file_exists($todoPath)
    ? json_decode(file_get_contents($todoPath), true)
    : [];
    
    if (isset($_POST['addNewTodo']) && !empty(trim($_POST['addNewTodo']))) {
        
        // *******************clear all session**********************
        if ($_POST['addNewTodo'] == CLEAR_SESSION){
            session_destroy();
        } else {
        // **********************************************************
            $todos[] = [
                'id' => uniqid(),
                'content' => $_POST['addNewTodo'],
                'enabled' => false,
                'finishedStatus' => false,
                'createdAt' => $now,
                'updatedAt' => $now,
                'deletedAt' => null,
            ];
        }
    }
    if (isset($_POST['clearAll'])){
        $todos = [];
    }
    if (isset($_POST['edit'])){
        foreach ($todos as $index => $todo){
            if ($todo['id'] == $_POST['edit']){
                $todo['enabled'] = !$todo['enabled'];
                $todo['updatedAt'] = $now;
                if(isset($_POST[$todo['id']])){
                    $todo['content'] = $_POST[$todo['id']];
                }
                $todos[$index] = $todo;
            }
        }
    }
    if (isset($_POST['finished'])){
        foreach ($todos as $index => $todo){
            if ($todo['id'] == $_POST['finished']){
                $todo['finishedStatus'] = !$todo['finishedStatus'];
                $todo['updatedAt'] = $now;
                $todos[$index] = $todo;
            }
        }
    }
    if (isset($_POST['delete'])){
        foreach($todos as $index => $todo){
            if($todo['id'] == $_POST['delete']){
                $todo['deletedAt'] = $now;
                unset($todos[$index]);
            }
        }
    }
    file_put_contents($todoPath, json_encode($todos));

} else {
    $todos = [];
}



// ********************註冊登出入處理*****************************************************
if (isset($_POST['registerUsername'])){
    $users[] = [
        'username' => $_POST['registerUsername'],
        'password' => $_POST['registerPassword']
    ];

    $_SESSION['users'] = $users;
    echo '<script>alert(\'註冊完成\')</script>';
}

if (isset($_POST['logingUsername'])){
    foreach ($users as $index => $value){
        if (
            $value['username'] == $_POST['logingUsername'] &&
            $value['password'] == $_POST['logingPassword']
        ){
            $logingUser = $value['username'];
        
            $todoPath = base_path("/temp/$logingUser todos");
            $todos = file_exists($todoPath)
            ? json_decode(file_get_contents($todoPath), true)
            : [];

            file_put_contents($todoPath, json_encode($todos));

            echo '<script>alert(\'登入成功\')</script>';

        } else {
            echo '<script>alert(\'帳號或密碼錯誤\')</script>';
        }
        
    }
    
    $_SESSION['logingUsername'] = $logingUser;
}

if (isset($_POST['logoutCheck'])){
    $_SESSION['logingUsername'] = '';
    $todos = [];
}

?>

<style>

    main {
        background-color: rgba(255, 255, 255, .6);
        min-width: 560px;
        max-width: 680px;
        width: 50%;
        height: 87vh;
        border-top: 2rem solid rgba(200, 200, 200, .5);
        outline: 1px solid rgba(200, 200, 200, 1);
        box-shadow: 0rem 85rem 0.1rem 72vh #0d6efd;
        border-radius: 12px;
        margin: 2rem;
        padding: 1rem;
    }

    .registerDiv{
        position: absolute;
        top: 0;
        right: 0;
        margin: 10px;
        .helloUser{
            margin: auto;
        }
    }

    h1 {
        position: absolute;
        top: 27px;
    }

    .addSection {
        padding-right: 20px;
        display: flex;
        justify-content: space-between;
    }

    .listContent{
        height: 87%;
        padding-right: 20px;
        overflow-y: auto;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px grey;
        border-radius: 30px;
    }

    ::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 30px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #0000d3;
    }

    .hoverTest:hover{
        transform: translateX(5px);
    }

    .reminderText{
        margin-right: auto;
    }
    
</style>

<body class="bg-primary bg-opacity-10">

    <div class="registerDiv d-flex">
        
        <?php if(!empty($_SESSION['logingUsername'])){ ?>
        
        <p class="text-nowrap fw-bold helloUser
        <?= !empty($_SESSION['logingUsername']) ? '' : 'px-4' ?>">
            Hi !!
        </p>
        <p class="text-nowrap text-light bg-primary rounded-pill px-4 mx-3 helloUser">
            <font face="微軟正黑體">
                <?= $_SESSION['logingUsername'] ?>
            </font>
        </p>

        <?php } ?>

        <div class="">
            <button 
                class="btn btn-outline-primary py-1" 
                type="button"
                data-bs-toggle="modal" 
                data-bs-target="#registerModal">
                註冊
            </button>
            <!-- 註冊 Modal -->
            <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="registerModalLabel">
                                Register
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" id="registerInput">

                                <i data-feather="user"></i>
                                <div class="form-floating mb-3">
                                    <input 
                                        type="text" 
                                        name="registerUsername" 
                                        class="form-control" 
                                        id="floatingUsername" 
                                        placeholder="Username" 
                                        autocomplete="new-username"
                                        minlength="6"
                                        required>
                                    <label for="floatingUsername">User Name</label>
                                </div>

                                <i data-feather="lock"></i>
                                <div class="form-floating">
                                    <input 
                                        type="password" 
                                        name="registerPassword" 
                                        class="form-control" 
                                        id="floatingPassword" 
                                        placeholder="Password" 
                                        autocomplete="new-password"
                                        minlength="6"
                                        required>
                                    <label for="floatingPassword">Password</label>
                                </div>

                                <br>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <i data-feather="alert-circle"></i>
                            <p class="text-danger reminderText">請輸入最少六個字元</p>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary" form="registerInput">
                                Register
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(empty($_SESSION['logingUsername'])){ ?>
            <button 
                id="liveToastBtn"
                class="btn btn-primary py-1" 
                type="button"
                data-bs-toggle="modal" 
                data-bs-target="#logInModal">
                登入
            </button>
            <?php } ?>
            <!-- 登入 Modal -->
            <div class="modal fade" id="logInModal" tabindex="-1" aria-labelledby="logInModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="logInModalLabel">
                            Log In
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <form action="" method="post" id="loginInput">

                            <i data-feather="user"></i>
                            <div class="form-floating mb-3">
                                <input 
                                    type="text" 
                                    name="logingUsername" 
                                    class="form-control" 
                                    id="floatingUsername" 
                                    placeholder="Username" 
                                    autocomplete="new-username"
                                    minlength="6"
                                    required>
                                <label for="floatingUsername">User Name</label>
                            </div>

                            <i data-feather="lock"></i>
                            <div class="form-floating">
                                <input 
                                    type="password" 
                                    name="logingPassword" 
                                    class="form-control" 
                                    id="floatingPassword" 
                                    placeholder="Password" 
                                    autocomplete="new-password"
                                    minlength="6"
                                    required>
                                <label for="floatingPassword">Password</label>
                            </div>

                            <br>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" form="loginInput">
                            Log In
                        </button>
                    </div>
                    </div>
                </div>
            </div>
            
            <?php if(!empty($_SESSION['logingUsername'])){ ?>
            <button 
                class="btn btn-primary py-1" 
                type="submit"
                data-bs-toggle="modal" 
                data-bs-target="#logOutModal">
                登出
            </button>
            <?php } ?>
            <!-- 登出 Modal -->
            <div class="modal fade" id="logOutModal" tabindex="-1" aria-labelledby="logOutModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="logOutModalLabel">
                            Log Out
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Are you sure you want to log out ?
                        </p>
                        <form action="" method="post" id="logoutInput">
                            <input type="hidden" name="logoutCheck" value="true">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" form="logoutInput">
                            Sure
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="m-auto mt-5 mb-5">

        <h1 class="text-primary text-opacity-75 fw-bold">Todo List</h1>

        <section class="addSection">
        
            <form action="" method="post" class="mt-2 mb-3 d-flex col-9">
                <input name="addNewTodo" autocomplete="off" type="text" class="form-control fw-bold">
                <button class="btn btn-outline-primary text-nowrap" type="submit">
                    Add new todo
                </button>
            </form>
            <form action="" method="post" class="mt-2 mb-3">
                <input name="clearAll" value="true" type="hidden" class="form-control">
                <button class="btn btn-outline-primary text-nowrap" type="submit">
                    Clear all
                </button>
            </form>
        </section>

        <hr class="me-4">

        <section class="listContent">
            <?php if(!isset($_POST['logoutCheck'])){?>
                <?php foreach ($todos as $todo) { ?>

                    <form action="" method="post" class="hoverTest input-group mt-4 mb-4 ">
                        <span 
                            class="input-group-text fw-bold  col-1 text-center justify-content-center
                            <?= $todo['finishedStatus'] ? 'bg-primary text-light' : 'bg-danger opacity-75' ?>" 
                            id="basic-addon1"
                            >
                            <?= $todo['finishedStatus'] ? '<i data-feather="check"></i>' : '' ?>
                        </span>

                        <input 
                            <?= $todo['enabled'] ? '' : 'disabled' ?> 
                            name="<?= $todo['id'] ?>" 
                            value="<?= $todo['content'] ?>" 
                            autocomplete="off" 
                            type="text" 
                            class="form-control col-5
                            <?= $todo['finishedStatus'] ? 'text-decoration-line-through' : '' ?>"
                            >

                        <button 
                            <?= $todo['finishedStatus'] ? 'disabled' : '' ?> 
                            name="edit" 
                            value="<?= $todo['id'] ?>" 
                            class="btn btn-outline-primary col-2
                            <?= $todo['finishedStatus'] ? 'btn-outline-secondary' : '' ?>" 
                            type="submit"
                            >
                            <?= $todo['enabled'] ? 'Save' : 'Edit' ?>
                        </button>

                        <button 
                            <?= $todo['enabled'] ? 'disabled' : '' ?> 
                            name="finished" 
                            value="<?= $todo['id'] ?>" 
                            class="btn btn-outline-primary col-3
                            <?= $todo['enabled'] ? 'btn-outline-secondary' : '' ?>" 
                            type="submit"
                            >
                            <?= $todo['finishedStatus'] ? 'Unfinished' : 'Finished' ?>
                        </button>

                        <button 
                            name="delete" 
                            value="<?= $todo['id'] ?>" 
                            class="btn btn-outline-danger col-1" 
                            type="submit"
                            >
                            X
                        </button>
                    </form>

                <?php } ?>
            <?php } ?>
            <hr class="mt-5 <?=  empty($todos) ? 'invisible' : '' ?>">
            <h5 style="margin-top: 200px;"
                class="text-secondary text-center
                <?= !empty($logingUser) ? 'invisible' : '' ?>">
                <i data-feather="log-in" class="mx-2 pb-1"></i>
                請先登入
            </h5>
        </section>
    </main>

</body>

<script>
    feather.replace();
</script>

</html>