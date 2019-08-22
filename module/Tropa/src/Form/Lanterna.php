<?php
namespace Tropa\Form;

use Zend\Form\Form;
use Tropa\Model\SetorTable;

/**
 *
 * @author samuel.xavier
 *        
 */
class Lanterna extends Form
{
    private $table;
    
    public function __construct($name = null, array $options = array()) 
    {
        if (isset($options['table'])){
            $this->table = $options['table'];
        } else {
            throw new \Exception('Form requires SetorTable instance');
        }
        
        parent::__construct('lanterna');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'codigo',
            'type' => 'hidden'
        ));
        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'autofocus' => 'autofocus'
            ),
            'options' => array(
                'label' => 'Nome',
            ),
        ));
        $this->add(array(
            'name' => 'codigo_setor',
            'type' => 'select',
            'options' => array(
                'label' => 'Setor',
                'value_options' => $this->getValueOptions()
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Gravar',
                'id' => 'submitbutton',
            ),
        ));
    }
    
    /**
     *
     * @return SetorTable
     */
    private function getSetorTable() {
        return $this->table;
    }
    
    /**
     *
     * @return Generator
     */
    private function getValueOptions() {
        $valueOptions = array();
        $setores = $this->getSetorTable()->fetchAll();
        $options = array();
        foreach($setores as $setor) {
            $options[$setor->codigo] = $setor->nome;
        }
        return $options;
    }
}

?>