<?php
// Bootup the Composer autoloader
include DIR_COMPOSER . 'autoload.php';

use Mautic\Auth\ApiAuth;

class ControllerModuleApiMautic extends Controller
{
    private $error = array();

// Creates table in the database if it has not yet been created
    public function install() {
        $this->load->model('module/apimautic');
        $this->model_module_apimautic->install();
    }
// Delete table from database module by clicking uninstall
public function uninstall() {
    $this->load->model('module/apimautic');
    $this->model_module_apimautic->uninstall();
    }

//    Render da view e save
    public function index() {
        $this->load->language('module/apimautic');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('module/apimautic');
        $DataApi = $this->model_module_apimautic->getData();

//Post data
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

            $DataApi = $this->model_module_apimautic->ExeUpdate($DataApi[0]['id'], $this->request->post);

//            Create session to use for site authentication
            $this->session->data['ApiIdMautic'] = $DataApi[0]['id'];

            $this->session->data['success'] = $this->language->get('text_success');
            $this->AuthMautic($DataApi);
        }

//       PAD X VIEW Data entry
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_edit'] = $this->language->get('text_edit');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->data['entry_public_key'] = $this->language->get('entry_public_key');
        $this->data['entry_secret_key'] = $this->language->get('entry_secret_key');
        $this->data['entry_base_url'] = $this->language->get('entry_base_url');
        $this->data['entry_website'] = $this->language->get('entry_website');
        $this->data['entry_status'] = $this->language->get('entry_status');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

//       Treatment of errors, access denied
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else if (isset($this->session->data['error'])) {
            $this->data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $this->data['error_warning'] = '';
        }

//        Error treatment - Empty post
        if (isset($this->error['content'])) {
            $this->data['error_content'] = $this->error['content'];
        } else {
            $this->data['error_content'] = '';
        }

        //     Messages of error/ Success of mautic authentication
        if (isset($this->error['ErrorMautic'])) {
            $this->data['error_mautic'] = $this->error['ErrorMautic'];
        } else if (isset($this->session->data['errormautic'])) {
            $this->data['error_mautic'] = $this->session->data['errormautic'];
            unset($this->session->data['errormautic']);
        } else {
            $this->data['error_mautic'] = '';
        }

        if(isset($this->session->data['MauticValidate'])){
            $this->data['success_mautic'] = $this->session->data['MauticValidate'];
            unset($this->session->data['MauticValidate']);
            } else {
                $this->data['success_mautic'] = '';
        }



//        Start BREADCRUMBS
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['id'])) {
            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/apimautic', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
            );
        } else {
            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/apimautic', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL'),
                'separator' => ' :: '
            );
        }

        $this->data['action'] = $this->url->link('module/apimautic', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['data'] = $this->url->link('module/apimautic/data', 'token=' . $this->session->data['token'], 'SSL');

        //    CREATE DATA
        if (isset($this->request->post['public_key'])) {
            $this->data['public_key'] = $this->request->post['public_key'];
        } else {
            $this->data['public_key'] = $DataApi[0]['public_key'];
        }

        if (isset($this->request->post['secret_key'])) {
            $this->data['secret_key'] = $this->request->post['secret_key'];
        } else {
            $this->data['secret_key'] = $DataApi[0]['secret_key'];
        }

        if (isset($this->request->post['base_url'])) {
            $this->data['base_url'] = $this->request->post['base_url'];
        } else {
            $this->data['base_url'] = $DataApi[0]['base_url'];
        }
        if (isset($this->request->post['callback'])) {
            $this->data['callback'] = $this->request->post['callback'];
        } else {
            $this->data['callback'] = $DataApi[0]['callback'];
        }

        if (isset($this->request->post['status'])) {
            $this->data['status'] = $this->request->post['status'];
        } else {
            $this->data['status'] = $DataApi[0]['status'];
        }

        $this->template = 'module/apimautic.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
        
    }

    protected function validate() {

//        Checks whether the request is sent from the user with administrator rights
        if (!$this->user->hasPermission('modify', 'module/apimautic')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
//        Checks if Base URL, Client Key, and Client secret is not empty
        if (in_array("", $this->request->post)) {
            $this->error['content'] = $this->language->get('error_content');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    protected function AuthMautic($Data) {

        $this->session->data['PageModule'] = $this->url->link('module/apimautic', 'token=' . $this->session->data['token'], 'SSL');

        // Variables to test isset on array $accessTokenData
        $AcessToken = $Data[0]['access_token'];
        $AccessTokenSecret = $Data[0]['access_token_secret'];
        $AccessTokenExpires = $Data[0]['expires'];
        // If there is Token data registered in the database then it stores in the array, otherwise it leaves empty
        $accessTokenData = array(
            'accessToken' => (isset($AcessToken) && !empty($AcessToken) ? $AcessToken : ''),
            'accessTokenSecret' => (isset($AccessTokenSecret) && !empty($AccessTokenSecret) ? $AccessTokenSecret : ''),
            'accessTokenExpires' => (isset($AccessTokenExpires) && !empty($AccessTokenExpires) ? $AccessTokenExpires : '')
        );

//        Stores the $ setting data in a session to use in the catalog
        $this->session->data['ApimauticBaseUrl'] = $Data[0]['base_url'];
        $this->session->data['ApimauticPublicKey'] = $Data[0]['public_key'];
        $this->session->data['ApimauticSecretKey'] = $Data[0]['secret_key'];
        $this->session->data['ApimauticCallback'] = $Data[0]['callback'];

        $settings = array(
            'baseUrl' => $Data[0]['base_url'],
            'clientKey' => $Data[0]['public_key'],
            'clientSecret' => $Data[0]['secret_key'],
            'callback' => $Data[0]['callback'],
            'version' => 'OAuth1a'
        );

        if (!empty($accessTokenData['accessToken']) && !empty($accessTokenData['accessTokenSecret'])) {
            $settings['accessToken'] = $accessTokenData['accessToken'];
            $settings['accessTokenSecret'] = $accessTokenData['accessTokenSecret'];
            $settings['accessTokenExpires'] = $accessTokenData['accessTokenExpires'];
        }

        //Initializes object AUTH
        $initAuth = new ApiAuth();
        $auth = $initAuth->newAuth($settings);

        try {
            if ($auth->validateAccessToken()) {
                if ($auth->accessTokenUpdated()) {

                    //    Success message
                    $this->session->data['MauticValidate'] = $this->language->get('text_authorized');
                } else {
                    // Displays informational message that this application is already authorized
                    return $this->error['ErrorMautic'] = $this->language->get('text_authorized_duplicate');
                }
            }
        } catch (Exception $e) {
            // Do Error handling
            return $this->error['ErrorMautic'] = $this->language->get('error_exception_mautic');
        }

    }

}