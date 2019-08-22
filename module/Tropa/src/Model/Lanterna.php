<?php
namespace Tropa\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 *
 * @author samuel.xavier
 *        
 */
class Lanterna
{
    /**
     *
     * @var integer
     */
    public $codigo;
    
    /**
     *
     * @var string
     */
    public $nome;
    
    /**
     *
     * @var Setor
    */
    public $setor;
    
    /**
     *
     * @var InputFilterInterface
     */
    private $inputFilter;
    
    function __construct()
    {
        $this->setor = new Setor();
    }
    
    /**
     *
     * @param array $data
     * 
     */
    public function exchangeArray($data)
    {
        $this->codigo = (isset($data['codigo'])) ? $data['codigo'] : null;
        $this->nome = (isset($data['nome'])) ? $data['nome'] : null;
        $this->setor = new Setor();
        $this->setor->codigo = (isset($data['codigo_setor'])) ? $data['codigo_setor'] : null;
        $this->setor->nome = (isset($data['setor'])) ? $data['setor'] : null;
    }
    
    /**
     *
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $inputFilter->add($factory->createInput([
                'name' => 'nome',
                'required' => true,
                'filters' => [
                    [
                        'name' => 'StripTags'
                    ],
                    [
                        'name' => 'StringTrim'
                     ]
                    ],
                    'validators' => [
                        [
                            'name' => 'StringLength',
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 2,
                                'max' => 30
                            ]
                        ]
                    ]
                    ]));
            $inputFilter->add($factory->createInput([
                'name' => 'codigo_setor',
                'required' => true,
                'filters' => [
                    [
                        'name' => 'Int'
                    ]
                ],
                'validators' => [
                    [
                        'name' => 'Digits'
                    ]
                ]
            ]));
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
    
    /**
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'codigo' => $this->codigo,
            'nome' => $this->nome,
            'codigo_setor' => $this->setor->codigo
        ];
    }
}

?>