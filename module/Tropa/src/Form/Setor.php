<?php
namespace Tropa\Form;

use Zend\Form\Form;

/**
 *
 * @author samuel.xavier
 *        
 */
class Setor extends Form
{

    /**
     */
    function __construct($name = null)
    {
        parent::__construct('setor');
        $this->setAttribute('method', 'post');
        $this->add([
            'name' => 'codigo',
            'attributes' => [
                'type' => 'number',
                'autofocus'=>'autofocus'
            ],
            'options' => [
                'label' => 'Código',
            ]
        ]);
        $this->add([
            'name' => 'nome',
            'attributes' => [
                'type' => 'text',
            ],
            'options' => [
                'label' => 'Nome',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Gravar',
                'id' => 'submitbutton',
            ],
        ]);
    }
}

?>