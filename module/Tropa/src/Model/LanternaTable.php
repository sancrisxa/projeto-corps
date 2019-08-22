<?php
namespace Tropa\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Select;

/**
 *
 * @author samuel.xavier
 *        
 */
class LanternaTable
{
    /**
     *
     * @var TableGatewayInterface
     */
    private $tableGateway;
    /**
     *
     * @var string
     */
    private $keyName = 'codigo';

    /**
    *
    * @param TableGatewayInterface $tableGateway
    */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    /**
     *
     * @return ResultInterface
     */
    public function fetchAll()
    {
        $select = new Select();
        $select->from('lanterna')
            ->columns(array('codigo','nome'))
            ->join(array('s'=>'setor'), 'lanterna.codigo_setor = s.codigo', array('setor'=>'nome'));
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
    
    /**
     *
     * @param string $keyValue
     * @return Setor
     */
    public function getModel($keyValue)
    {
        $select = new Select();
        $select->from('lanterna')
            ->columns(array('codigo','nome','codigo_setor'))
            ->join(array('s'=>'setor'), 'lanterna.codigo_setor = s.codigo', array('setor'=>'nome'))
            ->where(array('lanterna.codigo' => $keyValue));
        $rowset = $this->tableGateway->selectWith($select);
        if ($rowset->count()>0){
            $row = $rowset->current();
        } else {
            $row = new Lanterna();
        }
        return $row;
    }
    
    /**
     *
     * @param Setor $setor
     */
    public function saveModel(Lanterna $lanterna)
    {
        $data = array(
            'nome' => $lanterna->nome,
            'codigo_setor' => $lanterna->setor->codigo
        );
        $codigo = $lanterna->codigo;
        if (empty($this->getModel($codigo)->codigo)) {
            $this->tableGateway->insert($data);
        } else {
            $this->tableGateway->update($data, array(
                'codigo' => $codigo
            ));
        }
    }
    
    /**
    *
    * @param mixed $keyValue
    */
    public function deleteModel($keyValue)
    {
        $this->tableGateway->delete(array(
            $this->keyName => $keyValue
        ));
    }
}

?>