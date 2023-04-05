<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model\Config\Source;
 
class Template extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Generate list of email templates
     *
     * @return array
     */
    public function toOptionArray()
    {
        $path = $this->getPath();
        switch ($path) {
            case "magerma/email_template/new_email_template":
                $options = ['rma_new_email_template' => "New Rma Email Template (Deafult)"];
                break;
            case "magerma/email_template/new_guest_template":
                $options = ['rma_new_guest_template' => "New Guest Email (Deafult)"];
                break;
            case "magerma/email_template/comment_email_template":
                $options = ['rma_comment' => "Email comment (Deafult)"];
                break;
            default:
                break;
        }
        return $options;
    }
}
