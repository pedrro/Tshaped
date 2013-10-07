<?php
    //require('../inc/inc.autoload.php');
    require('../inc/class.TemplatePower.php');
    require('../model/class.UsuariosDAO.php');
    require('../model/class.DbAdmin.php');
        
    $tpl = new TemplatePower("../view/_master.htm");

    $tpl->prepare();
    
    $Dados = new usuariosDAO();  
    
    //print_r($_GET);
   
    //if( isset($_GET) && ($_GET['op'] == 'Inserir' ) ){
    // echo('teste');
        //Pega informações enviadas do formulário
        $nome           = $_POST['nome'];
        $sobreNome      = $_POST['sobrenome'];
        $email          = $_POST['email'];
        $confirmaEmail  = $_POST["conf_email"];
        $senha          = $_POST['password'];
        $dtNasc         = $_POST['dt_nascimento'];
        $sexo           = $_POST['sexoRadios'];

        //Guarda na sessão
         session_start();

         $_SESSION['nome']          = $nome; 
         $_SESSION['sobrenome']     = $sobreNome;
         $_SESSION['email']         = $email;
         $_SESSION['conf_email']    = $confirmaEmail;
         $_SESSION['password']      = $senha;
         $_SESSION['dt_nascimento'] = $dtNasc;        
         $_SESSION['sexoRadios']    = $sexo;

            //echo($_SESSION['nome']);
        //Validação do Formulário
        if($nome == "") {
            $msg = 'Campo obrigatório!';
            //$tpl->assign("_ROOT.RetornoPhp", "Insira seu nome");
            //$tpl->assign('msg', 'Insira seu nome');
            /*$tpl->printToScreen(); 
            return;*/
        }

        if($sobreNome == "") {
            //$tpl->assign('msg', 'Insira seu sobrenome');
            $msg = 'Insira seu sobrenome';
            /*$tpl->printToScreen();
            return;*/
        }

        //valida email e confirmaÃ§Ã£o
        if(($email == "") /*&& ($email == $confirmaEmail)*/) {
            //$tpl->assign('msg', 'e-mail invalido');
            $msg = 'e-mail invalido';
            /*$tpl->printToScreen();
            return;*/
        }

        //Validacao de senha
        $SenhaLen = strlen($senha);
        if (($SenhaLen < 6) || ($SenhaLen > 12)) {
            //$tpl->assign('msg', 'A senha deve ter entre 6 e 12 caracteres');
            $msg = 'A senha deve ter entre 6 e 12 caracteres';
            /*$tpl->printToScreen();
            return;*/
        }


        //Validacao data de nascimento
        if($dtNasc == "") {
            //$tpl->assign('msg', 'Insira data de nascimento');
            $msg = 'Insira data de nascimento';
            /*$tpl->printToScreen();
            return;*/
        }
        //


        //Validacao do sexo
        /*$sexo = $_POST["sexoRadios"];
        if(($sexo != 'M') && ($sexo != 'F')) {
            $tpl->assign('msg', 'Insira data de nascimento');
        }
        */

        if( !empty($msg) ){

            $tpl->assign('nome', $_SESSION['nome']);
            $tpl->assign('sobrenome', $_SESSION['nome']);
            $tpl->assign('email', $_SESSION['nome']);
            $tpl->assign('conf_email', $_SESSION['nome']);
            $tpl->assign('password', $_SESSION['nome']);
            $tpl->assign('dt_nascimento', $_SESSION['nome']);
            $tpl->assign('sexoRadios', $_SESSION['nome']);

            //$tpl->newBlock("mensagem"); //da problema de layout
            $tpl->assign("msg", $msg);
            //$tpl->assign('msg', $msg);

        }
        else{
           // die("opa");
            //Seta nome em caixa baixa
            $Dados->setNome(strtolower($nome));
            $Dados->setSobrenome(strtolower($sobreNome));
            $Dados->setEmail($email);
            $Dados->setSenha(md5($senha));    
            $Dados->setDt_nasc($dtNasc);
            $Dados->setSexo($sexo);

            //Executa insert
            $Retorno = $Dados::insert($Dados);

            if(!$Retorno)
                $tpl->assign('msg', 'Cadastrado com sucesso');
            else
                $tpl->assign('msg', $Retorno[1]);
        }
        
   // }
    $tpl->printToScreen();
?>
