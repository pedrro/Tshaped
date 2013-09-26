<?php
    require_once('../inc/inc.autoload.php');
    require_once('../inc/class.TemplatePower.php');
    require_once('../model/Class.QualificacoesDAOExt.php');
    
    $tpl = new TemplatePower("../view/tshaped.htm");

    $tpl->prepare();
    
    $Dados = new qualificacoesDAOExt();
    
    $where = "EXISTS(
	      SELECT 1
	      FROM qualificacoes_usuarios
	     ) ";
    
    /*Comando que executa a query e retorna a quantidade de registros selecionada
      e um array com os dados.     */
    list($countReg, $lstDados) = QualificacoesDAOExt::select($where);

        if ($countReg > 0) {

            foreach ($lstDados as $i => $Qualificacoes) {

                $id = $Qualificacoes->getId();
                $nome = $Qualificacoes->getNome();
                $tipo = $Qualificacoes->getTipo();
                $descricao = $Qualificacoes->getDescricao();
                $dtCadastro = $Qualificacoes->getDt_cadastro();
                
                if ($tipo == 'I'){
                    $tpl->newBlock("contenth");
                    $tpl->assign("interesses", $nome);
                }
                else{
                    $tpl->newBlock("contentv");
                    $tpl->assign("especialidades", $nome);
                }
            }     
        }
        
    $tpl->printToScreen();         
?>                