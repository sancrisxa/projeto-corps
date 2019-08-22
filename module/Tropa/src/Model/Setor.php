<?php
namespace Tropa\Model;

use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory;

class Setor
{
    /**
     *  @var integer
     */
    public $codigo;
    
    /**
     *  @var string
     */
    public $nome;
    
    /**
     *
     * @var InputFilterInterface
     */
    private $inputFilter;
    
    /**
     *  @param array $data
     */
    public function exchangeArray(array $data)
    {
        foreach ($data as $attribute => $value) {
            $this->$attribute = $value;
        }
    }
    
    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            
            $inputFilter = new InputFilter();
            $factory = new Factory();
            $inputFilter->add($factory->createInput([
                'name' => 'codigo',
                'required' => false,
                
                'filters' => [
                    [
                        'name' => 'Int'
                    ]
                ],
                'validators' => [
                    [
                        'name' => 'Between',
                        'options' => [
                            'min' => 0,
                            'max' => 3600
                        ]
                    ]
                ]
            ]));
            
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
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
        
    }
    
    /**
     *
     * @return array
     */
    public function getArrayCopy() {
        return [
            'codigo'=>$this->codigo,
            'nome'=>$this->nome
        ];
    }
}

?>