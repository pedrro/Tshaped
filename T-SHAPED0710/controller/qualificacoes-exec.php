<?php
    //Criar Sessão
    session_start();

    //require('../inc/inc.autoload.php');
    require('../inc/class.TemplatePower.php');
    require('../model/Class.qualificacoesDAOExt.php');
    require('../model/Class.qualificacoes_usuariosDAOExt.php');
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

        //Insert de novo registro
        if( isset($acaoInserir) && ( empty($acaoInserir)) ){ 
        
            //Faz validações de campos obrigatórios
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

                //Seta valores no objeto para inserir na tb qualificacoes
                $DadosQuali->setNome($nome);
                $DadosQuali->setTipo($tipo);
                $DadosQuali->setDescricao($descricao);

                //Insere registros na tabela qualificacoes
                list($codErro, $msgErro, $DadosQuali) = QualificacoesDAOExt::insert($DadosQuali);

                if( isset($codErro) && ($codErro == '0') ){

                    $msg = 'Cadastrado com Sucesso!';

                    //Seta valores no objeto para inserir na tb qualificacoes_usuarios
                    $DadosQualUsu->setQualificacoes_id($DadosQuali->getId());
                    $DadosQualUsu->setUsuarios_id($idUsuario);
                    $DadosQualUsu->setNota($nota); 
                    //$DadosQualUsu->setStatus($status); 

                    //Insere registros na tabela qualificacoes_usuarios
                    list($codErro, $msgErro, $DadosQualUsu) = Qualificacoes_usuariosDAOExt::insert($DadosQualUsu);

                    if( isset($codErro) && ($codErro == '0') ){

                        $tpl->newBlock("mensagem");
                        $tpl->assign("msg", $msg);

                        //direcionar para o m?dulo correto conforme o tipo
                        header('location: ../controller/qualificacoes-exec.php?op=Listar');
                        exit;                    

                    }
                    else{
                        //erro no insert
                        die("erro.");
                    }

                }
                else{
                    //erro no insert
                    die("erro.");
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

            //Pega informações do formulário enviadas por POST
            $idQualificacao = $_POST['idQualificacao'];
            $nome           = $_POST['nome'];
            $tipo           = $_POST['tipoRadios'];
            $descricao      = $_POST['descricao'];
            $idQualificacaoUsuario = $_POST["idQualificacaoUsuario"];     
            $nota           = $_POST["nota"];     
            
            //Seta valores no objeto para atualizar na tb qualificacoes
            $DadosQuali->setId($idQualificacao);
            $DadosQuali->setNome($nome);
            $DadosQuali->setTipo($tipo);
            $DadosQuali->setDescricao($descricao);

            //Insere registros na tabela qualificacoes
            list($codErro, $msgErro, $DadosQuali) = QualificacoesDAOExt::update($DadosQuali);

            //Seta valores no objeto para atualizar na tb qualificacoes_usuarios
            $DadosQualUsu->setQualificacoes_id($DadosQuali->getId());
            $DadosQualUsu->setId($idQualificacaoUsuario);
            $DadosQualUsu->setNota($nota); 

            //Insere registros na tabela qualificacoes_usuarios
            list($codErro, $msgErro, $DadosQualUsu) = Qualificacoes_usuariosDAOExt::update($DadosQualUsu);
            
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
            $tipoQualificacao      = $lstDadosQuali[0]->getTipo();
            $descricaoQualificacao = $lstDadosQuali[0]->getDescricao();                

            $filtro2 = ' qualificacoes_id ='.$idQualificacao.' and usuarios_id = '.$idUsuario;

            list($countReg, $lstDadosQualUsu) = qualificacoes_usuariosDAOExt::select($filtro2);   

            if($countReg > 0){

                 $idQualificacaoUsuario = $lstDadosQualUsu[0]->getId();
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
                        
        $DadosQuali   = new qualificacoesDAOExt();
        $DadosQualUsu = new qualificacoes_usuariosDAOExt();        

        $filtro = ' EXISTS(
                            SELECT * 
                            FROM usuarios u
                                ,qualificacoes_usuarios qu
                            WHERE u.id = qu.usuarios_id
                              AND qualificacoes.id = qu.qualificacoes_id
                              AND u.id = '.$idUsuario.')';
        
        list($countReg, $vetQU) = qualificacoesDAOExt::select($filtro);

        if($countReg > 0){
            
           foreach ($vetQU as $i => $lstQualifUsu) {

                $idQualificacao   = $lstQualifUsu->getId();
                $nomeQualificacao = $lstQualifUsu->getNome();
                $tipoQualificacao = $lstQualifUsu->getTipo();
        
                if($tipoQualificacao == 'I'){
                    $tpl->newBlock("interesses");
                    $tpl->assign("idQualificacao", $idQualificacao);
                    $tpl->assign("nomeQualificacao", $nomeQualificacao);
                }
                else{
                    $tpl->newBlock("especialidades");
                    $tpl->assign("idQualificacao", $idQualificacao);
                    $tpl->assign("nomeQualificacao", $nomeQualificacao);
                }
                            
            }

        }    
        $tpl->newBlock("formulario");
}    
?>    