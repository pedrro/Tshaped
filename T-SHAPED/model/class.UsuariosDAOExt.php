<?phprequire("../model/class.UsuariosDAO.php");include_once ('../model/class.qualificacoes_usuariosDAOExt.php');/** * Classe que representa uma extensão da classe DAO e inclui métodos * específicos para a tabela usuarios, base T-shaped. */class usuariosDAOExt extends usuariosDAO {	    public function getQualificacoes(){        list($countReg, $DadosQualificacoes) = qualificacoes_usuariosDAOExt::select(" usuarios_id = ".$this->id);        return($DadosQualificacoes);    }}?>