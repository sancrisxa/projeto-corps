<?php
namespace Tropa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tropa\Form\Lanterna as LanternaForm;
use Tropa\Model\Lanterna;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\I18n\Translator\Resources;
use Zend\Mvc\I18n\Translator as MvcTranslator;
use Zend\Validator\AbstractValidator;
use Zend\I18n\Translator\Translator;

/**
 *
 * @author samuel.xavier
 *        
 */
class LanternaController extends AbstractActionController
{
    private $table;
    private $parentTable;
    
    public function __construct($table, $parentTable, $sessionManager)
    {
        $this->table = $table;
        $this->parentTable = $parentTable;
        $sessionManager->start();
    }
    
    public function indexAction()
    {
        return new ViewModel(
            ['models' => $this->table->fetchAll()]
        );
    }
    
    /**
    * Action to add and change records
    */
    public function editAction()
    {
        $codigo = $this->params()->fromRoute('key', null);
        $lanterna = $this->table->getModel($codigo);
        $form = new LanternaForm('lanterna',['table' => $this->parentTable]);
        $form->get('submit')->setValue(
            empty($codigo) ? 'Cadastrar' : 'Alterar'
        );
        $sessionStorage = new SessionArrayStorage();
        if (isset($sessionStorage->model)){
            $lanterna->exchangeArray($sessionStorage->model->toArray());
            unset($sessionStorage->model);
            $form->setInputFilter($lanterna->getInputFilter());
            $this->initValidatorTranslator();
            $form->bind($lanterna);
            $form->isValid();
        } else {
            $form->bind($lanterna);
        }
        return [
            'form' => $form,
            'title' => empty($codigo) ? 'Incluir' : 'Alterar'
        ];
    }
    /**
     * Action to save a record
     */
    public function saveAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new LanternaForm('lanterna',['table'=>$this->
                parentTable]);
            $lanterna = new Lanterna();
            $form->setInputFilter($lanterna->getInputFilter());
            $post = $request->getPost();
            $form->setData($post);
            if (! $form->isValid()) {
                $sessionStorage = new SessionArrayStorage();
                $sessionStorage->model = $post;
                return $this->redirect()->toRoute('tropa', [
                    'action' => 'edit',
                    'controller' => 'lanterna'
                ]);
            }
            $lanterna->exchangeArray($form->getData());
            $this->table->saveModel($lanterna);
        }
        return $this->redirect()->toRoute('tropa', [
            'controller' => 'lanterna'
        ]);
    }
    
    /**
     * Action to remove records
     */
    public function deleteAction()
    {
        $codigo = $this->params()->fromRoute('key', null);
        $this->table->deleteModel($codigo);
        return $this->redirect()->toRoute(
            'tropa',
            ['controller'=>'lanterna']
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