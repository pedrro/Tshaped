<?php
    //Criar Sessão
    session_start();

	// Verifica sessao ativa
    if (empty($_SESSION['nomeUsuario']))
	{
		header('location: ../controller/exec.php');
		exit;
	};

    //require('../inc/inc.autoload.php');
    require('../inc/class.TemplatePower.php');
    //require('../model/Class.qualificacoesDAOExt.php');
    //require('../model/Class.qualificacoes_usuariosDAOExt.php');
    require('../model/Class.usuariosDAOExt.php');
    //require('../model/Class.de_paraDAOExt.php');
    require('../model/class.DbAdmin.php');
    //require('../inc/inc.util.php');

    $tpl = new TemplatePower("../view/qualificacoes.htm");
    $tpl->assignInclude('tshaped', "tshaped-exec.php");

    $tpl->prepare();
	
    //Pega dados da Sessão
    $nomeUsuario  = $_SESSION['nomeUsuario'];
    $idUsuario    = $_SESSION['idUsuario'];
    $emailUsuario = $_SESSION['emailUsuario'];

    //Pega valores enviados por POST ou GET
    if(isset($_REQUEST['sub'])) $acaoBtn     = $_REQUEST['sub'];
    if(isset($_REQUEST['acao'])) $acaoLink    = $_REQUEST['acao'];
    if(isset($_REQUEST['acaoInserir'])) $acaoInserir = $_REQUEST['acaoInserir'];
    
    //Mensagem de Bem Vindo com nome do usuário por sessão
    $tpl->assign("nomeUsuario", $nomeUsuario);

    /******************************************************
     * Listar Qualificações
    ******************************************************/    
    if( isset($_REQUEST['op']) && ($_REQUEST['op'] == 'Listar') ){

        //Chama função para listar qualificacoes
        listaQualif($idUsuario, $tpl);
        
        //Seta valores default quando entra na tela pela 1 vez
        $tpl->assign("radioI", 'checked');
        $tpl->assign("nota", '1');
        $tpl->assign("notaQualificacao", '1');
        $tpl->assignGlobal("corFundoQualif", '#FF0000');
        
    }
    /******************************************************
     * Insert de Qualificações
    ******************************************************/
    elseif( isset($acaoBtn) && ($acaoBtn == 'Inserir') ){

        $DadosQuali   = new qualificacoesDAOExt();
        $DadosQualUsu = new qualificacoes_usuariosDAOExt();
        
        //Pega informações do formulário enviadas por POST
        $nome           = $_POST['nome'];
        $tipo           = $_POST['tipoRadios'];
        $descricao      = $_POST['descricao'];
        $nota           = $_POST["nota"];
        $corFundoQualif = $_POST["corFundoQualif"];

        $permiteInsert = false;

        //Insert de novo registro
        if( isset($acaoInserir) && ( empty($acaoInserir)) ){ 
        
            //Bloco de validações de formulário
            if( empty($nome) ) {
                $msg = 'Informe o seu nome.';
            }
            
            if( empty($tipo) ) {
                $msg = 'Insira seu tipo';
            }

            if( empty ($nota)) {
                $msg = 'Insira a nota';
            }
            
            if( empty($msg) ){

                //Valida se qualificação já foi cadastrada 
                $idQualificacao = $DadosQuali->buscaQualificacao($nome);
                //die("==>".$idQualificacao);
                //Fim Bloco de validações de formulário

                //Verifica se a qualificação já está associada a este usuario
                if($DadosQualUsu->validaQualifUsuario($idUsuario ,$idQualificacao) ){
                   // die("==>".$idQualificacao);
                    $msg = 'Qualificação já existe.';
                    $tpl->newBlock("mensagem");
                    $tpl->assign("msg", $msg);                          
                }
                else{
                    //Seta valores no objeto para inserir na tb qualificacoes_usuarios
                    //$DadosQualUsu->setQualificacoes_id($DadosQuali->getId());
                    $DadosQualUsu->setQualificacoes_id($idQualificacao);
                    $DadosQualUsu->setUsuarios_id($idUsuario);
                    $DadosQualUsu->setNota($nota); 
                    $DadosQualUsu->setDescricao($descricao);
                    $DadosQualUsu->setTipo($tipo);
                    $DadosQualUsu->setCor_fundo_qualif($corFundoQualif);

                    //Insere registros na tabela qualificacoes_usuarios
                    list($codErro, $msgErro, $DadosQualUsu) = Qualificacoes_usuariosDAOExt::insert($DadosQualUsu);
                    //die("aki".$msgErro);        
                    if( isset($codErro) && ($codErro == '0') ){
                        
                        $msg = 'Cadastrado com Sucesso!';
                        $tpl->newBlock("mensagem");
                        $tpl->assign("msg", $msg);

                        //direcionar para o m?dulo correto conforme o tipo
                        header('location: ../controller/qualificacoes-exec.php?op=Listar');
                        exit;                    

                    }
                    else{
                        $msg = 'Erro na inserção da qualificação! '.$msgErro;
                        $tpl->newBlock("mensagem");
                        $tpl->assign("msg", $msg);
                    }

                }
            }
            else{
                //Se entrou aqui, é pq tem campo obrigatório que não foi prrenchido,
                //portanto, popula formulário com o que já foi digitado.
                $tpl->assign("nomeQualificacao", $nome);
                $tpl->assign("tipoQualificacao", $tipo);
                $tpl->assign("descricaoQualificacao", $descricao);
                $tpl->assign("notaQualificacao", $nota);
                $tpl->assign("corFundoQualif", $corFundoQualif);

                //Mostra mensagem de erro
                $tpl->newBlock("mensagem");
                $tpl->assign("msg", $msg);

            }
            
            //Chama função para listar qualificacoes
            listaQualif($idUsuario, $tpl);
        }
        elseif( isset($acaoInserir) && ( $acaoInserir == 'edit') ){ 

            $DadosQuali   = new qualificacoesDAOExt();
            $DadosQualUsu = new qualificacoes_usuariosDAOExt();

            //Pega informações do formulário enviadas por POST
            $idQualificacao = $_POST['idQualificacao'];
            $nome           = $_POST['nome'];
            $tipo           = $_POST['tipoRadios'];
            $descricao      = $_POST['descricao'];
            $idQualificacaoUsuario = $_POST["idQualificacaoUsuario"];     
            $nota           = $_POST["nota"];     
            $corFundoQualif = $_POST["corFundoQualif"];
            
            //Valida se qualificação já foi cadastrada 
            $idQualificacao = $DadosQuali->buscaQualificacao($nome);
            //die("==>".$idQualificacao);

            //Seta valores no objeto para atualizar na tb qualificacoes_usuarios
            $DadosQualUsu->setQualificacoes_id($DadosQuali->getId());
            $DadosQualUsu->setId($idQualificacaoUsuario);
            $DadosQualUsu->setQualificacoes_id($idQualificacao);
            //$DadosQualUsu->setUsuarios_id($idUsuario);
            $DadosQualUsu->setNota($nota); 
            $DadosQualUsu->setDescricao($descricao);
            $DadosQualUsu->setTipo($tipo);
            $DadosQualUsu->setCor_fundo_qualif($corFundoQualif);

            //Insere registros na tabela qualificacoes_usuarios
            list($codErro, $msgErro, $DadosQualUsu) = Qualificacoes_usuariosDAOExt::update($DadosQualUsu);
                //die($msgErro);
            if( isset($codErro) && ($codErro == '0') ){

                $msg = 'Atualizado com Sucesso!';
                $tpl->newBlock("mensagem");
                $tpl->assign("msg", $msg);

                //direcionar para o m?dulo correto conforme o tipo
                header('location: ../controller/qualificacoes-exec.php?op=Listar');
                exit;                    

            }
            else{
                $msg = 'Erro na atualização da qualificação! '.$msgErro;
                $tpl->newBlock("mensagem");
                $tpl->assign("msg", $msg);
            }                
        }       

        ///Seta valores default
        $tpl->assign("radioI", 'checked');
    }
    /******************************************************
     * Edição de Qualificações(seleciona registro)
    ******************************************************/
    elseif( isset($acaoLink) && ($acaoLink == 'Alterar') ){

        //Chama função para listar qualificacoes
        listaQualif($idUsuario, $tpl);
                
        $DadosQuali   = new qualificacoesDAOExt();
        $DadosQualUsu = new qualificacoes_usuariosDAOExt();
        
        $idQualificacao = $_REQUEST['idQualif'];

        //Monta clausula where
        $filtro1 = ' id ='.$idQualificacao;

        //EXecuta select
        list($countReg, $lstDadosQuali) = qualificacoesDAOExt::select($filtro1);        

        if($countReg > 0){

            $nomeQualificacao      = $lstDadosQuali[0]->getNome();
            //$tipoQualificacao      = $lstDadosQuali[0]->getTipo();
            //$descricaoQualificacao = $lstDadosQuali[0]->getDescricao();                

            $filtro2 = ' qualificacoes_id ='.$idQualificacao.' and usuarios_id = '.$idUsuario;

            list($countReg, $lstDadosQualUsu) = qualificacoes_usuariosDAOExt::select($filtro2);   

            if($countReg > 0){

                 $idQualificacaoUsuario = $lstDadosQualUsu[0]->getId();
                 $descricaoQualificacao = $lstDadosQualUsu[0]->getDescricao();    
                 $tipoQualificacao      = $lstDadosQualUsu[0]->getTipo();
                 $notaQualificacao      = $lstDadosQualUsu[0]->getNota();
                 $corFundoQualif        = $lstDadosQualUsu[0]->getCor_fundo_qualif();
                 //$fontQualif            = $lstDadosQualUsu[0]->getFont_qualif;

                 $tpl->assign("idQualificacao", $idQualificacao);
                 $tpl->assign("nomeQualificacao", $nomeQualificacao);
                 
                 if($tipoQualificacao == 'I'){
                    $tpl->assign("radioI", 'checked');
                 }
                 else{
                     $tpl->assign("radioE", 'checked');
                 }
                 $tpl->assign("descricaoQualificacao", $descricaoQualificacao);
                 $tpl->assign("idQualificacaoUsuario", $idQualificacaoUsuario);
                 $tpl->assign("notaQualificacao", $notaQualificacao);
                 $tpl->assignGlobal("corFundoQualif", $corFundoQualif);
                 $tpl->assign("nota", $notaQualificacao);
                 $tpl->assign("acaoInserir", 'edit');

            }

         }     
         
    }
    /******************************************************
     * Exclusão de Qualificações
    ******************************************************/    
    elseif( isset($acaoLink) && ($acaoLink == 'Excluir') ){

        $idQualificacao = $_REQUEST['idQualif'];
        
        $where = 'qualificacoes_id = '.$idQualificacao.' and usuarios_id = '.$idUsuario;
        
        list($codErro, $msgErro) = Qualificacoes_usuariosDAOExt::delete($where);
        
        if($codErro > 0 ){
            
            die("erro ao excluir registro.");
        }
        
        listaQualif($idUsuario, $tpl);
        
    }
    
    
    $tpl->printToScreen(); 

    /******************************************************
     * FUNÇÕES
    ******************************************************/       
    function listaQualif($idUsuario, $tpl){
                        
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
                //$tpl->assign("nivel_qualif", $objDePara->getPara());
               
               $tpl->assign("cor_fundo_qualif", $cor_fundo_qualif);
               $tpl->assign("font_qualif", $font_qualif);
           }   
           //die();
        }        
        
        $tpl->newBlock("formulario");
}    
?>    