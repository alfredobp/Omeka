<?php 
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 **/

/**
 * Handles all exceptions that are thrown in controllers
 *
 * @package Omeka
 * @author CHNM
 * @copyright Center for History and New Media, 2007-2008
 **/
class ErrorController extends Omeka_Controller_Action
{
    public function errorAction()
    {
        // Drop down to built-in error views if and only if we are in debug mode
        // These are the default script paths that need to be known to the app
        // @internal does setAssetPath() need to have this same value in 
        // Omeka_View::__construct()?
        if ($this->isInDebugMode()) {            
            $this->view->setScriptPath(VIEW_SCRIPTS_DIR);
            $this->view->setAssetPath(VIEW_SCRIPTS_DIR, WEB_VIEW_SCRIPTS);
        }
        
        //This is the pattern for retrieving the exception that occurred
        $handler = $this->_getParam('error_handler');        
        $e = $handler->exception;
        
        if ($this->is404($e, $handler)) {
            return $this->render404($e);
        }
        
        if ($this->is403($e)) {
            return $this->render403($e);
        }
        
        $this->logException($e, Zend_Log::ERR);
        
        return $this->renderException($e);
    }
    
    private function logException($e, $priority)
    {
        $logger = Omeka_Context::getInstance()->getLogger();
        if ($logger) {
            $logger->log($e->getMessage(), $priority);
        }
    }
    
    /**
     * Check to see whether the error qualifies as a 404 error
     *
     * @return boolean
     **/
    protected function is404(Exception $e, $handler)
    {
        return ($e instanceof Omeka_Controller_Exception_404 
                || $e instanceof Zend_View_Exception 
                || $handler->type == 'EXCEPTION_NO_CONTROLLER' 
                || $handler->type == 'EXCEPTION_NO_ACTION');
    }
    
    protected function is403(Exception $e)
    {
        return ($e instanceof Omeka_Controller_Exception_403);
    }
    
    protected function renderException(Exception $e)
    {
        $this->view->assign(compact('e'));
        if ($this->isInDebugMode()) {
            ini_set('memory_limit', '64M');
            $this->render('debug');
        } else {
            $this->render('index');
        }
    }
    
    protected function render404($e)
    {
        $this->getResponse()->setHttpResponseCode(404);
        $this->view->assign(array('badUri' => $this->getRequest()->getRequestUri(), 
                                  'e' => $e));
        $this->render('404');
    }
    
    protected function render403($e)
    {
        $this->getResponse()->setHttpResponseCode(403);
        $this->view->assign(array('e' => $e));
        $this->render('403');
    }
    
    protected function isInDebugMode()
    {
        $config = Omeka_Context::getInstance()->getConfig('basic');
        return (bool) $config->debug->exceptions;
    }
}