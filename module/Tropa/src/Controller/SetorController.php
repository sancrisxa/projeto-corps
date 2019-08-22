<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tropa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tropa\Form\Setor as SetorForm;
use Tropa\Model\Setor;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Mvc\I18n\Translator as MvcTranslator;
use Zend\Validator\AbstractValidator;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\Resources;

class SetorController extends AbstractActionController
{
    private $table;
    
    public function __construct($table, $sessionManager)
    {
        $this->table = $table;
        $sessionManager->start();
    }
    
    public function indexAction()
    {
        return new ViewModel(
            ['models' => $this->table->fetchAll()]    
        );
    }
    
    public function editAction()
    {
        $codigo = $this->params()->fromRoute('key', null);
        $setor = $this->table->getModel($codigo);
        $form = new SetorForm();
        $form->get('submit')->setValue(
            empty($codigo) ? 'Cadastrar' : 'Alterar'
        );
        $sessionStorage = new SessionArrayStorage();
        if (isset($sessionStorage->model)){
            $setor->exchangeArray($sessionStorage->model->toArray());
            unset($sessionStorage->model);
            $form->setInputFilter($setor->getInputFilter());
            $this->initValidatorTranslator();
        }
        $form->bind($setor);
        $form->isValid();
        return [
            'form' => $form,
           'title' => empty($codigo) ? 'Incluir' : 'Alterar'     
        ];
    }
    
    public function saveAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new SetorForm();
            $setor = new Setor();
            $form->setInputFilter($setor->getInputFilter());
            $post = $request->getPost();
            $form->setData($post);
            if (!$form->isValid()) {
                $sessionStorage = new SessionArrayStorage();
                $sessionStorage->model = $post;
                return $this->redirect()->toRoute(
                    'tropa',
                    [
                        'action'=>'edit',
                        'controller'=>'setor'
                    ]
                );
            }
            $setor->exchangeArray($form->getData());
            $this->table->saveModel($setor);
        }
        return $this->redirect()->toRoute(
            'tropa',
            ['controller'=>'setor']
        );
    }
    
    public function deleteAction()
    {
        $codigo = $this->params()->fromRoute('key', null);
        $this->table->deleteModel($codigo);
        return $this->redirect()->toRoute(
            'tropa',
            ['controller'=>'setor']
        );
    }
    
    protected function initValidatorTranslator()
    {
        $translator = new Translator();
        $mvcTranslator = new MvcTranslator($translator);
        $mvcTranslator->addTranslationFilePattern(
            'phparray',
            Resources::getBasePath(),
            Resources::getPatternForValidator()
        );
        AbstractValidator::setDefaultTranslator($mvcTranslator);
    }
}
