<?php

    //require('../inc/inc.autoload.php');
    require('../inc/class.TemplatePower.php');
    require('../model/Class.UsuariosDAOExt.php');
    require('../model/class.DbAdmin.php');
    require('../inc/inc.util.php');

    $tpl = new TemplatePower("../view/_master.htm");

    $tpl->prepare();

    if( isset($_GET['op']) && ($_GET['op'] == 'Inserir') ){
    
        $Dados = new usuariosDAOExt();

        //Pega informações do formulário enviadas por POST
        $nome           = $_POST['nome'];
        $sobreNome      = $_POST['sobrenome'];
        $email          = $_POST['email'];
        $confirmaEmail  = $_POST["conf_email"];
        $senha          = $_POST['password'];
        $dtNasc         = $_POST['dt_nascimento'];
        $sexo           = $_POST['sexoRadios'];

        
        //Faz validações
        if( empty($nome) ) {
            $msg = 'Informe o seu nome.';
        }

        if( empty($sobreNome) ) {
            $msg = 'Insira seu sobrenome';
        }

        if( empty($email) ){
            $msg = 'Informe seu e-mail.';
        }
        else{
            if($email != $confirmaEmail){
                $msg = 'e-mail invalido';
            }
        }

        $SenhaLen = strlen($senha);
        if (($SenhaLen < 6) || ($SenhaLen > 12)) {
            $msg = 'A senha deve ter entre 6 e 12 caracteres';
        }

        if( empty ($dtNasc)) {
            $msg = 'Insira data de nascimento';
        }
        
        if( empty($msg) ){
            
            //Seta os valores no objeto
            $Dados->setNome($nome);
            $Dados->setSobrenome($sobreNome);
            $Dados->setEmail($email);
            $Dados->setSenha(md5($senha));    
            $Dados->setDt_nasc(formatISO($dtNasc,'d/m/Y') );
            $Dados->setSexo($sexo);

            //Insere registros
            list($codErro, $msgErro, $Dados) = UsuariosDAOExt::insert($Dados);
            
            if( isset($codErro) && ($codErro == '0') ){
                $msg = 'Cadastrado com Sucesso!';
                
                $tpl->newBlock("mensagem");
                $tpl->assign("msg", $msg);
            }
        
        }
        else{
            $tpl->assign("nomeCadastro", $nome);
            $tpl->assign("sobrenomeCadastro", $sobreNome);
            $tpl->assign("emailCadastro", $email);
            $tpl->assign("confEmailCad", $confirmaEmail);
            $tpl->assign("senhaCadastro", $senha);
            $tpl->assign("dtNascimento", $dtNasc);
            
            $tpl->newBlock("mensagem");
            $tpl->assign("msg", $msg);
            
        }
    }
    $tpl->printToScreen(); 
?>
