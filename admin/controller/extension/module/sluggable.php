<?php
class ControllerExtensionModuleSluggable extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('extension/module/sluggable');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        $data['breadcrumbs'] = [
            [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ],
            [
                'text' => $this->language->get('text_extension'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
            ],
            [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/sluggable', 'user_token=' . $this->session->data['user_token'], true)
            ]
        ];

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_sluggable', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if ($this->model_setting_setting->getSettingValue('sluggable_status')) {
            $data['module_sluggable_status'] = $this->model_setting_setting->getSettingValue('module_sluggable_status');
        }

        $data['action'] = $this->url->link('extension/module/sluggable', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/sluggable', $data));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/sluggable')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}