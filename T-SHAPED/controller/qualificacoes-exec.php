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
    require('../model/Class.qualificacoesDAOExt.php');
    require('../model/Class.qualificacoes_usuariosDAOExt.php');
    require('../model/Class.usuariosDAOExt.php');
    require('../model/Class.de_paraDAOExt.php');
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
                list($countReg, $Qualif) = qualificacoes_usuariosDAOExt::select(" EXISTS( SELECT 1
                                                                                          FROM QUALIFICACOES Q
                                                                                          WHERE Q.ID = QUALIFICACOES_USUARIOS.QUALIFICACOES_ID
                                                                                          AND Q.NOME = '".$nome."')"
                                                                               );   
               //die("==>".$countReg);
                if($countReg > 0){//Caso sim
                    
                    //Pega id do usuario que possui a qualificaçao cadastrada
                    $idUsuarioQuali      = $Qualif[0]->getUsuarios_id();
                    
                    if($idUsuarioQuali == $idUsuario){ //Verifica se existe para o usuário logado
                        $msg = 'Qualificação já existe.';
                        $tpl->newBlock("mensagem");
                        $tpl->assign("msg", $msg);                        
                    }
                    else{ //pega id 
                        $idQualificacao      = $Qualif[0]->getQualificacoes_id();
                        $permiteInsert = true;
                        
                    }    
                    //die("==>".$idQualificacao);
                }
                else{ 
                    //Seta valores no objeto para inserir na tb qualificacoes
                    $DadosQuali->setNome($nome);
                    //$DadosQuali->setTipo($tipo);

                    //Insere registros na tabela qualificacoes
                    list($codErro, $msgErro, $DadosQuali) = QualificacoesDAOExt::insert($DadosQuali);  

                    if( isset($codErro) && ($codErro == '0') ){                    
                        
                        $idQualificacao  = $DadosQuali->getId();
                        $permiteInsert = true;
                    }
                }
                //die("==>".$idQualificacao);
                //Fim Bloco de validações de formulário

                if( $permiteInsert == true){

                    //Seta valores no objeto para inserir na tb qualificacoes_usuarios
                    //$DadosQualUsu->setQualificacoes_id($DadosQuali->getId());
                    $DadosQualUsu->setQualificacoes_id($idQualificacao);
                    $DadosQualUsu->setUsuarios_id($idUsuario);
                    $DadosQualUsu->setNota($nota); 
                    $DadosQualUsu->setDescricao($descricao);
                    $DadosQualUsu->setTipo($tipo);

                    //Insere registros na tabela qualificacoes_usuarios
                    list($codErro, $msgErro, $DadosQualUsu) = Qualificacoes_usuariosDAOExt::insert($DadosQualUsu);

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

                //Mostra mensagem de erro
                $tpl->newBlock("mensagem");
                $tpl->assign("msg", $msg);

            }
            
            //Chama função para listar qualificacoes
            listaQualif($idUsuario, $tpl);
        }
        //UPdate de edição do registro
        elseif( isset($acaoInserir) && ( $acaoInserir == 'edit') ){ 
            
            $DadosQuali   = new qualificacoesDAOExt();
            $DadosQualUsu = new qualificacoes_usuariosDAOExt();

            $permiteUpdate = false;
            $permiteInsert = false;
            
            //Pega informações do formulário enviadas por POST
            $idQualificacao = $_POST['idQualificacao'];
            $nome           = $_POST['nome'];
            $tipo           = $_POST['tipoRadios'];
            $descricao      = $_POST['descricao'];
            $idQualificacaoUsuario = $_POST["idQualificacaoUsuario"];     
            $nota           = $_POST["nota"];     
                    
            //Valida se qualificação já foi cadastrada 
            list($countReg, $Qualif) = qualificacoes_usuariosDAOExt::select(" EXISTS( SELECT 1
                                                                                      FROM QUALIFICACOES Q
                                                                                      WHERE Q.ID = QUALIFICACOES_USUARIOS.QUALIFICACOES_ID
                                                                                      AND Q.NOME = '".$nome."')"
                                                                           );   

            if($countReg > 0){//Caso sim            

                //Pega id do usuario que possui a qualificaçao cadastrada
                $idUsuarioQuali      = $Qualif[0]->getUsuarios_id();

                if($idUsuarioQuali == $idUsuario){ //Verifica se existe para o usuário logado

                    //Seta valores no objeto para inserir na tb qualificacoes
                    $DadosQuali->setNome($nome);
                    $DadosQuali->setId($idQualificacao);

                    //Insere registros na tabela qualificacoes
                    list($codErro, $msgErro, $DadosQuali) = QualificacoesDAOExt::update($DadosQuali);  

                    if( isset($codErro) && ($codErro == '0') ){                    
                        
                        $idQualificacao  = $DadosQuali->getId();
                        $permiteUpdate = true;
                    }                    
                }
                else{ //pega id           
                    $idQualificacao      = $Qualif[0]->getQualificacoes_id();
                    $permiteUpdate = true;
                }    
             }
             /**/
             else{                         
                    //Seta valores no objeto para inserir na tb qualificacoes
                    $DadosQuali->setNome($nome);

                    //Insere registros na tabela qualificacoes
                    list($codErro, $msgErro, $DadosQuali) = QualificacoesDAOExt::insert($DadosQuali);  

                    if( isset($codErro) && ($codErro == '0') ){                    
                        
                        $idQualificacao  = $DadosQuali->getId();
                        $permiteInsert = true;
                    }
                 
              }    
              /**/
              if( $permiteUpdate == true){

                    //Seta valores no objeto para inserir na tb qualificacoes_usuarios
                    //$DadosQualUsu->setQualificacoes_id($DadosQuali->getId());
                    $DadosQualUsu->setId($idQualificacaoUsuario);
                    $DadosQualUsu->setQualificacoes_id($idQualificacao);
                    //$DadosQualUsu->setUsuarios_id($idUsuario);
                    $DadosQualUsu->setNota($nota); 
                    $DadosQualUsu->setDescricao($descricao);
                    $DadosQualUsu->setTipo($tipo);

                    //Insere registros na tabela qualificacoes_usuarios
                    list($codErro, $msgErro, $DadosQualUsu) = Qualificacoes_usuariosDAOExt::update($DadosQualUsu);

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
              
              if( $permiteInsert == true){

                    //Seta valores no objeto para inserir na tb qualificacoes_usuarios
                    //$DadosQualUsu->setQualificacoes_id($DadosQuali->getId());
                    $DadosQualUsu->setQualificacoes_id($idQualificacao);
                    $DadosQualUsu->setUsuarios_id($idUsuario);
                    $DadosQualUsu->setNota($nota); 
                    $DadosQualUsu->setDescricao($descricao);
                    $DadosQualUsu->setTipo($tipo);

                    //Insere registros na tabela qualificacoes_usuarios
                    list($codErro, $msgErro, $DadosQualUsu) = Qualificacoes_usuariosDAOExt::insert($DadosQualUsu);

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
            
        //Chama função para listar qualificacoes
        listaQualif($idUsuario, $tpl);

        ///Seta valores default
        $tpl->assign("radioI", 'checked');
        }
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
        
        if($codErro == 0 ){
            
            $where2 = 'id = '.$idQualificacao;
            
            list($codErro, $msgErro) = QualificacoesDAOExt::delete($where2);
        }
        else{
            die("erro ao excluir registro.");
        }
        
        listaQualif($idUsuario, $tpl);
        
    }
    
    
    $tpl->printToScreen(); 

    /******************************************************
     * FUNÇÕES
    ******************************************************/       
    function listaQualif($idUsuario, $tpl){
                        
        //$DadosQuali   = new qualificacoesDAOExt();
        //$DadosUsuario = new usuariosDAOExt(); 

        /*
        $filtro = ' EXISTS(
                            SELECT * 
                            FROM usuarios u
                                ,qualificacoes_usuarios qu
                            WHERE u.id = qu.usuarios_id
                              AND qualificacoes.id = qu.qualificacoes_id
                              AND u.id = '.$idUsuario.')';
        
        //Busca dados de qualificações
        list($countReg, $DadosQuali) = qualificacoesDAOExt::select($filtro);

        if($countReg > 0){
            
           foreach ($DadosQuali as $i => $lstQualif) {

                $idQualificacao   = $lstQualif->getId();
                $nomeQualificacao = $lstQualif->getNome();
                $tipoQualificacao = $lstQualif->getTipo();
        
                if($tipoQualificacao == 'I'){
                    $tpl->newBlock("interesses");
                    $tpl->assign("nivel_qualif", $sizeQualif);
                    $tpl->assign("idQualificacao", $idQualificacao);
                    $tpl->assign("nomeQualificacao", $nomeQualificacao);
                }
                else{
                    $tpl->newBlock("especialidades");
                    $tpl->assign("nivel_qualif", $sizeQualif);
                    $tpl->assign("idQualificacao", $idQualificacao);
                    $tpl->assign("nomeQualificacao", $nomeQualificacao);
                }
                            
            }

        //}    
        */
        $filtro1 = ' id = '.$idUsuario;
        
        //Busca dados de usuarios
        list($countReg, $DadosUsuario) = usuariosDAOExt::select($filtro1);

        if($countReg > 0){
           foreach ($DadosUsuario as $i => $lstUsuario) {     
               $objUsuario = $lstUsuario;
               /*
               $cor_fundo_t = $lstUsuario->getCor_fundo_t();

               $tpl->assignGlobal("cor_fundo_t", $cor_fundo_t);
                * 
                */
               
           }   
           
           $cor_fundo_t = $objUsuario->getCor_fundo_t();

           $tpl->assignGlobal("cor_fundo_t", $cor_fundo_t);
           
        }
        
        
        //$filtro2 = ' usuarios_id = '.$idUsuario.' and qualificacoes_Id = '.$idQualificacao;
        
        //Busca dados de qualificacoes_usuarios
        //list($countReg, $DadosQualifUsu) = qualificacoes_usuariosDAOExt::select($filtro2);   
        
        $DadosQualifUsu = $objUsuario->getQualificacoes();
        /*
        echo '<pre>';
        print_r($DadosQualifUsu);
        echo '</pre>';
        */
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
               
               //$tpl->assignGlobal("nivel_qualif", $objDePara->getPara());
               /*
               list($countReg, $DadosDePara) = De_paraDAOExt::select(" descricao='SIZE_QUALIF' and de=".$nivel_qualif);   
               if($countReg > 0){

                    foreach ($DadosDePara as $i => $lstDadosDePara) {   
                        $sizeQualif = $lstDadosDePara->getPara();
                        //echo($sizeQualif);
                    }  
               } 
                * 
                */   
               //echo($sizeQualif);
//               $tpl->assignGlobal("nivel_qualif", $sizeQualif);
               $tpl->assignGlobal("cor_fundo_qualif", $cor_fundo_qualif);
               $tpl->assignGlobal("font_qualif", $font_qualif);
           }   
           //die();
        }        
        
        $tpl->newBlock("formulario");
}    
?>    