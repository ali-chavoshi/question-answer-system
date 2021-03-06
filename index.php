<?php
include_once 'inc/actions.php';
include_once 'inc/functions.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo QA_TITLE; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<!--loading-->
<!--<div class="loading show">
    <span></span>
</div>-->
<div class="container">
    <?php
    if ($errorMasage) {
        echo "<div class='error'>" . nl2br($errorMasage) . "</div>";
    } elseif ($successMasage) {
        echo "<div class='succes'>" . nl2br($successMasage) . "</div>";
    }
    ?>
    <?php
    if (isAdmin()): ?>
    <div class='admin'>
        <p class='pAdmin'>Hello dear </p>
        <strong class='pAdmin'><?php echo $_SESSION['username']; ?></strong>
        <a class='exit' href="<?php echo QA_HOME_URL . '?exit=1'; ?>">EXIT</a>
        <?php endif; ?>
        <?php
        if (isAdmin() == false): ?>
            <dvi class="login">
                <a class="login" href="<?php echo QA_HOME_URL . 'login.php' ?>">Login</a>
            </dvi>
        <?php endif; ?>
        <div class="header">

            <div class="title">
                <a class="titleA" href="<?php echo QA_HOME_URL ?>"><?php echo QA_TITLE; ?></a>
            </div>
            <form action="" method="get">
                <div class="search">
                    <form action="" name="search" method="post" class="searchform">
                        <button class="srchbtn">
                            SEARCH
                            <i class="fa fa-search"></i>
                        </button>

                        <select class="status" name="status">
                            <option value="all">All</option>
                            <?php if (isAdmin()): ?>
                                <option value="pending">Pending</option>
                            <?php endif; ?>
                            <option value="publish">Published</option>
                            <option value="answered">Answered</option>
                        </select>
                        <div class="serchBarMain">
                            <input class="serchBar" name="search" placeholder="search">

                        </div>
                    </form>
            </form>
        </div>

    </div>
    <div class="body">
        <!--------------------------admin answer input------------------------------>
        <?php if (isAdmin()): ?>
            <div class="adminAnswer">
                <?php if (isset($_GET['answer'])): ?>
                    <strong class="titleAdmAnswer">Answer <?php echo $_GET['name'] ?> Question</strong>
                <?php else: ?>
                    <strong class="titleAdmAnswer">Click On The Answer Button</strong>

                <?php endif; ?>
                <form action="" method="post" class="formQuestion">
                    <textarea name="adminAsnwer" class="inputQ" cols="20" rows="5"></textarea>
                    <?php if (isset($_GET['answer'])): ?>
                        <input type="hidden" name="ansId" value="<?php echo $_GET['answer'] ?>">
                    <?php endif; ?>

                    <button name="submitAnswer" class="inputbtn">SUBMIT</button>
                </form>
            </div>
        <?php else: ?>
            <!---------------------------submit question--------------------------------->
            <div class="inputQuestion">
                <form action="" method="post" class="formQuestion">
                    <strong class="inputTitle">Ask a question</strong>
                    <input type="text" name="Uname" class="inputQ" placeholder="Name:" required>
                    <input type="text" name="Umail" class="inputQ" placeholder="Email:" required>
                    <input type="text" name="Uphone" class="inputQ" placeholder="Phone">
                    <textarea name="Uqst" class="inputQ" cols="15" rows="5"
                              placeholder="Enter your question:"></textarea>
                    <button name="submitQuestion" class="inputbtn">
                        SUBMIT
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <div class="content">
            <!-----------------------questions------------------->
            <?php if ($questions != null): ?>
                <?php foreach ($questions as $value): ?>
                    <div class="question">
                        <div class="quContent" id="<?php echo 'q' . $value['id'] ?>"
                             onclick="<?php echo QA_HOME_URL . '?qid=' . $value['id'] ?>">
                            <div class="nameUser">
                                <strong><?php echo ": " . $value['uname'] ?></strong>
                            </div>
                            <div class="questionline">
                                <span class="status <?php echo $value['status'] ?>"></span>
                                <?php if (isAdmin()): ?>
                                    <div class="adminbtn">
                                        <a class="adminBtnA" id="answer"
                                           href="<?php echo QA_HOME_URL . '?answer=' . $value['id'] . '&name=' . $value['uname'] ?>">ANSWER</a>
                                        <a class="adminBtnA" id="delet"
                                           href="<?php echo QA_HOME_URL . '?delete=' . $value['id'] ?>">DELETE</a>
                                        <a class="adminBtnA" id="publish"
                                           href="<?php echo QA_HOME_URL . '?publish=' . $value['id'] ?>">PUBLISH</a>
                                    </div>
                                <?php endif; ?>
                                <span class="qtex"><?php echo $value['text'] ?></span>
                                <span class="time"><?php echo $value['create_date']; ?></span>
                            </div>


                            <!----------------answers-------------------->
                            <?php if (getAnswers($value['id'])): ?>
                                <?php foreach (getAnswers($value['id']) as $ans): ?>
                                    <div class="answer">
                                        <?php if (isAdmin()): ?>
                                            <a class="adminBtnA2"
                                               href="<?php echo QA_HOME_URL . '?asnswerId=' . $ans['id'] ?> ">DELETE</a>
                                        <?php endif; ?>

                                        <span class="ans"><?php echo $ans['text'] ?></span>
                                        <span class="timeans"><?php echo $ans['create_date']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="answer">
                                    <span class="ans">Not Answered !!!</span>
                                </div>
                            <?php endif; ?>
                        </div>


                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="notFoundAnswer">
                    <strong>No answer found!!</strong>
                </div>
            <?php endif; ?>

            <!--------------------pagination---------------------------->

            <div class="pages">
                <?php $numPages = getNumPage($numQuestion);
                /*--------------------back----------------------------*/
                getPageBack($page);
                /*--------------------numbers-------------------------*/
                if ($numPages >= 3) {
                    for ($i = 1; $i <= 3; $i++) {
                        echo "<a class='page' href='" . getPageUrl($i) . "'>$i</a>";
                    }
                } else {
                    for ($i = 1; $i <= $numPages; $i++) {
                        echo "<a class='page' href='" . getPageUrl($i) . "'>$i</a>";
                    }
                }
                /*---------------------next---------------------------*/
                getPageNext($numPages);

                ?>

            </div>


        </div>
    </div>
</div>

</body>
<script src="js/jquery.js"></script>
<script src="js/javascripts.js"></script>

</html>
