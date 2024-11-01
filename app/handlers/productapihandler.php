<?php

namespace SmartQuickView\App\Handlers;

use SmartQuickView\App\Components\DefaultPopup;
use SmartQuickView\App\Data\Preferences\Preferences;
use SmartQuickView\Original\Events\Handler\EventHandler;

Class ProductAPIHandler extends EventHandler
{
    protected $numberOfArguments = 1;
    protected $priority = 10;

    public function execute()
    {
        (integer) $productId = (integer) sanitize_text_field(wp_unslash($_GET['productId'] ?? ''));

        $this->checkRequest($productId);

        (object) $popup = Preferences::get()->getCurrentDesign()->getTemplateComponent($productId);

        $this->sendResponse(200, [
            'status' => 'success',
            'data' => [
                'id' => $productId,
                'template' => $popup->getRenderedMarkup()
            ]
        ]);
    }

    protected function checkRequest(int $productId)
    {
        if (!$productId) {
            $this->sendResponse(500, [
                'status' => 'error',
            ]);
        }
    }

    protected function sendResponse(int $code, array $data)
    {
        status_header($code);

        echo json_encode($data);

        exit;
    }
    
}