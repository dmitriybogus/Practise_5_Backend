<?php

namespace guestbook\Controllers;

// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions

class GuestbookController {

    public function execute() {

        $aConfig = require_once 'config.php';

        // TODO 2: ROUTING

        // TODO 3.raw: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3.raw) transforming data

        // 1. Create empty $infoMessage
        $infoMessage = '';

        // 2. handle form data
        if (!empty($_POST['name']) && !empty($_POST['email']) &&!empty($_POST['text'])) {

            // 3. Prepare data
            $aComment = $_POST;
            $aComment['date'] = date('Y-m-d H:i:s');

            // create new comment
            $pdo = new \PDO('mysql:host=localhost;dbname=guestbook','root','');
            $sth = $pdo->query("INSERT INTO comments (email, name, text, date) VALUES ('". $aComment['email']."','". $aComment['name']."','". $aComment['text']."','". $aComment['date']."')");
            $sth = null;
            $pdo = null;
        }
        elseif (!empty($_POST)) {
            $infoMessage = 'Заполните поля формы!';
        }

        $arguments = [
            'infoMassage' => $infoMessage
        ];

        $this->renderView($arguments);
    }

    // TODO 4: RENDER: 1) view (html) 2) data (from php)
    public function renderView($arguments = []) {
        ?>
        <!DOCTYPE html>
        <html>

        <?php require_once 'ViewSections/sectionHead.php' ?>

        <body>

        <div class="container">

            <!-- navbar menu -->
            <?php require_once 'ViewSections/sectionNavbar.php' ?>
            <br>

            <!-- guestbook section -->
            <div class="card card-primary">
                <div class="card-header bg-primary text-light">
                    Guestbook form
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">

                            <!-- form -->
                            <form method="post" name="form" class="fw-bold">
                                <div class="form-group">
                                    <label for="exampleInputEmail">Email address</label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName">Name</label>
                                    <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Enter name">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputText">Text</label>
                                    <textarea name="text" class="form-control" id="exampleInputText" placeholder="Enter text" required></textarea>
                                </div><br>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Send">
                                </div>
                            </form>
                            <br>
                        </div>

                        <!-- TODO: render php data   -->

                        <?php
                        if ($arguments['infoMassage']) {
                            echo "<span style='color:red'>{$arguments['infoMessage']}</span>";
                        }
                        ?>

                    </div>
                </div>
            </div>

            <br>

            <div class="card card-primary">
                <div class="card-header bg-body-secondary text-dark">
                    Сomments
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">

                            <!-- TODO: render php data   -->

                            <?php

                            // select from database
                            $pdo = new \PDO('mysql:host=localhost;dbname=guestbook','root','');

                            // render data
                            foreach ($pdo->query('SELECT * FROM comments') as $comment) {
                                print_r($comment['name'] . '<br>');
                                print_r($comment['email'] . '<br>');
                                print_r($comment['text'] . '<br>');
                                print_r($comment['date'] . '<br>');
                                print ('<hr>');
                            }
                            $db = null;

                            var_dump($comment['name']);

                            ?>

                        </div>
                    </div>
                </div>
            </div>

        </body>
        </html>
        <?php
    }
}