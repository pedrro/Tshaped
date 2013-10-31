<?php/* * Classe que representa a tabela "qualificacoes_usuarios" da base "T-shaped"  * e os comandos de acesso aos dados */class qualificacoes_usuariosDAO{    //atributos (variáveis) relacionadas as colunas da tabela    protected $id;    protected $qualificacoes_id;    protected $usuarios_id;    protected $descricao;    protected $tipo;    protected $nota;    protected $cor_fundo_qualif;    protected $cor_fundo_t;    protected $cor_font_qualif;    protected $font_qualif;    protected $dt_cadastro;    protected $dt_alteracao;    protected $url_imagem;    //método construtor que já faz a conexão com o BD    public function qualificacoes_usuariosDAO() {}    //métodos para obter e ajustar dados das variáveis (get e set)    // -- SET id    public function setId($id) {            $this->id = $id;    }    // -- GET id    public function getId() {            return $this->id;    }    // -- SET qualificacoes_id    public function setQualificacoes_id($qualificacoes_id) {            $this->qualificacoes_id = $qualificacoes_id;    }    // -- GET qualificacoes_id    public function getQualificacoes_id() {            return $this->qualificacoes_id;    }    // -- SET usuarios_id    public function setUsuarios_id($usuarios_id) {            $this->usuarios_id = $usuarios_id;    }    // -- GET usuarios_id    public function getUsuarios_id() {            return $this->usuarios_id;    }    // -- SET descricao    public function setDescricao($descricao) {            $this->descricao = $descricao;    }    // -- GET descricao    public function getDescricao() {            return $this->descricao;    }        // -- SET tipo    public function setTipo($tipo) {            $this->tipo = $tipo;    }    // -- GET tipo    public function getTipo() {            return $this->tipo;    }    // -- SET nota    public function setNota($nota) {            $this->nota = $nota;    }    // -- GET nota    public function getNota() {            return $this->nota;    }    // -- SET cor_fundo_qualif    public function setCor_fundo_qualif($cor_fundo_qualif) {            $this->cor_fundo_qualif = $cor_fundo_qualif;    }    // -- GET cor_fundo_qualif    public function getCor_fundo_qualif() {            return $this->cor_fundo_qualif;        }     // -- SET cor_font_qualif    public function setCor_font_qualif($cor_font_qualif) {            $this->cor_font_qualif = $cor_font_qualif;    }    // -- GET cor_font_qualif    public function getCor_font_qualif() {            return $this->cor_font_qualif;        }     // -- SET font_qualif    public function setFont_qualif($font_qualif) {            $this->font_qualif = $font_qualif;    }    // -- GET font_qualif    public function getFont_qualif() {            return $this->font_qualif;        }         // -- SET dt_cadastro    public function setDt_cadastro($dt_cadastro) {            $this->dt_cadastro = $dt_cadastro;    }    // -- GET dt_cadastro    public function getDt_cadastro() {            return $this->dt_cadastro;    }    // -- SET dt_alteracao    public function setDt_alteracao($dt_alteracao) {            $this->dt_alteracao = $dt_alteracao;    }    // -- GET dt_alteracao    public function getDt_alteracao() {            return $this->dt_alteracao;    }    // -- SET url_imagem    public function setUrl_imagem($url_imagem) {            $this->url_imagem = $url_imagem;    }    // -- GET dt_alteracao    public function getUrl_imagem() {            return $this->url_imagem;    }    //método que faz a inserção de Qualificacoes_usuarios no BD    public static function insert($obj) {        //pegar os dados do objeto        $id                             = $obj->getId();        $qualificacoes_id               = $obj->getQualificacoes_id();        $usuarios_id                    = $obj->getUsuarios_id();        $descricao                      = $obj->getDescricao();        $tipo                           = $obj->getTipo();        $nota                           = $obj->getNota();        $cor_fundo_qualif               = $obj->getCor_fundo_qualif();        $cor_font_qualif                = $obj->getCor_font_qualif();        $font_qualif                    = $obj->getFont_qualif();        $dt_cadastro                    = $obj->getDt_cadastro();        $dt_alteracao                   = $obj->getDt_alteracao();        $url_imagem                     = $obj->getUrl_imagem();        if (empty($dt_cadastro))                $dt_cadastro = "now()";        if (empty($dt_alteracao))                $dt_alteracao = "now()";        //$cor_fundo_qualif = "#CCCCCC";        //$font_qualif      = "Times";                 //montar o comando SQL        $sql = "insert into Qualificacoes_usuarios                       (id                       ,qualificacoes_id                       ,usuarios_id                       ,descricao                       ,tipo                       ,nota                       ,cor_fundo_qualif                       ,cor_font_qualif                       ,font_qualif                       ,dt_cadastro                       ,dt_alteracao                       ,url_imagem)                values                       ('$id'                       ,'$qualificacoes_id'                       ,'$usuarios_id'                       ,'$descricao'                           ,'$tipo'                       ,'$nota'                       ,'$cor_fundo_qualif'                       ,'$cor_font_qualif'                       ,'$font_qualif'                       ,'$dt_cadastro'                       ,'$dt_alteracao'                       ,'$url_imagem')";        $sql = str_replace("''", "null", $sql);        $sql = str_replace("'now()'", "now()", $sql);//die($sql);        // abre a conexão com o BD        $dba = new DbAdmin();        $dba->connectDefault();        //executar o comando SQL        $res = $dba->query($sql);        $retorno = Array();        if (!$res) {                $retorno[0] = mysql_errno();                $retorno[1] = mysql_error();                $retorno[2]  = "";        }        else {            $id = mysql_insert_id();            $sql = "select * from Qualificacoes_usuarios where id = $id";            $res = $dba->query($sql);            $num = $dba->rows($res);            if (!$res) {                    $retorno[0] = mysql_errno();                    $retorno[1] = mysql_error();                    $retorno[2] = "";            }            else {                    $id                             = $dba->result($res, 0, "id");                    $qualificacoes_id               = $dba->result($res, 0, "qualificacoes_id");                    $usuarios_id                    = $dba->result($res, 0, "usuarios_id");                    $descricao                      = $dba->result($res, 0, "descricao");                    $tipo                           = $dba->result($res, 0, "tipo");                    $nota                           = $dba->result($res, 0, "nota");                    $cor_fundo_qualif               = $dba->result($res, 0, "cor_fundo_qualif");                    $cor_font_qualif                = $dba->result($res, 0, "cor_font_qualif");                    $font_qualif                    = $dba->result($res, 0, "font_qualif");                    $dt_cadastro                    = $dba->result($res, 0, "dt_cadastro");                    $dt_alteracao                   = $dba->result($res, 0, "dt_alteracao");                    $url_imagem                     = $dba->result($res, 0, "url_imagem");                    $retObj = new Qualificacoes_usuariosDAOExt();                    $retObj->setId($id);                    $retObj->setQualificacoes_id($qualificacoes_id);                    $retObj->setUsuarios_id($usuarios_id);                    $retObj->setDescricao($descricao);                    $retObj->setTipo($tipo);                    $retObj->setNota($nota);                    $retObj->setCor_fundo_qualif($cor_fundo_qualif);                    $retObj->setCor_font_qualif($cor_font_qualif);                    $retObj->setFont_qualif($font_qualif);                    $retObj->setDt_cadastro($dt_cadastro);                    $retObj->setDt_alteracao($dt_alteracao);                    $retObj->setUrl_imagem($url_imagem);                    $retorno[0] = 0;                    $retorno[1] = "";                    $retorno[2] = $retObj;            } // if (!$res)...        } // if (!$res)...	return $retorno;    }    //método que faz a atualização de Qualificacoes_usuarios no BD    public static function update($obj) {        //pegar os dados do objeto        $id                             = $obj->getId();        $qualificacoes_id               = $obj->getQualificacoes_id();        $usuarios_id                    = $obj->getUsuarios_id();        $descricao                      = $obj->getDescricao();        $tipo                           = $obj->getTipo();        $nota                           = $obj->getNota();        $cor_fundo_qualif               = $obj->getCor_fundo_qualif();        $font_qualif                    = $obj->getFont_qualif();        $dt_cadastro                    = $obj->getDt_cadastro();        $dt_alteracao                   = $obj->getDt_alteracao();        $cor_font_qualif                = $obj->getCor_font_qualif();        $url_imagem                     = $obj->getUrl_imagem();        if (empty($dt_alteracao))                $dt_alteracao = "now()";        if (empty($cor_fundo_qualif))                $cor_fundo_qualif = "#CCCCCC";        if (empty($font_qualif))                $font_qualif = "Times";        if (empty($cor_font_qualif))                $cor_font_qualif = "000000";    //preto         //montar o comando SQL        $sql = "update Qualificacoes_usuarios           set                descricao                     = '$descricao'               ,tipo                          = '$tipo'               ,nota                          = '$nota'               ,cor_fundo_qualif              = '$cor_fundo_qualif'                ,font_qualif                   = '$font_qualif'                ,qualificacoes_id              = '$qualificacoes_id'                   ,dt_alteracao                  = '$dt_alteracao'               ,cor_font_qualif               = '$cor_font_qualif'               ,url_imagem                    = '$url_imagem'          where id = $id";        $sql = str_replace("''", "null", $sql);        $sql = str_replace("'now()'", "now()", $sql);//die($sql);        // abre a conexão com o BD        $dba = new DbAdmin();        $dba->connectDefault();        //executar o comando SQL        $res = $dba->query($sql);        $retorno = Array();        if (!$res) {                $retorno[0] = mysql_errno();                $retorno[1] = mysql_error();                $retorno[2]  = "";        }        else {            $sql = "select * from Qualificacoes_usuarios where id = $id";            $res = $dba->query($sql);            if (!$res) {                    $retorno[0] = mysql_errno();                    $retorno[1] = mysql_error();                    $retorno[2] = "";            }            else {                $id                             = $dba->result($res, 0, "id");                $qualificacoes_id               = $dba->result($res, 0, "qualificacoes_id");                $usuarios_id                    = $dba->result($res, 0, "usuarios_id");                $descricao                      = $dba->result($res, 0, "descricao");                $tipo                           = $dba->result($res, 0, "tipo");                $nota                           = $dba->result($res, 0, "nota");                $cor_fundo_qualif               = $dba->result($res, 0, "cor_fundo_qualif");                $font_qualif                    = $dba->result($res, 0, "font_qualif");                $dt_cadastro                    = $dba->result($res, 0, "dt_cadastro");                $dt_alteracao                   = $dba->result($res, 0, "dt_alteracao");                $cor_font_qualif                = $dba->result($res, 0, "cor_font_qualif");                $url_imagem                     = $dba->result($res, 0, "url_imagem");                $retObj = new Qualificacoes_usuariosDAOExt();                $retObj->setId($id);                $retObj->setQualificacoes_id($qualificacoes_id);                $retObj->setUsuarios_id($usuarios_id);                $retObj->setDescricao($descricao);                $retObj->setTipo($tipo);                $retObj->setNota($nota);                $retObj->setCor_fundo_qualif($cor_fundo_qualif);                $retObj->setFont_qualif($font_qualif);                $retObj->setDt_cadastro($dt_cadastro);                $retObj->setDt_alteracao($dt_alteracao);                $retObj->setCor_font_qualif($cor_font_qualif);                $retObj->setUrl_imagem($url_imagem);                $retorno[0] = 0;                $retorno[1] = "";                $retorno[2] = $retObj;            } // if (!$res)...        } // if (!$res)...	return $retorno;    }    //método que faz a exclusão de Qualificacoes_usuarios no BD    public static function delete(/*$id*/$where) {        //montar o comando SQL        //$sql = "delete from Qualificacoes_usuarios where id = $id";        $sql = "delete from Qualificacoes_usuarios where $where";//die($sql);        // abre a conexão com o BD        $dba = new DbAdmin();        $dba->connectDefault();        //executar o comando SQL        $res = $dba->query($sql);        $retorno = Array();        if (!$res) {                $retorno[0] = mysql_errno();                $retorno[1] = mysql_error();        }        else {                $retorno[0] = "0";                $retorno[1] = "";        }        return $retorno;    }    /* Método estático que retorna os regsitros da tabela     * Qualificacoes_usuarios conforme o filtro informado. */    public static function select($where="", $orderBy="", $limite=0, $offset=0) {        $dba = new DbAdmin();        $dba->connectDefault();        if (!empty($where))                $where = "where $where";        if (!empty($orderBy))                $where .= " order by $orderBy";        $sql = "select * from Qualificacoes_usuarios $where";//die($sql);        if ($limite > 0)                $sql.= " limit " . $limite;        if ($offset > 0)                $sql.= " offset " . $offset;        $res = $dba->query($sql);        $num = $dba->rows($res);        $vet = array();        for ($i=0; $i < $num; $i++) {            $id                             = $dba->result($res, $i, "id");            $qualificacoes_id               = $dba->result($res, $i, "qualificacoes_id");            $usuarios_id                    = $dba->result($res, $i, "usuarios_id");            $descricao                      = $dba->result($res, $i, "descricao");            $tipo                           = $dba->result($res, $i, "tipo");            $nota                           = $dba->result($res, $i, "nota");            $cor_fundo_qualif               = $dba->result($res, $i, "cor_fundo_qualif");            $font_qualif                    = $dba->result($res, $i, "font_qualif");            $dt_cadastro                    = $dba->result($res, $i, "dt_cadastro");            $dt_alteracao                   = $dba->result($res, $i, "dt_alteracao");            $cor_font_qualif                = $dba->result($res, $i, "cor_font_qualif");            $url_imagem                     = $dba->result($res, $i, "url_imagem");            $vet[$i] = new Qualificacoes_usuariosDAOExt();            $vet[$i]->setId($id);            $vet[$i]->setQualificacoes_id($qualificacoes_id);            $vet[$i]->setUsuarios_id($usuarios_id);            $vet[$i]->setDescricao($descricao);            $vet[$i]->setTipo($tipo);            $vet[$i]->setNota($nota);            $vet[$i]->setCor_fundo_qualif($cor_fundo_qualif);            $vet[$i]->setFont_qualif($font_qualif);            $vet[$i]->setDt_cadastro($dt_cadastro);            $vet[$i]->setDt_alteracao($dt_alteracao);            $vet[$i]->setCor_font_qualif($cor_font_qualif);            $vet[$i]->setUrl_imagem($url_imagem);	   }            $vetRetorno = Array(((!$num) ? 0 : $num), $vet);            //matriz com os dados (linhas e colunas)            return $vetRetorno;    }    /* Método estático que retorna um unico regsitro da tabela     * Qualificacoes_usuarios conforme chave informada. */    public static function selectOne($id) {        $dba = new DbAdmin();        $dba->connectDefault();        $sql = "select * from Qualificacoes_usuarios where id = '$id'";        $res = $dba->query($sql);        $num = $dba->rows($res);        $obj = new Qualificacoes_usuariosExt();        if ($num > 0) {            $id                             = $dba->result($res, 0, "id");            $qualificacoes_id               = $dba->result($res, 0, "qualificacoes_id");            $usuarios_id                    = $dba->result($res, 0, "usuarios_id");            $descricao                      = $dba->result($res, 0, "descricao");            $tipo                           = $dba->result($res, 0, "tipo");            $nota                           = $dba->result($res, 0, "nota");            $cor_fundo_qualif               = $dba->result($res, 0, "cor_fundo_qualif");            $font_qualif                    = $dba->result($res, 0, "font_qualif");            $dt_cadastro                    = $dba->result($res, 0, "dt_cadastro");            $dt_alteracao                   = $dba->result($res, 0, "dt_alteracao");            $cor_font_qualif                = $dba->result($res, 0, "cor_font_qualif");            $url_imagem                     = $dba->result($res, 0, "url_imagem");            $obj->setId($id);            $obj->setQualificacoes_id($qualificacoes_id);            $obj->setUsuarios_id($usuarios_id);            $obj->setDescricao($descricao);            $obj->setTipo($tipo);            $obj->setNota($nota);            $obj->setCor_fundo_qualif($cor_fundo_qualif);            $obj->setFont_qualif($font_qualif);            $obj->setDt_cadastro($dt_cadastro);            $obj->setDt_alteracao($dt_alteracao);            $obj->setCor_font_qualif($cor_font_qualif);            $obj->setUrl_imagem($url_imagem);	}	return $obj;    }        }?>