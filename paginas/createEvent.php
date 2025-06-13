<?php
    
    session_start();
    require(__DIR__ . '/../includes/config.php');
    // if($_SESSION['email'] == null){
    //     echo "<script>
    //         alert('Voce precisa estar logado para criar um evento.');
    //     </script>";
    //     header("Location: ./login.php");
    // }



    include('../includes/head.php');
?>
<body>
        
<?php
    include('../includes/footer.php');
?>