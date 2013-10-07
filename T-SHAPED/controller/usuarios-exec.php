<?php
    //Criar Sessão
    session_start();
    
    //require('../inc/inc.autoload.php');
    require('../inc/class.TemplatePower.php');
    require('../model/class.UsuariosDAOExt.php');
    require('../model/class.DbAdmin.php');
    require('../inc/inc.util.php');

    $tpl = new TemplatePower("../view/usuarios.htm");

    $tpl->prepare();
    
    //Pega dados da Sessão
    $nomeUsuario  = $_SESSION['nomeUsuario'];
    $idUsuario    = $_SESSION['idUsuario'];
    $emailUsuario = $_SESSION['emailUsuario'];
//die($idUsuario);
//print_r($_REQUEST);
//die();
    /******************************************************
     * Listar Qualificações
    ******************************************************/    
    if( isset($_REQUEST['op']) && ($_REQUEST['op'] == 'Listar') ){

        //Chama função para listar qualificacoes
    //listaQualif($idUsuario, $tpl);
        
        //Seta valores default quando entra na tela pela 1 vez
        //$tpl->assign("radioI", 'checked');
        //$tpl->assign("nota", '1');
        //$tpl->assign("notaQualificacao", '1');
       
        $tpl->assign("nomeUsuario", $nomeUsuario);
    }

    /******************************************************
     * Insert de Usuários
    ******************************************************/
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
            $Dados->setDt_nasc(formatISO($dtNasc,'d/m/Y', "date") );
            $Dados->setSexo($sexo);

            //Insere registros
            list($codErro, $msgErro, $Dados) = UsuariosDAOExt::insert($Dados);
            
            if( isset($codErro) && ($codErro == '0') ){
                /*$msg = 'Cadastrado com Sucesso!';
                
                $tpl->newBlock("mensagem");
                $tpl->assign("msg", $msg);*/
                header('location: ../view/usuarios.htm');
            }
            else{
                $msg = 'Erro no Insert: '.$msgErro;
                
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
    /******************************************************
     * Edição de Usuários
    ******************************************************/
    
    /******************************************************
     * Exclusão de Usuários
    ******************************************************/
    
    $tpl->printToScreen(); 

    /******************************************************
     * FUNÇÕES
    ******************************************************/       
/*    function listaQualif($idUsuario, $tpl){
                        

        $filtro1 = ' id = '.$idUsuario;
        
        //Busca dados de usuarios
        list($countReg, $DadosUsuario) = usuariosDAOExt::select($filtro1);

        if($countReg > 0){
           foreach ($DadosUsuario as $i => $lstUsuario) {     
               $objUsuario = $lstUsuario;
           }   
           
           $cor_fundo_t = $objUsuario->getCor_fundo_t();

           $tpl->assignGlobal("cor_fundo_t", $cor_fundo_t);
           
        }
        
        $DadosQualifUsu = $objUsuario->getQualificacoes();
        
        //VOLTAR AQUI      
        echo '<pre>';
        print_r($DadosQualifUsu);
        echo '</pre>';
        die("entrei".$countReg);
        
        $countReg = count($DadosQualifUsu);
  
        if($countReg > 0){

           foreach ($DadosQualifUsu as $i => $lstDadosQualifUsu) {     
  
               $objQualificacao  = $lstDadosQualifUsu->getQualificacao();
               $idQualificacao   = $objQualificacao->getId();
               $nomeQualificacao = $objQualificacao->getNome();
               //$tipoQualificacao = $objQualificacao->getTipo();
               
               $nivel_qualif     = $lstDadosQualifUsu->getNota();
               $cor_fundo_qualif = $lstDadosQualifUsu->getCor_fundo_qualif();
               $font_qualif      = $lstDadosQualifUsu->getFont_qualif();
               $sizeQualif       = $lstDadosQualifUsu->getFont_qualif();
               $tipoQualificacao = $lstDadosQualifUsu->getTipo();
               
               $objDePara = $lstDadosQualifUsu->getDePara();
               $sizeQualif = $objDePara->getPara();
               //echo '<b>';
               //print_r($objDePara);
               //echo '</b>';
               //$objDePara = $objDePara[0];
                if($tipoQualificacao == 'I'){
                    $tpl->newBlock("interesses");
                }
                else{
                    $tpl->newBlock("especialidades");
                }
                $tpl->assign("nivel_qualif", $sizeQualif);
                $tpl->assign("idQualificacao", $idQualificacao);
                $tpl->assign("nomeQualificacao", $nomeQualificacao);
                //  $tpl->assignGlobal("nivel_qualif", $sizeQualif);
               $tpl->assignGlobal("cor_fundo_qualif", $cor_fundo_qualif);
               $tpl->assignGlobal("font_qualif", $font_qualif);
           }   
           //die();
        }        
        
        $tpl->newBlock("formulario");
}  */  

?>
