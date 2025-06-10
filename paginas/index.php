<?php
    session_start();

    require(__DIR__ . '/../includes/config.php');

    include('../includes/head.php');
?>
<body>
    <section id="home" class="d-flex justify-content-center align-items-center">
        <div class="container">
            <h1>Descubra, crie, participe e divirta-se.</h1>
            <p>Encontre ou crie você mesmo, eventos relevantes para você.</p>
        </div>
    </section>
    <section id="eventos">
        <div class="container">
            
        </div>
    </section>
<?php
    include('../includes/footer.php');
?>